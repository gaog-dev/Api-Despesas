<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Despesa $model */

$this->title = 'Editar Despesa';
?>

<div class="container mt-4">
    <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">Atualizar Despesa</div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => 'dashboard/despesas/update',
            ]); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'descricao')->textInput()->label('Descrição') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'valor')->textInput(['type' => 'number', 'step' => '0.01'])->label('Valor (R$)') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'data')->textInput(['type' => 'date'])->label('Data') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'categoria')->dropDownList([
                        'alimentação' => 'Alimentação',
                        'transporte' => 'Transporte',
                        'lazer' => 'Lazer',
                    ], ['prompt' => 'Categoria'])->label('Categoria') ?>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <?= Html::a('Voltar', ['dashboard/despesas'], ['class' => 'btn btn-secondary']) ?>
                <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
