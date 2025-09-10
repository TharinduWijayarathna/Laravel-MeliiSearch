<?php

namespace App\Services;

use MeiliSearch\Client;
use App\Models\Advertisement;
use Illuminate\Support\Collection;

class MeiliSearchService
{
    private Client $client;
    private string $indexName = 'advertisements';

    public function __construct()
    {
        $this->client = new Client(
            config('meilisearch.host', 'http://localhost:7700'),
            config('meilisearch.key', 'masterKey')
        );
    }

    /**
     * Initialize MeiliSearch index and settings
     */
    public function initializeIndex(): void
    {
        try {
            // Create index if it doesn't exist
            try {
                $this->client->index($this->indexName)->getPrimaryKey();
            } catch (\Exception $e) {
                // Index doesn't exist, create it
                $this->client->createIndex($this->indexName, ['primaryKey' => 'id']);
            }

            // Configure searchable attributes with priority
            $this->client->index($this->indexName)->updateSearchableAttributes([
                'title',      // Highest priority
                'content',    // High priority
                'description', // Medium priority
                'category',   // Medium priority
                'location',   // Medium priority
                'tags'        // Lower priority
            ]);

            // Configure filterable attributes
            $this->client->index($this->indexName)->updateFilterableAttributes([
                'category',
                'location',
                'price',
                'is_active',
                'expires_at'
            ]);

            // Configure sortable attributes
            $this->client->index($this->indexName)->updateSortableAttributes([
                'price',
                'created_at',
                'expires_at'
            ]);

            // Configure ranking rules for better relevance
            $this->client->index($this->indexName)->updateRankingRules([
                'words',
                'typo',
                'proximity',
                'attribute',
                'sort',
                'exactness'
            ]);

        } catch (\Exception $e) {
            \Log::error('MeiliSearch initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Index all advertisements
     */
    public function indexAllAdvertisements(): void
    {
        $advertisements = Advertisement::all()->map(function ($ad) {
            return $this->formatAdvertisementForIndexing($ad);
        });

        if ($advertisements->isNotEmpty()) {
            $this->client->index($this->indexName)->addDocuments($advertisements->toArray());
        }
    }

    /**
     * Add or update a single advertisement
     */
    public function indexAdvertisement(Advertisement $advertisement): void
    {
        $document = $this->formatAdvertisementForIndexing($advertisement);
        $this->client->index($this->indexName)->addDocuments([$document]);
    }

    /**
     * Remove advertisement from index
     */
    public function removeAdvertisement(int $id): void
    {
        $this->client->index($this->indexName)->deleteDocument($id);
    }

    /**
     * Search advertisements using MeiliSearch
     */
    public function search(string $query, array $filters = [], array $options = []): array
    {
        $searchOptions = array_merge([
            'limit' => 15,
            'offset' => 0,
            'attributesToRetrieve' => ['*'],
            'attributesToHighlight' => ['title', 'content', 'description'],
            'highlightPreTag' => '<mark>',
            'highlightPostTag' => '</mark>',
        ], $options);

        // Add filters if provided
        if (!empty($filters)) {
            $searchOptions['filter'] = $this->buildFilterString($filters);
        }

        $results = $this->client->index($this->indexName)->search($query, $searchOptions);

        return [
            'hits' => $results->getHits(),
            'total' => $results->getTotalHits(),
            'query' => $query,
            'processingTime' => $results->getProcessingTimeMs(),
            'facetDistribution' => $results->getFacetDistribution() ?? [],
        ];
    }

    /**
     * Get search suggestions
     */
    public function getSuggestions(string $query, int $limit = 10): array
    {
        $results = $this->client->index($this->indexName)->search($query, [
            'limit' => $limit,
            'attributesToRetrieve' => ['title', 'category', 'location'],
            'attributesToHighlight' => ['title', 'category', 'location'],
            'highlightPreTag' => '',
            'highlightPostTag' => '',
        ]);

        $suggestions = collect($results->getHits())->map(function ($hit) {
            return [
                'type' => 'title',
                'value' => $hit['title'],
                'category' => $hit['category'] ?? null,
                'location' => $hit['location'] ?? null,
            ];
        })->unique('value')->take($limit);

        return $suggestions->toArray();
    }

    /**
     * Get facet counts for filters
     */
    public function getFacets(array $filters = []): array
    {
        $searchOptions = [
            'limit' => 0,
            'facets' => ['category', 'location'],
        ];

        if (!empty($filters)) {
            $searchOptions['filter'] = $this->buildFilterString($filters);
        }

        $results = $this->client->index($this->indexName)->search('', $searchOptions);

        return $results->getFacetDistribution() ?? [];
    }

    /**
     * Format advertisement for MeiliSearch indexing
     */
    private function formatAdvertisementForIndexing(Advertisement $advertisement): array
    {
        return [
            'id' => $advertisement->id,
            'title' => $advertisement->title,
            'description' => $advertisement->description,
            'content' => $advertisement->content,
            'category' => $advertisement->category,
            'location' => $advertisement->location,
            'price' => $advertisement->price,
            'contact_email' => $advertisement->contact_email,
            'contact_phone' => $advertisement->contact_phone,
            'tags' => $advertisement->tags,
            'is_active' => $advertisement->is_active,
            'expires_at' => $advertisement->expires_at?->toISOString(),
            'created_at' => $advertisement->created_at->toISOString(),
            'updated_at' => $advertisement->updated_at->toISOString(),
        ];
    }

    /**
     * Build filter string for MeiliSearch
     */
    private function buildFilterString(array $filters): string
    {
        $filterParts = [];

        if (isset($filters['category'])) {
            $filterParts[] = "category = '{$filters['category']}'";
        }

        if (isset($filters['location'])) {
            $filterParts[] = "location = '{$filters['location']}'";
        }

        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $priceFilter = 'price';
            if (isset($filters['min_price'])) {
                $priceFilter .= " >= {$filters['min_price']}";
            }
            if (isset($filters['max_price'])) {
                $priceFilter .= isset($filters['min_price']) ? " AND price <= {$filters['max_price']}" : " <= {$filters['max_price']}";
            }
            $filterParts[] = $priceFilter;
        }

        if (isset($filters['is_active'])) {
            $filterParts[] = "is_active = " . ($filters['is_active'] ? 'true' : 'false');
        }

        return implode(' AND ', $filterParts);
    }

    /**
     * Get index statistics
     */
    public function getStats(): array
    {
        try {
            $index = $this->client->index($this->indexName);
            $stats = $index->stats();
            return [
                'numberOfDocuments' => $stats['numberOfDocuments'] ?? 0,
                'isIndexing' => $stats['isIndexing'] ?? false,
                'fieldDistribution' => $stats['fieldDistribution'] ?? [],
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
