<?php

/**
 * This is the Base Controller fo one of the derived controller setting of the Jframe
 * You has several choice to make your controller to finish the Job
 * But i recommend you to avoid it. and use the library class for you to do the same work.
 * Also if you have understood the meanning of the Jframe, Glad to me receving the code for your hand.
 * License : MIT
 * Copyright (c) 2017-2020 supjos.cn All Rights Reserved.
 */

namespace Jframe\base;

use Jframe;

class Controller extends Component
{

    public function __construct()
    {
        $this->on(self::EVENT_BEFORE_ACTION, function() {
            echo 'Before';
        });
    }

    const EVENT_BEFORE_ACTION = 'beforeAction';
    const EVENT_AFTER_ACTION = 'afterAction';

    /**
     * @var string|null|false The default layout file to render before other file
     * -'string'
     *      The layout file
     * - 'null'
     *      To render the default layout file named 'main'
     * - 'false'
     *      To deny the layout render Only to render the file
     */
    public $layout = 'main';

    /**
     * To let the Jframe to fileter some function with
     * Some method can access it or not
     * Example:
     * return [
     *    'access' => [
     *     ],
     *     'verbs'=>[
     *          'actions'=>[
     *              'index'=>['action]
     *          ]
     *     ]
     * ];
     */
    public function behaviors()
    {

    }

    /**
     * Before Event-Action
     */
    public function beforeAction()
    {
        $this->trigger(self::EVENT_BEFORE_ACTION);
        echo 'before';
    }

    /**
     * After Event-Action
     */
    public function afterAction()
    {
        $this->trigger(self::EVENT_AFTER_ACTION);
    }

    /**
     * Getting the view object to the Jframe
     * @return \Jframe\base\View $view
     */
    public function getView()
    {
        return Jframe::$app->view;
    }

    /**
     * To invoke the View's method to rendering the result
     * @param string $viewName The view file name
     * @param array $variables The view file variables
     * @return string HTML code
     */
    protected function render($viewName, array $variables = [])
    {
        $result = $this->getView()->getViewFileResult($viewName, $variables);
        return $result;
    }

}
