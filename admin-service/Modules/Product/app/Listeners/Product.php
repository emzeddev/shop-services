<?php

namespace Modules\Product\Listeners;

use Illuminate\Support\Facades\Bus;
use Modules\Product\Helpers\Indexers\Flat as FlatIndexer;
use Modules\Product\Jobs\ElasticSearch\DeleteIndex as DeleteElasticSearchIndexJob;
use Modules\Product\Jobs\ElasticSearch\UpdateCreateIndex as UpdateCreateElasticSearchIndexJob;
use Modules\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;
use Modules\Product\Jobs\UpdateCreatePriceIndex as UpdateCreatePriceIndexJob;
use Modules\Product\Repositories\ProductBundleOptionProductRepository;
use Modules\Product\Repositories\ProductGroupedProductRepository;
use Modules\Product\Repositories\ProductRepository;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository,
        protected FlatIndexer $flatIndexer
    ) {}

    /**
     * Update or create product indices
     *
     * @param  \Modules\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        $this->flatIndexer->refresh($product);

        $productIds = $this->getAllRelatedProductIds($product);

        UpdateCreateElasticSearchIndexJob::dispatch($productIds);
    }

    /**
     * Update or create product indices
     *
     * @param  \Modules\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $this->flatIndexer->refresh($product);

        $productIds = $this->getAllRelatedProductIds($product);

        Bus::chain([
            new UpdateCreateInventoryIndexJob($productIds),
            new UpdateCreatePriceIndexJob($productIds),
            new UpdateCreateElasticSearchIndexJob($productIds),
        ])->dispatch();
    }

    /**
     * Delete product indices
     *
     * @param  int  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        if (core()->getConfigData('catalog.products.search.engine') != 'elastic') {
            return;
        }

        $product = $this->productRepository->find($productId);

        if (! $product) {
            return;
        }

        $productIds = $this->getAllRelatedProductIds($product);

        DeleteElasticSearchIndexJob::dispatch($productIds);
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Modules\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProductIds($product)
    {
        $productIds = [$product->id];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $productIds[] = $product->parent_id;
            }

            $productIds = array_merge(
                $productIds,
                $this->getParentBundleProductIds($product),
                $this->getParentGroupProductIds($product)
            );
        } elseif ($product->type == 'configurable') {
            $productIds = [
                ...$product->variants->pluck('id')->toArray(),
                ...$productIds,
            ];
        }

        return $productIds;
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Modules\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentBundleProductIds($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $productIds = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $productIds[] = $bundleOptionProduct->bundle_option->product_id;
        }

        return $productIds;
    }

    /**
     * Returns parents group product ids associated with simple product
     *
     * @param  \Modules\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentGroupProductIds($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        return $groupedOptionProducts->pluck('product_id')->toArray();
    }
}
