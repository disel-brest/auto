<?php

namespace app\modules\main\widgets;


use app\modules\main\models\CarOptions;
use yii\base\Model;
use yii\base\Widget;

/**
 * Class CarOptionsWidget
 * @package app\modules\main\widgets
 *
 * @property Model $form
 */
class CarOptionsWidget extends Widget
{
    /* @var $form Model */
    public $form;

    private $options;
    private $formOptions;

    /* @var $categories CarOptions[] */
    private $categories;

    public function init()
    {
        $this->setOptions();
        $this->formOptions = $this->form->options;
    }

    public function run()
    {
        foreach ($this->categories as $category) {
            ?>
            <div class="select-dop-option-transport">
                <p><?= $category->name ?></p>
                <?php
                if (isset($this->options[$category->id]) && is_array($this->options[$category->id])) {
                    foreach ($this->options[$category->id] as $option) {
                        /* @var $option CarOptions */
                        ?>
                        <label>
                            <input type="checkbox" name="<?= $this->form->formName() ?>[options][<?= $option->id ?>]" value="1"<?= (isset($this->formOptions[$option->id]) && $this->formOptions[$option->id]) ? " checked" : "" ?>>
                            &nbsp;<?= $option->name ?>
                        </label>
                        <?php
                    }
                } ?>
            </div>
            <?php
        }
    }

    private function setOptions()
    {
        $allOptions = CarOptions::find()->all();
        $this->categories = array_filter($allOptions, function($row) {
            return ($row->parent_id == 0);
        });
        foreach ($allOptions as $option) {
            if ($option->parent_id > 0) {
                $this->options[$option->parent_id][] = $option;
            }
        }
    }
}