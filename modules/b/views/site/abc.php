<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542606@qq.com | www.supjos.cn>
 */

use Jframe\helpers\Html;
use Jframe\helpers\AppAsset;

AppAsset::register();

?>
<?= Html::beginTag('div', ['class'=>'container']) ?>

<?= Html::beginForm(['id'=>'form0', 'class'=>'form-horizontal']) ?>


<?= Html::textInput(['class'=>'form-control','id'=>'ipname']) ?>
<?= Html::textInput(['class'=>'form-control', 'id'=>'ipnams']) ?>
<?= Html::textInput(['class'=>'form-control', 'id'=>'ipnaml']) ?>
<?= Html::selectInput([''=>'选择','0'=>'男', '1'=>'女'],['class'=>'form-control']) ?>
<?= Html::submitButton('提交', ['class'=>'form-control btn btn-warning']) ?>

<?= Html::endForm() ?>
<?= Html::endTag('div') ?>