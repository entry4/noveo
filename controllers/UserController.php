<?php
/**
 * Created by PhpStorm.
 * User: entry
 * Date: 26.11.2016
 * Time: 15:19
 */

namespace app\controllers;
use yii\rest\ActiveController;

class UserController extends ActiveController{
    public $modelClass = 'app\models\User';
}