<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */
use Jframe\helpers\bootstrap\ActiveForm;
use Jframe\helpers\bootstrap\Html;

?>
<?= Html::beginTag('div', ['class' => 'container']) ?>

<?php $form = ActiveForm::begin(['id' => 'form0', 'class' => 'form-horizontal', 'method' => 'POST']) ?>

<?= $form->field($model, 'userName')->textInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'userAge')->textInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'mobile')->textInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'gender')->selectInput(['' => '选择', '0' => '男', '1' => '女', '2' => '中性'], ['class' => 'form-control']) ?>
<?= $form->field($model, 'address')->textInput(['class' => 'form-control']) ?>
<?= Html::submitButton('提交', ['class' => 'form-control btn btn-warning']) ?>

<?php $form->end(); ?>

<?= Html::endTag('div') ?>
