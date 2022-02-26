<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Nueva nota agregada</title>
</head>
<body>
    <p>Hola! El usuario {{ $userCreator->username }} a agregado una nueva nota al grupo {{ $group->name }}.</p>
    <p>Título de la nota: {{ $postit->title }}.</p>
</body>
</html>