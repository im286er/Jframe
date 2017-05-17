<?php

/**
 * This is the Html helper of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\helpers;

use Jframe;

class Html
{

    /**
     * @var array The store stack
     */
    public static $stack = [];

    /**
     * @var array htmlOptions The HTML options which used to generate the HTML Tag
     */
    public static $htmlOptions = [];

    /**
     * Use this function tag() to generate the HTML Tag with some specified attributes
     * @param string $name Tag name
     * @param string $tagValue Tag Value
     * @param array $htmlOptions Tag's html attributes
     */
    public static function tag($name, $tagHtml, array $htmlOptions = [])
    {
        return "<{$name} " . static::renderTagAttributes($htmlOptions) . '>' . "$tagHtml</{$name}>";
    }

    /**
     * @param type $name
     * @param array $htmlOptions
     * @return string Html begin tag
     */
    public static function beginTag($name, array $htmlOptions = [])
    {
        return "<$name " . static::renderTagAttributes($htmlOptions) . '>';
    }

    /**
     * @param type $name
     * @param array $htmlOptions
     * @return string
     */
    public static function endTag($name)
    {
        return "</$name>";
    }

    /**
     * Render the HTML Tag attributes
     * eg. ['class'=>'form-control', 'value'=>'hello'] will generate blow:
     * 'class'='form-control' 'vlaue'='hello'
     * @param array $htmlOptions
     */
    protected static function renderTagAttributes(array $htmlOptions = [])
    {
        if (is_array($htmlOptions) && !empty($htmlOptions)) {
            $tagAttr = '';
            foreach ($htmlOptions as $key => $value) {
                $tagAttr .= "{$key}='{$value}'" . " ";
            }
            return $tagAttr;
        }
    }

    /**
     * @param array $htmlOptions
     * @return \Jframe\helpers\Html
     */
    public static function beginForm(array $htmlOptions = [])
    {
        return '<form ' . static::renderTagAttributes($htmlOptions) . '>';
    }

    /**
     * @throws \Jframe\exception\HtmlTagNotMatch
     */
    public static function endForm()
    {
        return '</form>';
    }
    
    /**
     * @param type $name
     * @param array $htmlOptions
     */
    public static function textInput(array $htmlOptions = [], $name = 'input')
    {
        return static::beginTag($name, $htmlOptions);
    }
    
    /**
     * @param type $name
     * @param type $value
     * @param array $htmlOptions
     * @return string
     */
    public static function button($value, array $htmlOptions = [], $name='button')
    {
        return static::beginTag($name, array($htmlOptions, ['value'=>$value]));
    }
    /**
     * @param type $name
     * @param type $value
     * @param array $htmlOptions
     * @return string
     */
    public static function submitButton($value, array $htmlOptions = [], $name='button')
    {
        return static::beginTag($name, array_merge($htmlOptions, ['type'=>'submit'])) . $value . static::endTag($name);
    }
    
    /**
     * @param array $htmlOptions
     * @param type $name
     * @return type
     */
    public static function selectInput(array $options = [], array $htmlOptions = [], $name='select')
    {
        $begin = static::beginTag($name, $htmlOptions);
        $op = '';
        foreach($options as $k=>$v){
            $op .= '<option value="' . $k . '">' . $v . '</option>';
        }
        return $begin . $op . static::endTag($name);
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
