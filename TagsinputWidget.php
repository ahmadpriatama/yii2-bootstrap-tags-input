<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace pudinglabs\tagsinput;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Json;

/**
 * 
 * @author Mustoharin mustoharin@yahoo.com
 * @since 1.0
 */
class TagsinputWidget extends InputWidget
{

    public $options = ['class' => 'form-control'];
    public $clientOptions = [];
    public $clientEvents = [];

    public function init()
    {
        if (!isset($this->options['id'])) {
            if ($this->hasModel()) {
                $this->options['id'] = Html::getInputId($this->model, $this->attribute);
            } else {
                $this->options['id'] = $this->getId();
            }
        }
        TagsInputAsset::register($this->getView());
        $this->registerScript();
        $this->registerEvent();
        $this->preventHitEnter();
    }

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
    }

    protected function registerScript()
    {
        $clientOptions = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#{$this->options["id"]}').tagsinput({$clientOptions});";
        $this->getView()->registerJs($js);
    }

    protected function registerEvent()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $js[] = "jQuery('#{$this->options["id"]}').on('$event',$handle);";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }

    protected function preventHitEnter()
    {
        $js =  "$(document).ready(function() {
                    $(window).keydown(function(event){
                        if(event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });
                });";
        $this->getView()->registerJs($js);
    }

}