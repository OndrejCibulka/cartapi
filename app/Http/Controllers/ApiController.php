<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiController extends Controller
{
    public function getProducts()
    {
    	$products = Product::all()->toArray();
    	$output = [];

    	foreach ($products as $product) 
		{
    		$output[] = $this->camelCaseProduct($product);
    	}

    	return json_encode($output);
    }

    public function cartProductAdd(Request $request)
    {
    	/* ======== */
    		$p = Product::find($request->id);
    		$productId = $p->product_id;
	    	$variantId = $p->variant_id;
	    	$amount = $p->amount;
    	/* ======== */

    	$output = [];
    	// $productId = $request->productId;
    	// $variantId = $request->variantId;
    	// $amount = $request->amount;

    	$addedProduct = Product::where('product_id', $productId)->where('variant_id', $variantId)->first();
    	$sessionProducts = Session::get('cart-products', []);
    	$sessionProducts[] = [
			'id'          => $addedProduct->id,
			'ordering'    => 1,
			'productId'   => $addedProduct->product_id,
			'code'        => $addedProduct->code,
			'baseName'    => $addedProduct->complete_name,
			'variantId'   => $addedProduct->variant_id,
			'variantName' => $addedProduct->complete_name,
			'amount'      => $amount,
			'amountUnit'  => $addedProduct->amount_unit
    	];
    	session(['cart-products' => $sessionProducts]);
    	$prices = $this->calcPrices($sessionProducts);

    	$output['product'] = end($sessionProducts);
    	$output['summary'] = $prices;

    	return json_encode($output);
    }

    public function cartProductRemove(Request $request)
    {
    	$sessionProducts = Session::get('cart-products', []);

    	foreach ($sessionProducts as $key => $value) {
    		if ($value['id'] == $request->id) {
    			unset($sessionProducts[$key]);
    		}
    	}

    	session(['cart-products' => $sessionProducts]);
    	return 'odebráno';
    }

    public function getCart()
    {
    	$products = Session::get('cart-products', []);
    	return json_encode($products);
    }

    private function camelCaseProduct($product)
    {
    	$p = [];
    	foreach ($product as $key => $value) {
    		$p[camel_case($key)] = $value;
    	}
    	return $p;
    }

    private function calcPrices($products)
    {
    	$pIDs = [];
    	foreach ($products as $product) {
    		$pIDs[] = $product['id'];
    	}
    	$products = Product::whereIn('id', $pIDs)->get();
    	$priceVAT = 0;
    	foreach ($products as $product) {
    		$priceVAT += $product->price_with_vat_for_customer;
    	}

    	$price = $priceVAT * 0.79;

    	$prices = [
			'price'         => round($price, 2), // cena bez dpi
			'priceVAT'      => round($priceVAT, 2), // cena s dph
			'taxPrecentage' => 21, // procenta daně
			'taxValue'      => round($priceVAT - $price, 2)   // cena daně 
    	];

    	return $prices;
    }
}
