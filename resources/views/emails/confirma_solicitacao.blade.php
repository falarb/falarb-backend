<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Confirmação de Solicitação</title>
</head>

<body>
    <header>
        <img src="{{ asset('img/logo') }}" alt="Logo Solicita Aí">
    </header>

    <main>
        <h1>Olá, {{ $userName }}!</h1>
        
        <p>Seu código de confirmação é: <strong>{{ $token }}</strong></p>
        <p>Por favor, utilize este código para confirmar sua solicitação.</p>
        <p>Atenciosamente,</p>
        <p>Sua equipe</p>
        <a href="http://localhost:3000/confirmar-solicitacao/{{ $token }}">Confirmar Solicitação</a>
    </main>
</body>

</html>