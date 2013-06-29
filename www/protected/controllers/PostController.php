<?php

class PostController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array('accessControl',);
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('view'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('create', 'addfavorite', 'removefavorite','repost','delete'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new Post;
        $this->performAjaxValidation($model);
        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            $model->insert_date=time();
            $model->author_id=yii::app()->user->id;            
            if ($model->save()){
                yii::app()->user->setFlash('success', 'Пост успешно добавлен');
                User::editCache(yii::app()->user->id,'postsCount',1);
                $this->redirect(Yii::app()->homeUrl);      
            }
                      
        }
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionRepost($id) {
        $model = new Post;
        $model->repost_id = $id;
        $model->author_id=yii::app()->user->id;
        $model->insert_date=time();
        if ($model->save()) {
            yii::app()->user->setFlash('success', 'Пост успешно добавлен');
            User::editCache(yii::app()->user->id,'postsCount',1);
            echo 'Добавлено';
            yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAddFavorite($id) {
        $model = new Favorite;
        $model->post_id = $id;
        $model->insert_date = time();
        $model->author_id = yii::app()->user->id;
        if ($model->save()) {
            echo 'Добавлено в избраное';
            yii::app()->favorites->add($model->id,$id);
            yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionRemoveFavorite($id) {
        $model = Favorite::model()->findByPk($id);
        if ($model->author_id == yii::app()->user->id)
            $model->delete();
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        if (!isset($_GET['ajax'])) {
            echo 'Удалено из избраного';
            yii::app()->favorites->remove($model->post_id);
            yii::app()->end();
        }
    }

    public function actionDelete($id) {
        $model=$this->loadModel($id);
        if($model->author_id==yii::app()->user->id) {
            $model->delete();
            User::editCache(yii::app()->user->id,'postsCount',-1);
            echo 'Удалено';
            yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }    

    public function loadModel($id) {
        $model = Post::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'post-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
