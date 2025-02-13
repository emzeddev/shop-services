<?php

return [
    'type_required'                => 'The product type is required.',
    'attribute_family_id_required' => 'Selecting an attribute family is required.',
    'sku_required'                 => 'The SKU field cannot be empty.',
    'sku_unique'                   => 'The entered SKU has already been used.',
    'super_attributes_array'       => 'The main attributes must be an array.',
    'super_attributes_min'         => 'At least one main attribute must be selected.',
    'super_attributes_per_array'   => 'Each main attribute must be an array.',
    "datagiven" => "The given data was invalid.",
    'super_attributes_per_min'     => 'Each main attribute must have at least one value.',

    
    'url_key_required'             => 'The URL field is required.',
    'images_files_mimes'           => 'The image format must be one of bmp, jpeg, jpg, png, or webp.',
    'images_positions_integer'     => 'The image position must be an integer.',
    'videos_files_mimetypes'       => 'The selected video format is invalid. Allowed formats: mp4, webm, quicktime.',
    'videos_files_max'             => 'The maximum allowed video size is :max KB.',
    'videos_positions_integer'     => 'The video position must be an integer.',
    'special_price_from_date'      => 'The special price start date is invalid.',
    'special_price_to_date'        => 'The special price end date is invalid.',
    'special_price_to_after_or_equal' => 'The special price end date must be after or equal to the start date.',
    'special_price_nullable'       => 'The special price can be empty.',
    'special_price_lt'             => 'The special price must be less than the original price.',
    'visible_individually_required'=> 'The visibility status is required.',
    'visible_individually_in'      => 'The visibility status must be 0 or 1.',
    'status_required'              => 'The product status is required.',
    'status_in'                    => 'The product status must be 0 or 1.',
    'guest_checkout_required'      => 'The guest checkout option is required.',
    'guest_checkout_in'            => 'The guest checkout option must be 0 or 1.',
    'new_required'                 => 'The "new product" field is required.',
    'new_in'                       => 'The "new product" value must be 0 or 1.',
    'featured_required'            => 'The "featured product" field is required.',
    'featured_in'                  => 'The "featured product" value must be 0 or 1.',

    'inventories_required'          => 'The inventories field is required.',
    'inventories_array'             => 'The inventories must be an array.',
    'inventories_per_required'      => 'Each inventory item is required.',
    'inventories_numeric'           => 'Each inventory item must be a number.',
    'inventories_min'               => 'Each inventory item must be at least 0.',
    
    
    'indices_required'   => 'The indices field is required.',
    'indices_array'      => 'The indices must be an array.',
    'indices_per_integer'  => 'Each item in the indices array must be an integer.',

    
    
 
    
];
