<?php
$this->title = 'Категории'
?>
<div class="panel">
    <?php foreach ($categories as $category): ?>
    <a href="/<?=$category->code?>">
        <img src="<?= url($category->image) ?>">
        <h2><?=$category->name?></h2>
    </a>
    <p>
        <?=$category->description?>
    </p>
    <?php endforeach; ?>
</div>
