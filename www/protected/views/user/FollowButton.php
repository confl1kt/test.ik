<?php
$this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'ajaxLink',
                        'url' => $url,
                        'label' => $label,
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $id,),
                        'ajaxOptions' => array('method' => 'POST', 'update' => '.usr_profile .actions'),
                    ));
?>
