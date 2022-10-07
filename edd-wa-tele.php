<?php

/**
 * Easy Digital Download: WA-TELE
 *
 * @package     EDDWATele
 * @author      Henri Susanto
 * @copyright   2022 Henri Susanto
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Easy Digital Download: WA-TELE
 * Plugin URI:  https://github.com/susantohenri
 * Description: Store Customer WA & Tele on EDD
 * Version:     1.0.0
 * Author:      Henri Susanto
 * Author URI:  https://github.com/susantohenri
 * Text Domain: edd-wa-tele
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_action('edd_purchase_form_user_info_fields', function () {
?>
    <p id="edd-whatsapp-wrap">
        <label class="edd-label" for="edd-whatsapp">Whatsapp</label>
        <span class="edd-description"> Enter your whatsapp number so we can get in touch with you. </span>
        <input class="edd-input" type="text" name="edd_whatsapp" id="edd-whatsapp" placeholder="whatsapp Number">
    </p>
    <p id="edd-telegram-wrap">
        <label class="edd-label" for="edd-telegram">Telegram</label>
        <span class="edd-description"> Enter your telegram so we can get in touch with you. </span>
        <input class="edd-input" type="text" name="edd_telegram" id="edd-telegram" placeholder="Telegram">
    </p>
<?php
});

add_filter('edd_purchase_form_required_fields', function ($required_fields) {
    $required_fields['edd_whatsapp'] = array(
        'error_id' => 'invalid_whatsapp',
        'error_message' => 'Please enter a valid Whatsapp number'
    );
    return $required_fields;
});

add_action('edd_checkout_error_checks', function ($valid_data, $data) {
    if (empty($data['edd_whatsapp'])) {
        edd_set_error('invalid_whatsapp', 'Please enter your whatsapp number.');
    }
}, 10, 2);

add_action('edd_built_order', function ($order_id, $order_data) {
    if (0 !== did_action('edd_pre_process_purchase')) {
        $whatsapp = isset($_POST['edd_whatsapp']) ? sanitize_text_field($_POST['edd_whatsapp']) : '';
        edd_add_order_meta($order_id, 'whatsapp', $whatsapp);
        $telegram = isset($_POST['edd_telegram']) ? sanitize_text_field($_POST['edd_telegram']) : '';
        edd_add_order_meta($order_id, 'telegram', $telegram);
    }
}, 10, 2);

add_action('edd_payment_view_details', function ($order_id) {
    $whatsapp = edd_get_order_meta($order_id, 'whatsapp', true);
    $telegram = edd_get_order_meta($order_id, 'telegram', true);
?>
    <div class="column-container">
        <div class="column">
            <strong>Whatsapp: </strong>
            <?php echo $whatsapp; ?>
        </div>
        <div class="column">
            <strong>Telegram: </strong>
            <?php echo $telegram; ?>
        </div>
    </div>
<?php
}, 10, 1);
