<?php

use core\base\exception\RouteException;

?>
<!--  Начало формы-->
<form id="main-form" class="vg-wrap vg-element vg-ninteen-of-twenty" method="post" action="<?= $this->adminPath . $this->action ?>"
      enctype="multipart/form-data">

    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
            <div class="vg-element vg-half vg-left">
                <div class="vg-element vg-padding-in-px">

                    <!--      Кнопка сохранить              -->
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button"
                           value="Сохранить">
                </div>

                <!--      Если есть что удалять показываем кнопку          -->
                <?php if (!$this->noDelete && $this->data): ?>
                    <div class="vg-element vg-padding-in-px">
                        <a href="<?= $this->adminPath . 'delete/' . $this->table . '/' . $this->data[$this->columns['id_row']] ?>"
                           class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button vg-center vg_delete">
                            <span>Удалить</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php if ($this->data): ?>
        <type="hidden" name="<?= $this->columns['id_row'] ?>" value="<?= $this->data[$this->columns['id_row']] ?>">
    <?php endif; ?>

    <input id="tableId" type="hidden" name="table" value="<?= $this->table ?>">


    <?php
    // Перебор
    foreach ($this->blocks as $class => $block) {

        // Если $class число то $class будет равен 'vg-rows';
        if (is_int($class)) $class = 'vg-rows';

        echo '<div class="vg-wrap vg-element ' . $class . '">';

        if ($class !== 'vg-content') echo '<div class="vg-full vg-firm-background-color4 vg-box-shadow">';

        if ($block) {

            // Разбираем на строки
            foreach ($block as $row) {

                foreach ($this->templateArr as $template => $items) {


//                    private $templateArr = [
//                        'text' => ['name'],
//                        'textarea' => ['content', 'keywords'],
//                        'radio' => ['visible'],
//                        'select' => ['menu_position', 'parent_id'],
//                        'img' => ['img'],
//                        'gallery_img' => ['gallery_img']
//                    ];

                    // Пробегаем по настройкам и сравниваем, если есть совпадение, подключаем
                    if (in_array($row, $items)) {

                        if (!@include $_SERVER['DOCUMENT_ROOT'] . $this->formTemplates . $template . '.php') {
                            throw new RouteException('Не найден шаблон ' .
                                $_SERVER['DOCUMENT_ROOT'] . $this->formTemplates . $template . '.php');
                        }

                        break;

                    }

                }

            }

        }

        if ($class !== 'vg-content') echo '</div>';
        echo '</div>';

    }

    ?>

    // Нижний блок кнопок сохранить / удалить
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
            <div class="vg-element vg-half vg-left">
                <div class="vg-element vg-padding-in-px">
                    <input type="submit"
                           class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button"
                           value="Сохранить">
                </div>


                <div class="vg-element vg-padding-in-px">
                    <a href="/admin/shop/delete/table/shop_products/id_row/id/id/92"
                       class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button vg-center vg_delete">
                        <span>Удалить</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</form>

				