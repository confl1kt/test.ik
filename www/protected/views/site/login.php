    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('class' => 'well'),
            ));
    ?>
    <h1>Login</h1>
    <?php echo $form->textFieldRow($model, 'username'); ?>
    <?php echo $form->passwordFieldRow($model, 'password'); ?>
    <?php echo $form->checkBoxRow($model, 'rememberMe'); ?>

    <div class="row buttons">
    <?php echo CHtml::submitButton('Login'); ?>
    </div>

<?php $this->endWidget(); ?>

