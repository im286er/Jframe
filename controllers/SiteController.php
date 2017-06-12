<?php

/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace app\controllers;

use app\modules\b\models\UserModel;
use Jframe;
use Jframe\base\Controller;
use josin\curl\Curl;

class SiteController extends Controller
{

    public $enableCsrfFilter = true;

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
        $postData = Jframe::$app->request->post();
        $model = new UserModel();
        if ($model->load($postData)) {
            $model->setError('userName', 'Not NULL');
        }
        $this->render('index', ['model' => $model]);
    }

    public function actionSay($name)
    {
        $this->layout = false;
        $this->render('say');
    }

}
