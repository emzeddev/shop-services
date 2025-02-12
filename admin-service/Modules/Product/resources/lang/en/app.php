<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'At least one product should have more than 1 quantity.',
            ],

            'inventory-warning' => 'The requested quantity is not available, please try again later.',
            'missing-links'     => 'Downloadable links are missing for this product.',
            'missing-options'   => 'Options are missing for this product.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copy-of-:value',
        'copy-of'                       => 'Copy Of :value',
        'variant-already-exist-message' => 'Variant with same attribute options already exists.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Products of type :type can not be copied',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Cheapest First',
            'expensive-first' => 'Expensive First',
            'from-a-z'        => 'From A-Z',
            'from-z-a'        => 'From Z-A',
            'latest-first'    => 'Newest First',
            'oldest-first'    => 'Oldest First',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Buy :qty for :price each and save :discount',
        ],

        'bundle'       => 'Bundle',
        'configurable' => 'Configurable',
        'downloadable' => 'Downloadable',
        'grouped'      => 'Grouped',
        'simple'       => 'Simple',
        'virtual'      => 'Virtual',
    ],

    'products' => [
        'index' => [
            'already-taken' => 'The :name has already been taken.',
            'create-btn'    => 'Create Product',
            'title'         => 'Products',

            'create' => [
                'back-btn'                => 'Back',
                'configurable-attributes' => 'Configurable Attributes',
                'create-btn'              => 'Create Product',
                'family'                  => 'Family',
                'save-btn'                => 'Save Product',
                'sku'                     => 'SKU',
                'title'                   => 'Create New Product',
                'type'                    => 'Type',
            ],

            'datagrid' => [
                'active'                 => 'Active',
                'attribute-family-value' => 'Attribute Family - :attribute_family',
                'attribute-family'       => 'Attribute Family',
                'category'               => 'Category',
                'channel'                => 'Channel',
                'copy-of-slug'           => 'copy-of-:value',
                'copy-of'                => 'Copy Of :value',
                'delete'                 => 'Delete',
                'disable'                => 'Disable',
                'id-value'               => 'Id - :id',
                'id'                     => 'Id',
                'image'                  => 'Image',
                'mass-delete-success'    => 'Selected Products Deleted Successfully',
                'mass-update-success'    => 'Selected Products Updated Successfully',
                'name'                   => 'Name',
                'out-of-stock'           => 'Out of Stock',
                'price'                  => 'Price',
                'product-image'          => 'Product Image',
                'qty-value'              => ':qty Available',
                'qty'                    => 'Quantity',
                'sku-value'              => 'SKU - :sku',
                'sku'                    => 'SKU',
                'status'                 => 'Status',
                'type'                   => 'Type',
                'update-status'          => 'Update Status',
            ],
        ],

        'edit' => [
            'preview'  => 'Preview',
            'remove'   => 'Remove',
            'save-btn' => 'Save Product',
            'title'    => 'Edit Product',

            'channels' => [
                'title' => 'Channels',
            ],

            'price' => [
                'group' => [
                    'add-group-price'           => 'Add Group Price',
                    'all-groups'                => 'All Groups',
                    'create-btn'                => 'Add New',
                    'discount-group-price-info' => 'For :qty Qty at discount of :price',
                    'edit-btn'                  => 'Edit',
                    'empty-info'                => 'Special pricing for customers belonging to a specific group.',
                    'fixed-group-price-info'    => 'For :qty Qty at fixed price of :price',
                    'title'                     => 'Customer Group Price',

                    'create' => [
                        'all-groups'     => 'All Groups',
                        'create-title'   => 'Create Customer Group Price',
                        'customer-group' => 'Customer Group',
                        'delete-btn'     => 'Delete',
                        'discount'       => 'Discount',
                        'fixed'          => 'Fixed',
                        'price'          => 'Price',
                        'price-type'     => 'Price Type',
                        'qty'            => 'Minimum Qty',
                        'save-btn'       => 'Save',
                        'update-title'   => 'Update Customer Group Price',
                    ],
                ],
            ],

            'inventories' => [
                'pending-ordered-qty'      => 'Pending Ordered Qty: :qty',
                'pending-ordered-qty-info' => 'Pending Ordered quantity will be deducted from the respective inventory source after the shipment. In case of cancellation pending quantity will be available for sale.',
                'title'                    => 'Inventories',
            ],

            'categories' => [
                'title' => 'Categories',
            ],

            'images' => [
                'info'  => 'Image resolution should be like 560px X 609px',
                'title' => 'Images',
            ],

            'videos' => [
                'error' => 'The :attribute may not be greater than :max kilobytes. Please choose a smaller file.',
                'info'  => 'Maximum video size should be like :size',
                'title' => 'Videos',
            ],

            'links' => [
                'related-products' => [
                    'empty-info' => 'Add related products on the go.',
                    'info'       => 'In addition to the product the customer is viewing, they are presented with related products.',
                    'title'      => 'Related Products',
                ],

                'up-sells' => [
                    'empty-info' => 'Add up sells products on the go.',
                    'info'       => 'The customer is presented with an up-sell products, which serves as a premium or higher-quality alternative to the product they are currently viewing.',
                    'title'      => 'Up-Sell Products',
                ],

                'cross-sells' => [
                    'empty-info' => 'Add cross sells products on the go.',
                    'info'       => 'Adjacent to the shopping cart, you\'ll find these \"impulse-buy\" products positioned as cross-sells to complement the items already added to your cart.',
                    'title'      => 'Cross-Sell Products',
                ],

                'add-btn'           => 'Add Product',
                'delete'            => 'Delete',
                'empty-info'        => 'To add :type products on a go.',
                'empty-title'       => 'Add Product',
                'image-placeholder' => 'Product Image',
                'sku'               => 'SKU - :sku',
            ],

            'types' => [
                'configurable' => [
                    'add-btn'           => 'Add Variant',
                    'delete-btn'        => 'Delete',
                    'edit-btn'          => 'Edit',
                    'empty-info'        => 'To create various combination of product on a go.',
                    'empty-title'       => 'Add Variant',
                    'image-placeholder' => 'Product Image',
                    'info'              => 'Variation products are depend on all possible combination of attribute.',
                    'qty'               => ':qty Qty',
                    'sku'               => 'SKU - :sku',
                    'title'             => 'Variations',

                    'create' => [
                        'description'            => 'Description',
                        'name'                   => 'Name',
                        'save-btn'               => 'Add',
                        'title'                  => 'Add Variant',
                        'variant-already-exists' => 'This variant already exists',
                    ],

                    'edit' => [
                        'disabled'        => 'Disabled',
                        'edit-info'       => 'If you want to update product information in detail, then go to the',
                        'edit-link-title' => 'Product Details Page',
                        'enabled'         => 'Enabled',
                        'images'          => 'Images',
                        'name'            => 'Name',
                        'price'           => 'Price',
                        'quantities'      => 'Quantities',
                        'save-btn'        => 'Save',
                        'sku'             => 'SKU',
                        'status'          => 'Status',
                        'title'           => 'Product',
                        'weight'          => 'Weight',
                    ],

                    'mass-edit' => [
                        'add-images'          => 'Add Images',
                        'apply-to-all-btn'    => 'Apply to All',
                        'apply-to-all-name'   => 'Apply a name to all variants.',
                        'apply-to-all-sku'    => 'Apply a price to all SKU.',
                        'apply-to-all-status' => 'Apply a status to all variants.',
                        'apply-to-all-weight' => 'Apply a weight to all variants.',
                        'edit-inventories'    => 'Edit Inventories',
                        'edit-names'          => 'Edit Names',
                        'edit-prices'         => 'Edit Prices',
                        'edit-sku'            => 'Edit SKU',
                        'edit-status'         => 'Edit Status',
                        'edit-weight'         => 'Edit Weight',
                        'name'                => 'Name',
                        'price'               => 'Price',
                        'remove-images'       => 'Remove Images',
                        'remove-variants'     => 'Remove Variants',
                        'select-action'       => 'Select Action',
                        'select-variants'     => 'Select Variants',
                        'status'              => 'Status',
                        'variant-name'        => 'Variant Name',
                        'variant-sku'         => 'Variant SKU',
                        'weight'              => 'Weight',
                    ],
                ],

                'grouped' => [
                    'add-btn'           => 'Add Product',
                    'default-qty'       => 'Default Qty',
                    'delete'            => 'Delete',
                    'empty-info'        => 'To create various combination of product on a go.',
                    'empty-title'       => 'Add Product',
                    'image-placeholder' => 'Product Image',
                    'info'              => 'A grouped product comprises standalone items presented as a set, allowing variations or coordination by season or theme. Each product can be bought individually or as part of the group.',
                    'sku'               => 'SKU - :sku',
                    'title'             => 'Group Products',
                ],

                'bundle' => [
                    'add-btn'           => 'Add Option',
                    'empty-info'        => 'To create bundle options on a go.',
                    'empty-title'       => 'Add Option',
                    'image-placeholder' => 'Product Image',
                    'info'              => 'A bundle product is a package of multiple items or services sold together at a special price, providing value and convenience to customers.',
                    'title'             => 'Bundle Items',

                    'update-create' => [
                        'checkbox'    => 'Checkbox',
                        'is-required' => 'Is Required',
                        'multiselect' => 'Multiselect',
                        'name'        => 'Title',
                        'no'          => 'No',
                        'radio'       => 'Radio',
                        'save-btn'    => 'Save',
                        'select'      => 'Select',
                        'title'       => 'Option',
                        'type'        => 'Type',
                        'yes'         => 'Yes',
                    ],

                    'option' => [
                        'add-btn'     => 'Add Product',
                        'default-qty' => 'Default Qty',
                        'delete'      => 'Delete',
                        'delete-btn'  => 'Delete',
                        'edit-btn'    => 'Edit',
                        'empty-info'  => 'To create various combination of product on a go.',
                        'empty-title' => 'Add Product',
                        'sku'         => 'SKU - :sku',

                        'types' => [
                            'checkbox' => [
                                'info'  => 'Set default product using checkbox',
                                'title' => 'Checkbox',
                            ],

                            'multiselect' => [
                                'info'  => 'Set default product using checkbox button',
                                'title' => 'Multiselect',
                            ],

                            'radio' => [
                                'info'  => 'Set default product using radio button',
                                'title' => 'Radio',
                            ],

                            'select' => [
                                'info'  => 'Set default product using radio button',
                                'title' => 'Select',
                            ],
                        ],
                    ],
                ],

                'downloadable' => [
                    'links' => [
                        'add-btn'     => 'Add Link',
                        'delete-btn'  => 'Delete',
                        'edit-btn'    => 'Edit',
                        'empty-info'  => 'To create link on a go.',
                        'empty-title' => 'Add Link',
                        'file'        => 'File : ',
                        'info'        => 'Downloadable product type allows to sell digital products, such as eBooks, software applications, music, games, etc.',
                        'sample-file' => 'Sample File : ',
                        'sample-url'  => 'Sample URL : ',
                        'title'       => 'Downloadable Links',
                        'url'         => 'URL : ',

                        'update-create' => [
                            'downloads'   => 'Download Allowed',
                            'file'        => 'File',
                            'file-type'   => 'File Type',
                            'name'        => 'Title',
                            'price'       => 'Price',
                            'sample'      => 'Sample',
                            'sample-type' => 'Sample Type',
                            'save-btn'    => 'Save',
                            'title'       => 'Link',
                            'url'         => 'URL',
                        ],
                    ],

                    'samples' => [
                        'add-btn'     => 'Add Sample',
                        'delete-btn'  => 'Delete',
                        'edit-btn'    => 'Edit',
                        'empty-info'  => 'To create sample on a go.',
                        'empty-title' => 'Add Sample',
                        'file'        => 'File : ',
                        'info'        => 'Downloadable product type allows to sell digital products, such as eBooks, software applications, music, games, etc.',
                        'title'       => 'Downloadable Samples',
                        'url'         => 'URL : ',

                        'update-create' => [
                            'file'      => 'File',
                            'file-type' => 'File Type',
                            'name'      => 'Title',
                            'save-btn'  => 'Save',
                            'title'     => 'Link',
                            'url'       => 'URL',
                        ],
                    ],
                ],
            ],
        ],

        'create-success'          => 'Product created successfully',
        'delete-failed'           => 'Product deleted Failed',
        'delete-success'          => 'Product deleted successfully',
        'product-copied'          => 'Product copied successfully',
        'saved-inventory-message' => 'Product saved successfully',
        'update-success'          => 'Product updated successfully',
    ],
];
