<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Breve</title>
</head>
<body>
	<p>
		Estimado {{ $data['cliente'] }}, gracias por crear su cuenta en nuestra plataforma. Para confirmar su correo por favor presione el link a continuación <a href="https://www.breve.com.co/verify-email/{{$data['id']}}" target="_blank">Confirmar Correo</a>
	</p>
	<br>

	<p>Gracias por su colaboración!!</p>

</body>
</html>