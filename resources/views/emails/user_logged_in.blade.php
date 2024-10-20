<!DOCTYPE html>
<html>
<head>
    <title>Notifica Login</title>
</head>
<body>
    <h1>Un utente ha effettuato il login</h1>
    <p><strong>Nome:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Data e ora di login:</strong> {{ now() }}</p>
</body>
</html>
