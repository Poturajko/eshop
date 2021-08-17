<?php
$this->title = 'Ошибка'
/** @var $exception \Exception */
?>

<h3><?= $exception->getCode()?> - <?= $exception->getMessage()?></h3>