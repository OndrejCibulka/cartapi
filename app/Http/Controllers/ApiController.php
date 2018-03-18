<?php

namespace App\Http\Controllers;

use App\Product;
use App\Variant;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ApiController extends Controller
{
    public function getProducts() // DONE
    {
    	$products = Product::all();
    	$output = [];

    	foreach ($products as $p)
		{
            $v = Variant::where('product_id', $p->id)->first();
            $priceVAT = $v->price_with_vat_for_customer;
    		$output[] = [
                'productId'          => $p->id,
                'productName'        => $p->name,
                'url'                => $p->url,
                'descriptionSummary' => $p->description_summary,
                'image'              => $p->image,
                'price'              => $this->calcPriceWithoutVat($priceVAT),
                'priceVAT'           => $priceVAT,
                'stockCount'         => $v->stock_count,
            ];
    	}

    	return json_encode($output);
    }

    public function getProductDetail(Request $request) // DONE
    {
        // $productId = $request->productId;

        $productId = 1;

        $p = Product::find($productId);
        $vs = Variant::where('product_id', $productId)->get();

        $output = [
            'product' => [
                'productId'           => $p->id,
                'productName'         => $p->name,
                'url'                 => $p->url,
                'descriptionSummary'  => $p->description_summary,
                'code'                => $p->code,
                'producerName'        => $p->producer_name,
                'producerHomePageUrl' => $p->producer_home_page_url,
                'image'               => $p->image,
            ],
            'variants' => [],
        ];

        foreach ($vs as $v) {
            $output['variants'][] = [
                'variantId'   => $v->variant_id,
                'variantname' => $v->name,
                'price'       => $this->calcPriceWithoutVat($v->price_with_vat_for_customer),
                'priceVAT'    => $v->price_with_vat_for_customer,
                'stockCount'  => $v->stock_count,
            ];
        }

        return json_encode($output);
    }

    public function cartProductAdd(Request $request) // DONE
    {
		$productId = 1;
    	$variantId = 1;
    	$amount = 1;

    	// $output = [];
    	// $productId = $request->productId;
    	// $variantId = $request->variantId;
    	// $amount = $request->amount; // jestli se množstí přidává, nebo nastaví

    	$addedProduct = Product::find($productId);
        $addedVariant = Variant::where('product_id', $productId)->where('variant_id', $variantId)->first();
    	$sessionProducts = Session::get('cart-products', []);
    	$isInCart = false;
    	$sessionProduct = null;
    	foreach ($sessionProducts as $key => $value) {
    		if ($value['productId'] == $productId && $value['variantId'] == $variantId) {
    			$value['amount'] += $amount;
    			$sessionProducts[$key] = $value;
    			$sessionProduct = $value;
    			$isInCart = true;
    			break;
    		}
    	}

    	if (!$isInCart) {
    		$sessionProduct = [
				'ordering'    => 1,
                'productId'   => intval($addedProduct->id),
				'variantId'   => $addedVariant->variant_id,
                'code'        => $addedProduct->code,
                'productName' => $addedProduct->name,
				'variantName' => $addedVariant->name,
				'amount'      => $amount,
				'amountUnit'  => $addedProduct->amount_unit,
                'price'       => $addedVariant->price_with_vat_for_customer,
                'priceVAT'    => $this->calcPriceWithoutVat($addedVariant->price_with_vat_for_customer),
                'stockCount'  => $addedVariant->stock_count
	    	];
	    	$sessionProducts[] = $sessionProduct;
    	}

    	session(['cart-products' => $sessionProducts]);
    	$prices = $this->calcPrices($sessionProducts);

    	$output['product'] = $sessionProduct;
    	$output['summary'] = $prices;

        \Log::info(json_encode($output));
    	return json_encode($output);
    }

    public function cartProductRemove(Request $request) // DONE
    {
        $productId = 1;
        $variantId = 1;

    	$sessionProducts = Session::get('cart-products', []);

    	foreach ($sessionProducts as $key => $value) {
    		if ($value['productId'] == $productId && $value['variantId'] == $variantId) {
    			unset($sessionProducts[$key]);
    		}
    	}

    	session(['cart-products' => array_values($sessionProducts)]);
    	$output = [
    		'summary' => $this->calcPrices($sessionProducts)
    	];
    	return json_encode($output);
    }

    public function getCart() // DONE
    {
    	$products = Session::get('cart-products', []);
    	$output = [
    		'products' => $products,
    		'summary' => $this->calcPrices($products)
    	];
    	return json_encode($output);
    }

    public function cartProductChangeAmount(Request $request) // DONE
    {
    	// $productId = $request->productId;
    	// $variantId = $request->variantId;
    	// $amount = $request->amount;

		$productId = 1;
    	$variantId = 1;
    	$amount = 2;

    	$sessionProducts = Session::get('cart-products', []);

    	foreach ($sessionProducts as $key => $value) {
    		if ($value['productId'] == $productId && $value['variantId'] == $variantId) {
    			$value['amount'] = $amount;
    			$sessionProducts[$key] = $value;
    			break;
    		}
    	}

    	session(['cart-products' => $sessionProducts]);
    	$output = [
    		'summary' => $this->calcPrices($sessionProducts)
    	];
    	return json_encode($output);
    }

    public function cartVoucherApply(Request $request) // DONE
    {
        $voucherCode = 'SLEVA50';

        $voucher = Voucher::where('code', $voucherCode)->first();

        if ($voucher) {
            $sessionVoucher = [
                'code' => $voucherCode,
                'discount_value' => $voucher->discount_value,
            ];
            session(['cart-voucher' => $sessionVoucher]);
            
            $output = [
                'summary' => $this->calcPrices(Session::get('cart-products', []))
            ];

            return json_encode($output);
        } else {
            http_response_code(422);
            $output = [
                'error' => 'Voucher není validní'
            ];
        }
    }

    public function cartVoucherRemove() // DONE
    {
        session(['cart-voucher' => []]);
        
        $output = [
            'summary' => $this->calcPrices(Session::get('cart-products', []))
        ];

        return json_encode($output);
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
    	$priceVAT = 0;
    	foreach ($products as $product) {
            $v = Variant::where('variant_id', $product['variantId'])->where('product_id', $product['productId'])->first();
    		$priceVAT += $v->price_with_vat_for_customer * $product['amount'];
    	}

        $discount = Session::get('cart-voucher', []);

        if (isset($discount['discount_value'])) {
            $priceVAT = $priceVAT * ((100 - $discount['discount_value']) / 100);
        }

    	$price = $this->calcPriceWithoutVat($priceVAT);

    	$prices = [
			'price'         => round($price, 2), // cena bez dph
			'priceVAT'      => round($priceVAT, 2), // cena s dph
			'taxPrecentage' => 21, // procenta daně
			'taxValue'      => round($priceVAT - $price, 2)   // cena daně
    	];

    	return $prices;
    }

    private function calcPriceWithoutVat($price)
    {
        return $price * 0.79;
    }
}
