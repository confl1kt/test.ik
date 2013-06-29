<?php

class SiteController extends Controller {

    public $layout = '//layouts/column2';

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex() {
        $this->layout = '//layouts/column2';
        if (yii::app()->user->isGuest) {
            $this->layout = '//layouts/empty';
            $this->render('index');
            yii::app()->end();
        } else {
            $id = yii::app()->user->name;
            if (!$model = yii::app()->cache->get($id)) {
                $model = User::model()
                        ->with('postsCount', 'followsCount', 'followersCount')
                        ->find('LOWER(username)=:username', array(':username' => strtolower($id)));
                yii::app()->cache->set($id, $model, 3600);
            }
            $criteria = new CDbCriteria();
            $criteria->addInCondition('t.author_id', yii::app()->follows->getList(1));
            $criteria->order = 't.insert_date DESC';
            $posts = Post::model()->with('reposts.Refavorite', 'favorite')->findAll($criteria);
            $posts = new CArrayDataProvider($posts);

            $this->render('index2', array(
                'model' => $model,
                'posts' => $posts,
            ));
        }
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionLogin() {
        $this->layout='//layouts/empty';
        $model = new LoginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];            
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        
        $this->render('login', array('model' => $model));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}