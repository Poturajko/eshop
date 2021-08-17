<?php
$this->title = 'Категории'
?>
<div class="panel">
    <?php foreach ($categories as $category): ?>
    <a href="/<?=$category->code?>">
        <img src="http://internet-shop.tmweb.ru/storage/categories/mobile.jpg">
        <h2><?=$category->name?></h2>
    </a>
    <p>
        <?=$category->description?>
    </p>
    <?php endforeach; ?>
</div>
