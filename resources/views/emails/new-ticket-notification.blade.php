<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nouveau ticket créé</title>
</head>

<body>
    <h1>Nouveau ticket créé</h1>
    @if (is_string($message))
    <p>{{ htmlspecialchars($message) }}</p>
    @else
    <p>Le message du ticket est vide.</p>
    @endif
</body>

</html>
