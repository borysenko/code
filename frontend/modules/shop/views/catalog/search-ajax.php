<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<? if (!empty($categories)): ?>
    <p class="text-bold">Категории:</p>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><?= Html::a($category->title, ['/shop/catalog/list', 'slug' => $category->slug, 'id' => $category->id]) ?></li>
        <?php endforeach; ?>
    </ul>
<? endif; ?>
<? if (!empty($products)): ?>
    <p class="text-bold">Товары:</p>
    <?php foreach ($products as $product): ?>
        <div class="row">
            <div class="col-xs-4">
                <a href="<?=Url::to(['/shop/catalog/view', 'slug' => $product->slug, 'id' => $product->id])?>">
                    <?php

                    echo Html::img($product->getThumbFileUrl('image', 'thumb', '/img/no_image.jpg'), ['width' => '100%']);

                    ?>
                </a>
            </div>
            <div class="col-xs-8">
                <a href="<?=Url::to(['/shop/product/view', 'slug' => $product->slug, 'id' => $product->id])?>"><h4><?= Html::encode($product->title) ?></h4></a>
                <div class="price">
                    <?if($product->price > 0):?>
                        <p><?= $product->price ?> &#8372;</p>
                    <?endif;?>
                    <?if($product->price_old > 0):?>
                        <p><strike><?= $product->price_old ?> &#8372;</strike></p>
                    <?endif;?>
                    <?if($product->not_available == 1):?>
                        <p class="not_available"><?=Yii::t('shop', 'Not available')?></p>
                    <?else:?>
                        <p class="are_available"><?=Yii::t('shop', 'Are available')?></p>
                    <?endif;?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<? endif; ?>

<? if (empty($categories) && empty($products)): ?>
    <div class="text-not-found">Ничего не найдено!</div>
<? endif; ?>
