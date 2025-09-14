<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

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
                                            Html::a('<i class="bi bi-pencil"></i> Editar', ['dashboard/update', 'id' => $model->id], [
                                                    'class' => 'btn btn-sm btn-warning',
                                            ]),
                                            'delete' => fn($url, $model) =>
                                            Html::a('<i class="bi bi-trash"></i> Excluir', ['dashboard/delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-sm btn-danger',
                                                    'data' => [
                                                            'confirm' => 'Tem certeza que deseja excluir esta despesa?',
                                                            'method' => 'post',
                                                    ],
                                            ]),
                                    ],
                            ],
                    ],
            ]); ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('despesa-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const response = await fetch('/dashboard/despesas/create', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = '/dashboard/despesas';
        } else {
            alert('Erro ao salvar despesa: ' + JSON.stringify(data.errors || data));
        }
    });
</script>