<?php
use yii\helpers\Html;

$this->title = 'Despesas Pessoais API';
?>

<div class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-primary">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="lead text-muted mt-3">
            Gerencie suas despesas de forma simples e rápida.
            Uma aplicação <strong>API RESTful</strong> moderna, integrada ao Yii2.
        </p>

        <div class="mt-4">
            <?= Html::a('Minhas Despesas', ['/dashboard/despesas'], ['class' => 'btn btn-lg btn-success me-2']) ?>
        </div>
    </div>
</div>

<!-- Features -->
<div id="features" class="container py-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-wallet2 text-primary display-5"></i>
                    <h5 class="card-title mt-3">Controle Financeiro</h5>
                    <p class="card-text text-muted">
                        Acompanhe todas as suas despesas em um só lugar.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-graph-up text-success display-5"></i>
                    <h5 class="card-title mt-3">Relatórios e Filtros</h5>
                    <p class="card-text text-muted">
                        Visualize gastos por categoria e períodos específicos.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-shield-lock text-danger display-5"></i>
                    <h5 class="card-title mt-3">Segurança JWT</h5>
                    <p class="card-text text-muted">
                        API protegida por autenticação segura com tokens JWT.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-primary text-white p
