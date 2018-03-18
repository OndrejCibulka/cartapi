<?php

namespace App\Http\Controllers;

use App\Product;
use App\Variant;
use App\Voucher;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function uploadProducts()
    {
    	Product::where('id', '>', 0)->delete();

    	Product::create([
            'id'                     => 1,
            'name'                   => 'iPhone 7',
            'url'                    => 'iphone-7',
            'code'                   => '0001',
            'image'                  => '',
            'producer_name'          => 'Applce Inc.',
            'producer_home_page_url' => 'https://www.apple.com/',
            'description_summary'    => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná.',
            'amount_step'            => 1,
            'amount_unit'            => 'pieces',
    	]);

        Variant::create([
            'id'                          => 1,
            'variant_id'                  => 1,
            'product_id'                  => 1,
            'name'                        => '32 GB',
            'price_with_vat_for_customer' => 15390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 2,
            'variant_id'                  => 2,
            'product_id'                  => 1,
            'name'                        => '128 GB',
            'price_with_vat_for_customer' => 18390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 3,
            'variant_id'                  => 3,
            'product_id'                  => 1,
            'name'                        => '256 GB',
            'price_with_vat_for_customer' => 20390,
            'stock_count'                 => 5,
        ]);





        Product::create([
            'id'                     => 2,
            'name'                   => 'iPhone 8',
            'url'                    => 'iphone-8',
            'code'                   => '0002',
            'image'                  => '',
            'producer_name'          => 'Applce Inc.',
            'producer_home_page_url' => 'https://www.apple.com/',
            'description_summary'    => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná.',
            'amount_step'            => 1,
            'amount_unit'            => 'pieces',
        ]);

        Variant::create([
            'id'                          => 4,
            'variant_id'                  => 1,
            'product_id'                  => 2,
            'name'                        => '32 GB',
            'price_with_vat_for_customer' => 16390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 5,
            'variant_id'                  => 2,
            'product_id'                  => 2,
            'name'                        => '128 GB',
            'price_with_vat_for_customer' => 19390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 6,
            'variant_id'                  => 3,
            'product_id'                  => 2,
            'name'                        => '256 GB',
            'price_with_vat_for_customer' => 21390,
            'stock_count'                 => 5,
        ]);






        Product::create([
            'id'                     => 3,
            'name'                   => 'iPhone X',
            'url'                    => 'iphone-x',
            'code'                   => '0003',
            'image'                  => '',
            'producer_name'          => 'Applce Inc.',
            'producer_home_page_url' => 'https://www.apple.com/',
            'description_summary'    => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná.',
            'amount_step'            => 1,
            'amount_unit'            => 'pieces',
        ]);

        Variant::create([
            'id'                          => 7,
            'variant_id'                  => 1,
            'product_id'                  => 3,
            'name'                        => '32 GB',
            'price_with_vat_for_customer' => 17390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 8,
            'variant_id'                  => 2,
            'product_id'                  => 3,
            'name'                        => '128 GB',
            'price_with_vat_for_customer' => 20390,
            'stock_count'                 => 5,
        ]);

        Variant::create([
            'id'                          => 9,
            'variant_id'                  => 3,
            'product_id'                  => 3,
            'name'                        => '256 GB',
            'price_with_vat_for_customer' => 22390,
            'stock_count'                 => 5,
        ]);



        Voucher::where('id','>','0')->delete();

        Voucher::create([
            'id'             => 1,
            'code'           => 'SLEVA10',
            'discount_value' => 10,
        ]);

        Voucher::create([
            'id'             => 2,
            'code'           => 'SLEVA20',
            'discount_value' => 20,
        ]);

        Voucher::create([
            'id'             => 3,
            'code'           => 'SLEVA30',
            'discount_value' => 30,
        ]);

        Voucher::create([
            'id'             => 4,
            'code'           => 'SLEVA40',
            'discount_value' => 40,
        ]);

        Voucher::create([
            'id'             => 5,
            'code'           => 'SLEVA50',
            'discount_value' => 50,
        ]);

    	
    	return 'Nahráno, vraťte se zpět';
    }
}




