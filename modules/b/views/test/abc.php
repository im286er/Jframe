<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */
use Jframe\helpers\Html;
?>
<?= Html::beginTag('div', ['class' => 'container']) ?>

<?= Html::beginForm(['id' => 'form0', 'class' => 'form-horizontal', 'method' => 'POST']) ?>


<?= Html::textInput(['class' => 'form-control', 'id' => 'ipname']) ?>
<?= Html::textInput(['class' => 'form-control', 'id' => 'ipnams']) ?>
<?= Html::textInput(['class' => 'form-control', 'id' => 'ipnaml']) ?>
<?= Html::selectInput(['' => '选择', '0' => 's男', '1' => '女'], ['class' => 'form-control']) ?>
<?= Html::submitButton('提交', ['class' => 'form-control btn btn-warning']) ?>

<?= Html::endForm() ?>
<?= Html::endTag('div') ?>