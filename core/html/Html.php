<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\html;

use Jframe\exception\InvalidValueException;

class Html
{
    /**
     * Generate the HTML [[input]] tag
     * @param array $htmlOptions The Html Options of the given html tag
     * @return string The result of the HTML input tag
     */
    public static function textInput($htmlOptions = [])
    {
        return static::beginTag('input', array_merge(['type' => 'text'], $htmlOptions));
    }

    /**
     * Generate a tag with the given tag attributes
     * @param string $tagName The Tag which want to generate, can be everything, such as [[div]]、[[a]].etc..
     * @param array $tagAttributes , The tag attributes, almost the HTML attributes and owner attributes, like data-xxx.
     * @return string The result HTML string
     * @throws InvalidValueException
     */
    public static function beginTag($tagName, $tagAttributes = [])
    {
        if (!empty($tagName)) {
            return "<{$tagName}" . static::renderTagAttributes($tagAttributes) . '>';
        } else {
            throw new InvalidValueException("$tagName Cannot be empty!", 210);
        }
    }

    /**
     * According to the given attributes to generate the attributes of the HTML
     * @param array $attributes The HTML attributes
     * @return string The result string
     */
    public static function renderTagAttributes($attributes = [])
    {
        $attrString = '';
        if (!empty($attributes)) {
            foreach ($attributes as $property => $value) {
                $attrString .= " {$property}='{$value}'";
            }
        }
        return $attrString;
    }

    /**
     * Generate the HTML select tag result
     * @param array $selectOptions The select options
     * @param array $htmlOptions The html select option value set
     * @return string The result of the select tag result
     */
    public static function selectInput($selectOptions = [], $htmlOptions = [])
    {
        return static::beginTag('select', $htmlOptions) . static::renderSelectOptions($selectOptions, $htmlOptions) . static::endTag('select');
    }

    /**
     * According the selectOptions to generate the SELECT options
     * @param array $selectOptions
     * @param array $htmlOptions
     * @return string The result of the select options
     */
    public static function renderSelectOptions($selectOptions = [], $htmlOptions = [])
    {
        $optionString = '';
        if (!empty($selectOptions)) {
            foreach ($selectOptions as $value => $name) {
                $optionOptions = ['value' => $value];
                if (isset($htmlOptions['value'])) {
                    if ($htmlOptions['value'] !== '' && $value !== '') {
                        if ($htmlOptions['value'] == $value) {
                            $optionOptions['selected'] = 'selected';
                        }
                    }
                }
                $optionString .= static::beginTag('option', $optionOptions) . $name . static::endTag('option');
            }
        }
        return $optionString;
    }

    /**
     * Generate the end tag of a pair of the HTML element
     * @param $tagName The close html tag
     * @return string the result string
     * @throws InvalidValueException
     */
    public static function endTag($tagName)
    {
        if (!empty($tagName)) {
            return "</{$tagName}>";
        } else {
            throw new InvalidValueException("$tagName Cannot be empty!", 210);
        }
    }

    /**
     * To generate the input file control
     * @param array $htmlOptions file input control htmlOptions
     * @return string The file input tag control
     */
    public static function fileInput($htmlOptions = [])
    {
        return static::beginTag('input', array_merge(['type' => 'file'], $htmlOptions));
    }

    /**
     * According to the user-given htmlOptions to generate the checkBox tag control
     * @param string $checkBoxName The checkbox name to show to user
     * @param array $htmlOptions The checkbox htmlOptions
     * @return string The result checkBox's string
     */
    public static function checkBox($checkBoxName = '', $htmlOptions = [])
    {
        return static::beginTag('input', array_merge(['type' => 'checkbox'], $htmlOptions)) . $checkBoxName;
    }

    /**
     * According to the user-given htmlOptions to generate the radio tag control
     * @param string $radioName The radio name to show to user
     * @param array $htmlOptions The radio htmlOptions
     * @return string The result checkBox's string
     */
    public static function radioInput($radioName = '', $htmlOptions = [])
    {
        return static::beginTag('input', array_merge(['type' => 'radio'], $htmlOptions)) . $radioName;
    }

    /**
     * Generate the submit button tag control
     * @param string $buttonName Button name show to user
     * @param array $htmlOptions The Submit button options
     * @return string The result of the submit button string
     */
    public static function submitButton($buttonName = 'Submit', $htmlOptions = [])
    {
        return static::beginTag('button', array_merge(['type' => 'submit', 'class' => 'form-control'], $htmlOptions)) . $buttonName . static::endTag('button');
    }

    /**
     * Generate the textarea html5 tag control according to the textarea htmlOptions
     * @param array $htmlOptions The html options of the textarea control
     * @return string The result of the rendering
     */
    public static function textAreaInput($htmlOptions = [])
    {
        return static::beginTag('textarea', array_merge(['rows' => 3], $htmlOptions)) . static::endTag('textarea');
    }

    /**
     * @param string $file
     * @return string
     */
    public static function registerJsFile($file)
    {
        $pathRoot = dirname(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')));
        $filePath = $pathRoot . $file;
        return "<script src='{$filePath}' type='text/javascript'></script>";
    }

    /**
     * @param string $file
     * @return string
     */
    public static function registerCssFile($file)
    {
        $pathRoot = dirname(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')));
        $filePath = $pathRoot . $file;
        return "<link href='{$filePath}' rel='stylesheet'>";
    }

}