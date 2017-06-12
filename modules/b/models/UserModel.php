<?php
/**
 * Welcome everyone to given some advices to improve the Jframe PHP Framework
 * Copyright (c) 2017.-2020 Jframe www.supjos.cn All Rights Reserved.
 * Author : Josin
 * Email  : 774542602@qq.com
 */

namespace app\modules\b\models;

use Jframe\base\Model;

class UserModel extends Model
{
    public $userName;
    public $userAge;
    public $mobile;
    public $gender;
    public $address;
    public $email;

    /**
     * The validate rules
     * @return array
     */
    public function rules()
    {
        return [
            [['userName', 'mobile', 'userAge', 'gender'], 'required', 'message'=>'{attribute}不能不填!'],
            [['email'], 'email', 'message' => '邮箱非法!'],
            [['address'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'userName'=>'用户姓名',
            'userAge'=>'用户年龄',
            'mobile'=>'手机号码',
            'gender'=>'性别',
            'address'=>'地址',
            'email'=>'邮箱'
        ];
    }
}