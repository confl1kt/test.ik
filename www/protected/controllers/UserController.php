<?php

class UserController extends Controller {

    public $layout = '//layouts/user';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'register', 'follow', 'followers', 'posts', 'favorite', 'list'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('changePassword'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionChangePassword() {
        $model = $this->loadModel(yii::app()->user->id);        
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->checkPassword=$_POST['User']['checkPassword'];
            $model->newpassword=$_POST['User']['newpassword'];
            $model->newpassword2=$_POST['User']['newpassword2'];
            $model->setScenario('passwordChange');
            
            if ($model->save()){
                yii::app()->user->setFlash('success', 'Пароль успешно изменен.');
                $this->redirect('/');
            }
                
        }

        $this->render('changepassword', array(
            'model' => $model,
        ));
    }

    public function actionView($id) {
        if (!$model = yii::app()->cache->get($id)) {
            $model = User::model()
                    ->with('postsCount', 'followsCount', 'followersCount')
                    ->find('LOWER(username)=:username', array(':username' => strtolower($id)));
            $model->saveCache();
        }
        $posts = Post::model()->with('reposts.Refavorite', 'favorite')->findAll(array(
            'condition' => 't.author_id=:id',
            'params' => array(':id' => $model->id),
            'order' => 't.insert_date DESC'
                ));
        $posts = new CArrayDataProvider($posts);

        $this->render('view', array(
            'model' => $model,
            'posts' => $posts,
        ));
    }

    public function actionRegister() {
        $model = new RegisterForm('insert');
        $this->performAjaxValidation($model);
        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];
            if ($model->validate()) {
                yii::app()->user->setFlash('success', 'Регистрация успешно завершена.');
                
                $login=new LoginForm;
                $login->username=$model->username;
                $login->password=$model->password;
                $login->login();
                $this->redirect(array('/index.php'));
            } else {
                $this->render('register', array('model' => $model));
                return;
            }
        }

        $this->render('/', array('model' => $model));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionList() {
        $this->layout = '//layouts/userlist';
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->user = $_GET['User']['user'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionFollow($id) {
        if (!$model = yii::app()->cache->get($id)) {
            $model = User::model()
                    ->with('postsCount', 'followsCount', 'followersCount')
                    ->find('LOWER(username)=:username', array(':username' => strtolower($id)));
            $model->saveCache();
        }
        $follows = Follower::model()->findAll(array(
            'condition' => 't.author_id=:id',
            'params' => array(':id' => $model->id),
            'order' => 't.insert_date DESC'
                ));
        $follows = new CArrayDataProvider($follows);

        $this->render('follows', array(
            'model' => $model,
            'follows' => $follows,
            'action' => 'Follows'
        ));
    }

    public function actionFollowers($id) {
        if (!$model = yii::app()->cache->get($id)) {
            $model = User::model()
                    ->with('postsCount', 'followsCount', 'followersCount')
                    ->find('LOWER(username)=:username', array(':username' => strtolower($id)));
            $model->saveCache();
        }
        $followers = Follower::model()->findAll(array(
            'condition' => 't.user_id=:id',
            'params' => array(':id' => $model->id),
            'order' => 't.insert_date DESC'
                ));
        $followers = new CArrayDataProvider($followers);

        $this->render('follows', array(
            'model' => $model,
            'follows' => $followers,
            'action' => 'Followers'
        ));
    }

    public function actionFavorite($id) {
        if (!$model = yii::app()->cache->get($id)) {
            $model = User::model()
                    ->with('postsCount', 'followsCount', 'followersCount')
                    ->find('LOWER(username)=:username', array(':username' => strtolower($id)));
            yii::app()->cache->set($id, $model, 3600);
        }
        $favorites = Favorite::model()->with('post.reposts')->findAll(array(
            'condition' => 't.author_id=:id',
            'params' => array(':id' => $model->id),
            'order' => 't.insert_date DESC'
                ));
        $favorites = new CArrayDataProvider($favorites);

        $this->render('favorites', array(
            'model' => $model,
            'favorites' => $favorites,
        ));
    }

}
