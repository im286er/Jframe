<?php

/**
 * This is the View of the Jframe
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 * @author Josin <774542602@qq.com | www.supjos.cn>
 */

namespace Jframe\base;

use Jframe;
use Jframe\exception\ViewFileNotFound;

class View extends Object
{

    /**
     * Rendering a php file into the web browser
     * @param string $viewFileName The template view file which you want to display
     * -"starts with '//a/b' means that the views file directory you wnat to define
     * -"starts with 'a ' means the view files is in the current controller' directory and the file
     * name 'a.php'
     * @param array $variables Some special PHP variables you want to use in the view file
     * @return string $result The result of the view engine rendering result
     */
    public function getViewFileResult($viewFileName, $variables)
    {
        $modulePath = Jframe::$app->modulePath;
        $commonPath = $modulePath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        $layoutFile = $commonPath . 'layouts' . DIRECTORY_SEPARATOR;
        if (substr($viewFileName, 0, 2) == '//') {
            // Analyse the directory
            $fileName = trim($viewFileName, '/');
            $fullFilePath = $this->reslash($commonPath . $fileName);
            $this->checkAndGet($fullFilePath, $layoutFile, $variables);
        } else {
            // Find the file and then check wheather the file is exists
            $contextController = Jframe::$app->pureController;
            $contextMethod = Jframe::$app->pureMethod;
            if (empty($viewFileName)) {
                $viewFileName = $contextMethod;
            }
            $fullFilePath = $this->reslash($commonPath . $contextController . DIRECTORY_SEPARATOR . $viewFileName);
            $this->checkAndGet($fullFilePath, $layoutFile, $variables);
        }
    }

    /**
     * Returns the result of the reslashed name
     * @param string $name
     * @return string
     */
    private function reslash($name)
    {
        return $name;
    }

    /**
     * @param string $file
     * @param string $layoutFile
     * @param array $variables
     */
    private function checkAndGet($file, $layoutFile, $variables)
    {
        // The layout directory
        $layout = $this->controller->layout;
        if ($layout === false) {
            // to render the file directly
            echo $this->getObClean($file, $variables);
        } elseif ($layout === null) {
            // To render the file with the default layout file named ['main']
            $content = $this->getObClean($file, $variables);
            echo $this->getObClean($layoutFile . 'main', ['content' => $content]);
        } else {
            // Render the file with the layou file named [[$layout]];
            $content = $this->getObClean($file, $variables);
            echo $this->getObClean($layoutFile . $layout, ['content' => $content]);
        }
    }

    /**
     * @param string $absoluteFile
     * @param array $variables
     * @return string The ob string in the cache
     * @throws ViewFileNotFound
     */
    private function getObClean($absoluteFile, array $variables)
    {
        $absoluteFile .= '.php';
        if (!file_exists($absoluteFile)) {
            throw new ViewFileNotFound("View file [{$absoluteFile}] Not found!", '104');
        }
        ob_start();
        ob_implicit_flush(FALSE);
        extract($variables);
        require($absoluteFile);
        return ob_get_clean();
    }

    /**
     * Return the current view id of the current MVC
     * @return string $viewId
     */
    public function getId()
    {
        return Jframe::$app->viewId;
    }

    /**
     * From the view to find the controller
     * @return \Jframe\base\Controller $controllerId
     */
    public function getController()
    {
        return Jframe::$app->context;
    }

    /**
     * After the body do the endBody job
     */
    public function endBody()
    {

    }

}
