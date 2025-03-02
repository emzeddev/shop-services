<?php

namespace Modules\Core\Repositories;

use Modules\Core\Eloquent\Repository;

class SubscribersListRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Core\Contracts\SubscribersList';
    }

    /**
     * Delete a slider item and delete the image from the disk or where ever it is
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}
