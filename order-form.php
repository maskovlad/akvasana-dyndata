<?php
function as_show_order_form()
{

    // запрос к базе данных
    global $wpdb;

    $query_reg = "SELECT * FROM as_regions";
    $query_acc = "SELECT * FROM as_accessory";

    $result_reg = $wpdb->get_results($wpdb->prepare($query_reg), OBJECT); // получаем массив обьектов районов
    $result_acc = $wpdb->get_results($wpdb->prepare($query_acc), OBJECT); // получаем массив обьектов цен помпы и бутля

    // echo "<pre>";
    // echo var_dump($result_acc[0]->cost);
    // echo "</pre>";

    if (!$result_reg)
        wp_die('Помилка запиту до бази даних! Зверніться до адміністратора!');

    $bottle_cost = $result_acc[0]->cost; /* цена пустого бутля */
    $pomp_cost = $result_acc[1]->cost; /* цена помпи */


    ob_start();
?>
    <!-- <div class="as-form-container"> -->
    <form action="" class="as-form" id="as-form">
        <!--            адрес и телефон -->
        <div class="wrap-bot">
            <a href="https://t.me/akvasanakr_bot" target="_blanc">
                <img class="bot-img" src="wp-content/plugins/akvasana-dyndata/assets/telegram.svg" alt="Telegram" />
                Спробуйте наш Telegram-бот для замовлень
            </a>
        </div>
        <div class="row">
            <div class="wrap wrap-text-input">
                <label for="as-regions" class="labels">Район <span class="red-star">*</span></label>
                <select name="regions" id="as-regions" class="as-input as-regions">
                    <option value="" disabled selected>Виберіть район</option>
                    <?php
                    // вытягиваем нужные значения из результата запроса
                    // и заполняем поля в селекте
                    foreach ($result_reg as $result) { ?>
                        <option value="<?php echo $result->region_name; ?>" data-calc="<?php echo $result->cost ?>" data-calc1="<?php echo $result->cost1 ?>" data-min-value="<?php echo $result->min_quantity; ?>">
                            <?php echo $result->region_name; ?>
                        </option><?php
                                }
                                    ?>
                </select>
            </div>

            <!--            адрес -->
            <div class="wrap wrap-text-input">
                <label for="as-address" class="labels">Адреса <span class="red-star">*</span></label>
                <input type="text" id="as-address" name="as-address" class="as-input as-address" maxlength="40" placeholder="Введіть адресу (квартира/офіс)">
            </div>

            <!--            телефон -->
            <div class="wrap wrap-text-input">
                <label for="as-phone" class="labels">Телефон <span class="red-star">*</span></label>
                <input type="text" id="as-phone" name="as-phone" class="as-input as-phone" placeholder="Введіть номер телефону">
            </div>
        </div>

        <!--            является ли клиентом -->
        <div class="row">
            <div class="wrap-toggle">
                <p class="labels">Чи є ви клієнтом Аква Сана <span class="red-star">*</span></p>
                <div class="wrap-radio-items">
                    <label class="radio">
                        <input type="radio" name="as-is-client" class="as-is-client as-is-client-yes" value="+" />
                        <svg viewbox="0 0 24 24" filter="url(#goo-light)">
                            <circle class="top" cx="12" cy="-12" r="8" />
                            <circle class="dot" cx="12" cy="12" r="5" />
                            <circle class="drop" cx="12" cy="12" r="2" />
                        </svg>
                    </label>

                    <label class="radio">
                        <input type="radio" name="as-is-client" class="as-is-client as-is-client-no" value="-" />
                        <svg viewBox="0 0 24 24" filter="url(#goo-light)">
                            <circle class="top" cx="12" cy="-12" r="8" />
                            <circle class="dot" cx="12" cy="12" r="5" />
                            <circle class="drop" cx="12" cy="12" r="2" />
                        </svg>
                    </label>
                </div>
            </div>
            <div class="wrap-toggle">
                <label><span class="red-star">*</span> Зірочкою позначені поля, обов'язкові для заповнення</label>
            </div>
        </div>

        <!--            поля для проверки на спам         -->
        <input type="checkbox" name="as_chk_padlo" id="as_chk_padlo" class="as_chk_padlo" style="display: none !important;" value="true" checked="checked" />

        <input type="text" name="as_txt_padlo" id="as_txt_padlo" value="" style="display: none !important;" />



        <!--            тара и помпа-->
        <div class="row">
            <div class="wrap-toggle">
                <p class="labels">Чи потрібна Вам тара (<?php echo $bottle_cost ?>грн/бутиль)</p>
                <label class="switch">
                    <input type="checkbox" name="bottle-need" id="bottle-need" value="<?php echo $bottle_cost ?>" />
                    <svg viewBox="0 0 38 24" filter="url(#goo)">
                        <circle class="default" cx="12" cy="12" r="8" />
                        <circle class="dot" cx="26" cy="12" r="8" />
                        <circle class="drop" cx="25" cy="-1" r="2" />
                    </svg>
                </label>
            </div>
            <div class="wrap-toggle">
                <p class="labels">Чи потрібна Вам помпа (<?php echo $pomp_cost ?>грн/бутиль)</p>
                <label class="switch">
                    <input type="checkbox" name="pomp-need" id="pomp-need" value="<?php echo $pomp_cost ?>" />
                    <svg viewBox="0 0 38 24" filter="url(#goo)">
                        <circle class="default" cx="12" cy="12" r="8" />
                        <circle class="dot" cx="26" cy="12" r="8" />
                        <circle class="drop" cx="25" cy="-1" r="2" />
                    </svg>
                </label>
            </div>
        </div>

        <!--            количество бутлей и общая сумма -->
        <div class="row row-btn">
            <div class="wrap">
                <p class="labels">Кількість бутлів</p>
                <div class="wrap-btn-add">
                    <button type="button" id="quantity-bottles-minus" class="btn-add">-</button>
                    <input type="number" name="quantity-bottles" id="quantity-bottles" class="numbers" value="1" min="1" readonly>
                    <button type="button" id="quantity-bottles-plus" class="btn-add">+</button>
                </div>
            </div>
            <div class="wrap">
                <p class="labels">До сплати</p>
                <input type="number" name="total" id="total" class="numbers" readonly>
            </div>
        </div>

        <!--            кнонка ЗАМОВИТИ -->
        <div class="row row-btn row-last">
            <div class="wrap">
                <button type="submit" class="btn">
                    <span id="text-on-button">ЗАМОВИТИ</span>
                    <svg preserveAspectRatio="none" viewBox="0 0 132 45">
                        <g clip-path="url(#clip)" filter="url(#goo-big)">
                            <circle class="top-left" cx="49.5" cy="-0.5" r="26.5" />
                            <circle class="middle-bottom" cx="70.5" cy="40.5" r="26.5" />
                            <circle class="top-right" cx="104" cy="6.5" r="27" />
                            <circle class="right-bottom" cx="123.5" cy="36.5" r="26.5" />
                            <circle class="left-bottom" cx="16.5" cy="28" r="30" />
                        </g>
                        <defs>
                            <clipPath id="clip">
                                <rect width="132" height="45" rx="7" />
                            </clipPath>
                        </defs>
                    </svg>
                </button>

                <svg width="0" height="0">
                    <defs>
                        <filter id="goo" x="-50%" width="200%" y="-50%" height="200%" color-interpolation-filters="sRGB">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur" />
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7" result="cm" />
                        </filter>
                        <filter id="goo-light" x="-50%" width="200%" y="-50%" height="200%" color-interpolation-filters="sRGB">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="1.25" result="blur" />
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7" result="cm" />
                        </filter>
                        <filter id="goo-big" x="-50%" width="200%" y="-50%" height="200%" color-interpolation-filters="sRGB">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="7" result="blur" />
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7" result="cm" />
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
    </form>
    <!-- </div> -->
<?php
    return ob_get_clean();
}
