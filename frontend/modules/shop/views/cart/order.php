<?php

use \yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;

$title = Yii::t('shop', 'Order');

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */
?>
<div class="head2">
    <div class="container">
        <div class="info2">
            <h1 class="section-title text-center"><?= Html::encode($title) ?></h1>

            <nav class="breadcrumbs">
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('yii', 'Home'),
                        'url' => ['site/index'],
                    ],
                    'links' => [
                        $title
                    ],
                ]) ?>
            </nav>


        </div>
    </div>
</div>
<div class="list_content">
    <div class="content">
        <div class="container cnt">


            <div class="order-products">
                <div class="row cart_list_begin">
                    <div class="col-xs-9">
                        <div class="col-xs-3">
                        </div>
                        <div class="col-xs-9">
                            <?= Yii::t('shop', 'Product') ?>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <?= Yii::t('shop', 'Cost') ?>
                    </div>
                </div>
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
                            'link_remove' => Html::a('×', ['cart/list', 'remove' => 1, 'id' => $product->getId()], [
                                'class' => 'btn btn-danger',
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
                            'link_remove' => Html::a('×', ['cart/list', 'remove' => 1, 'size'=>true, 'id' => $product->getId()], [
                                'class' => 'btn btn-danger',
                            ]),
                        ];
                    }
                    ?>
                <div class="cart_item">
                    <div class="row">
                        <div class="col-xs-9">
                            <div class="col-xs-3">
                                <a href="<?= $product['url'] ?>"
                                   data-pjax="0">
                                    <?php

                                    echo $product['image'];

                                    ?>
                                </a>
                            </div>
                            <div class="col-xs-9">
                                <h4><?= $product['link'] ?></h4>

                                <div>
                                    <?= Yii::t('shop', 'Price') ?>:
                                    <strong><?= $product['price'] ?> грн.</strong>
                                </div>
                                <div>
                                    <?= Yii::t('shop', 'Quantity') ?>:
                                    <strong><?= $quantity = $product['quantity'] ?></strong>
                                </div>

                            </div>

                        </div>
                        <div class="col-xs-3">
                            <strong><?= $product['cost'] ?> грн.</strong>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
                <div class="row">
                    <div class="col-md-2 col-md-offset-5 text-center">
                        <h4><?= Yii::t('shop', 'Total') ?>: <strong><?= $total ?>  грн.</strong></h4>
                    </div>
                </div>
            </div>
            <hr />
            <div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 well">
                        <h2><?=Yii::t('shop', 'Contact details')?></h2>
                        <?php
                        /* @var $form ActiveForm */
                        $form = ActiveForm::begin([
                            'id' => 'order-form',
                        ]) ?>

                        <?= $form->field($order, 'name') ?>
                        <?= $form->field($order, 'phone') ?>
                        <?= $form->field($order, 'email') ?>
                        <?= $form->field($order, 'address') ?>
                        <?= $form->field($order, 'notes')->textarea(['rows' => 8]) ?>

                        <div class="form-group row">
                            <div class="col-xs-12">
                                <?= Html::submitButton(Yii::t('shop', 'Order'), ['class' => 'btn btn-danger']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>