<?php

use \yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use yii\helpers\Url;

$title = Yii::t('shop', 'Cart');

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */
?>
<?php Pjax::begin(['enablePushState' => false]); ?>

    <div class="shp__cart__wrap">
        <?php foreach ($products as $product): ?>
        <?php
            if($product instanceof \common\models\Product){
                $product = [
                    'url' => Url::to(['/shop/product/view', 'slug' => $product->slug, 'id' => $product->id]),
                    'image' => Html::img($product->getThumbFileUrl('image', 'ico', '/img/no_image.jpg'), ['class' => 'img-responsive']),
                    'link' => Html::a(Html::encode($product->title), ['/shop/product/view', 'slug' => $product->slug, 'id' => $product->id], ['data-pjax' => 0]),
                    'price' => $product->price,
                    'quantity' => $product->getQuantity(),
                    'id' => $product->getId(),
                    'cost' => $product->getCost(),
                    'link_minus' => Html::a('-', ['cart/list', 'update' => 1, 'id' => $product->getId(), 'quantity' => $product->getQuantity() - 1], ['class' => 'btn btn-sm btn-danger', 'disabled' => ($product->getQuantity() - 1) < 1]),
                    'link_plus' => Html::a('+', ['cart/list', 'update' => 1, 'id' => $product->getId(), 'quantity' => $product->getQuantity() + 1], ['class' => 'btn btn-sm btn-success']),
                    'link_remove' => Html::a('<i class="zmdi zmdi-close"></i>', ['cart/list', 'remove' => 1, 'id' => $product->getId()], [
                        'class' => '',
                    ]),
                    ];
            }
            elseif($product instanceof \common\models\ProductSize){
                $product = [
                    'url' => Url::to(['/shop/product/view', 'slug' => $product->product->slug, 'id' => $product->product->id]),
                    'image' => Html::img($product->product->getThumbFileUrl('image', 'ico', '/img/no_image.jpg'), ['class' => 'img-responsive']),
                    'link' => Html::a(Html::encode($product->product->title . ' ' . $product->size->name), ['/shop/product/view', 'slug' => $product->product->slug, 'id' => $product->product->id], ['data-pjax' => 0]),
                    'price' => $product->price,
                    'quantity' => $product->getQuantity(),
                    'id' => $product->getId(),
                    'cost' => $product->getCost(),
                    'link_minus' => Html::a('-', ['cart/list', 'update' => 1, 'size'=>true, 'id' => $product->getId(), 'quantity' => $product->getQuantity() - 1], ['class' => 'btn btn-sm btn-danger', 'disabled' => ($product->getQuantity() - 1) < 1]),
                    'link_plus' => Html::a('+', ['cart/list', 'update' => 1, 'size'=>true, 'id' => $product->getId(), 'quantity' => $product->getQuantity() + 1], ['class' => 'btn btn-sm btn-success']),
                    'link_remove' => Html::a('<i class="zmdi zmdi-close"></i>', ['cart/list', 'remove' => 1, 'size'=>true, 'id' => $product->getId()], [
                        'class' => '',
                    ]),
                ];
            }
            ?>
            <div class="shp__single__product">
                <div class="shp__pro__thumb">
                    <a href="<?= $product['url'] ?>" data-pjax="0">
                        <?php

                        echo $product['image'];

                        ?>
                    </a>
                </div>
                <div class="shp__pro__details">
                    <h2><?= $product['link'] ?></h2>
                    <span class="quantity">Кол-во: <?= $quantity = $product['quantity'] ?></span>
                    <?= $product['link_minus'] ?>
                    <?= $product['link_plus'] ?>
                    <span class="shp__price"><?= $product['cost'] ?> грн.</span>
                </div>
                <div class="remove__btn">
                    <?= $product['link_remove'] ?>
                </div>
            </div>

        <?php endforeach ?>
    </div>
        <ul class="shoping__total">
            <li class="subtotal">Сумма за всё:</li>
            <li class="total__price"><?= $total ?> грн.</li>
        </ul>
        <?php if($total > 0):?>
        <ul class="shopping__btn">
            <li><?= Html::a('Оформить заказ', ['cart/order'], ['data-pjax' => 0]) ?></li>
        </ul>
        <?php endif;?>

<?php Pjax::end(); ?>