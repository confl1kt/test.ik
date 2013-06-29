<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
    <div class="span4">
        <div id="sidebar">
            <?php
            Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
            ?>
            <div class="well">
                <p></p>
                <div class="search-form">
                    <?php
                    $this->renderPartial('_search', array(
                        'model' => new User,
                    ));
                    ?>
                </div><!-- search-form -->
            </div>
            <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true, 
                'closeText' => '&times;', 
                'alerts' => array(
                    'success' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), 
                    'warning' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), 
                    'info' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), 
                    'error' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), 
                ),
            ));
            ?>
        </div><!-- sidebar -->
    </div>
    <div class="span7 ">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>

</div>
<?php $this->endContent(); ?>