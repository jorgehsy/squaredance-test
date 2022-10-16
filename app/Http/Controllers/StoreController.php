<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Make the sale of the product deducting their inventory
     * @param  \Illuminate\Http\Request  $request
     * @param Product
     */
    public function makeSell(Request $request, Product $product){
        try {

            $user = $request->user();
            $transaction = Transaction::createSale($user, $product);

        } catch (\Exception $e){
            abort(400, $e->getMessage());
        }

        return response()->json(['status' => 'success', $transaction]);
    }
}
