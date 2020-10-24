<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Breve</title>
</head>
<body>
	<p>
		Estimado {{ $data['cliente'] }}, su clave temporal para acceder al sistema es <b>{{ $data['clave'] }}</b>. Recuerde cambiarla al momento de ingresar para mayor seguridad y comodidad.
	</p>
	<br>

	<p>Gracias por su colaboración!!</p>

</body>
</html>