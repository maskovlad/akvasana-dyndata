<?php
function as_menu_page_callback()
{
  //must check that the user has the required capability 
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  global $wpdb;

  // Read in existing option value from database
  $order_email_val = get_option('order_email');
  $test_email_val = get_option('test_email');

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if (!empty($_POST)) {
    // Read their posted value
    $order_email_val = $_POST['order_email'];
    $test_email_val = $_POST['test_email'];

    // Save the posted value in the database
    update_option('order_email', trim($order_email_val));
    update_option('test_email', trim($test_email_val));

    // Put an options updated message on the screen
?>
    <div class="updated">
      <p><strong>Изменения сохранены</strong></p>
    </div>
  <?php

  }

  ?>
  <!----------------- MENU PAGE ----------------->
  <div class="wrap">

    <h2>Настройки Аква Сана</h2>

    <form name="form1" method="post" action="">
      <input type="hidden" name="submit_hidden" value="Y">

      <table class="form-table">

        <tr>
          <th scope="row"><label>Email, на который отправлять заказы:</label></th>
          <td>
            <input type="text" name="order_email" value="<?php echo $order_email_val; ?>" size="20">
          </td>
        </tr>
        <tr>
          <th scope="row"><label>Email для тестирования:</label></th>
          <td>
            <input type="text" name="test_email" value="<?php echo $test_email_val; ?>" size="20">
          </td>
        </tr>

      </table>

      <p class="submit">
          <input type="submit" class='button button-primary' value="Сохранить изменения" />
      </p>

    </form>
    <?php
    $wpdb->flush();
  }

  // страница редактирования цены на воду 
  function price_page_callback()
  {
    global $wpdb;
    $table_name = 'as_regions';

    // запись введенных значений в БД
    if (!empty($_POST)) {

      foreach ($_POST as $key => $price) {

        if ($key[0] == '1') {  // если это цена за 1 бутль
          $cost = 'cost1';
          $region_id = trim($key, '1');
        } else {              //  если за 2 бутля
          $cost = 'cost';
          $region_id = $key;
        }
        if ($wpdb->update($table_name, array($cost => trim($price)), array('region_id' => $region_id)) === false) {
          wp_die('Не удалось записать данные в БД. Обратитесь к администратору.');
        }
      }

      // Put an options updated message on the screen
    ?>
      <div class="updated">
        <p><strong>Изменения сохранены</strong></p>
      </div>
    <?php

    } //if (!empty($_POST))

    // выборка значений из БД
    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '$table_name'")) != $table_name) {
      return 0;
    } else {
      $regions_prices = $wpdb->get_results($wpdb->prepare("SELECT region_id, region_name, cost, cost1 FROM $table_name"));
    }
    ?>
    <!----------------- SUBMENU PAGE ----------------->
    <div class="wrap">

      <h2>Настройки Аква Сана</h2>

      <form name="form1" method="post" action="">
        <h2>Цена воды</h2>

        <table class="form-table">
          <tr>
            <th scope="row"><label>Район</label>
              <hr />
            </th>
            <th scope="row"><label>За 2 бутля</label>
              <hr />
            </th>
            <th scope="row"><label>За 1 бутль</label>
              <hr />
            </th>
          </tr>

          <?php foreach ($regions_prices as $region_price) { ?>

            <tr>
              <th scope="row"><label><?php echo $region_price->region_name; ?></label></th>
              <td>
                <input type="text" name=<?php echo $region_price->region_id; ?> value="<?php echo $region_price->cost; ?>" size="3">
              </td>
              <td>
                <input type="text" name=<?php echo '1' . $region_price->region_id; ?> value="<?php echo $region_price->cost1; ?>" size="3">
              </td>
            </tr>

          <?php } ?>

        </table>

        <p class="submit">
          <input type="submit" class='button button-primary' value="Сохранить изменения" />
        </p>

      </form>
      <?php
      $wpdb->flush();
    }


    // ======== ===СТРАНИЦА ЦЕН НА АКСЕССУАРЫ=== =============
    function accessory_page_callback()
    {
      global $wpdb;
      $table_name = 'as_accessory';

      if (!empty($_POST)) {

        foreach ($_POST as $key => $price) {

          if ($wpdb->update($table_name, array('cost' => trim($price)), array('item' => $key)) === false) {
            wp_die('Не удалось записать данные в БД. Обратитесь к администратору.');
          }
        }

        // Put an options updated message on the screen
      ?>
        <div class="updated">
          <p><strong>Изменения сохранены</strong></p>
        </div>
      <?php

      } //if (!empty($_POST))

      // выборка значений из БД
      if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '$table_name'")) != $table_name) {
        return 0;
      } else {
        $prices = $wpdb->get_results($wpdb->prepare("SELECT item, title, cost FROM $table_name"));
      }
      ?>

      <!----------------- SUBMENU PAGE ----------------->
      <div class="wrap">

        <h2>Настройки Аква Сана</h2>

        <form name="form1" method="post" action="">
          <h2>Цены на аксессуары</h2>

          <table class="form-table">
            <tr>
              <th scope="row"><label>Аксессуар</label>
                <hr />
              </th>
              <th scope="row"><label>Цена</label>
                <hr />
              </th>
            </tr>

            <?php foreach ($prices as $price) { ?>

              <tr>
                <th scope="row"><label><?php echo $price->title; ?></label></th>
                <td>
                  <input type="text" name=<?php echo $price->item; ?> value="<?php echo $price->cost; ?>" size="3">
                </td>
              </tr>

            <?php } ?>

          </table>

          <p class="submit">
            <input type="submit" class='button button-primary' value="Сохранить изменения" />
          </p>

        </form>
      <?php

      // echo "<pre>";
      // echo var_dump($_POST);
      // echo "</pre>";

      $wpdb->flush();
    }
