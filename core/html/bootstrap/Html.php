<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\html\bootstrap;

use Jframe;
use Jframe\di\Reflex;
use Jframe\html\Html as SimpleHtml;

class Html extends SimpleHtml
{
    /**
     * Generate the Bootstrap3 text-input control
     * @param array $htmlOptions
     * @return string The result of the final string
     */
    public static function textInput($htmlOptions = [])
    {
        return parent::textInput(array_merge(['class' => 'form-control'], $htmlOptions));
    }

    /**
     * Generate the Bootstrap3 select input control
     * @param array $selectOptions
     * @param array $htmlOptions
     * @return string The result
     */
    public static function selectInput($selectOptions = [], $htmlOptions = [])
    {
        return parent::selectInput($selectOptions, array_merge(['class' => 'form-control'], $htmlOptions));
    }

    /**
     * The result of the bootstrap3 checkbox
     * @param string $checkBoxName
     * @param array $contentOptions The content div's htmlOptions
     * @param array $labelOptions The label options of the checkbox
     * @param array $htmlOptions
     * @return string The bootstrap3 checkbox
     */
    public static function checkBox($checkBoxName = '', $contentOptions = [], $labelOptions = [], $htmlOptions = [])
    {
        return static::beginTag('div', array_merge(['class' => 'checkbox'], $contentOptions)) .
            static::beginTag('label', $labelOptions) .
            parent::checkBox($checkBoxName, $htmlOptions) .
            static::endTag('label') .
            static::endTag('div');
    }

    /**
     *  To generate the Bootstrap3's radio box
     * @param string $radioName
     * @param array $contentOptions The content div's htmlOptions
     * @param array $labelOptions The labelOptions
     * @param array $htmlOptions
     * @return string the bootstrap3 radio control
     */
    public static function radioInput($radioName = '', $contentOptions = [], $labelOptions = [], $htmlOptions = [])
    {
        return static::beginTag('div', array_merge(['class' => 'radio'], $contentOptions)) .
            static::beginTag('label', $labelOptions) .
            parent::radioInput($radioName, $htmlOptions) .
            static::endTag('label') .
            static::endTag('div');
    }

    /**
     * Generate the form's static text control
     * @param $text
     * @return string
     */
    public static function staticText($text = '')
    {
        return static::beginTag('p', ['class' => 'form-control-static']) . $text . static::endTag('p');
    }

    /**
     * Given the model object and attribute
     * Returning the attribute's validator collection
     * @param $model
     * @param $attribute
     * @param $controlName
     * @return array
     */
    public static function dealValidator($model, $attribute, $controlName)
    {
        $validators = [];
        $validatorCollection = Reflex::invoke($model, 'rules');
        if (!empty($validatorCollection)) {
            $smallTip = '';
            foreach ($validatorCollection as $validator) {
                if (in_array($attribute, $validator[0])) {
                    $validatorClass = 'Jframe\\validator\\' . ucfirst($validator[1]) . 'Validator';
                    $validatorInfo = Jframe::createObject($validatorClass)->getValidatorInfo($validator[1]);
                    $validatorLabels = Reflex::invoke($model, 'attributeLabels');
                    if (isset($validatorLabels[$attribute])) {
                        $attribute = $validatorLabels[$attribute];
                    }
                    if (isset($validator['message'])) {
                        $attribute = str_replace('{attribute}', $attribute, $validator['message']);
                    } else {
                        $attribute .= $validatorInfo[1];
                    }
                    $validators[$validatorInfo[0]] = ['message' => $attribute];
                    $smallTip .= static::beginTag('small', ['data-bv-validator' => $validatorInfo[0], 'data-bv-validator-for' => "{$controlName}",
                            'class' => 'help-block', 'style' => 'display:none']) . static::endTag('small');
                }
            }
        }
        return [$validators, $smallTip];
    }

}