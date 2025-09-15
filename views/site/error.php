<?php
/* @var $exception \Exception */

$this->title = $exception->statusCode . ' ' . $exception->getName();
?>
<div class="site-error">
    <h1><?= htmlspecialchars($exception->getName()) ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(htmlspecialchars($exception->getMessage())) ?>
    </div>
    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>
</div>