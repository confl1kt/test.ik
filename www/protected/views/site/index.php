<?php
$this->pageTitle = Yii::app()->name;
?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
    <div class="span6">
        <div id="content">
            <?php
            $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
                'heading' => 'Welcome to ' . CHtml::encode(Yii::app()->name),
                'headingOptions'=>array('style'=>'font-size:24px;'),
            ));
            ?>           
                Text
            <?php $this->endWidget(); ?>
        </div><!-- content -->
        
    </div>
    <div class="span6 ">
        <div id="sidebar">
            <?php
            $this->renderPartial('login', array('model' => new LoginForm));
            $this->renderPartial('/user/register', array('model' => new RegisterForm));
            ?>
        </div><!-- sidebar -->
    </div>

</div>
<?php $this->endContent(); ?>