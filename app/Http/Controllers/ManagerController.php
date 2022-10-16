<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManagerController extends Controller
{
    /**
     * Manage the product status
     * @param  \App\Models\Product  $product
     * @param  string  $status
     *
     * @return Product
     */
    public function manageProduct(Product $product, string $status){
        switch ($status){
            case UserProduct::$APPROVED:
                $product->approve();
                break;
            case UserProduct::$REJECTED:
                $product->reject();
                break;

            default:
                abort(400, "Product status incorrect");
        }

        return $product;
    }
}
