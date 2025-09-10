<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'category',
        'location',
        'price',
        'contact_email',
        'contact_phone',
        'tags',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope to get only active advertisements
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope to search advertisements using Melli search algorithm
     */
    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        $searchTerm = trim($searchTerm);
        $words = explode(' ', $searchTerm);
        
        return $query->where(function ($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere(function ($subQuery) use ($word) {
                    // Search in title (highest priority)
                    $subQuery->where('title', 'LIKE', "%{$word}%")
                             // Search in content (high priority)
                             ->orWhere('content', 'LIKE', "%{$word}%")
                             // Search in description (medium priority)
                             ->orWhere('description', 'LIKE', "%{$word}%")
                             // Search in category (medium priority)
                             ->orWhere('category', 'LIKE', "%{$word}%")
                             // Search in location (medium priority)
                             ->orWhere('location', 'LIKE', "%{$word}%")
                             // Search in tags (lower priority)
                             ->orWhereJsonContains('tags', $word);
                });
            }
        });
    }

    /**
     * Scope to filter by category
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by location
     */
    public function scopeLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    /**
     * Scope to filter by price range
     */
    public function scopePriceRange(Builder $query, ?float $minPrice = null, ?float $maxPrice = null): Builder
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        
        return $query;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price === null) {
            return 'Price not specified';
        }
        
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get excerpt of content
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit($this->content, 150);
    }

    /**
     * Check if advertisement is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get search relevance score (for future advanced search)
     */
    public function getSearchRelevanceScore(string $searchTerm): int
    {
        $score = 0;
        $searchTerm = strtolower($searchTerm);
        $words = explode(' ', $searchTerm);
        
        foreach ($words as $word) {
            // Title matches get highest score
            if (stripos($this->title, $word) !== false) {
                $score += 10;
            }
            
            // Content matches get high score
            if (stripos($this->content, $word) !== false) {
                $score += 5;
            }
            
            // Description matches get medium score
            if (stripos($this->description, $word) !== false) {
                $score += 3;
            }
            
            // Category matches get medium score
            if (stripos($this->category ?? '', $word) !== false) {
                $score += 3;
            }
            
            // Location matches get medium score
            if (stripos($this->location ?? '', $word) !== false) {
                $score += 3;
            }
            
            // Tag matches get lower score
            if (is_array($this->tags)) {
                foreach ($this->tags as $tag) {
                    if (stripos($tag, $word) !== false) {
                        $score += 1;
                    }
                }
            }
        }
        
        return $score;
    }
}
