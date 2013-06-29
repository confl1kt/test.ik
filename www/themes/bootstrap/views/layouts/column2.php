<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
    <div class="span4 ">
        <div id="sidebar">            
            <?php $this->renderPartial('/post/create', array('model' => new Post)); ?>
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
        </div>
    </div>
    <div class="span7 ">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>

</div>
<?php $this->endContent(); ?>