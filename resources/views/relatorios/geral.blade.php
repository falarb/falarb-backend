<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de dados - Fala Rebouças</title>
    <link rel="stylesheet" href="{{ asset('css/relatorio.css') }}">
</head>

<body>
    <div class="container">
        <header class="report-header">
            <div class="title-section">
                <h1 class="main-title">Relatório de dados</h1>
                <h2 class="subtitle">Fala Rebouças</h2>
            </div>

            <div class="filters-section">
                <div class="filter-row">
                    <span class="filter-label">Tipo de solicitação:</span>
                    <span
                        class="filter-value">{{(!$categoria || $categoria == 'todas') ? 'Todas os tipos de solicitações' : $dados[0]->categoria->nome}}</span>
                </div>
                <div class="filter-row">
                    <span class="filter-label">Comunidade:</span>
                    <span
                        class="filter-value">{{(!$comunidade || $comunidade == 'todas') ? 'Todas as comunidades' : $dados[0]->comunidade->nome}}</span>
                </div>
                <div class="filter-row">
                    <span class="filter-label">Período:</span>
                    <span class="filter-value">{{ $dataEscrita }}</span>
                </div>
            </div>
        </header>

        <main class="report-content">
            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th class="col-date">Data de Criação</th>
                            <th class="col-citizen">Cidadão</th>
                            <th class="col-request">Tipo de pedido</th>
                            <th class="col-community">Comunidade</th>
                            <th class="col-status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados ?? [] as $dado)
                            <tr>
                                <td class="date-cell">
                                    {{ $dado->created_at->format('d/m/Y H:i:s') ?? '12/08/2025 12:09:12' }}
                                </td>
                                <td class="citizen-cell">
                                    <div class="citizen-name">
                                        {{ $dado->cidadao->nome ?? 'Nome extremamente grande grande de usuário' }}
                                    </div>
                                    <div class="citizen-id">
                                        {{ $dado->cidadao->cpf ?? '115.888.888-9' }}
                                    </div>
                                </td>
                                <td class="request-cell">
                                    {{ $dado->categoria->nome ?? 'Tipo extremamente Grande de pedido' }}
                                </td>
                                <td class="community-cell">
                                    {{ $dado->comunidade->nome ?? 'Nome extremamente grande de Comunidade' }}
                                </td>
                                <td class="status-cell">
                                    <span class="status-badge status-waiting">
                                        {{ $dado->status ?? 'Em espera' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        @if(empty($dados))
                            @for($i = 0; $i < 7; $i++)
                                <tr>
                                    <td class="date-cell">12/08/2025 12:09:12</td>
                                    <td class="citizen-cell">
                                        <div class="citizen-name">Nome extremamente grande grande de usuário</div>
                                        <div class="citizen-id">115.888.888-9</div>
                                    </td>
                                    <td class="request-cell">Tipo extremamente Grande de pedido</td>
                                    <td class="community-cell">Nome extremamente grande de Comunidade</td>
                                    <td class="status-cell">
                                        <span class="status-badge status-waiting">Em espera</span>
                                    </td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>