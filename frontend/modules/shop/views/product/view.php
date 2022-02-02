<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use frontend\modules\shop\widgets\FilterCheckboxWidget;

/* @var $this yii\web\View */
$title = $model->title;
$this->title = Html::encode($model->meta_title);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
//$this->registerMetaTag(['name' => 'keywords', 'content' => $text->meta_keywords]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);

$this->registerCssFile("/js/fancyBox/source/jquery.fancybox.css");
$this->registerJsFile('/js/fancyBox/source/jquery.fancybox.pack.js', ['position' => yii\web\View::POS_END, 'depends' => ['yii\web\JqueryAsset']]);
$script = <<< JS
    $('.fancybox').fancybox();
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<!-- BREADCRUMB -->
<div>
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('yii', 'Home'),
                        'url' => ['site/index'],
                    ],
                    'links' => [
                        ['label' => $model->category->title, 'url' => ['catalog/list', 'slug' => $model->category->slug, 'id' => $model->category->id]],
                        $title
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- Product main img -->
            <div class="col-md-5 col-md-push-2">
                <div id="product-main-img">
                    <div class="product-preview">
                        <a class="fancybox" rel="gallery" href="<?= $model->getImageFileUrl('image') ?>">
                        <img src="<?= $model->getPic('image', 'preview', '/img/no_image.jpg') ?>"
                             alt="<?= Html::encode($model->title) ?>"/>
                        </a>
                    </div>
                    <?php foreach ($model->images as $image): ?>
                        <div class="product-preview">
                            <a class="fancybox" rel="gallery" href="<?= $image->getImageFileUrl('image') ?>">
                            <img src="<?= $image->getThumbFileUrl('image', 'thumb', '/img/no_image.jpg') ?>"
                                 alt="<?= Html::encode($image->alt) ?>"/>
                            </a>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <!-- /Product main img -->

            <!-- Product thumb imgs -->
            <div class="col-md-2  col-md-pull-5">
                <div id="product-imgs">
                    <div class="product-preview">
                        <img src="<?= $model->getPic('image', 'ico', '/img/no_image.jpg') ?>"
                             alt="<?= Html::encode($model->title) ?>"/>
                    </div>
                    <?php foreach ($model->images as $image): ?>
                        <div class="product-preview">
                            <img src="<?= $image->getThumbFileUrl('image', 'ico', '/img/no_image.jpg') ?>"
                                 alt="<?= Html::encode($image->alt) ?>"/>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <!-- /Product thumb imgs -->

            <!-- Product details -->
            <div class="col-md-5">
                <div class="product-details">
                    <h1 class="product-name"><?=$model->title?></h1>
                    <?if(!empty($model->code)):?>
                        <p class="code-text">Код товара: <span><?=$model->code?></span></p>
                    <?endif;?>
                    <div>
                        <h3 class="product-price"><?=$model->price?> грн. <?if(!empty($model->price_old)):?><del class="product-old-price"><?=$model->price_old?> грн.</del><?endif;?></h3>
                        <?if($model->deliveryFree == 1):?>
                        <span class="product-delivery">Бесплатная доставка</span>
                        <?endif;?>
                    </div>
                    <?=$model->description?>

                    <div class="add-to-cart">
                        <div class="qty-label">
                            Кол-во
                            <div class="input-number">
                                <input type="number" value="1">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>
                        <a href="<?=Url::to(['cart/add', 'id' => $model->id])?>" class="add_to_cart add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Купить</a>
                    </div>
<?/**
                    <ul class="product-btns">
                        <li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
                        <li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li>
                    </ul>
**/?>
                    <ul class="product-links">
                        <li>Категория:</li>
                        <li><a href="<?=Url::to(['/shop/catalog/list', 'slug' => $model->category->slug, 'id' => $model->category->id])?>"><?=$model->category->title?></a></li>
                    </ul>
                    <?/**
                    <ul class="product-links">
                        <li>Share:</li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                    **/?>
                </div>
            </div>
            <!-- /Product details -->

            <!-- Product tab -->
            <div class="col-md-12">
                <div id="product-tab">
                    <!-- product tab nav -->
                    <ul class="tab-nav">
                        <?if(!empty($model->option)):?>
                            <li class="active"><a data-toggle="tab" href="#tab1">Характеристики</a></li>
                        <?endif;?>
                        <?if(!empty($model->video1)):?>
                            <li><a data-toggle="tab" href="#tab2">Видео</a></li>
                        <?endif;?>
                    </ul>
                    <!-- /product tab nav -->

                    <!-- product tab content -->
                    <div class="tab-content">
                        <?if(!empty($model->option)):?>
                        <!-- tab1  -->
                        <div id="tab1" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12">
                                    <?if(!empty($model->option)):?>
                                        <table class="table table-bordered">
                                            <tbody>
                                            <?php foreach ($model->option as $item):?>
                                                <tr>
                                                    <td><?=$item->option?>:</td>
                                                    <td><?=$item->value?></td>
                                                </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    <?endif;?>
                                </div>
                            </div>
                        </div>
                        <!-- /tab1  -->
                        <?endif;?>
                        <?if(!empty($model->video1)):?>
                        <!-- tab2  -->
                        <div id="tab2" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-12">
                                    <iframe width="100%" height="515" src="<?=$model->video?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <!-- /tab2  -->
                        <?endif;?>
                    </div>
                    <!-- /product tab content  -->
                </div>
            </div>
            <!-- /product tab -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

