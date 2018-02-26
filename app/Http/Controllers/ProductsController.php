<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function uploadProducts()
    {
    	Product::truncate();

    	Product::create([
    		'product_id'                  => '1',
    		'variant_id'                  => '1',
    		'complete_name'               => 'iPhone 7 32GB',
    		'code'                        => 'code-iphone-7-32gb',
    		'image'                       => '',
    		'producer_name'               => 'Applce Inc.',
    		'producer_home_page_url'      => 'https://www.apple.com/',
    		'description_summary'         => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná. Jeho luxusní vzhled nelze přehlédnout, stejně jako výbavu, na které společnost Apple rozhodně nešetřila. Celkem snadno se tak iPhone 7 stává klenotem, který se zavděčí i těm nejnáročnějším uživatelům. Okouzlí vás nepřekonatelným výkonem, neskutečně živým displejem, špičkovými stereo reproduktory a fotoaparáty, které nemají konkurenci. K tomu všemu je tento model voděodolný a jeho baterie má výdrž, jakou jste u iPhonů dosud nezažili. Pokrok zkrátka nelze zastavit a iPhone 7 je toho jasným důkazem!',
    		'amount_step'                 => 1,
    		'amount_unit'                 => 'pieces',
    		'price_with_vat_for_customer' => '15390',
    		'sale_sticker'                => true,
    		'new_sticker'                 => false
    	]);

    	Product::create([
    		'product_id'                  => '1',
    		'variant_id'                  => '2',
    		'complete_name'               => 'iPhone 7 128GB',
    		'code'                        => 'code-iphone-7-128gb',
    		'image'                       => '',
    		'producer_name'               => 'Applce Inc.',
    		'producer_home_page_url'      => 'https://www.apple.com/',
    		'description_summary'         => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná. Jeho luxusní vzhled nelze přehlédnout, stejně jako výbavu, na které společnost Apple rozhodně nešetřila. Celkem snadno se tak iPhone 7 stává klenotem, který se zavděčí i těm nejnáročnějším uživatelům. Okouzlí vás nepřekonatelným výkonem, neskutečně živým displejem, špičkovými stereo reproduktory a fotoaparáty, které nemají konkurenci. K tomu všemu je tento model voděodolný a jeho baterie má výdrž, jakou jste u iPhonů dosud nezažili. Pokrok zkrátka nelze zastavit a iPhone 7 je toho jasným důkazem!',
    		'amount_step'                 => 1,
    		'amount_unit'                 => 'pieces',
    		'price_with_vat_for_customer' => '18390',
    		'sale_sticker'                => true,
    		'new_sticker'                 => false
    	]);

    	Product::create([
    		'product_id'                  => '1',
    		'variant_id'                  => '3',
    		'complete_name'               => 'iPhone 7 256GB',
    		'code'                        => 'code-iphone-7-256gb',
    		'image'                       => '',
    		'producer_name'               => 'Applce Inc.',
    		'producer_home_page_url'      => 'https://www.apple.com/',
    		'description_summary'         => 'Hledáte-li dokonalý designerský kousek, je vaše hledání u konce. iPhone 7 je totiž majstrštyk, který jen tak něco nepřekoná. Jeho luxusní vzhled nelze přehlédnout, stejně jako výbavu, na které společnost Apple rozhodně nešetřila. Celkem snadno se tak iPhone 7 stává klenotem, který se zavděčí i těm nejnáročnějším uživatelům. Okouzlí vás nepřekonatelným výkonem, neskutečně živým displejem, špičkovými stereo reproduktory a fotoaparáty, které nemají konkurenci. K tomu všemu je tento model voděodolný a jeho baterie má výdrž, jakou jste u iPhonů dosud nezažili. Pokrok zkrátka nelze zastavit a iPhone 7 je toho jasným důkazem!',
    		'amount_step'                 => 1,
    		'amount_unit'                 => 'pieces',
    		'price_with_vat_for_customer' => '19390',
    		'sale_sticker'                => true,
    		'new_sticker'                 => false
    	]);

    	return 'Nahráno, vraťte se zpět';
    }
}




