<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * ````
 * The Validator class helpes the Jframe to generate the universal bootstrap validator js file online
 *
 * ````
 *  Each form will accept the js validator to validator the user-input
 *      If you not the feature you can use the Html class not the BootstrapHtml
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace Jframe\helpers;

class Validator
{
    /**
     * To generate the real js file when the current form generate
     * @param $formId
     * @param $fields
     * @return string
     */
    public static function generateValidateJs($formId, $fields)
    {
        $path = ROOT . '/web/bootstrap/Validator/js';
        ob_start();
        ob_implicit_flush(FALSE);
        extract(['formId'=>$formId, 'fields'=>json_encode($fields)]);
        require($path . '/validateJs.php');
        $jsFile = ob_get_clean();
        echo '<script>'.$jsFile.'</script>';
    }
}
