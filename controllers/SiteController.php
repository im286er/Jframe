<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Jframe;
use Jframe\base\Controller;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'actions' => [
                    'index' => ['post', 'GET']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return Jframe::$app;
        echo "<a href='site/say/name/Micosoft'>actionSay()</a>";
    }

    public function actionSay($name)
    {
        $this->layout = false;
        $this->render('say', []);
    }

}
