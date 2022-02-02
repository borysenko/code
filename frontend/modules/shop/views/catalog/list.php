<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use frontend\modules\shop\widgets\FilterCheckboxWidget;

/* @var $this yii\web\View */
$title = $category === null ? 'Welcome!' : $category->title;
$this->title = Html::encode($category->meta_title);
$this->registerMetaTag(['name' => 'description', 'content' => $category->meta_description]);
//$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta_keywords]);

$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
?>

<div class="head2">
    <div class="container">

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
<div class="body_box">
    <div class="list_content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <p class="text-center">
                    <a href="#" id="btn-filters" class="btn btn-default"><span class="fa fa-list"></span> Фильтры</a>
                    </p>
                    <div id="leftsidebar">
                        <?if(Yii::$app->request->get('filters')):?>
                        <p class="text-center">
                            <?=Html::a('Очистить фильтры', ['catalog/list', 'slug' => $category->slug, 'id' => $category->id, 'sort' => $sort], ['class' => 'btn btn-sm btn-danger'])?>
                        </p><br />
                        <?endif;?>
                    <?php foreach ($filters as $filter):?>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?=$filter['parent']?></div>
                            <div class="panel-body">
                                <?php foreach ($filter['filters'] as $item):?>
                                    <p><?=FilterCheckboxWidget::widget(['url' => ['catalog/list', 'slug' => $category->slug, 'id' => $category->id, 'sort' => $sort],'item' => $item, 'productsQuery' => $productsQueryClone])?></p>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-8">

                        </div>
                        <div class="col-md-4">
                            <?php
                            $url = rawurldecode(\yii\helpers\Url::to(['/shop/catalog/list', 'slug' => $category->slug, 'id' => $category->id, 'view-sort' => 'all', 'filters' => Yii::$app->request->get('filters')]));
                            $items = [
                                'price' => Yii::t('shop', 'From cheap to expensive'),
                                '-price' => Yii::t('shop', 'From expensive to cheap'),
                                '-novelty' => Yii::t('shop', 'Novelty'),
                                '-action' => Yii::t('shop', 'Actions'),
                            ];
                            echo Html::dropDownList('sort', $sort, $items, ['prompt' => Yii::t('shop', 'Choose sort'), 'onchange' => "document.location='{$url}&sort='+this.value;", 'class' => 'form-control']);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                    <?= ListView::widget([
                        'dataProvider' => $productsDataProvider,
                        'itemView' => '_product',
                    ]) ?>
                    </div>
                    <?if(!empty($category->body)):?>
                    <hr />
                    <?=$category->body?>
                    <?endif;?>
                </div>
            </div>
        </div>
    </div>
</div>