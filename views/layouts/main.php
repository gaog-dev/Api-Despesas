<?php
/** @var yii\web\View $this */
/** @var string $content */

use Yii;
use app\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?> <!-- ✅ Garante CSRF em todos os POSTs -->
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
    </head>
    <body>
    <?php $this->beginBody() ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <div class="wrap">
        <?php
        NavBar::begin([
                'brandLabel' => '💰 Despesas Pessoais',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar navbar-expand-lg navbar-dark bg-dark'],
        ]);

        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => array_merge(
                        [
                                ['label' => 'Início', 'url' => ['/site/index']],
                        ],
                        Yii::$app->user->isGuest
                                ? [
                                ['label' => 'Signup', 'url' => ['/site/signup']],
                                ['label' => 'Login', 'url' => ['/site/login']],
                        ]
                                : [
                                ['label' => 'Minhas Despesas', 'url' => ['/dashboard/despesas']],
                                '<li>'
                                . Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                        'Sair (' . Yii::$app->user->identity->username . ')',
                                        ['class' => 'btn btn-link logout text-white']
                                )
                                . Html::endForm()
                                . '</li>',
                        ]
                ),
        ]);

        NavBar::end();
        ?>

        <div class="container mt-4">
            <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>

            <!-- ✅ Exibir flash messages do controller -->
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endforeach; ?>

            <?= $content ?>
        </div>
    </div>

    <footer class="footer bg-light text-center py-3 mt-auto">
        <div class="container">
            <p class="text-muted mb-0">
                &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>