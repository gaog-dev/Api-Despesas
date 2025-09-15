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
    document.getElementById('despesa-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        // Coletar os dados do formulário
        const formData = {
            descricao: this.querySelector('input[name="descricao"]').value,
            valor: this.querySelector('input[name="valor"]').value,
            data: this.querySelector('input[name="data"]').value,
            categoria: this.querySelector('select[name="categoria"]').value
        };

        console.log('Dados a serem enviados:', formData);
        console.log('Tipo de dados:', {
            descricao: typeof formData.descricao,
            valor: typeof formData.valor,
            data: typeof formData.data,
            categoria: typeof formData.categoria
        });
        console.log('Tamanho dos dados:', {
            descricao: formData.descricao.length,
            valor: formData.valor.length,
            data: formData.data.length,
            categoria: formData.categoria.length
        });

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
            // Converter para JSON e verificar
            const jsonBody = JSON.stringify(formData);
            console.log('JSON a ser enviado:', jsonBody);

            const response = await fetch('/api/despesas/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                },
                body: jsonBody
            });

            console.log('Status da resposta:', response.status);
            console.log('Headers da resposta:', response.headers);

            // Verificar se a resposta é JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const textResponse = await response.text();
                console.error('Resposta não é JSON:', textResponse);
                throw new Error('Resposta do servidor não é JSON');
            }

            const result = await response.json();
            console.log('Resposta do servidor:', result);

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
                let errorMessage = 'Erro ao salvar despesa';
                if (result.errors) {
                    errorMessage = Object.entries(result.errors)
                        .map(([field, errors]) => `${field}: ${errors.join(', ')}`)
                        .join('<br>');
                } else if (result.message) {
                    errorMessage = result.message;
                }

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
</script>