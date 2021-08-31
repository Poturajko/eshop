<?php
(isset($product))
    ? $this->title = 'Редактировать товар ' . $product->name
    : $this->title = 'Создать товар';
?>


    <div class="col-md-12">


        <?php
        echo (isset($product))
            ? '<h1>Редактировать товар <b>' . $product->name . '</b></h1>'
            : '<h1>Добавить товар</h1>';
        ?>

       <form method="POST" enctype="multipart/form-data"
           <?php
           echo (isset($product))
               ? "action='/admin/products/$product->id'"
               : "action='/admin/products'";
           ?>
       >
                <div class="input-group row">
                    <label for="code" class="col-sm-2 col-form-label">Код: </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="code" id="code"
                               value="<?=$product->code?>">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="name" class="col-sm-2 col-form-label">Название: </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="name" id="name"
                               value="<?=$product->name?>">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="category_id" class="col-sm-2 col-form-label">Категория: </label>
                    <div class="col-sm-6">
                        <select name="category_id" id="category_id" class="form-control">
                            <?php foreach($categories as $category): ?>
                                <option value="<?=$category->id?>"

                                        <?php if (isset($product)):?>

                                        <?php if ($product->category_id == $category->id):?>
                                             <?= 'selected' ?>
                                       <?php endif; ?>
                                       <?php endif; ?>

                                ><?=$category->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="description" class="col-sm-2 col-form-label">Описание: </label>
                    <div class="col-sm-6">
								<textarea name="description" id="description" cols="72"rows="7"> <?=$product->description?> </textarea>
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="image" class="col-sm-2 col-form-label">Картинка: </label>
                    <div class="col-sm-10">
                        <label class="btn btn-default btn-file">
                            Загрузить <input type="file" style="display: none;" name="image" id="image">
                        </label>
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="price" class="col-sm-2 col-form-label">Цена: </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="price" id="price"
                               value="<?=$product->price?>">
                    </div>
                </div>
          <br>
          <?php foreach(['hit' => 'Хит продаж','new'=> 'Новинка','recommend'=>'Рекомендуемые'] as $field => $title): ?>
          <div class="form-group row">
             <label for="code" class="col-sm-2 col-form-label"> <?= $title ?> </label>
             <div class="col-sm-6">
                <input type="checkbox" class="form-control" name="<?= $field ?>" id="<?= $field ?>"
                       <?php if(isset($product) && $product->{$field} == 1): ?>
                checked="checked"
                <?php endif; ?>
                >
             </div>
          </div>
          <br>
          <?php endforeach; ?>
                <button class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>
