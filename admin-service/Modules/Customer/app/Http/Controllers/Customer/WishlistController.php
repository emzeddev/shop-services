<?php

namespace Modules\Customer\Http\Controllers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use Modules\Customer\Http\Resources\WishlistItemResource;
use Modules\Customer\Repositories\WishlistRepository;

class WishlistController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected WishlistRepository $wishlistRepository) {}

    /**
     * Returns the compare items of the customer.
     */
    public function items(int $id): JsonResource
    {
        $wishlistItems = $this->wishlistRepository
            ->with('product')
            ->where('customer_id', $id)
            ->get();

        return WishlistItemResource::collection($wishlistItems);
    }

    /**
     * Removes the item from the cart if it exists.
     */
    public function destroy(int $id): JsonResource
    {
        $this->validate(request(), [
            'item_id' => 'required|exists:wishlist_items,id',
        ]);

        $this->wishlistRepository->delete(request()->input('item_id'));

        return new JsonResource([
            'message' => trans('admin::app.customers.customers.view.wishlist.delete-success'),
        ]);
    }
}
