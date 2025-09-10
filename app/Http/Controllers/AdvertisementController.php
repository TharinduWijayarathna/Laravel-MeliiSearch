<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Services\MeiliSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AdvertisementController extends Controller
{
    private MeiliSearchService $meiliSearch;

    public function __construct(MeiliSearchService $meiliSearch)
    {
        $this->meiliSearch = $meiliSearch;
    }
    /**
     * Display a listing of the resource with search functionality.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // If search query is provided, use MeiliSearch
            if ($request->has('search') && !empty($request->search)) {
                return $this->meiliSearch($request);
            }

            // Otherwise, use regular Eloquent query
            $query = Advertisement::active();

            // Apply category filter
            if ($request->has('category') && !empty($request->category)) {
                $query->category($request->category);
            }

            // Apply location filter
            if ($request->has('location') && !empty($request->location)) {
                $query->location($request->location);
            }

            // Apply price range filter
            if ($request->has('min_price') || $request->has('max_price')) {
                $query->priceRange(
                    $request->min_price ? (float) $request->min_price : null,
                    $request->has('max_price') ? (float) $request->max_price : null
                );
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if (in_array($sortBy, ['title', 'price', 'created_at', 'expires_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50); // Max 50 per page
            $advertisements = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $advertisements->items(),
                'pagination' => [
                    'current_page' => $advertisements->currentPage(),
                    'last_page' => $advertisements->lastPage(),
                    'per_page' => $advertisements->perPage(),
                    'total' => $advertisements->total(),
                    'from' => $advertisements->firstItem(),
                    'to' => $advertisements->lastItem(),
                ],
                'filters' => [
                    'search' => $request->search,
                    'category' => $request->category,
                    'location' => $request->location,
                    'min_price' => $request->min_price,
                    'max_price' => $request->max_price,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ]
            ]);

        } catch (\Exception $e) {
            // Fallback to regular search if MeiliSearch is not available
            return $this->fallbackSearch($request);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'content' => 'required|string',
                'category' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'expires_at' => 'nullable|date|after:now',
            ]);

            $advertisement = Advertisement::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Advertisement created successfully',
                'data' => $advertisement
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $advertisement = Advertisement::active()->find($id);

        if (!$advertisement) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $advertisement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:1000',
                'content' => 'sometimes|required|string',
                'category' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'is_active' => 'sometimes|boolean',
                'expires_at' => 'nullable|date|after:now',
            ]);

            $advertisement->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Advertisement updated successfully',
                'data' => $advertisement
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found'
            ], 404);
        }

        $advertisement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Advertisement deleted successfully'
        ]);
    }

    /**
     * Advanced search with relevance scoring
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $query = Advertisement::active();

        // Apply basic filters first
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->category($request->category);
        }

        if ($request->has('location') && !empty($request->location)) {
            $query->location($request->location);
        }

        if ($request->has('min_price') || $request->has('max_price')) {
            $query->priceRange(
                $request->min_price ? (float) $request->min_price : null,
                $request->has('max_price') ? (float) $request->max_price : null
            );
        }

        // Get results
        $advertisements = $query->get();

        // Apply relevance scoring if search term is provided
        if ($request->has('search') && !empty($request->search)) {
            $advertisements = $advertisements->map(function ($ad) use ($request) {
                $ad->relevance_score = $ad->getSearchRelevanceScore($request->search);
                return $ad;
            })->sortByDesc('relevance_score');
        }

        // Paginate manually for relevance-sorted results
        $perPage = min($request->get('per_page', 15), 50);
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $advertisements->slice($offset, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginatedItems,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $advertisements->count(),
                'last_page' => ceil($advertisements->count() / $perPage),
            ],
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'location' => $request->location,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
            ]
        ]);
    }

    /**
     * Get search suggestions based on existing data
     */
    public function suggestions(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', '');
        
        if (strlen($searchTerm) < 2) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        try {
            // Use MeiliSearch for suggestions
            $suggestions = $this->meiliSearch->getSuggestions($searchTerm);
            
            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            // Fallback to regular suggestions
            return $this->fallbackSuggestions($request);
        }
    }

    /**
     * MeiliSearch implementation
     */
    private function meiliSearch(Request $request): JsonResponse
    {
        $searchQuery = $request->get('search', '');
        $filters = [];
        
        // Build filters
        if ($request->has('category') && !empty($request->category)) {
            $filters['category'] = $request->category;
        }
        
        if ($request->has('location') && !empty($request->location)) {
            $filters['location'] = $request->location;
        }
        
        if ($request->has('min_price') || $request->has('max_price')) {
            if ($request->has('min_price')) {
                $filters['min_price'] = (float) $request->min_price;
            }
            if ($request->has('max_price')) {
                $filters['max_price'] = (float) $request->max_price;
            }
        }

        $filters['is_active'] = true;

        // Search options
        $options = [
            'limit' => min($request->get('per_page', 15), 50),
            'offset' => ($request->get('page', 1) - 1) * min($request->get('per_page', 15), 50),
        ];

        // Add sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['price', 'created_at', 'expires_at'])) {
            $options['sort'] = ["{$sortBy}:{$sortOrder}"];
        }

        $results = $this->meiliSearch->search($searchQuery, $filters, $options);

        return response()->json([
            'success' => true,
            'data' => $results['hits'],
            'pagination' => [
                'current_page' => $request->get('page', 1),
                'per_page' => min($request->get('per_page', 15), 50),
                'total' => $results['total'],
                'last_page' => ceil($results['total'] / min($request->get('per_page', 15), 50)),
            ],
            'filters' => [
                'search' => $searchQuery,
                'category' => $request->category,
                'location' => $request->location,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'meilisearch' => [
                'processing_time' => $results['processingTime'],
                'facet_distribution' => $results['facetDistribution'],
            ]
        ]);
    }

    /**
     * Fallback search using Eloquent
     */
    private function fallbackSearch(Request $request): JsonResponse
    {
        $query = Advertisement::active();

        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->category($request->category);
        }

        // Apply location filter
        if ($request->has('location') && !empty($request->location)) {
            $query->location($request->location);
        }

        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->priceRange(
                $request->min_price ? (float) $request->min_price : null,
                $request->has('max_price') ? (float) $request->max_price : null
            );
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['title', 'price', 'created_at', 'expires_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 50);
        $advertisements = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $advertisements->items(),
            'pagination' => [
                'current_page' => $advertisements->currentPage(),
                'last_page' => $advertisements->lastPage(),
                'per_page' => $advertisements->perPage(),
                'total' => $advertisements->total(),
                'from' => $advertisements->firstItem(),
                'to' => $advertisements->lastItem(),
            ],
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'location' => $request->location,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'fallback' => true
        ]);
    }

    /**
     * Fallback suggestions using Eloquent
     */
    private function fallbackSuggestions(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', '');
        $suggestions = collect();

        // Get title suggestions
        $titleSuggestions = Advertisement::active()
            ->where('title', 'LIKE', "%{$searchTerm}%")
            ->distinct()
            ->pluck('title')
            ->take(5);

        // Get category suggestions
        $categorySuggestions = Advertisement::active()
            ->where('category', 'LIKE', "%{$searchTerm}%")
            ->distinct()
            ->pluck('category')
            ->filter()
            ->take(5);

        // Get location suggestions
        $locationSuggestions = Advertisement::active()
            ->where('location', 'LIKE', "%{$searchTerm}%")
            ->distinct()
            ->pluck('location')
            ->filter()
            ->take(5);

        $suggestions = $suggestions
            ->merge($titleSuggestions->map(fn($title) => ['type' => 'title', 'value' => $title]))
            ->merge($categorySuggestions->map(fn($category) => ['type' => 'category', 'value' => $category]))
            ->merge($locationSuggestions->map(fn($location) => ['type' => 'location', 'value' => $location]));

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions->take(10)->values(),
            'fallback' => true
        ]);
    }
}
