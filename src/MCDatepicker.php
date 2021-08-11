<?php
/**
*
*
*
 */
namespace saddinamo\mcdatepicker;

use saddinamo\mcdatepicker\McdatepickerAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii;

/**
 * mcdatepicker widget renders input for date
 *
 */
class Mcdatepicker extends Widget
{
    /**
     * @var string ID tag of target input field
     */
    public $id = '';

    /**
     * @var string Name to be used with Pjax
     */
    public $name = '';

    /**
     * @var string Class string for input tag
     */
    public $class = '';

    public $size = '';

    public $required = '';

    public $placeholder = '';

    /**
     * @var array Options for input field
     * @see https://mcdatepicker.netlify.app/docs/configuration/
     * 
     * Option               Type	    Default	        Description
     * ---------------------------------------------------------------------------------------------
     * dateFormat	        String	    DD-MMM-YYYY	    Sets the format of the returned date.
     * el	                String	    null	        The ID of the instance's linked element.
     * autoClose	        Boolean	    false	        Closes the calendar when a date is selected.
     * closeOndblclick	    Boolean	    true	        Closes the calendar on double click.
     * closeOnBlur	        Boolean	    false	        Closes the calendar when it loses focus.
     * showCalendarDisplay	Boolean	    true	        Shows or hides the calendar display.
     * customWeekDays	    Array	    EN Weekdays	    Sets custom calendar weekdays.
     * customMonths	        Array	    EN Month names	Sets custom calendar months name.
     * customOkBTN	        String	    OK	            Sets custom OK button label.
     * customClearBTN	    String	    Clear	        Sets custom Clear button label.
     * customCancelBTN	    String	    CANCEL	        Sets custom CANCEL button label.
     * firstWeekday	        Number	    0	            Sets first weekday of the calendar.
     * selectedDate	        Date	    null	        Sets the default picked date.
     * minDate	            Date	    null	        Sets the min selectable date.
     * maxDate	            Date	    null	        Sets the max selectable date.
     * jumpToMinMax	        Boolean	    true	        Jumps to min | max dates using year arrows
     * jumpOverDisabled	    Boolean	    true	        Jumps over the disabled months and years.
     * disableWeekends	    Boolean	    false	        Disables weekends.
     * disableWeekDays	    Array	    []	            Disables specific days of the week.
     * disableDates	        Array	    []	            Disables specific dates.
     * disableMonths	    Array	    []	            Disables specific months.
     * disableYears	        Array	    []	            Disables specific years.
     * allowedMonths	    Array	    []	            Allows specific months only.
     * allowedYears	        Array	    []	            Allows specific years only.
     * markDates	        Array	    []	            Mark specific dates.
     * bodyType	            String	    modal	        Sets the calendar mode.
     */
    public $mcoptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->id)) {
            $this->id = $this->getId();
        }
        $this->mcoptions = ArrayHelper::merge(
            [
                'exporting' => [
                    'enabled' => true
                ]
            ],
            $this->mcoptions
        );
        
        if (ArrayHelper::getValue($this->clientOptions, 'exporting.enabled')) {
            $this->modules[] = 'exporting.js';
        }

        $this->_renderTo = ArrayHelper::getValue($this->clientOptions, 'chart.renderTo');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::input(
            'text', 
            $this->name, 
            Yii::$app->request->post('string'),
            [
                'required' => 'true', 
                'size' => '10', 
                'placeholder' => 'yyyy-mm-dd', 
                'id'=>'enddateinput', 
                'class'=>'form-control'
            ]
        );



        if (empty($this->_renderTo)) {
            echo Html::tag('div', '', $this->mcoptions);
            $this->clientOptions['chart']['renderTo'] = $this->id];
        }
        $this->registerClientScript();
    }

    /**
     * Registers the script for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        $bundle = McdatepickerAsset::register($view);
        $id = str_replace('-', '_', $this->options['id']);
        $options = $this->clientOptions;

        if ($this->enable3d) {
            $bundle->js[] = YII_DEBUG ? 'highcharts-3d.src.js' : 'highcharts-3d.js';
        }

        if ($this->enableMore) {
            $bundle->js[] = YII_DEBUG ? 'highcharts-more.src.js' : 'highcharts-more.js';
        }

        foreach ($this->modules as $module) {
            $bundle->js[] = "modules/{$module}";
        }

        if ($theme = ArrayHelper::getValue($options, 'theme')) {
            $bundle->js[] = "themes/{$theme}.js";
        }

        $options = Json::encode($options);

        $view->registerJs(";var highChart_{$id} = new Highcharts.Chart({$options});");
    }
}
