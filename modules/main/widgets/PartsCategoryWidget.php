<?php

namespace app\modules\main\widgets;

use app\helpers\AutoHelper;
use Yii;
use yii\base\Widget;

/**
 * Created by PhpStorm.
 * User: aleksandrsavelev
 * Date: 11.04.17
 * Time: 12:05
 */
class PartsCategoryWidget extends Widget
{
    private $data = [];

    public function init()
    {
        $data = AutoHelper::getPartsCategories();
        $order = require Yii::getAlias('@app/data/spare_parts_lk_order.php');
        foreach ($order as $key) {
            $this->data[] = $data[$key];
        }
    }

    public function run()
    {
        ?>
        <ul>
            <?php
            foreach ($this->data as $category) {
                echo $this->printCategory($category);
            }
            ?>
        </ul>
        <?php
    }

    public static function getCategories()
    {
        return self::setItems(AutoHelper::getPartsCategories());
    }

    /**
     * @param array $array
     * @return array
     */
    private static function setItems(array $array) {
        $items = [];
        foreach ($array as $item) {
            if (isset($item['sub_categories'])) {
                $items[$item['title']] = self::setItems($item['sub_categories']);
            } else {
                $items[$item['id']] = $item['title'];
            }
        }

        return $items;
    }

    private function printCategory($cat)
    {
        $title = isset($cat['title']) ? $cat['title'] : "";
        $subCategories = (isset($cat['sub_categories']) && is_array($cat['sub_categories'])) ? $this->printSubCategories($cat['sub_categories']) : "";
        $items = $this->printSubCatItems((isset($cat['items']) && is_array($cat['items'])) ? $cat['items'] : []);

        $html = <<<HTML
            <li class="add-part-category-list-item" data-cat-id="{$cat['id']}">
                <div class="add-part-category-list-item-inner">
                    <span class="add-part-category-list-item-icon"></span>
                    <span class="add-part-category-list-item-title">{$title}</span>
                </div>
                {$subCategories}
                {$items}
            </li>
HTML;

        return $html;
    }

    private function printSubCategories(array $subCats)
    {
        $subCategories = "";
        foreach ($subCats as $subCat) {
            $subCategories .= $this->printSubCategory($subCat);
        }

        $html = <<<HTML
            <ul class="subcategory">
                {$subCategories}
            </ul>
HTML;

        return $html;
    }

    /**
     * @param array|mixed $subCat
     * @return string
     */
    private function printSubCategory($subCat)
    {
        $title = isset($subCat['title']) ? $subCat['title'] : "";
        $items = $this->printSubCatItems((isset($subCat['items']) && is_array($subCat['items'])) ? $subCat['items'] : []);

        $html = <<<HTML
            <li class="subcategory-item" data-cat-id="{$subCat['id']}">
                <span>{$title}</span>
                {$items}
            </li>
HTML;

        return $html;
    }

    /**
     * @param array $items
     * @return string
     */
    private function printSubCatItems(array $items)
    {
        $html = '<div class="addblock-form-subcategory-elements">';
        foreach ($items as $item) {
            $html .= $this->printSubCatItem($item);
        }
        $html .= $this->printSubCatItem();

        return $html . "</div>";
    }

    /**
     * @param string|bool $item
     * @return string
     */
    private function printSubCatItem($item = false)
    {
        $otherClass = !$item ? " add-form-subcategory-element-other" : "";
        $itemBlock = !$item ?
            '<div class="check inp"></div><div class="check-inp">Прочее</div><input type="text" class="check-inp pi-name" placeholder ="Введите название детали">'
            :
            '<div class="check"><span>' . $item . '</span></div><input type="hidden" class="pi-name" value="' . $item . '">';

        $html = <<<HTML
            <div class="addblock-form-subcategory-element{$otherClass}">
                <div class="addblock-form-subcategory-element-item">
                    {$itemBlock}
                </div>
                <div class="addblock-form-subcategory-element-item">
                    <div class="addblock-form-subcategory-element-item-table">
                        <div class="addblock-form-subcategory-element-item-cell descr">
                            <textarea rows="1" placeholder="Введите описание детали" class="addblock-form-subcategory-element-item-name" maxlength="255"></textarea>
                        </div>
                        <div class="addblock-form-subcategory-element-item-cell photo">
                            <div class="addblock-form-subcategory-element-item-filebtn tooltip-has">
                                <div class="photo-btn-delete">x</div>
                                <div class="addblock-form-subcategory-element-item-img">
                                    + Фото 1
                                </div>
                                <input type="file" class="addblock-form-subcategory-element-item-file" />
                                <div class="tooltip-popup">Прикрепить главное фото</div>
                            </div>
                            <div class="addblock-form-subcategory-element-item-filebtn tooltip-has">
                                <div class="photo-btn-delete">x</div>
                                <div class="addblock-form-subcategory-element-item-img">
                                    + Фото 2
                                </div>
                                <input type="file" class="addblock-form-subcategory-element-item-file" />
                                <div class="tooltip-popup">Прикрепить фото</div>
                            </div>
                            <div class="addblock-form-subcategory-element-item-filebtn tooltip-has">
                                <div class="photo-btn-delete">x</div>
                                <div class="addblock-form-subcategory-element-item-img">
                                    + Фото 3
                                </div>
                                <input type="file" class="addblock-form-subcategory-element-item-file" />
                                <div class="tooltip-popup">Прикрепить фото</div>
                            </div>
                        </div>
                        <div class="addblock-form-subcategory-element-item-cell price tooltip-has">
                            <input type="text" placeholder="Цена" class="addblock-form-subcategory-element-item-price" />
                            <div class="tooltip-popup">Укажите цену или по умолчанию</br>она будет стоять <span>договорная</span></div>
                            <div class="addblock-form-subcategory-element-item-cell currency">
                                <span>руб</span>
                            </div> 
                        </div>
                          
                    </div>
                </div>
            </div>
HTML;

        return $html;
    }

}