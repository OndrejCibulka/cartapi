<?php



header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Headers: *");



Route::get('/', function () {

	$products = App\Product::all();

    return view('index', [

    	'products' => $products,

    	'cart' => \Session::get('cart-products', [])

    ]);

});



Route::get('upload-products', ['as' => 'uploadProducts', 'uses' => 'ProductsController@uploadProducts']);



Route::prefix('api')->group(function () {

	Route::get('products/get', ['as' => 'apiProductsGet', 'uses' => 'ApiController@getProducts']);

	Route::post('product/detail/get', ['as' => 'apiProductDetailGet', 'uses' => 'ApiController@getProductDetail']);

	Route::post('cart/get', ['as' => 'apiCartGet', 'uses' => 'ApiController@getCart']);

	Route::post('cart/product/add', ['as' => 'apiCartProductAdd', 'uses' => 'ApiController@cartProductAdd']);

	Route::post('cart/product/remove', ['as' => 'apiCartProductRemove', 'uses' => 'ApiController@cartProductRemove']);

	Route::post('cart/change/amount', ['as' => 'apiCartProductChangeAmount', 'uses' => 'ApiController@cartProductChangeAmount']);

	Route::post('cart/voucher/apply', ['as' => 'apiCartVoucherApply', 'uses' => 'ApiController@cartVoucherApply']);

	Route::post('cart/voucher/remove', ['as' => 'apiCartVoucherRemove', 'uses' => 'ApiController@cartVoucherRemove']);
	Route::get('cart/carriers/get', ['as' => 'apiCartCarriersGet', 'uses' => 'ApiController@getCarriers']);
	Route::post('cart/carrier/detail/get', ['as' => 'apiCartCarrierDetailGet', 'uses' => 'ApiController@getCarrierDetail']);
	Route::post('cart/carrier/set', ['as' => 'apiCartCarrierSet', 'uses' => 'ApiController@setCarrier']);
	Route::get('cart/carrier/unset', ['as' => 'apiCartCarrierUnset', 'uses' => 'ApiController@unsetCarrier']);

	Route::get('cart/payments/get', ['as' => 'apiCartPaymentsGet', 'uses' => 'ApiController@getPayments']);
	Route::post('cart/payment/set', ['as' => 'apiCartPaymentSet', 'uses' => 'ApiController@setPayment']);
	Route::get('cart/payment/unset', ['as' => 'apiCartPaymentUnset', 'uses' => 'ApiController@unsetPayment']);

	Route::get('cart/summary/get', ['as' => 'apiCartSummaryGet', 'uses' => 'ApiController@getCartSummary']);
	Route::post('cart/terms/check', ['as' => 'apiCartTermsCheck', 'uses' => 'ApiController@checkTerms']);

	Route::post('cart/customer/set', ['as' => 'apiCartCustomerSet', 'uses' => 'ApiController@setCustomer']);
});
