<?php

class FollowerController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array('accessControl',);
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('follow', 'unfollow'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionFollow($id) {
        if (!yii::app()->user->isGuest) {
            $model = new Follower;
            $model->user_id = $id;
            $model->author_id = yii::app()->user->id;
            $model->insert_date = time();
            User::editCache($model->user_id, 'followersCount', 1);
            User::editCache($model->author_id, 'followsCount', 1);
            if ($model->save()) {
                yii::app()->follows->add($model->user_id, $model->id);
                $this->redirect(array('/' . yii::app()->users->get($model->user_id), array()));
            }
        }
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionUnfollow($id) {
        if ($id = yii::app()->follows->is($id)) {
            $model = $this->loadModel($id);
            if (!yii::app()->user->isGuest && $model->author_id == yii::app()->user->id) {
                $model->delete();
                User::editCache($model->user_id, 'followersCount', -1);
                User::editCache($model->author_id, 'followsCount', -1);
                yii::app()->follows->remove($model->user_id);
                $this->redirect(array('/' . yii::app()->users->get($model->user_id), array()));
            }
        }
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id) {
        $model = Follower::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'follower-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
