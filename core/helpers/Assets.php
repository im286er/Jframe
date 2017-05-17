<?php

/**
 * This is the Assets of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\helpers;

class Assets
{

    public static function register()
    {
        $path = '/core/helpers/bootstrap/';
        if (!empty(static::$js)) {
            foreach (static::$js as $value) {
                echo Html::registerJsFile($path . $value);
            }
        }
        if (!empty(static::$css)) {
            foreach (static::$css as $value) {
                echo Html::registerCssFile($path . $value);
            }
        }
    }

}
