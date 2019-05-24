<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h2>Olá {{$user['name']}}!</h2>
<br/>
O teu email registado é {{$user['email']}} , carrega no link em baixo para concluir o processo de verificação.
<br/>
<a href="{{url('email/verify',$user->id)}}">Verificar email</a>
</body>

</html>
