
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $model */

$form = ActiveForm::begin([
    'action' => ['dashboard/update', 'id' => $model['id']],
    'method' => 'post',
]);
?>

<?= $form->field(new \app\models\Despesa(), 'descricao')->textInput([
    'value' => $model['descricao'] ?? '',
]) ?>

<?= $form->field(new \app\models\Despesa(), 'valor')->textInput([
    'type' => 'number', 'step' => '0.01',
    'value' => $model['valor'] ?? '',
]) ?>

<?= $form->field(new \app\models\Despesa(), 'data')->input('date', [
    'value' => $model['data'] ?? '',
]) ?>

<?= $form->field(new \app\models\Despesa(), 'categoria')->dropDownList([
    'alimentação' => 'Alimentação',
    'transporte'  => 'Transporte',
    'lazer'       => 'Lazer',
], [
    'prompt' => 'Selecione uma categoria',
    'value' => $model['categoria'] ?? '',
]) ?>

<div class="form-group">
    <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
