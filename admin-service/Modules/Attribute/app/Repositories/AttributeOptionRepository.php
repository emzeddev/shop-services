<?php

namespace Modules\Attribute\Repositories;

use Illuminate\Http\UploadedFile;
use Modules\Core\Eloquent\Repository;

class AttributeOptionRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Attribute\Contracts\AttributeOption';
    }

    /**
     * @return \Modules\Attribute\Contracts\AttributeOption
     */
    public function create(array $data)
    {
        $option = parent::create($data);

        $this->uploadSwatchImage($data, $option->id);

        return $option;
    }

    /**
     * @param  int  $id
     * @param  string  $attribute
     * @return \Modules\Attribute\Contracts\AttributeOption
     */
    public function update(array $data, $id)
    {
        $option = parent::update($data, $id);

        $this->uploadSwatchImage($data, $id);

        return $option;
    }

    /**
     * @param  array  $data
     * @param  int  $optionId
     * @return void
     */
    public function uploadSwatchImage($data, $optionId)
    {
        if (empty($data['swatch_value'])) {
            return;
        }

        if ($data['swatch_value'] instanceof UploadedFile) {
            parent::update([
                'swatch_value' => $data['swatch_value']->store('attribute_option'),
            ], $optionId);
        }
    }
}
