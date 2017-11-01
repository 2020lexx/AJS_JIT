<?php

namespace common\controllers;
use Yii;
use yii\web\Controller;

class TestproController extends Controller
{
	
	public function actionHitest(){
		echo "->".$this->id."<-";

		return "hello world test";

	}
}