<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Confirmação de Solicitação</title>
</head>

<body>
    <h1>Confirmação de Solicitação</h1>
    <p>Olá,</p>
    <p>Seu código de confirmação é: <strong>{{ $token }}</strong></p>
    <p>Por favor, utilize este código para confirmar sua solicitação.</p>
    <p>Atenciosamente,</p>
    <p>Sua equipe</p>
    <a href="http://localhost:3000/confirmar-solicitacao/{{ $token }}">Confirmar Solicitação</a>
</body>

</html>