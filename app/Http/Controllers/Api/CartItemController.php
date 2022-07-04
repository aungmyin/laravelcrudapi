<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //$cartitem = CartItem::all();
        $cartitem = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
               ->get(['cart_items.*', 'products.name']);

        return sendResponse(CartItemResource::collection($cartitem), 'Cart items retrieved successfully.');
    }

    public function create(Request $request, CartItem $cartitem)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $cartitem->product_id = $request->product_id;

            $cartitem->save();

            $success['product_id']  = $cartitem->product_id;
            $message          = 'Yay! A cart item has been successfully created.';
            $success['token'] = $cartitem->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a cart item.';
        }

        return sendResponse($success, $message);
    }

    public function updatebyid(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id'       => 'required'
        ]);

        //dd($request);

       if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);


        try {
            $cartitem = CartItem::find($id);
            $cartitem->product_id = $request->product_id;

            $cartitem->save();

            $success['product_id'] = $cartitem->product_id;
            $message = 'Yay! cart item has been successfully updated.';
            
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the cart item.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $products
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroybyid(Request $request, $id)
    {
        try {
            $cartitem = CartItem::find($id);
            $cartitem->delete();

            return sendResponse([], 'The cart item has been successfully deleted.');

        } catch (Exception $e) {

            return sendError('Oops! Unable to delete cart item.');
        }
    }

}
