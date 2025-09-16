<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Minhas Despesas';
?>
<div class="container mt-4">
    <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

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
            <form id="filter-form" method="get" class="row g-2">
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
            <div id="despesas-list">
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
            </div>
        </div>
    </div>
</div>

<script>
    // Criar container de notificações no footer se não existir
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar se já existe um container de notificações
        if (!document.querySelector('.notification-footer')) {
            // Criar container de notificações no footer
            const notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-footer fixed-bottom p-3';
            notificationContainer.style.zIndex = '9999';
            document.body.appendChild(notificationContainer);
        }

        // Carregar despesas ao carregar a página
        loadDespesas();
        // Adicionar eventos aos botões existentes
        addEventListeners();
    });

    // Função para exibir notificações no footer
    function showNotification(message, type = 'info') {
        // Remover notificações anteriores para evitar acúmulo
        const existingNotifications = document.querySelectorAll('.notification-footer .alert');
        existingNotifications.forEach(notif => notif.remove());

        // Criar elemento de notificação
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.marginBottom = '0';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Adicionar ao container de notificações no footer
        const notificationContainer = document.querySelector('.notification-footer');
        notificationContainer.appendChild(alertDiv);

        // Auto-remover após 5 segundos
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => {
                alertDiv.remove();
            }, 150);
        }, 5000);
    }

    // Função para carregar despesas via API
    async function loadDespesas() {
        const filterForm = document.getElementById('filter-form');
        const params = new URLSearchParams(new FormData(filterForm));
        try {
            const response = await fetch(`/api/despesas?${params.toString()}`);
            const despesas = await response.json();
            // Atualizar a tabela
            const tableBody = document.querySelector('#despesas-list table tbody');
            if (!tableBody) {
                console.error('Tabela não encontrada');
                return;
            }
            tableBody.innerHTML = '';
            if (!Array.isArray(despesas) || despesas.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">Nenhuma despesa encontrada</td>';
                tableBody.appendChild(row);
                return;
            }
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
            button.addEventListener('click', async function(e) {
                e.preventDefault();
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
                    form.dataset.editId = id;

                    // Alterar o texto do botão de submit
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.textContent = 'Atualizar';
                    submitButton.classList.remove('btn-success');
                    submitButton.classList.add('btn-primary');

                    // Adicionar botão de cancelar
                    if (!form.querySelector('.cancel-edit')) {
                        const cancelButton = document.createElement('button');
                        cancelButton.type = 'button';
                        cancelButton.className = 'btn btn-secondary cancel-edit';
                        cancelButton.textContent = 'Cancelar';
                        cancelButton.style.marginLeft = '10px';
                        submitButton.parentNode.insertBefore(cancelButton, submitButton.nextSibling);

                        // Evento para cancelar a edição
                        cancelButton.addEventListener('click', function() {
                            form.reset();
                            delete form.dataset.editId;
                            submitButton.textContent = 'Adicionar';
                            submitButton.classList.remove('btn-primary');
                            submitButton.classList.add('btn-success');
                            cancelButton.remove();
                        });
                    }

                    // Rolar para o formulário
                    document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
                } else {
                    showNotification('Não foi possível carregar os dados da despesa.', 'danger');
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
                            showNotification(result.message, 'success');
                            await loadDespesas();
                        } else {
                            showNotification(`Erro ao excluir despesa: ${result.message}`, 'danger');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification(`Erro ao excluir despesa: ${error.message}`, 'danger');
                    }
                }
            });
        });
    }

    // Evento para o formulário de criação/atualização
    document.getElementById('despesa-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        // Coletar os dados do formulário
        const formData = {
            descricao: this.querySelector('input[name="descricao"]').value,
            valor: this.querySelector('input[name="valor"]').value,
            data: this.querySelector('input[name="data"]').value,
            categoria: this.querySelector('select[name="categoria"]').value
        };

        // Validar se todos os campos estão preenchidos
        if (!formData.descricao || !formData.valor || !formData.data || !formData.categoria) {
            showNotification('Por favor, preencha todos os campos.', 'danger');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        try {
            // Verificar se é uma atualização ou criação
            const isUpdate = this.dataset.editId;
            const url = isUpdate ? `/api/despesas/update/${isUpdate}` : '/api/despesas/create';

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8',
                    'X-CSRF-Token': csrfToken,
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');

                // Limpar formulário
                this.reset();

                // Se era uma atualização, resetar o formulário para o modo de criação
                if (isUpdate) {
                    delete this.dataset.editId;
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.textContent = 'Adicionar';
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-success');
                    const cancelButton = this.querySelector('.cancel-edit');
                    if (cancelButton) {
                        cancelButton.remove();
                    }
                }

                // Recarregar a lista de despesas
                await loadDespesas();
            } else {
                // Exibir mensagem de erro
                let errorMessage = 'Erro ao salvar despesa';
                if (result.errors) {
                    errorMessage = Object.entries(result.errors)
                        .map(([field, errors]) => `${field}: ${errors.join(', ')}`)
                        .join('<br>');
                } else if (result.message) {
                    errorMessage = result.message;
                }
                showNotification(`Erro ao salvar despesa:<br>${errorMessage}`, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(`Erro ao salvar despesa: ${error.message}`, 'danger');
        }
    });

    // Evento para o formulário de filtros
    document.getElementById('filter-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        await loadDespesas();
    });
</script>