<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Product::available()->with('owner')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest  $productRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $productRequest)
    {
        $user = $productRequest->user();
        $product = $user->products()->create(
            $productRequest->toArray()
        );

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        if ( !$product->canBeSell() )
            return abort(404);

        return response()->json($product);
    }

    /**
     * Methods to make the demo front
     */
    public function pendingList(){
        return response()->json(Product::pending()->get());
    }
}
