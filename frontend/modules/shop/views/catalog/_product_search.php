<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Markdown;
use yii\helpers\StringHelper;
?>
<!-- product -->
<div class="col-md-3 col-xs-12">
    <div class="product">
        <div class="product-img">
            <a href="<?=Url::to(['/shop/product/view', 'slug' => $model->slug, 'id' => $model->id])?>">
                <?php

                echo Html::img($model->getPic('image', 'thumb', '/img/no_image.jpg'), ['class' => 'img-responsive', 'alt' => Html::encode($model->title)]);

                ?>
            </a>
            <?/**
            <div class="product-label">
            <?if($model->action):?>
            <span class="action"><?=Yii::t('shop', 'Action')?></span>
            <?endif;?>
            <?if($model->novelty):?>
            <span class="new"><?=Yii::t('shop', 'Novelty one')?></span>
            <?endif;?>
            </div>**/?>
        </div>
        <div class="product-body">
            <p class="product-category"><?=$model->category->title?></p>
            <h3 class="product-name"><a href="<?=Url::to(['/shop/product/view', 'slug' => $model->slug, 'id' => $model->id])?>"><h4><?= StringHelper::truncate($model->title,70,'...') ?></h4></a></h3>

            <?if($model->price > 0):?>
                <h4 class="product-price"><?= $model->price ?> грн.</h4>
            <?endif;?>
            <?if($model->price > 0 && $model->price_old > 0):?>
                <del class="product-old-price"><?= $model->price_old ?> грн.</del>
            <?endif;?>

            <?/**
            <div class="product-btns">
            <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span
            class="tooltipp">add to wishlist</span></button>
            <button class="add-to-compare"><i class="fa fa-exchange"></i><span
            class="tooltipp">add to compare</span></button>
            <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span>
            </button>
            </div>
             **/?>

        </div>
        <?if($model->price > 0 && $model->not_available == 0):?>
            <div class="add-to-cart">
                <a href="<?=Url::to(['/shop/product/view', 'slug' => $model->slug, 'id' => $model->id])?>" class="add-to-cart-btn"><i class="fa fa-eye"></i> Подробнее</a>
            </div>
        <?endif;?>
    </div>
</div>
<!-- /product -->
