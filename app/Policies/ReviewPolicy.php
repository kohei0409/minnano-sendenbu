<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Review;

class ReviewPolicy
{
    /**
     * Determine if the customer can update the review.
     */
    public function update(Customer $customer, Review $review): bool
    {
        return $customer->id === $review->customer_id;
    }

    /**
     * Determine if the customer can delete the review.
     */
    public function delete(Customer $customer, Review $review): bool
    {
        return $customer->id === $review->customer_id;
    }
}
