<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\html\bootstrap;

use Jframe;
use Jframe\exception\InvalidParameterException;
use Jframe\helpers\Validator;
use Jframe\tool\Inflector;

class ActiveForm extends Jframe\base\Object
{
    /**
     * The ActiveForm number
     * @var int
     */
    private $index = 0;
    /**
     * The formName of the ActiveForm
     * It was the form's id
     * @var string
     */
    private $formName = '';
    /**
     * @var object The model object which you want to fetch value from
     */
    private $model;
    /**
     * @var string The attribute which the value from the model's attribute
     */
    private $attribute;
    /**
     * The rendering layout associated with the result
     *
     * ```` If you want to modify the style of the result
     * Try to modify it, as the example is below:
     *
     * [[Notice]]: The ```{label}``` ```{control}``` ```{error}``` will replace into the result html string
     * $layput = '<div class="form-group>"{label}<div class="form-group">{control}{error}</div></div>'';
     *
     * @var string
     */
    private $layout = '{label}{control}{error}';

    /**
     * Not use it manually
     * @var array $fields
     */
    private $fields = [];

    /**
     * This is the formOptions to rendering the form tag
     * @var
     */
    private $formOptions = ['method' => 'POST'];

    /**
     * ActiveForm constructor.
     */
    public function __construct()
    {
        $this->formName = 'jform' . $this->getIndex();
    }

    /**
     * Return the index of the current form, and add one for the next form
     * @return int
     */
    private function getIndex()
    {
        return $this->index++;
    }

    /**
     * Using the given options to initialise the ActiveForm Object
     * ``` The options will only accept the properties the ActiveForm had
     *
     * ```` for example:
     * $widget = ActiveForm::begin(
     *      [
     *          'layout'  => '<div class="form-group>"{label}<div class="form-group">{control}{error}</div></div>',
     *          'formName'=> 'jform#1', // This property can omit, to let the ActiveForm generate automatically
     *      ]
     * );
     *
     * @param array $options
     * @return ActiveForm The ActiveForm's object
     */
    public static function begin($options = [])
    {
        $formObject = Jframe::createObject(self::className(), $options);
        echo Html::beginTag('form', array_merge(['id' => $formObject->getFormName()], $formObject->formOptions));
        return $formObject;
    }

    /**
     * Setting the model's attribute to show the value of the given attribue
     * @param null $model
     * @param string $attribute
     * @return $this The ActiveForm
     * @throws InvalidParameterException
     */
    public function field($model = null, $attribute = '')
    {
        if (!is_null($model) && !empty($attribute)) {
            $this->model = $model;
            $this->attribute = $attribute;
        } else {
            throw new InvalidParameterException('$model & $attribute must be set!', 221);
        }
        return $this;
    }

    /**
     * According to the given model and htmlOption to generate the textInput control
     * @param array $htmlOptions
     * @return mixed
     */
    public function textInput($htmlOptions = [])
    {
        /**
         * Judge whether the name or id attribute is set, if not use the default one
         */
        if (isset($htmlOptions['id'])) {
            $for = $htmlOptions['id'];
        } else {
            $for = $htmlOptions['id'] = 'jtext' . static::getIndex();
        }

        if (property_exists($this->model, $this->attribute)) {
            $htmlOptions['value'] = $this->model->{$this->attribute};
        }

        $htmlOptions['name'] = $htmlOptions['data-bv-field'] = Jframe::getOnlyClassName($this->model) . "[{$this->attribute}]";

        /*
         * To deal the validator for the form
         */
        $this->fields[$htmlOptions['name']]['validators'] = Html::dealValidator($this->model, $this->attribute, $htmlOptions['name'])[0];

        /**
         * Rendering the result of the given html value
         */
        $label = Html::beginTag('label', ['class' => 'control-label', 'for' => $for]) . static::getInflectorName($this->model, $this->attribute) . Html::endTag('label');
        $control = Html::textInput($htmlOptions);
        //$error = Html::beginTag('span', ['class' => 'help-block']) . $this->model->getErrors($this->attribute) . Html::endTag('span');
        $error = Html::dealValidator($this->model, $this->attribute, $htmlOptions['name'])[1];
        return Html::beginTag('div', ['class' => 'form-group']) .
            str_replace(['{label}', '{control}', '{error}'], [$label, $control, $error], $this->layout) .
            Html::endTag('div');
    }

    /**
     * @param $model
     * @param $attribute
     * @return string The result label name from the change
     */
    private function getInflectorName($model, $attribute)
    {
        $labels = $model->attributeLabels();
        if (!empty($labels) && isset($labels[$attribute])) {
            return $labels[$attribute];
        } else {
            return Inflector::camel2Id($this->attribute);
        }
    }

    /**
     * Generate the Select HTML control for the H5 create
     * @param array $options
     * @param array $htmlOptions
     * @return string the result of the string
     */
    public function selectInput($options = [], $htmlOptions = [])
    {
        /**
         * Judge whether the name or id attribute is set, if not use the default one
         */
        if (isset($htmlOptions['id'])) {
            $for = $htmlOptions['id'];
        } else {
            $for = $htmlOptions['id'] = 'jselect' . static::getIndex();
        }

        if (property_exists($this->model, $this->attribute)) {
            $htmlOptions['value'] = $this->model->{$this->attribute};
        }

        $htmlOptions['name'] = Jframe::getOnlyClassName($this->model) . "[{$this->attribute}]";

        /*
         * To deal the validator for the form
         */
        $this->fields[$htmlOptions['name']]['validators'] = Html::dealValidator($this->model, $this->attribute, $htmlOptions['name'])[0];

        /**
         * Rendering the result of the given html value
         */
        $label = Html::beginTag('label', ['class' => 'control-label', 'for' => $for]) . static::getInflectorName($this->model, $this->attribute) . Html::endTag('label');
        $control = Html::selectInput($options, $htmlOptions);
        $error = Html::beginTag('span', ['class' => 'help-block']) . $this->model->getErrors($this->attribute) . Html::endTag('span');
        return Html::beginTag('div', ['class' => 'form-group']) .
            str_replace(['{label}', '{control}', '{error}'], [$label, $control, $error], $this->layout) .
            Html::endTag('div');
    }

    /**
     * End the form
     * @return string
     */
    public function end()
    {
        Validator::generateValidateJs('#' . $this->getFormName(), $this->fields);
        return Html::endTag('form');
    }

    /**
     * Get the current form's id, if you need for further use
     * @return string
     */
    public function getFormName()
    {
        return $this->formName;
    }
}