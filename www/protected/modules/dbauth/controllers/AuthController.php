<?php

class AuthController extends Controller
{
public $layout='//layouts/column2';
	public function filters(){return array('accessControl',);}

	public function accessRules(){
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','native'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'roles'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    public function actionIndex()
    {
        $this->redirect('index.php');
    }

    public function actionNative()
    {

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(array('/site/adminka')); //Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->redirect(array('/site/login'));
    }

    public function actionEAuthLogin()
    {
        $service = Yii::app()->request->getQuery('service');
        if (isset($service) && in_array($service, array('vkontakte','twitter','facebook','google')))
        {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('/site/login');
            
            $params = array();
            if ($authIdentity->authenticate())
            {
                $params['username'] =$authIdentity->getAttribute('name');
                
                if (User::smartServiceLogin($authIdentity->serviceName, $authIdentity->id, false, $params))
                {
                    if (Yii::app()->user->returnUrl)
                    {
                        $link = Yii::app()->user->returnUrl;
                        if (strpos($link, 'site/login') === false)
                            $this->redirect($link);
                        else
                            $this->redirect(array('/users/myprofile'));
                    }
                    else
                        $this->redirect(array('/users/myprofile'));
                    
                    $this->redirect(array('/users/myprofile'));
                    $authIdentity->redirect();
                }
                else
                {
                    $this->redirect(array('/site/index'));
                    $authIdentity->cancel();
                }
            }
            else
            {
                var_dump($authIdentity->getErrors());
            }
            // Something went wrong, redirect to login page
            $this->redirect(array('/site/login'));
        }
    }

    public function actionVKLogin()
    {
        //print_r($_REQUEST);
        if (User::externalServiceLogin($_REQUEST['uid'], 'vk.com', $_REQUEST))
            $this->redirect(Yii::app()->user->returnUrl);
        else
            $this->redirect(array('/site/login'));
    }

    /**
     * 
     */
    public function actionLoginza()
    {
        if (isset($_POST['token']))
        {
            $token = trim($_POST['token']);
            // НЕВОЗМОЖНО ПОКА СДЕЛАТЬ БОЛЕЕ БЕЗОПАСНУЮ АВТОРИЗАЦИЮ
            // УГРОЗА ПОДДЕЛКИ ТОКЕН
            $wid = 4883;
            $skey = '7565445f74f39bea682de5bed49333d4';
            $sig = md5($token + $skey);

            $auth = json_decode(file_get_contents("http://loginza.ru/api/authinfo?token=$token")); //&id=$wid&sig=$sig"));
            if (!property_exists($auth, 'error_type'))
            {
                $params = array();
                $params ['username'] = (property_exists($auth->name, 'full_name')) ? $auth->name->full_name : $auth->name->first_name . ' ' . $auth->name->last_name;
                if (property_exists($auth, 'photo'))
                {
                    $params ['photo'] = $auth->photo;
                }

                if (User::externalServiceLogin($auth->identity, false, $params))
                {
                    if ($_GET['returnlink'])
                    {
                        $link = base64_decode($_GET['returnlink']);
                        if (strpos($link, 'site/login') === false)
                            $this->redirect($link);
                        else
                            $this->redirect(array('/users/myprofile'));
                    }
                    else
                        $this->redirect(array('/users/myprofile'));
                }
                else
                {
                    throw new CHttpException('Внутренний сбой системы. Сообщите системному администратору', 500);
                }
            }
            else
            {
                throw new Exception('Невозможно авторизоваться через выбранный сервис: ' . $auth->error_message, 500);
            }
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}