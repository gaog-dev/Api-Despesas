<?php
use yii\helpers\Html;

if (Yii::$app->session->hasFlashes()) {
    $flashes = Yii::$app->session->getAllFlashes();
    foreach ($flashes as $type => $message) {
        $alertClass = match($type) {
            'error', 'danger' => 'alert-danger',
            'success' => 'alert-success',
            'info' => 'alert-info',
            'warning' => 'alert-warning',
            default => 'alert-info'
        };

        echo '<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050; max-width: 350px;">';
        echo '<div class="toast align-items-center text-white bg-' . $alertClass . ' border-0" role="alert" aria-live="assertive" aria-atomic="true">';
        echo '<div class="d-flex">';
        echo '<div class="toast-body">';
        echo Html::encode($message);
        echo '</div>';
        echo '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}