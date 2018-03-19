<!DOCTYPE html>
<html>
<head>
	<title>CartApi</title>

</head>
<body>
	<ul>
		<li>
			<a href="{{route('uploadProducts')}}">Nahrát produkty do DB</a>
		</li>
		<li>
			<a href="{{route('apiProductsGet')}}">Vrať produkty</a>
		</li>
		<li>
			<a href="{{route('apiProductDetailGet')}}">Vrať detail</a>
		</li>
		<li>
			<a href="{{route('apiCartGet')}}">Vrať košík</a>
		</li>
		<li>
			<form action="{{route('apiCartProductAdd')}}" method="POST">
				{{ csrf_field() }} 
				<button type="submit">Přidat do košíku</button>	
			</form>
		</li>
		<li>
			<form action="{{route('apiCartProductRemove')}}" method="POST">
				{{ csrf_field() }}
				<button type="submit">Odebrat z košíku</button>
			</form>
		</li>
		<li><a href="{{route('apiCartProductChangeAmount')}}">Změnit počet</a></li>
		<hr>
		<li><a href="{{route('apiCartVoucherApply')}}">Uplatnit voucher</a></li>
		<li><a href="{{route('apiCartVoucherRemove')}}">Odstranit voucher</a></li>
		<hr>
		<li><a href="{{route('apiCartCarriersGet')}}">Vrať přepravce</a></li>
		<li><a href="{{route('apiCartCarrierDetailGet')}}">Vrať detail přepravce</a></li>
		<li><a href="{{route('apiCartCarrierSet')}}">Nastav přepravce</a></li>
		<li><a href="{{route('apiCartCarrierUnset')}}">Ostraň přepravce</a></li>
		<hr>
		<li><a href="{{route('apiCartPaymentsGet')}}">Vrať platby</a></li>
		<li><a href="{{route('apiCartPaymentSet')}}">Nastav platbu</a></li>
		<li><a href="{{route('apiCartPaymentUnset')}}">Ostraň platbu</a></li>
		<hr>
		<li><a href="{{route('apiCartSummaryGet')}}">Vrať shrnutí košíku</a></li>

	</ul>
</body>
</html>
