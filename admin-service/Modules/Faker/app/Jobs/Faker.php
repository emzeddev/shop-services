<?php

namespace Modules\Faker\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Faker\Helpers\Faker as FakerHelper;

class Faker implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  string  $productType
     * @return void
     */
    public function __construct(
        protected string $entity,
        protected int $count,
        protected $productType
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(FakerHelper::class)->fake(
            $this->entity,
            $this->count,
            $this->productType
        );
    }
}
