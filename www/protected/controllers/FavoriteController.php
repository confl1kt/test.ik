<?php

class FavoriteController extends Controller{	
	public $layout='//layouts/column2';
	public function filters(){return array('accessControl',);}

	public function accessRules(){
		return array(
			array('allow',  
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', 
				'actions'=>array(),
				'users'=>array('@'),
			),
			array('allow', 
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  
				'users'=>array('*'),
			),
		);
	}
	
	public function actionView($id){
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionCreate(){
		$model=new Favorite;
		if(isset($_POST['Favorite'])){
			$model->attributes=$_POST['Favorite'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if(isset($_POST['Favorite'])){
			$model->attributes=$_POST['Favorite'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){			
			$this->loadModel($id)->delete();			
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('Favorite');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionAdmin(){
		$model=new Favorite('search');
		$model->unsetAttributes();  
		if(isset($_GET['Favorite']))
			$model->attributes=$_GET['Favorite'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
	public function loadModel($id){
		$model=Favorite::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='favorite-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
