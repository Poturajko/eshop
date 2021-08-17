<?php
$this->title = 'Оформить заказ';
?>
<h1>Подтвердите заказ:</h1>
<div class="container">
   <div class="row justify-content-center">
      <p>Общая стоимость: <b><?= (new \App\Models\Cart())->getFullSum() ?></b></p>
      <form action="/checkout" method="POST">
         <div>
            <p>Укажите свои имя и номер телефона, чтобы наш менеджер мог с вами связаться:</p>

            <div class="container">
               <div class="form-group">
                  <label for="name" class="control-label col-lg-offset-3 col-lg-2">Имя: </label>
                  <div class="col-lg-4">
                     <input type="text" name="name" id="name" value="<?= $order->name ?>" class="form-control">
                      <?php if ($order->hasError('name')): ?>
                         <div class="alert alert-danger">
                             <?= $order->getFirstError('name') ?>
                         </div>
                      <?php endif; ?>
                  </div>
               </div>
               <br>
               <br>
               <div class="form-group">
                  <label for="phone" class="control-label col-lg-offset-3 col-lg-2">Номер
                     телефона: </label>
                  <div class="col-lg-4">
                     <input type="text" name="phone" id="phone" value="<?= $order->phone ?>" class="form-control">
                      <?php if ($order->hasError('phone')): ?>
                         <div class="alert alert-danger">
                             <?= $order->getFirstError('phone') ?>
                         </div>
                      <?php endif; ?>
                  </div>
               </div>
               <br>
               <br>
               <div class="form-group">
                  <label for="name" class="control-label col-lg-offset-3 col-lg-2">Email: </label>
                  <div class="col-lg-4">
                     <input type="text" name="email" id="email" value="<?= $order->email ?>" class="form-control">
                      <?php if ($order->hasError('email')): ?>
                         <div class="alert alert-danger">
                             <?= $order->getFirstError('email') ?>
                         </div>
                      <?php endif; ?>
                  </div>
               </div>
            </div>
            <br>
            <input type="submit" class="btn btn-success" value="Подтвердите заказ">
         </div>
      </form>
   </div>
</div>

