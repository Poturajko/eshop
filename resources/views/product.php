<?php
$this->title = 'Товар';
?>

<h1><?=$product->name?></h1>
<h2><?= $product->category()->name ?></h2>
<p>Цена: <b><?=$product->price?></b></p>
<img src="<?= url($product->image) ?>">
<p><?=$product->description?></p>


<span></span>
<br>
<span>Сообщить мне, когда товар появится в наличии:</span>
<div class="warning">
</div>
<form method="POST" action="/subscription/<?= $product->id ?>">
   <input type="text" name="email"></input>
   <button type="submit">Отправить</button>
</form>
