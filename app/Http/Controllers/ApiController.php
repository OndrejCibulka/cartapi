<?php

namespace App\Http\Controllers;

use App\Product;
use App\Variant;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

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
                'price'              => $this->calcPriceWithoutVat($priceVAT) . ' Kč',
                'priceVAT'           => $priceVAT . ' Kč',
                'stockCount'         => $v->stock_count,
            ];
    	}

    	return json_encode($output);
    }

    public function getProductDetail(Request $request) // DONE
    {
        $productId = $request->productId;

        // $productId = 1;

        $p = Product::find($productId);
        $vs = Variant::where('product_id', $productId)->get();

        $output = [
            'productId'           => $p->id,
            'productName'         => $p->name,
            'url'                 => $p->url,
            'descriptionSummary'  => $p->description_summary,
            'code'                => $p->code,
            'producerName'        => $p->producer_name,
            'producerHomePageUrl' => $p->producer_home_page_url,
            'image'               => $p->image,
            'variants' => [],
        ];

        foreach ($vs as $v) {
            $output['variants'][] = [
                'variantId'   => $v->variant_id,
                'variantName' => $v->name,
                'price'       => $this->calcPriceWithoutVat($v->price_with_vat_for_customer) . ' Kč',
                'priceVAT'    => $v->price_with_vat_for_customer . ' Kč',
                'stockCount'  => $v->stock_count,
            ];
        }

        return json_encode($output);
    }

    public function cartProductAdd(Request $request) // DONE
    {
		// $productId = 1;
    	// $variantId = 1;
    	// $amount = 1;

    	$output = [];
    	$productId = $request->productId;
    	$variantId = $request->variantId;
    	$amount = $request->amount; // jestli se množstí přidává, nebo nastaví

    	$addedProduct = Product::find($productId);
        $addedVariant = Variant::where('product_id', $productId)->where('variant_id', $variantId)->first();
    	$sessionProducts = Cache::get('cart-products', []);
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
                'image'       => $addedProduct->image,
				'amount'      => $amount,
				'amountUnit'  => $addedProduct->amount_unit,
                'price'       => round($addedVariant->price_with_vat_for_customer, 2),
                'priceVAT'    => round($this->calcPriceWithoutVat($addedVariant->price_with_vat_for_customer),2),
                'stockCount'  => round($addedVariant->stock_count, 2)
	    	];
	    	$sessionProducts[] = $sessionProduct;
    	}

    	Cache::put('cart-products', $sessionProducts, 120);
    	$prices = $this->calcPrices($sessionProducts);

        $sessionProduct['price'] .= ' Kč';
        $sessionProduct['priceVAT'] .= ' Kč';

    	$output['product'] = $sessionProduct;
    	$output['summary'] = $prices;

    	return json_encode($output);
    }

    public function cartProductRemove(Request $request) // DONE
    {
        // $productId = 1;
        // $variantId = 1;

        $productId = $request->productId;
        $variantId = $request->variantId;


    	$sessionProducts = Cache::get('cart-products', []);

    	foreach ($sessionProducts as $key => $value) {
    		if ($value['productId'] == $productId && $value['variantId'] == $variantId) {
    			unset($sessionProducts[$key]);
    		}
    	}

    	Cache::put('cart-products', array_values($sessionProducts), 120);
    	$output = $this->calcPrices($sessionProducts);
    	return json_encode($output);
    }

    public function getCart() // DONE
    {
    	$products = Cache::get('cart-products', []);

        foreach ($products as $key => $value) {
            $value['price'] .= ' Kč';
            $value['priceVAT'] .= ' Kč';
            $products[$key] = $value;
        }

    	$output = [
    		'products' => $products,
    		'summary' => $this->calcPrices($products)
    	];
    	return json_encode($output);
    }

    public function cartProductChangeAmount(Request $request) // DONE
    {
    	$productId = $request->productId;
    	$variantId = $request->variantId;
    	$amount = $request->amount;

		// $productId = 1;
    	// $variantId = 1;
    	// $amount = 2;

    	$sessionProducts = Cache::get('cart-products', []);

        $sessionProduct = null;
    	foreach ($sessionProducts as $key => $value) {
    		if ($value['productId'] == $productId && $value['variantId'] == $variantId) {
    			$value['amount'] = $amount;
    			$sessionProducts[$key] = $value;
                $value['price'] = ($value['price'] * $amount) . ' Kč';
                $value['priceVAT'] = ($value['priceVAT'] * $amount) . ' Kč';
                $sessionProduct = $value;
    			break;
    		}
    	}

    	Cache::put('cart-products', $sessionProducts, 120);
    	$output = [
            'product' => $sessionProduct,
    		'summary' => $this->calcPrices($sessionProducts)
    	];
    	return json_encode($output);
    }

    public function cartVoucherApply(Request $request) // DONE
    {
        // $voucherCode = 'SLEVA50';
        $voucherCode = $request->voucherCode;

        $voucher = Voucher::where('code', $voucherCode)->first();

        if ($voucher) {
            $sessionVoucher = [
                'code' => $voucherCode,
                'discount_value' => $voucher->discount_value,
            ];
            Cache::put('cart-voucher', $sessionVoucher, 120);
            
            $output = $this->calcPrices(Cache::get('cart-products', []));

            return json_encode($output);
        } else {
            http_response_code(422);
            $output = [
                'error' => 'Voucher není validní'
            ];

            echo json_encode($output);
        }
    }

    public function cartVoucherRemove() // DONE
    {
        Cache::put('cart-voucher', [], 120);
        
        $output = $this->calcPrices(Cache::get('cart-products', []));

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

        $discount = Cache::get('cart-voucher', []);

        if (isset($discount['discount_value'])) {
            $priceVAT = $priceVAT * ((100 - $discount['discount_value']) / 100);
        }

    	$price = $this->calcPriceWithoutVat($priceVAT);

    	$prices = [
			'price'         => round($price, 2) . ' Kč', // cena bez dph
			'priceVAT'      => round($priceVAT, 2) . ' Kč', // cena s dph
			'taxPrecentage' => 21, // procenta daně
			'taxValue'      => round($priceVAT - $price, 2) . ' Kč'   // cena daně
    	];

    	return $prices;
    }

    private function calcPriceWithoutVat($price)
    {
        return $price * 0.79;
    }
}
