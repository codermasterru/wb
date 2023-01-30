<div class="vg-wrap vg-element vg-ninteen-of-twenty">

    <!--     Блок  с добавлением   -->
    <div class="vg-element vg-fourth">
        <a href="<?= $this->adminPath ?>add/<?= $this->table ?>"
           class="vg-wrap vg-element vg-full vg-firm-background-color3 vg-box-shadow">
            <div class="vg-element vg-half vg-center">

                <!--       Иконка добавления (плюс)         -->
                <img src="<?= PATH . ADMIN_TEMPLATE ?>img/plus.png" alt="plus">
            </div>
            <div class="vg-element vg-half vg-center vg-firm-background-color1">
                <span class="vg-text vg-firm-color3">Добавить</span>
            </div>
        </a>
    </div>


    <!--  Данные из текущей таблицы-->
    <!--
    Array
    (
        [0] =>[
                [id] => 13
                [name] => goods_1
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] =>
                [content] =>
                [keywords] =>
                [date] =>
                [datetime] =>
                [alias] =>
            ]

        [1] =>[
                [id] => 16
                [name] => goods_4
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2022-12-27
                [datetime] => 2022-12-27 13:46:11
                [alias] => goods_4
            ]

        [2] => [
                [id] => 17
                [name] => ghfghfgh
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2022-12-27
                [datetime] => 2022-12-27 16:16:25
                [alias] => ghfghfgh
            ]

        [3] => [
                [id] => 18
                [name] => bxcvbcvb
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2022-12-27
                [datetime] => 2022-12-27 16:16:50
                [alias] => bxcvbcvb
            ]

        [4] => [
                [id] => 19
                [name] => ryyyyyyyhfgh
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2022-12-27
                [datetime] => 2022-12-27 16:19:59
                [alias] => ryyyyyyyhfgh
            ]

        [5] => [
                [id] => 20
                [name] => 3452352345235
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2023-01-26
                [datetime] => 2023-01-26 13:22:38
                [alias] => 3452352345235
            ]

        [6] => [
                [id] => 21
                [name] => 23452345325235
                [img] =>
                [gallery_img] =>
                [menu_position] => 1
                [visible] => 1
                [content] =>
                [keywords] =>
                [date] => 2023-01-26
                [datetime] => 2023-01-26 13:23:11
                [alias] => 23452345325235
            ]

        [7] =>[
                [id] => 14
                [name] => goods_2
                [img] =>
                [gallery_img] =>
                [menu_position] => 2
                [visible] =>
                [content] =>
                [keywords] =>
                [date] =>
                [datetime] =>
                [alias] =>
            ]

        [8] =>[
                [id] => 15
                [name] => goods_3
                [img] =>
                [gallery_img] =>
                [menu_position] => 3
                [visible] =>
                [content] =>
                [keywords] =>
                [date] =>
                [datetime] =>
                [alias] =>
            ]

    )


    -->
    <?php if ($this->data): ?>
        <?php foreach ($this->data as $data): ?>
            <div class="vg-element vg-fourth">
                <a href="<?= $this->adminPath ?>edit/<?= $this->table ?>/<?= $data['id'] ?>"
                   class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow show_element">
                    <div class="vg-element vg-half vg-center">
                        <?php if ($data['img']): ?>
                            <img src="<?= PATH . UPLOAD_DIR . $data['img'] ?>" alt="service">
                        <?php endif; ?>
                    </div>
                    <div class="vg-element vg-half vg-center">
                        <span class="vg-text vg-firm-color1"><?= $data['name'] ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


