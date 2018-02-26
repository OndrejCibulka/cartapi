<?php

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
	Route::get('cart/get', ['as' => 'apiCartGet', 'uses' => 'ApiController@getCart']);
	Route::post('cart/product/add', ['as' => 'apiCartProductAdd', 'uses' => 'ApiController@cartProductAdd']);
	Route::post('cart/product/remove', ['as' => 'apiCartProductRemove', 'uses' => 'ApiController@cartProductRemove']);
});
