<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
	
	public function actionIndex(){
		return "hello world";
	}
}