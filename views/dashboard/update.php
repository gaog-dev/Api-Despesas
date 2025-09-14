<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Despesa $model */

$this->title = 'Editar Despesa';
?>

<div class="container mt-4">
    <h3><?= Html::encode($this->title) ?></h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                    'action' => ['dashboard/update', 'id' => $model->id],
                    'method' => 'post',
            ]); ?>

            <?= $form->field($model, 'descricao')->textInput() ?>
            <?= $form->field($model, 'valor')->input('number', ['step' => '0.01']) ?>
            <?= $form->field($model, 'data')->input('date') ?>
            <?= $form->field($model, 'categoria')->dropDownList([
                    'alimentação' => 'Alimentação',
                    'transporte' => 'Transporte',
                    'lazer' => 'Lazer',
            ]) ?>

            <div class="text-end">
                <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
