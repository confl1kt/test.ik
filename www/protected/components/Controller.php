<?php

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {

    function mb_ucfirst($string) {
        $string = mb_ereg_replace("^[\ ]+", "", $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8") . mb_substr($string, 1, mb_strlen($string), "UTF-8");
        return $string;
    }

}

yii::import('zii.widgets.jui.*');

class _ZH extends CJuiWidget {

    protected function registerScriptFile($fileName, $position = CClientScript::POS_HEAD) {
        Yii::app()->getClientScript()->registerScriptFile($this->scriptUrl . '/' . $fileName, $position);
    }

}

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $title = '';
    public $menuTop = array();
    public $myMenu = array();
    public $myMenuProfile = array();
    public $currentMenu = '';
    public $description;

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * AJAX parameters
     */
    public $ajaxEnabled = true;
    public $ajaxExtended = true;

    public function init() {

        if (yii::app()->request->isAjaxRequest)
            CHtml::$count = 1000;

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('yiiactiveform');        
        /*$this->widget('application.extensions.fancybox.EFancyBox', array(
            'target' => 'a[class=fancy]',
            'config' => array(),
                )
        );*/
        $this->pageTitle = '';       
    }

    public function ajaxEndApp($output) {
        $this->layout = '//layouts/empty';
        if (is_array($output))
            $output = json_encode($output);
        Yii::app()->getClientScript()->renderBodyEnd($output);
        echo $output;
    }

    public function ajaxRender($view, $data) {
        $this->layout = '//layouts/empty';
        $output = $this->renderPartial($view, $data, true);
        Yii::app()->getClientScript()->renderBodyEnd($output);
        echo $output;
    }

    public function clearRender($view, $data) {
        $this->layout = '//layouts/main-clear';
        $this->render($view, $data);
    }

    public function smartRender($view, $data) {
        if (isset($_GET['iframe'])) {
            $this->clearRender($view, $data);
        } else
        if (Yii::app()->request->isAjaxRequest) {
            $this->ajaxRender($view, $data);
        }
        else
            $this->render($view, $data);
    }

    public static function formatSeconds($seconds, $options = 'dhis') {
        $days = floor($seconds / 86400);
        $seconds = $seconds - ($days * 86400);
        $hours = floor($seconds / 3600);
        $seconds = $seconds - ($hours * 3600);
        $minutes = floor($seconds / 60);
        $seconds = $seconds - ($minutes * 60);
        $r = array();
        if ($days > 0 && strpos($options, 'd') !== false)
            $r ['d'] = "$days д.";
        if ($hours > 0 && strpos($options, 'h') !== false)
            $r ['h'] = ($r != '' ? ' ' : '') . "$hours ч.";
        if ($minutes > 0 && strpos($options, 'i') !== false)
            $r ['m'] = ($r != '' ? ' ' : '') . "$minutes мин.";
        if ($seconds > 0 && strpos($options, 's') !== false)
            $r ['s'] = ($r != '' ? ' ' : '') . "$seconds сек.";
        return implode(' ', $r);
    }

    protected function performAjaxValidation(CModel $model, $ajaxVar = false) {
        if (isset($_POST['ajax']) && ($_POST['ajax'] == get_class($model) || $ajaxVar == $_POST['ajax'])) {
            $old = $model->getScenario();
            $model->setScenario('ajax-validate');
            echo CActiveForm::validate($model);
            $model->setScenario($old);
            Yii::app()->end();
        }
    }

    public function showAjaxMessage($message, $outcontent = '') {
        $this->showAjaxError($message, $outcontent);
    }

    public function showAjaxError($message, $outcontent = '') {
        if (Yii::app()->request->isAjaxRequest)
            $this->ajaxEndApp('<script>alert("' . $message . '");</script>');
        exit($outcontent);
    }

    public function redirect($url, $terminate = true, $statusCode = 302) {
        if (Yii::app()->request->isAjaxRequest) {
            if (is_array($url)) {
                $route = isset($url[0]) ? $url[0] : '';
                $url = $this->createUrl($route, array_splice($url, 1));
            }
            $this->ajaxEndApp('<script>location = "' . $url . '";</script>');
        } else {
            parent::redirect($url, $terminate, $statusCode);
        }
    }

    public static function formatDate($date, $dow = false) {
        return ($dow ? Controller::getDayOfWeek($date) : '') . ' ' . date('d', $date) . ' ' .
                Controller::getMonthName($date) . date(' Y ' . Yii::t('main', 'г.'), $date);
    }

    public static function getMonthName($date, $dic = 0, $diconly = false) {
        $dicDB = array(
            array('null', yii::t('main', 'января'), yii::t('main', 'февраля'), yii::t('main', 'марта'), yii::t('main', 'апреля'), yii::t('main', 'мая'), yii::t('main', 'июня'), yii::t('main', 'июля'), yii::t('main', 'августа'), yii::t('main', 'сентября'), yii::t('main', 'октября'), yii::t('main', 'ноября'), yii::t('main', 'декабря')),
            array('-месяц-', yii::t('main', 'Январь'), yii::t('main', 'Февраль'), yii::t('main', 'Март'), yii::t('main', 'Апрель'), yii::t('main', 'Май'), yii::t('main', 'Июнь'), yii::t('main', 'Июль'), yii::t('main', 'Август'), yii::t('main', 'Сентябрь'), yii::t('main', 'Октябрь'), yii::t('main', 'Ноябрь'), yii::t('main', 'Декабрь')),
        );
        $monthes = $dicDB[$dic];

        if ($diconly)
            return $monthes;

        return $monthes[intval(date('m', $date))];
    }

    public static function getDayOfWeek($date) {
        $days = array('null', 'Mon' => yii::t('main', 'понедельник'), 'Tue' => yii::t('main', 'вторник'), 'Wed' => yii::t('main', 'среду'), 'Thu' => yii::t('main', 'четверг'), 'Fri' => yii::t('main', 'пятницу'), 'Sat' => yii::t('main', 'субботу'), 'Sun' => yii::t('main', 'воскресенье'));

        return $days[(date('D', $date))];
    }

    public static function YearTextArg($date) {
        $year = abs(date('Y', $date));
        $t1 = $year % 10;
        $t2 = $year % 100;
        return date('Y', $date) . ' ' . ($t1 == 1 && $t2 != 11 ? yii::t('main', 'год') : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? yii::t('main', 'года') : yii::t('main', 'лет')));
    }

    public function getRWeekNumber($date) {
        $w = intval(date('w', $date));
        if ($w == 0)
            return 6;
        return $w - 1;
    }
   
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }    

    public function renderFlash($id) {
        if (Yii::app()->user->hasFlash($id))
            echo '
        <div class="flash-' . $id . '">
           ' . Yii::app()->user->getFlash($id) . '
        </div>';
    } 
}