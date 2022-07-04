<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::all();

        return sendResponse(ProductResource::collection($products), 'Product retrieved successfully.');
    }

    /**
     * Product add new API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|min:4',
            'price' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product = Product::create([
                'name'     => $request->name,
                'price'    => $request->price,
                'category_id' => $request->category_id,
                'image'     => $request->image,
            ]);

            $success['name']  = $product->name;
            $message          = 'Yay! A product has been successfully created.';
            $success['token'] = $product->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|min:4',
            'price' => 'required',
            'category_id' => 'required',
            'image'     => 'required',
        ]);


        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product    = Product::create([
                'name'       => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'image' => $request->image,
            ]);
            $success = new PostResource($product);
            $message = 'Yay! A product has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new product.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $products = Product::find($id);

        if (is_null($products)) return sendError('products not found.');

        return sendResponse(new ProductResource($products), 'products retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post    $products
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $products)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|min:5',
            'price' => 'required|min:6',
            'category_id' => 'required',
            'image'     => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $storefilepath = $request->file('file')->store('apiDocs');

        //return ['result' => $result];

        try {
            $product = [
                'name'     => $request->name,
                'price'    => $request->price,
                'category_id' => $request->category_id,
                'image'     => $storefilepath,
            ];

            $product->update($request->all());

            //$success = new ProductResource($products);
            $message = 'Yay! products has been successfully updated.';

        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the products.';
        }

        return sendResponse($message);
    }

    public function updatebyid(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'image'     => 'required|file',
        ]);

        //dd($request);

       if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $storefilepath = $request->file('image')->store('apiDocs');


        try {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->image = $storefilepath;

            $product->save();

            $success['name'] = $product->name;
            $success['id'] = $product->id;
            $message = 'Yay! products has been successfully updated.';
            
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the products.';
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
            $products = Product::find($id);
            $products->delete();
            return sendResponse([], 'The products has been successfully deleted.');
        } catch (Exception $e) {
            return sendError('Oops! Unable to delete products.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $products
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $products)
    {
        try {
            $products->delete();
            return sendResponse([], 'The products has been successfully deleted.');
        } catch (Exception $e) {
            return sendError('Oops! Unable to delete products.');
        }
    }
}
