<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * ```` The model class implement the model feature for the user to use the model
 * convenient
 *
 * ```` Remember that the Model class extends the Component class
 * That means that the model class can use the event
 *
 * ```` Each model means the data were from the code, not from the database-design
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\base;

use Jframe;

class Model extends Component
{
    /**
     * Event-constants for the Model
     */
    const EVENT_BEFORE_VALIDATE = 'beforeValidate';
    const EVENT_AFTER_VALIDATE = 'afterValidate';

    /**
     * Setting the errors of the property
     * @param string $property
     * @param string $errors
     */
    public function setError($property = '', $errors = '')
    {
        echo 'hehe';
        $this->errors[$property] = $errors;
    }

    /**
     * @var array The model errors
     */
    private $errors = [];

    /**
     * Getting the errors of the model
     * @param string $property
     * @return array
     */
    public function getErrors($property = '')
    {
        if (!empty($property)) {
            if (property_exists($this, $property) && isset($this->errors[$property])) {
                return $this->errors[$property];
            }
            return '';
        }
        return $this->errors;
    }

    /**
     * To load the value from the given data
     * ```` Notice:
     * ``` Every model will only accept the attribute value from the
     * outside.
     * @param array $attributeValueData
     * @param bool $modelLoad
     * @return bool true means the loading process success
     */
    public function load($attributeValueData = [], $modelLoad = true)
    {
        if (!empty($attributeValueData)) {
            if ($modelLoad) {
                $attributeValueData = $attributeValueData[Jframe::getOnlyClassName($this)];
            }
            foreach ($attributeValueData as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->$property = $value;
                } else {
                    $this->errors[$property] = "The model hasn't the property {$property} ";
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * The rules return the rules for the model to validate
     * Each rule was an array, such as :
     * return [
     *      [["name", "gender"], "required"],
     *      [["mobile"], "number"]
     * ];
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * The attributes label
     * `````
     * return [
     *      'userName'=>'用户姓名',
     * ];
     * @return array the attributeLabels
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Do the before action when validate the fields
     */
    public function beforeValidate()
    {
        $this->trigger(static::EVENT_BEFORE_VALIDATE);
    }

    /**
     * Do the after validate job when finished the validation
     */
    public function afterValidate()
    {
        $this->trigger(static::EVENT_AFTER_VALIDATE);
    }
}
