<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\b\controllers;

use Jframe;
use Jframe\base\Controller;
use Jframe\base\Response;
use Jframe\behavior\AccessFilter;

class SiteController extends Controller
{

    /*
     * behaviors动作解析：
     * return [
     *       'verbs' => [// verbs是动作过滤器
     *           'actions' => [
     *               'index' => ['post', 'get'] // 这个表示actionIndex方法只能是post或者get方法访问，其余访问方式均会被阻止
     *           ]
     *       ],
     *       'access' => [// access是访问控制器
     *           'class' => AccessFilter::className(), // 访问控制器的操作类
     *           'only' => ['index', 'say'], // 访问控制器控制的方法为actionIndex与actionSay方法，其余方法均会被忽略
     *           'rules' => [// 控制规则,每条规则是一个数组，里面可以包含allow、roles、verbs三种操作.
     *                   [
     *                   'allow' => true, // 这条规则表示？，未登录的用户可以访问allow=true
     *                   'roles' => '?',
     *               //'verbs' => ['get']
     *               ],
     *                   [
     *                   'allow' => true, // allow为true表示允许，false表示阻止
     *                   'verbs' => ['get'] // 表示get操作访问上方设置的actionIndex、actionSay方法时GET操作会被允许
     *               ]
     *           ]
     *       ]
     *   ];
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'actions' => [
                    'index' => ['post', 'get']
                ]
            ],
            'access' => [
                'class' => AccessFilter::className(),
                'only' => ['index', 'say'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => '?',
                    //'verbs' => ['get']
                    ],
                    [
                        'allow' => true,
                        'verbs' => ['get']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex($ids = 9, $age = 22)
    {
        $this->layout = 'import';
        return $this->render('//test/abc', ["name" => "Josin", "domain" => 'www.supjos.cn']);
    }

    public function actionSay($name, $age = 18, $place = "hel")
    {
        $this->layout = 'import';
        return $this->render('abc', ['name' => 'supjos', 'domain' => 'supjos.cn']);
    }

    public function actionJson()
    {
        Jframe::$app->response->format = Response::FORMAT_JSON;
        return ['name' => 'Josin', 'domain' => 'www.supjos.cn', 'age' => '24'];
    }

    public function actionXml()
    {
        Jframe::$app->response->format = Response::FORMAT_XML;
        return ['name' => 'Josin', 'domain' => 'www.supjos.cn', 'age' => '24'];
    }

    public function version()
    {
        return '32.2';
    }

}
