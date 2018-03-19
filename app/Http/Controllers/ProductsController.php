<?php



namespace App\Http\Controllers;



use App\Carrier;
use App\CarrierPlace;
use App\Payment;
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

            'name'                   => 'iPhone 8 Black',

            'url'                    => 'iphone-8-black',

            'code'                   => '0001',

            'image'                  => '/images/iphone8b.png',

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

            'name'                   => 'iPhone 8 White',

            'url'                    => 'iphone-8-white',

            'code'                   => '0002',

            'image'                  => '/images/iphone8w.png',

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

            'image'                  => '/images/iphonex.png',

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


        Carrier::where('id', '>', 0)->delete();

        Carrier::create([
			'id'          => 1,
			'label'       => 'PPL',
			'name'        => 'delivery',
			'image'       => '/images/deliveries/ppl.png',
			'description' => '2-3 pracovní dny',
			'full_description' => 'PPL je přední poskytovatel přepravních služeb pro firmy a podnikatele. Specializacují se na balíkovou přepravu po ČR a do Evropy, v rámci ČR doručování zásilek na soukromé adresy a také vnitrostátní paletovou přepravu.',
			'price'       => 70,
        ]);

        CarrierPlace::create([
        	'id' => 1,
        	'carrier_id' => 1,
        	'place_id' => 1,
        	'label' => 'Ostrava',
        ]);

        CarrierPlace::create([
        	'id' => 2,
        	'carrier_id' => 1,
        	'place_id' => 2,
        	'label' => 'Brno',
        ]);

        CarrierPlace::create([
        	'id' => 3,
        	'carrier_id' => 1,
        	'place_id' => 3,
        	'label' => 'Praha',
        ]);


        Carrier::create([
			'id'          => 2,
			'label'       => 'DPD',
			'name'        => 'delivery',
			'image'       => '/images/deliveries/dpd.png',
			'description' => '5 pracovních dnů',
			'full_description' => 'DPD je součástí mezinárodní přepravní sítě DPDgroup, která patří na trhu zásilkových a expresních služeb k absolutním špičkám, v Evropě dokonce zastává 2. příčku.',
			'price'       => 89,
        ]);

        Carrier::create([
			'id'          => 3,
			'label'       => 'Zásilkovna',
			'name'        => 'delivery',
			'image'       => '/images/deliveries/zasilkovna.png',
			'description' => 'Následující pracovní den',
			'full_description' => 'Zásilkovna jsou přeborníci na posílání a doručování. Svou objednávku
z e-shopu si běžně můžete vyzvednout už druhý den na výdejním místě, které si vyberete. S komfortem a péči, kterou si zasloužíte!',
			'price'       => 0,
        ]);

        CarrierPlace::create([
        	'id' => 4,
        	'carrier_id' => 3,
        	'place_id' => 1,
        	'label' => 'Ostrava, Poruba, Alšovo nám. 688',
        ]);

        CarrierPlace::create([
        	'id' => 5,
        	'carrier_id' => 3,
        	'place_id' => 2,
        	'label' => 'Ostrava, Svinov, Peterkova 370/10',
        ]);

        CarrierPlace::create([
        	'id' => 6,
        	'carrier_id' => 3,
        	'place_id' => 3,
        	'label' => 'Ostrava, Avion shopping park, Rudná 114',
        ]);

        CarrierPlace::create([
        	'id' => 7,
        	'carrier_id' => 3,
        	'place_id' => 4,
        	'label' => 'DEPO, Ostrava, Polanecká 803/72',
        ]);


        Payment::where('id', '>', 0)->delete();

        Payment::create([
        	'id' => 1,
        	'name' => 'payment',
        	'label' => 'Platba kartou',
        	'price' => 0,
        	'description' => 'Jednoduchý a velmi rychlý způsob platby.',
        	'long_description' => 'Jednuduchá a velmi rychlá je platba kartou. A hlavně levná, díky minimálním poplatkům. V dnešní době téměř všechny bankovní karty umožňují internetovou platbu. Vždy je nutné mít kartu k těmto transakcím aktivovanou. Při žádné platbě kartou se nezadává PIN! Vyplňuje se číslo karty, platnost karty a CVV2 kód, který je umístěn na zadní straně karty.',
        	'image' => '/images/payments/credit-cart.png',
        ]);

        Payment::create([
        	'id' => 2,
        	'name' => 'payment',
        	'label' => 'Převodem na účet',
        	'price' => 0,
        	'description' => 'Bezhotovostní převod mezi dvěma účty.',
        	'long_description' => 'Počátky bezhotovostních převodů nalezneme v 19. století, kdy se rozvíjela telegrafní síť. Ta umožnila odeslat částku bezpečně a rychle. Respektive byla odeslána informace o převodu z místa jeho podání na místo určení. Na začátku i na konci tak byla hotovost, peníze ale mohly být vyplaceny krátce poté, co byl na jiném místě stejný obnos složen příkazcem. Samotná platba probíhala následovně: Nejprve příkazce dorazil do banky, nebo na telegrafní úřad, kde podal pokyn k převodu. Zároveň složil příkazce stanovenou částku. Obsluha telegrafu pak odeslala informace do úřadu, kde byl následně příjemci vyplacen daný obnos. Část převodu tak již byla bezhotovostní, protože peníze, které složil příkazce, byly fyzicky převezeny případně až následně.',
        	'image' => '/images/payments/credit-cart.png',
        ]);

        Payment::create([
        	'id' => 3,
        	'name' => 'payment',
        	'label' => 'Dobírka',
        	'price' => 60,
        	'description' => 'Platba na pobočce, nebo přepravci.',
        	'long_description' => 'Zboží zaplatíte až při převzení na jedné z našich poboček, nebo přepravci, který vám zakoupené zboží doručí až do vašich rukou.',
        	'image' => '/images/payments/credit-cart.png',
        ]);

    	return 'Nahráno, vraťte se zpět';

    }

}









