<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Minhas Despesas';
?>

<div class="container mt-4">
    <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

    <!-- Flash messages -->
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>

    <!-- Formulário para adicionar despesa via API -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Adicionar Nova Despesa</div>
        <div class="card-body">
            <form id="despesa-form">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="descricao" class="form-control" placeholder="Descrição" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" step="0.01" name="valor" class="form-control" placeholder="Valor (R$)" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" name="data" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="categoria" class="form-select" required>
                            <option value="">Categoria</option>
                            <option value="alimentação">Alimentação</option>
                            <option value="transporte">Transporte</option>
                            <option value="lazer">Lazer</option>
                        </select>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Adicionar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">Filtros</div>
        <div class="card-body">
            <form method="get" class="row g-2">
                <div class="col-md-3">
                    <select name="categoria" class="form-select">
                        <option value="">Todas categorias</option>
                        <option value="alimentação">Alimentação</option>
                        <option value="transporte">Transporte</option>
                        <option value="lazer">Lazer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="data_inicio" class="form-control" placeholder="Data inicial">
                </div>
                <div class="col-md-3">
                    <input type="date" name="data_fim" class="form-control" placeholder="Data final">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Lista de Despesas</div>
        <div class="card-body">
            <?php if ($dataProvider->getCount() > 0): ?>
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
                    'columns' => [
                            'descricao',
                            [
                                    'attribute' => 'valor',
                                    'value' => fn($model) => 'R$ ' . number_format($model->valor, 2, ',', '.'),
                            ],
                            [
                                    'attribute' => 'data',
                                    'value' => fn($model) => date('d/m/Y', strtotime($model->data)),
                            ],
                            'categoria',
                            [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Ações',
                                    'template' => '{update} {delete}',
                                    'buttons' => [
                                            'update' => fn($url, $model) =>
                                            Html::a('<i class="bi bi-pencil"></i> Editar', '#', [
                                                    'class' => 'btn btn-sm btn-warning update-despesa',
                                                    'data-id' => $model->id,
                                            ]),
                                            'delete' => fn($url, $model) =>
                                            Html::a('<i class="bi bi-trash"></i> Excluir', '#', [
                                                    'class' => 'btn btn-sm btn-danger delete-despesa',
                                                    'data-id' => $model->id,
                                            ]),
                                    ],
                            ],
                    ],
            ]); ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Nenhuma despesa encontrada. Adicione sua primeira despesa usando o formulário acima.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Coletar os dados do formulário
    const formData = {
        descricao: this.querySelector('input[name="descricao"]').value,
        valor: this.querySelector('input[name="valor"]').value,
        data: this.querySelector('input[name="data"]').value,
        categoria: this.querySelector('select[name="categoria"]').value
    };

    // Validar se todos os campos estão preenchidos
    if (!formData.descricao || !formData.valor || !formData.data || !formData.categoria) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            Por favor, preencha todos os campos.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container').prepend(alertDiv);
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch('/api/despesas/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            // Exibir mensagem de sucesso
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${result.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container').prepend(alertDiv);

            // Limpar formulário
            this.reset();

            // Recarregar a lista de despesas
            await loadDespesas();
        } else {
            // Exibir mensagem de erro detalhada
            const errorMessage = Object.entries(result.errors)
                .map(([field, errors]) => `${field}: ${errors.join(', ')}`)
                .join('<br>');

            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                Erro ao salvar despesa:<br>${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container').prepend(alertDiv);
        }
    } catch (error) {
        console.error('Error:', error);
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            Erro ao salvar despesa: ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container').prepend(alertDiv);
    }
    });

    // Função para carregar despesas via API
    async function loadDespesas() {
        console.log('Carregando despesas...');

        const filterForm = document.getElementById('filter-form');
        const params = new URLSearchParams(new FormData(filterForm));

        try {
            console.log('Fazendo requisição para: /api/despesas?' + params.toString());
            const response = await fetch(`/api/despesas?${params.toString()}`);

            console.log('Status da resposta:', response.status);
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const despesas = await response.json();
            console.log('Despesas recebidas:', despesas);

            // Atualizar a tabela
            const tableBody = document.querySelector('#despesas-list table tbody');
            if (!tableBody) {
                console.error('Tabela não encontrada');
                return;
            }

            tableBody.innerHTML = '';

            if (!Array.isArray(despesas)) {
                console.error('Resposta não é um array:', despesas);
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">Erro ao carregar despesas</td>';
                tableBody.appendChild(row);
                return;
            }

            if (despesas.length === 0) {
                console.log('Nenhuma despesa encontrada');
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">Nenhuma despesa encontrada</td>';
                tableBody.appendChild(row);
                return;
            }

            console.log('Renderizando ' + despesas.length + ' despesas');
            despesas.forEach(despesa => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${despesa.descricao}</td>
                <td>R$ ${parseFloat(despesa.valor).toFixed(2).replace('.', ',')}</td>
                <td>${new Date(despesa.data).toLocaleDateString('pt-BR')}</td>
                <td>${despesa.categoria}</td>
                <td>
                    <button class="btn btn-sm btn-warning update-despesa" data-id="${despesa.id}">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-danger delete-despesa" data-id="${despesa.id}">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </td>
            `;
                tableBody.appendChild(row);
            });

            // Adicionar eventos aos novos botões
            addEventListeners();
        } catch (error) {
            console.error('Error loading despesas:', error);
            const tableBody = document.querySelector('#despesas-list table tbody');
            if (tableBody) {
                tableBody.innerHTML = '';
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">Erro ao carregar despesas: ' + error.message + '</td>';
                tableBody.appendChild(row);
            }
        }

    // Função para carregar uma despesa específica
    async function loadDespesa(id) {
        try {
            const response = await fetch(`/api/despesas/view/${id}`);
            if (!response.ok) {
                throw new Error('Despesa não encontrada');
            }
            return await response.json();
        } catch (error) {
            console.error('Error loading despesa:', error);
            return null;
        }
    }

    // Adicionar eventos aos botões
    function addEventListeners() {
        // Para editar
        document.querySelectorAll('.update-despesa').forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                const despesa = await loadDespesa(id);

                if (despesa) {
                    // Preencher o formulário com os dados da despesa
                    document.querySelector('input[name="descricao"]').value = despesa.descricao;
                    document.querySelector('input[name="valor"]').value = despesa.valor;
                    document.querySelector('input[name="data"]').value = despesa.data;
                    document.querySelector('select[name="categoria"]').value = despesa.categoria;

                    // Mudar o comportamento do formulário para atualização
                    const form = document.getElementById('despesa-form');
                    form.onsubmit = async function(e) {
                        e.preventDefault();

                        const formData = {
                            descricao: this.querySelector('input[name="descricao"]').value,
                            valor: this.querySelector('input[name="valor"]').value,
                            data: this.querySelector('input[name="data"]').value,
                            categoria: this.querySelector('select[name="categoria"]').value
                        };

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                        try {
                            const response = await fetch(`/api/despesas/update/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': csrfToken,
                                },
                                body: JSON.stringify(formData)
                            });

                            const result = await response.json();

                            if (result.success) {
                                // Exibir mensagem de sucesso
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                alertDiv.innerHTML = `
                                ${result.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                                document.querySelector('.container').prepend(alertDiv);

                                // Limpar formulário e resetar comportamento
                                this.reset();
                                form.onsubmit = arguments.callee.caller;

                                // Recarregar a lista de despesas
                                await loadDespesas();
                            } else {
                                // Exibir mensagem de erro
                                const errorMessage = Object.entries(result.errors)
                                    .map(([field, errors]) => `${field}: ${errors.join(', ')}`)
                                    .join('<br>');

                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                                alertDiv.innerHTML = `
                                Erro ao atualizar despesa:<br>${errorMessage}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                                document.querySelector('.container').prepend(alertDiv);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                            alertDiv.innerHTML = `
                            Erro ao atualizar despesa: ${error.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                            document.querySelector('.container').prepend(alertDiv);
                        }
                    };

                    // Rolar para o formulário
                    document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Para excluir
        document.querySelectorAll('.delete-despesa').forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');

                if (confirm('Tem certeza que deseja excluir esta despesa?')) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    try {
                        const response = await fetch(`/api/despesas/delete/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-Token': csrfToken,
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Exibir mensagem de sucesso
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show';
                            alertDiv.innerHTML = `
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                            document.querySelector('.container').prepend(alertDiv);

                            // Recarregar a lista de despesas
                            await loadDespesas();
                        } else {
                            // Exibir mensagem de erro
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                            alertDiv.innerHTML = `
                            Erro ao excluir despesa: ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                            document.querySelector('.container').prepend(alertDiv);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.innerHTML = `
                        Erro ao excluir despesa: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                        document.querySelector('.container').prepend(alertDiv);
                    }
                }
            });
        });
    }

    // Inicializar eventos
    addEventListeners();

    // Carregar despesas ao carregar a página
    document.addEventListener('DOMContentLoaded', loadDespesas);

    // Adicionar evento ao formulário de filtros
    document.getElementById('filter-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        await loadDespesas();
    });
</script>