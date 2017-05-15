<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\b\controllers;

use Jframe;
use Jframe\base\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'=>[
                'actions'=>[
                    'index'=>['post', 'get']
                ]
            ]
        ];
    }

    public function actionIndex($ids=9, $age=22)
    {
        $this->layout = null;
        return $this->render('abc', ["name"=>"Josin", "domain"=>'www.supjos.cn']);
    }
    
    public function actionSay($name='Liming', $age = 18, $place)
    {
        return ['a', 'b', 'c'];
    }
    
    public function actionJson()
    {
        Jframe::$app->response->format = Jframe\base\Response::FORMAT_XML;
        return ['name'=>'Josin', 'domain'=>'www.supjos.cn', 'age'=>'24'];
    }
    
    public function version()
    {
        return '32.2';
    }
}