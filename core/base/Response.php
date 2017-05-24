<?php

/**
 * This is the Base class of the response from the web server
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\base;

class Response extends Object
{

    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const FORMAT_RAW = 'raw';

    /**
     * If you change the result of the $format
     * You can change if below:
     * - 'json' 
     *      : The json will be returned to the web user or interface
     * - 'xml'
     *      : The xml data will be output to the web user or interface invoking
     * - 'raw
     *      : Output the data without any conversion
     * @var string $format The default format of the output result 
     */
    public $format = Response::FORMAT_RAW;

    /**
     *
     * @var string $version The version of the Response
     */
    private $version = '';

    /**
     * @var array $data The data which will do the conversion 
     */
    public $data = [];

    /**
     * Construct the response object for the Response
     * @param type $data The data which will be convert
     * @param type $format The data will be apply
     * @return string|xml|mixed The data which you want
     */
    public function __construct($data = [], $format = null)
    {
        if (!empty($data)) {
            $this->data = $data;
        }
        if ($format !== null) {
            $this->format = $format;
        }
        if ($this->version === null) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->version = '1.0';
            } else {
                $this->version = '1.1';
            }
        }
    }

    /**
     * @return mixed $data The output data for the user with the corresponding format
     */
    public function formatOut()
    {
        if (strcmp($this->format, Response::FORMAT_RAW) == 0) {
            // Not that the array can not be outputed by echo
            if (is_array($this->data) || is_object($this->data)) {
                echo '<pre>';
                print_r($this->data);
                echo '</pre>';
            } else {
                echo $this->data;
            }
        } elseif (strcmp($this->format, Response::FORMAT_JSON) == 0) {
            header('Content-Type:application/json;charset=UTF-8');
            echo json_encode($this->data);
        } elseif (strcmp($this->format, Response::FORMAT_XML) == 0) {
            header('Content-Type:application/xml;charset=UTF-8');
            echo $this->arrToXml($this->data, '', true);
        }
    }

    /**
     * Change the array data into xml format
     * @param type $data The data 
     * @param type $head The head tag
     * @param type $simplexml Generate the simple xml, recommend simple-xml
     * @return string
     */
    private function arrToXml($data, $head = '', $simplexml = false)
    {
        $str = $head;
        if ($head !== null) {
            if ($simplexml) {
                $str = '<xml><response>';
            } else {
                $str = '<?xml version="1.0" encoding="UTF-8"?><response>';
            }
        }
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $child = $this->arrToXml($val, null);
                $str .= "<{$key}>" . $child . "</{$key}>";
            } else {
                if (is_numeric($val)) {
                    if (is_numeric($key)) {
                        $str .= "<item>{$val}</item>";
                    } else {
                        $str .= "<{$key}>{$val}</{$key}>";
                    }
                } else {
                    if (is_numeric($key)) {
                        $str .= "<item><![CDATA[{$val}]]></item>";
                    } else {
                        $str .= "<{$key}><![CDATA[{$val}]]></{$key}>";
                    }
                }
            }
        }
        $str .= "</response>";
        if ($simplexml) {
            $str .= '</xml>';
        }
        return $str;
    }

    /**
     *  Return the version of the Jframe response
     * @return string $version
     */
    public function getVersion()
    {
        return $this->version;
    }

}
