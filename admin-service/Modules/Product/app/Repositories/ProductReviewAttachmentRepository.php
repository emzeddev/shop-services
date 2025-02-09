<?php

namespace Modules\Product\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\Product\Contracts\ProductReview;
use Modules\Product\Contracts\ProductReviewAttachment;

class ProductReviewAttachmentRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductReviewAttachment::class;
    }

    /**
     * Upload.
     */
    public function upload(array $attachments, ProductReview $review): void
    {
        foreach ($attachments as $attachment) {
            $fileType = explode('/', $attachment->getMimeType());

            $this->create([
                'path'      => $attachment->store('review/'.$review->id),
                'review_id' => $review->id,
                'type'      => $fileType[0],
                'mime_type' => $fileType[1],
            ]);
        }
    }
}
