<?php

namespace josin\curl;

/**
 * <strong>Easy curl tool you can use for any PHPer do the cURL work</strong><br>
 * <p>eg.
 * <b>1</b>、<code>require 'jcurl.php'; </code><br>or if you use the composer with the
 * command "composer require josin/josincurl" it will auto include the library file
 * <b>2</b>、<code>use josin\curl\JosinCurl </code> as JosinCurl<br>
 * <b>3</b>、<code>$curl = new JosinCurl(); </code><br>
 * <b>4</b>、setting the url and method and data you want to send to the server<br>
 *    <code>$curl->setUrl("www.a.com"); </code>
 *    <code>$curl->setMethod(JosinCurl::CURL_POST); </code>
 *    <code>$curl->name = "Josin"; </code>
 *    <code>$curl->age  = 25; </code>
 *    <code>$curl->token = md5(time()); </code>
 * <b>5</b>、Get the data from the curl request<br>
 *    <code>$result = $curl->httpCurl(); </code>
 * @link http://www.supjos.cn
 * @author Josin <774542602@qq.com>
 */
class Curl
{

    /**
     * @var integer The timeout of the curl request
     */
    private $_timeOut = 30;

    /**
     * @param int $timeOut seting the default timeout of the curl request, The default timeout is 30s
     */
    public function setTimeOut($timeOut = 30)
    {
        $this->_timeOut = $timeOut;
    }

    private $_url = '';

    /**
     * @param type $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Method
     */
    const CURL_POST = 'POST';
    const CURL_GET = 'GET';
    const CURL_DELETE = 'DELETE';
    const CURL_PUT = 'PUT';

    /**
     * @var string The data format which you send to the server, below are the options you can choose:
     * - TYPE_RAW:
     *   Send the data without any convension
     * - TYPE_JSON:
     *   Send the data with the josn format
     * - TYPE_XML:
     *   Send the data with the xml format
     */
    private static $_format = self::TYPE_RAW;

    /**
     * @param type $format
     */
    public function setDataFormat($format = self::TYPE_JOSN)
    {
        if (in_array($format, [self::TYPE_JOSN, self::TYPE_XML])) {
            self::$_format = $format;
        }
    }

    /**
     * @var type
     */
    private $_method = 'POST';

    /**
     *
     * @param type $method
     */
    public function setMethod($method = self::CURL_POST)
    {
        if (in_array($method, [self::CURL_DELETE, self::CURL_GET, self::CURL_PUT, self::CURL_POST])) {
            $this->_method = $method;
        }
    }

    /**
     * The data you want to sent to the server
     */
    const TYPE_JOSN = 'json';
    const TYPE_XML = 'xml';
    const TYPE_RAW = 'raw';

    /**
     * @var array The header data
     */
    private $_head = [];

    /**
     * @var array The data you want to sent to the WebSERVER
     * eg. ['id'=>'1', 'order'=>'time']
     */
    private $_data = [];

    /**
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value)
    {
        if (!empty($name)) {
            $this->_data[$name] = $value;
        }
    }

    /**
     * The camelString turning situation function
     * @param string $camelStr
     * @param boolean $lowerCase
     */
    public static function getCamelId($camelStr = '', $lowerCase = false)
    {
        if (!empty($camelStr)) {
            $matches = [];
            \preg_match_all('/[A-Z][^A-Z]+/', $camelStr, $matches);
            if (!empty($matches[0])) {
                if ($lowerCase) {
                    $matches = \array_map(["self", 'arrayToLower'], $matches[0]);
                } else {
                    $matches = $matches[0];
                }
                return \implode('-', $matches);
            }
        }
    }

    /**
     * Return the lowercase of the given value
     * eg. String ===> string
     * @param string $value
     * @return string The lowercase value of the value
     */
    public static function arrayToLower($value)
    {
        return \strtolower($value);
    }

    /**
     * Setting the header
     * @param string $headName The head name must be Camel format , such as [["ContentType"]] mustn't be [["contenttype"]]
     * @param string $headValue The value must be IEEE standard format, below are some value example:
     * ACCEPT_TYPE_JOSN
     * ACCEPT_TYPE_XML
     * or other standard value for HTTP header
     */
    public function setHeader($headName, $headValue)
    {
        if (!empty($headName) && !empty($headValue)) {
            if ($headValue == self::TYPE_JOSN) {
                $headValue = 'application/json';
            } elseif ($headValue == self::TYPE_XML) {
                $headValue = 'application/xml';
            }
            $this->_head[] = self::getCamelId($headName) . ':' . $headValue;
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
     * Sending the PHP curl request to the destination webserver
     */
    public function httpCurl()
    {
        $ch = curl_init();
        if ($this->_method == self::CURL_GET) {
            $dataUrl = \http_build_query($this->_data);
            $this->_url .= '?' . $dataUrl;
        }
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_method);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_head);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeOut);
        if ($this->_method == self::CURL_POST) {
            if (self::$_format == self::TYPE_JOSN) {
                $this->_data = json_encode($this->_data);
            }
            if (self::$_format == self::TYPE_XML) {
                $this->_data = $this->arrToXml($this->_data);
            }
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result || !empty($result)) {
            return $result;
        }
        return false;
    }

}
