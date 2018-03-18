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
	Route::get('product/detail/get', ['as' => 'apiProductDetailGet', 'uses' => 'ApiController@getProductDetail']);
	Route::get('cart/get', ['as' => 'apiCartGet', 'uses' => 'ApiController@getCart']);
	Route::post('cart/product/add', ['as' => 'apiCartProductAdd', 'uses' => 'ApiController@cartProductAdd']);
	Route::post('cart/product/remove', ['as' => 'apiCartProductRemove', 'uses' => 'ApiController@cartProductRemove']);
	Route::get('cart/change/amount', ['as' => 'apiCartProductChangeAmount', 'uses' => 'ApiController@cartProductChangeAmount']);
	Route::get('cart/voucher/apply', ['as' => 'apiCartVoucherApply', 'uses' => 'ApiController@cartVoucherApply']);
	Route::get('cart/voucher/remove', ['as' => 'apiCartVoucherRemove', 'uses' => 'ApiController@cartVoucherRemove']);
});
