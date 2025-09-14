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
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?> | Despesas Pessoais</title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100 bg-light">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
            'brandLabel' => '<i class="bi bi-wallet2"></i> Despesas Pessoais',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top'],
    ]);

    $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    } else {
        $menuItems[] = [
                'label' => 'Olá, ' . Html::encode(Yii::$app->user->identity->username),
                'items' => [
                        ['label' => 'Minhas Despesas', 'url' => ['/despesa/index']],
                        '<hr class="dropdown-divider">',
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
                        . Html::submitButton('Logout', ['class' => 'dropdown-item text-danger'])
                        . Html::endForm()
                        . '</li>'
                ],
        ];
    }

    echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => $menuItems,
            'encodeLabels' => false,
    ]);

    NavBar::end();
    ?>
</header>

<main class="flex-shrink-0 mt-5 pt-4">
    <div class="container py-4">
        <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
                'options' => ['class' => 'breadcrumb bg-white px-3 py-2 rounded shadow-sm'],
        ]) ?>

        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-dark text-light shadow-sm">
    <div class="container d-flex justify-content-between">
        <span>&copy; Despesas Pessoais <?= date('Y') ?></span>
        <span>Powered by Yii Framework</span>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
