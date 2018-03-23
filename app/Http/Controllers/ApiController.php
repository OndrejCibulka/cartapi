<?php

namespace App\Http\Controllers;

use App\Carrier;
use App\CarrierPlace;
use App\Payment;
use App\Product;
use App\Variant;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
	public function getProducts()
	{
		$products = Product::all();
		$output = [];

		foreach ($products as $p) {
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

	public function getProductDetail(Request $request)
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
			'variants'            => [],
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

	public function cartProductAdd(Request $request)
	{
		// $productId = 1;
		// $variantId = 1;
		// $amount = 1;

		$output = [];
		$productId = $request->productId;
		$variantId = $request->variantId;
		$amount = $request->amount;

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
				'priceVAT'    => round($this->calcPriceWithoutVat($addedVariant->price_with_vat_for_customer), 2),
				'stockCount'  => round($addedVariant->stock_count, 2),
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

	public function cartProductRemove(Request $request)
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
		$output = $this->calcPrices($sessionProducts);
		return json_encode($output);
	}

	public function getCart()
	{
		$products = Cache::get('cart-products', []);

		foreach ($products as $key => $value) {
			$value['price'] .= ' Kč';
			$value['priceVAT'] .= ' Kč';
			$products[$key] = $value;
		}
		$output = [
			'products' => $products,
			'summary' => $this->calcPrices($products),
			'voucherCode' => isset(Cache::get('cart-voucher')['code']) ? Cache::get('cart-voucher')['code'] : '',
		];
		return json_encode($output);
	}

	public function cartProductChangeAmount(Request $request)
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
			'summary' => $this->calcPrices($sessionProducts),
		];
		return json_encode($output);
	}

	public function cartVoucherApply(Request $request)
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

			$output = $this->calcPrices(Cache::get('cart-products', []));

			return json_encode($output);
		} else {
			http_response_code(422);
			$output = [
				'error' => 'Voucher není validní',
			];

			echo json_encode($output);
		}
	}

	public function cartVoucherRemove()
	{
		Cache::put('cart-voucher', [], 120);

		$output = $this->calcPrices(Cache::get('cart-products', []));

		return json_encode($output);
	}

	public function getCarriers()
	{
		$cs = Carrier::all();

		$output = [];
		foreach ($cs as $c) {
			$output[] = [
				'id'          => $c->id,
				'label'       => $c->label,
				'name'        => $c->name,
				'image'       => $c->image,
				'description' => $c->description,
				'price'       => $c->price,
			];
		}

		return json_encode($output);
	}

	public function getCarrierDetail(Request $request)
	{
		$carrierId = $request->carrierId;
		// $carrierId = 1;

		$c = Carrier::find($carrierId);
		$cps = CarrierPlace::where('carrier_id', $carrierId)->get();

		$output = [
			'fullDescription' => $c->full_description,
			// 'places' => [],
		];

		foreach ($cps as $cp) {
			$output['places'][] = [
				'placeId' => $cp->place_id,
				'placeLabel' => $cp->label,
			];
		}

		return json_encode($output);
	}

	public function setCarrier(Request $request)
	{
		$carrierId = $request->carrierId;
		$placeId = $request->placeId;

		// $carrierId = 1;
		// $placeId = 1;

		$carrier = [
			'carrierId' => $carrierId,
			'placeId' => $placeId,
		];
		Cache::put('cart-carrier', $carrier, 120);
	}

	public function unsetCarrier()
	{
		Cache::put('cart-carrier', [], 120);
	}

	public function getPayments()
	{
		$ps = Payment::all();

		$output = [];
		foreach ($ps as $p) {
			$output[] = [
				'id' => $p->id,
				'name' => $p->name,
				'label' => $p->label,
				'price' => $p->price,
				'description' => $p->description,
				'longDescription' => $p->long_description,
				'image' => $p->image,
			];
		}

		return json_encode($output);
	}

	public function setPayment(Request $request)
	{
		$paymentId = $request->paymentId;

		// $paymentId = 1;

		Cache::put('cart-payment', ['paymentId' => $paymentId], 120);
	}

	public function unsetPayment()
	{
		Cache::put('cart-payment', [], 120);
	}

	public function getCartSummary()
	{
		$cartCarrier = Cache::get('cart-carrier');
		$c = Carrier::find($cartCarrier['carrierId']);
		$cp = CarrierPlace::where('carrier_id', $cartCarrier['carrierId'])->where('place_id', $cartCarrier['placeId'])->first();

		$cartPayment = Cache::get('cart-payment');
		$p = Payment::find($cartPayment['paymentId']);

		$cu = Cache::get('cart-customer');
		\Log::info('z cache:'.json_encode($cu));

		$shippingInfo = [];
		if ($cu['sendElseWhere'] == true) {
			$shippingInfo = [
				'name' => $cu['deliveryFirstName'] . ' ' . $cu['deliverySurName'],
				'address' => $cu['deliveryAddress'],
				'city' => $cu['deliveryCity'],
				'zipCode' => $cu['deliveryZipCode'],
				'phone' => $cu['phone'],
			];
		} else {
			$shippingInfo = [
				'name' => $cu['firstName'] . ' ' . $cu['surName'],
				'address' => $cu['address'],
				'city' => $cu['city'],
				'zipCode' => $cu['zipCode'],
				'phone' => $cu['phone'],
			];
		}


		$output = [
			'cart' => json_decode($this->getCart()),
			'carrier' => [
				'image' => $c->image,
				'label' => $c->label,
				'price' => $c->price,
				'placeLabel' => isset($cp->label) ?: '',
			],
			'payment' => [
				'image' => $p->image,
				'label' => $p->label,
				'price' => $p->price
			],
			'customerInfo' => [
				'billingInfo' => [
					'name' => $cu['firstName'] . ' ' . $cu['surName'],
					'address' => $cu['address'],
					'city' => $cu['city'],
					'zipCode' => $cu['zipCode'],
					'email' => $cu['email'],
					'phone' => $cu['phone'],
				],
			],
		];

		$output['customerInfo']['shippingInfo'] = $shippingInfo;
		\Log::info('summary json: '. json_encode($output));
		return json_encode($output);
	}

	public function checkTerms(Request $request)
	{
		$checked = $request->acceptTerms;
		if ($checked) {
			$output = [
				'link' => '/dekujeme.php',
			];
			Cache::put('cart-products', [], 120);
			return json_encode($output);
		} else {
			http_response_code(422);
			$output = [
				'error' => 'Prosím souhlaste s podmínkami.',
			];
			echo json_encode($output);
		}
	}

	public function setCustomer(Request $request)
	{
		Cache::put('cart-customer', $request->all(), 120);

		$output = [];
		return json_encode($output);
	}

	private function calcPrices($products) {
		$priceVAT = 0;
		foreach ($products as $product) {
			$v = Variant::where('variant_id', $product['variantId'])->where('product_id', $product['productId'])->first();
			$priceVAT += intval($v->price_with_vat_for_customer) * intval($product['amount']);
		}

		$discount = Cache::get('cart-voucher', []);

		if (isset($discount['discount_value'])) {
			$priceVAT = $priceVAT * ((100 - $discount['discount_value']) / 100);
		}

		$price = $this->calcPriceWithoutVat($priceVAT);

		$prices = [
			'price' => round($price, 2) . ' Kč', // cena bez dph
			'priceVAT' => round($priceVAT, 2) . ' Kč', // cena s dph
			'taxPrecentage' => 21, // procenta daně
			'taxValue' => round($priceVAT - $price, 2) . ' Kč', // cena daně
		];

		return $prices;
	}

	private function calcPriceWithoutVat($price) {
		return $price * 0.79;
	}
}
