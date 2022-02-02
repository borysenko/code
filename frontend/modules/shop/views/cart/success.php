<?php

use \yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

$title = Yii::t('shop', 'Cart');

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

          <?php
          echo Alert::widget([]);
          ?>
        </div>
    </div>
</div>
