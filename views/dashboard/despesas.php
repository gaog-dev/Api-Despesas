<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Despesa $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Minhas Despesas';
?>

<div class="container mt-4">
    <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

    <!-- ✅ Flash messages -->
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>

    <!-- Formulário -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Adicionar Nova Despesa</div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                    'action' => ['dashboard/despesas'], // ✅ envia para DashboardController
                    'method' => 'post',
            ]); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'descricao')
                            ->textInput(['placeholder' => 'Ex: Supermercado'])
                            ->label('Descrição') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'valor')
                            ->input('number', ['step' => '0.01', 'placeholder' => 'R$'])
                            ->label('Valor (R$)') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'data')
                            ->input('date')
                            ->label('Data') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'categoria')
                            ->dropDownList([
                                    'alimentação' => 'Alimentação',
                                    'transporte' => 'Transporte',
                                    'lazer' => 'Lazer',
                            ], ['prompt' => 'Selecione uma categoria'])
                            ->label('Categoria') ?>
                </div>
            </div>

            <div class="text-end">
                <?= Html::submitButton('Adicionar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Lista -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Lista de Despesas</div>
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
                                    'template' => '{update} {delete}',
                                    'buttons' => [
                                            'update' => fn($url, $model) =>
                                            Html::a('<i class="bi bi-pencil"></i>', ['dashboard/update', 'id' => $model->id], [
                                                    'class' => 'btn btn-sm btn-warning',
                                                    'title' => 'Editar',
                                            ]),
                                            'delete' => fn($url, $model) =>
                                            Html::a('<i class="bi bi-trash"></i>', ['dashboard/delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-sm btn-danger',
                                                    'title' => 'Excluir',
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
