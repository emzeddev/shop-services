<?php

namespace Modules\Product\Type;

use Modules\Attribute\Repositories\AttributeRepository;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Product\Helpers\Indexers\Price\Grouped as GroupedIndexer;
use Modules\Product\Repositories\ProductAttributeValueRepository;
use Modules\Product\Repositories\ProductCustomerGroupPriceRepository;
use Modules\Product\Repositories\ProductGroupedProductRepository;
use Modules\Product\Repositories\ProductImageRepository;
use Modules\Product\Repositories\ProductInventoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\ProductVideoRepository;

class Grouped extends AbstractType
{
    /**
     * Skip attribute for downloadable product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'price',
        'cost',
        'special_price',
        'special_price_from',
        'special_price_to',
        'length',
        'width',
        'height',
        'weight',
        'depth',
        'manage_stock',
    ];

    /**
     * Is a composite product type.
     *
     * @var bool
     */
    protected $isComposite = true;

    /**
     * Product can be added to cart with options or not.
     *
     * @var bool
     */
    protected $canBeAddedToCartWithoutOptions = false;

    /**
     * Create a new product type instance.
     *
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository
    ) {
        parent::__construct(
            $customerRepository,
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productVideoRepository,
            $productCustomerGroupPriceRepository
        );
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attributes
     * @return \Modules\Product\Contracts\Product
     */
    public function update(array $data, $id, $attributes = [])
    {
        $product = parent::update($data, $id);

        if (! empty($attributes)) {
            return $product;
        }

        $this->productGroupedProductRepository->saveGroupedProducts($data, $product);

        return $product;
    }

    /**
     * Copy relationships.
     *
     * @param  \Modules\Product\Models\Product  $product
     * @return void
     */
    protected function copyRelationships($product)
    {
        parent::copyRelationships($product);

        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        if (in_array('grouped_products', $attributesToSkip)) {
            return;
        }

        foreach ($this->product->grouped_products as $groupedProduct) {
            $product->grouped_products()->save($groupedProduct->replicate());
        }
    }

    /**
     * Returns children ids.
     *
     * @return array
     */
    public function getChildrenIds()
    {
        return array_unique($this->product->grouped_products()->pluck('associated_product_id')->toArray());
    }

    /**
     * Check if catalog rule can be applied.
     *
     * @return bool
     */
    public function priceRuleCanBeApplied()
    {
        return false;
    }

    /**
     * Is saleable.
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->product->status) {
            return false;
        }

        foreach ($this->product->grouped_products as $groupedProduct) {
            if ($groupedProduct->associated_product->isSaleable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is product have sufficient quantity.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        foreach ($this->product->grouped_products as $groupedProduct) {
            if ($groupedProduct->associated_product->haveSufficientQuantity($qty)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return view('shop::products.prices.grouped', [
            'product' => $this->product,
            'prices'  => $this->getProductPrices(),
        ])->render();
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array|string
     */
    public function prepareForCart($data)
    {
        if (
            ! isset($data['qty'])
            || ! is_array($data['qty'])
        ) {
            return trans('product::app.checkout.cart.missing-options');
        }

        $cartProductsList = [];

        foreach ($data['qty'] as $productId => $qty) {
            if (! $qty) {
                continue;
            }

            $product = $this->productRepository->find($productId);

            $cartProducts = $product->getTypeInstance()->prepareForCart([
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);

            if (is_string($cartProducts)) {
                return $cartProducts;
            }

            $cartProductsList[] = $cartProducts;
        }

        $products = array_merge(...$cartProductsList);

        if (! count($products)) {
            return trans('product::app.checkout.cart.integrity.qty-missing');
        }

        return $products;
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(GroupedIndexer::class);
    }
}
