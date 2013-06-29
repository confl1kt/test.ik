<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>



<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
        'type'=>'horizontal',
        'action'=>'login',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
        'htmlOptions'=>array('class'=>'well'),
)); ?>
    <legend>Login</legend>

	<?php echo $form->textFieldRow($model,'username'); ?>
	<?php echo $form->passwordFieldRow($model,'password',array()); ?>
	<?php echo $form->checkBoxRow($model,'rememberMe'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>'Login',
        )); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
