<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
    <div class="span4">
        <div id="sidebar">
            <?php
            $this->beginWidget('zii.widgets.CPortlet', array(
            ));
            $this->widget('bootstrap.widgets.TbMenu', array(
                'items' => $this->menu,
                'type' => 'tabs',
                'stacked' => true,
                'dropup' => false,
                'htmlOptions' => array('class' => 'operations'),
            ));
            $this->endWidget();
            ?>
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