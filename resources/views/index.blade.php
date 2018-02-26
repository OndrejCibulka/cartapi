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
			<a href="{{route('apiCartGet')}}">Vrať košík</a>
		</li>
		<li>
			<form action="{{route('apiCartProductAdd')}}" method="POST">
				{{ csrf_field() }}
				<select name="id">
			  		@foreach ($products as $product)
						<option value="{{$product->id}}">{{$product->complete_name}}</option>
			  		@endforeach
				</select> 
				<button type="submit">Přidat do košíku</button>	
			</form>
		</li>
		<li>
			<form action="{{route('apiCartProductRemove')}}" method="POST">
				{{ csrf_field() }}
				<select name="id">
			  		@foreach ($cart as $cartProduct)
						<option value="{{$cartProduct['id']}}">{{$cartProduct['baseName']}}</option>
			  		@endforeach
				</select> 
				<button type="submit">Odebrat z košíku</button>
			</form>
		</li>
	</ul>
</body>
</html>