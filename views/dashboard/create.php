<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Adicionar Despesa';
?>

<div class="container mt-4">
    <h3><?= Html::encode($this->title) ?></h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'valor')->input('number', ['step' => '0.01']) ?>
            <?= $form->field($model, 'data')->input('date') ?>
            <?= $form->field($model, 'categoria')->dropDownList([
                'alimentação' => 'Alimentação',
                'transporte' => 'Transporte',
                'lazer' => 'Lazer',
            ], ['prompt' => 'Selecione uma categoria']) ?>

            <div class="form-group">
                <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Cancelar', ['dashboard/despesas'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
