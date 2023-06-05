<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nouveau ticket créé</title>
</head>

<body>
    <h1>Nouveau ticket créé</h1>
    <p>Un nouveau ticket a été créé:</p>
    <ul>
        <li>Nom: {{ $ticket['name'] }}</li>
        <li>Email: {{ $ticket['email'] }}</li>
        <li>Sujet: {{ $ticket['subject'] }}</li>
        <li>Commentaire: {{ $ticket['comment'] }}</li>
        <li>Résolu: {{ $ticket['isSolved'] ? 'oui' : 'non' }}</li>
        @if ($ticket['agent_id'] == null)
            <li>Merci de bien vouloir assigner un agent à ce ticket.</li>
        @else
            <li>ID agent: {{ $ticket['agent_id'] }}</li>
        @endif
    </ul>
</body>

</html>
