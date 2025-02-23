<?php

namespace Modules\Customer\Http\Controllers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use Modules\Customer\Http\Resources\CompareItemResource;
use Modules\Customer\Repositories\CompareItemRepository;

class CompareController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CompareItemRepository $compareItemRepository) {}

    /**
     * Returns the compare items of the customer.
     */
    public function items(int $id): JsonResource
    {
        $compareItems = $this->compareItemRepository
            ->with('product')
            ->where('customer_id', $id)
            ->get();

        return CompareItemResource::collection($compareItems);
    }

    /**
     * Removes the item from the cart if it exists.
     */
    public function destroy(int $id): JsonResource
    {
        $this->validate(request(), [
            'item_id' => 'required|exists:compare_items,id',
        ]);

        $this->compareItemRepository->delete(request()->input('item_id'));

        return new JsonResource([
            'message' => trans('admin::app.customers.customers.view.compare.delete-success'),
        ]);
    }
}
