<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(               
                array('label'=>'Главная', 'url'=>yii::app()->homeUrl),
                //array('label'=>'Обновления','url'=>array('user/news'),'visible'=>!yii::app()->user->isGuest),
                array('label'=>'Пользователи','url'=>array('/user/list')),
                array('label'=>'Я','url'=>array('/'.yii::app()->user->name),'visible'=>!yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                
            ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Пользователь','items'=>array(
                    array('label'=>'Сменить пароль','url'=>array('user/changepassword'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>'Registration','url'=>array('user/register'),'visible'=>Yii::app()->user->isGuest),
                )),
            ),
        ),
    ),
)); ?>

<div class="container-fluid" id="page">
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer" class="row">
		Copyright &copy; <?php echo date('Y'); ?> by stl.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
<style>
    #page{
       width:1024px;
        margin:0 auto;
    }
</style>