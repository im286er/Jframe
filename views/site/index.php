<?php

/**
 * This is the bootstrap setting of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

?>

<?php
$form = \Jframe\html\bootstrap\ActiveForm::begin([
    'layout' => '{label}{control}{error}'
]);
?>

<?= $form->field($model, 'userName')->textInput(); ?>
<?= $form->field($model, 'userAge')->textInput(); ?>
<?= $form->field($model, 'gender')->selectInput(['' => '选择', '0' => '男', '1' => '女']) ?>
<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'address')->textInput() ?>
<?= \Jframe\html\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-primary form-control']) ?>

<?php $form->end() ?>
