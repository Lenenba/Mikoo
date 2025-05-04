<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Service to encapsulate review-related calculations and sorting logic.
 */
class ReviewService
{
    /**
     * Sort reviews by given key.
     *
     * @param Collection $reviews Collection of Review models
     * @param string     $sortKey 'newest', 'highest', or 'lowest'
     * @return Collection         Sorted collection
     */
    public function sortReviews(Collection $reviews, string $sortKey): Collection
    {
        return (match ($sortKey) {
            'highest' => $reviews->sortByDesc('rating'),
            'lowest'  => $reviews->sortBy('rating'),
            default   => $reviews->sortByDesc('created_at'),
        })->values();
    }

    /**
     * Calculate average rating (1–5).
     *
     * @param Collection $reviews
     * @return float
     */
    public function averageRating(Collection $reviews): float
    {
        if ($reviews->isEmpty()) {
            return 0.0;
        }

        return round($reviews->avg('rating'), 1);
    }

    /**
     * Total number of reviews.
     *
     * @param Collection $reviews
     * @return int
     */
    public function totalReviews(Collection $reviews): int
    {
        return $reviews->count();
    }

    /**
     * Count how many reviews per star rating.
     * Returns array [count1star, count2star, ..., count5star].
     *
     * @param Collection $reviews
     * @return array<int,int>
     */
    public function starCounts(Collection $reviews): array
    {
        $counts = array_fill(0, 5, 0);

        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if ($rating >= 1 && $rating <= 5) {
                $counts[$rating - 1]++;
            }
        }

        return $counts;
    }

    /**
     * Calculate percentage of reviews for each star rating (0–100).
     * Returns array [pct1star, ..., pct5star].
     *
     * @param Collection $reviews
     * @return array<int,int>
     */
    public function starPercentages(Collection $reviews): array
    {
        $total = $this->totalReviews($reviews) ?: 1;
        $counts = $this->starCounts($reviews);

        return array_map(
            fn(int $count): int => (int) round(($count / $total) * 100),
            $counts
        );
    }
}
