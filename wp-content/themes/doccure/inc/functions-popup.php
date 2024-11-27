<?php
/**
 * doccure Popup
 *
 * @package doccure
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Check if popup is active or not.
 *
 * @since 1.0.0
 */
function doccure_popup_is_active()
{
    $current_id = doccure_get_page_id();
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    $popup_disable = isset($page_settings['doccure_popup_disable']) ? (bool)$page_settings['doccure_popup_disable'] : '';
    $popup_conditions = !doccure_get_option('enable-popup');
    if (($current_id && $popup_disable) || $popup_conditions) {
        return false;
    }
    return true;
}
