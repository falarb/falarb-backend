<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de dados - Fala Rebouças</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
            font-size: 14px;
            margin: 0 auto;
            padding: 20px;
        }

        .report-header {
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 20px;
        }

        .title-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .main-title {
            font-size: 28px;
            font-weight: 400;
            color: #000;
        }

        .subtitle {
            font-size: 28px;
            font-weight: 300;
            color: #999;
        }

        .filters-section {
            display: flex;
            flex-direction: column;
            margin-top: 8px;
        }

        .filter-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label {
            color: #999;
        }

        .filter-value {
            color: #000;
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid #e5e5e5;
            border-radius: 20px;
            margin-top: 30px;
        }

        .report-table {
            border-collapse: collapse;
            font-size: 13px;
        }

        .report-table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e5e5e5;
        }

        .report-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-right: 1px solid #e5e5e5;
        }

        .report-table th:last-child {
            border-right: none;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e5e5e5;
        }

        .report-table tbody tr:last-child {
            border-bottom: none;
        }

        .report-table td {
            padding: 14px 16px;
            border-right: 1px solid #e5e5e5;
        }

        .report-table td:last-child {
            border-right: none;
        }

        .citizen-id {
            font-size: 12px;
            color: #666;
        }

        tbody td {
            align-content: center;
            word-break: break-word;
            color: #333;
        }

        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #FFF;
        }

        @page {
            size: A4;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <header class="report-header">
        <div class="title-section">
            <h1 class="main-title">Relatório de dados</h1>
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="4" cy="4" r="4" fill="#D9D9D9" />
            </svg>
            <h2 class="subtitle">Solicita Aí</h2>
        </div>

        <div class="filters-section">
            <div class="filter-row">
                <span class="filter-label">Tipo de solicitação </span>

                <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="2" cy="2" r="2" fill="#D9D9D9" />
                </svg>

                <span
                    class="filter-value">{{(!$categoria || $categoria == 'todas') ? 'Todas os tipos de solicitações' : $dados[0]->categoria->nome}}</span>
            </div>
            <div class="filter-row">
                <span class="filter-label">Comunidade</span>

                <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="2" cy="2" r="2" fill="#D9D9D9" />
                </svg>

                <span
                    class="filter-value">{{(!$comunidade || $comunidade == 'todas') ? 'Todas as comunidades' : $dados[0]->comunidade->nome}}</span>
            </div>
            <div class="filter-row">
                <span class="filter-label">Período</span>

                <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="2" cy="2" r="2" fill="#D9D9D9" />
                </svg>

                <span class="filter-value">{{ $dataEscrita }}</span>
            </div>
        </div>
    </header>

    <div class="table-container">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 15%">Data de Criação</th>
                    <th style="width: 25%">Cidadão</th>
                    <th style="width: 20%">Tipo de pedido</th>
                    <th style="width: 25%">Comunidade</th>
                    <th style="width: 15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados ?? [] as $dado)
                    <tr>
                        <td>
                            {{ $dado->created_at->format('d/m/Y') ?? '12/08/2025' }}
                            <br />
                            {{ $dado->created_at->format('H:i:s') ?? '12:09:12' }}
                        </td>
                        <td>
                            <div>
                                {{ $dado->cidadao->nome ?? 'Nome extremamente grande grande de usuário' }}
                            </div>
                            <div class="citizen-id">
                                {{ $dado->cidadao->cpf ?? '115.888.888-9' }}
                            </div>
                        </td>
                        <td>
                            {{ $dado->categoria->nome ?? 'Tipo extremamente Grande de pedido' }}
                        </td>
                        <td>
                            {{ $dado->comunidade->nome ?? 'Nome extremamente grande de Comunidade' }}
                        </td>
                        <td style="text-align: center;">
                            <span class="status-badge" style="background-color: {{ $dado->status_cor ?? '#f59e0b' }};">
                                {{ $dado->status ?? 'analise' }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                @if(empty($dados))
                    @for($i = 0; $i < 7; $i++)
                        <tr>
                            <td>12/08/2025 <br /> 12:09:12</td>
                            <td>
                                <div>Nome extremamente grande grande de usuário</div>
                                <div class="citizen-id">115.888.888-9</div>
                            </td>
                            <td>Tipo extremamente Grande de pedido</td>
                            <td>Nome extremamente grande de Comunidade</td>
                            <td style="text-align: center;">
                                <span class="status-badge" style="background-color: {{ $dado->status_cor ?? '#f59e0b' }};">
                                    analise
                                </span>
                            </td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>