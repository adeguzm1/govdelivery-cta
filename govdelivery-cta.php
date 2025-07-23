<?php

/**
 * Plugin Name: GovDelivery CTA (Call-to-Action)
 * Description: Accessible, translatable call-to-action block for GovDelivery email subscriptions.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPLv2 or later
 * Text Domain: govdelivery-cta
 */

defined('ABSPATH') || exit;

/**
 * Register the dynamic block using metadata and provide a render callback.
 */
function govdelivery_cta_register_block()
{
    register_block_type(
        __DIR__,
        array(
            'render_callback' => 'govdelivery_cta_render_callback',
        )
    );
}
add_action('init', 'govdelivery_cta_register_block');

/**
 * Render callback for the GovDelivery CTA block.
 */
function govdelivery_cta_render_callback($attributes)
{
    $title      = esc_html($attributes['title'] ?? '');
    $desc       = esc_html($attributes['description'] ?? '');
    $button     = esc_html($attributes['buttonText'] ?? 'Subscribe');
    $actionType = esc_attr($attributes['actionType'] ?? 'account');
    $topicId    = esc_attr($attributes['topicId'] ?? '');
    $accountId  = get_option('govdelivery_cta_account_id', '');

    if (empty($accountId)) {
        $settings_url = esc_url(admin_url('options-general.php?page=govdelivery-cta'));
        return sprintf(
            '<p style="color: red;">%s <a href="%s">%s</a>.</p>',
            esc_html__('Error: GovDelivery account ID is not set. Please configure it in', 'govdelivery-cta'),
            $settings_url,
            esc_html__('Settings → GovDelivery CTA', 'govdelivery-cta')
        );
    }


    ob_start();
    // error_log('GovDelivery CTA render callback hit');
?>
<section class="govdelivery-cta" aria-labelledby="govdelivery-cta-title">
    <h2 id="govdelivery-cta-title"><?php echo esc_html($title); ?></h2>
    <p><?php echo $desc; ?></p>

    <?php if ($actionType === 'account') : ?>
    <form action="https://public.govdelivery.com/accounts/<?php echo esc_attr($accountId); ?>/subscribers/qualify"
        method="post" accept-charset="UTF-8">
        <fieldset>
            <legend><?php esc_html_e('Subscribe to Email Updates', 'govdelivery-cta'); ?></legend>
            <label for="gd-email-account"><?php esc_html_e('Email Address', 'govdelivery-cta'); ?></label>
            <input type="email" name="email" id="gd-email-account" required aria-required="true" autocomplete="email" />
            <input type="submit" value="<?php echo $button; ?>" class="gd-cta-btn" />
        </fieldset>
    </form>
    <?php else : ?>
    <form action="https://public.govdelivery.com/accounts/<?php echo esc_attr($accountId); ?>/subscribers/qualify"
        method="post" accept-charset="UTF-8">
        <input type="hidden" name="topic_id" value="<?php echo esc_attr($topicId); ?>" />
        <fieldset>
            <legend><?php esc_html_e('Subscribe to Topic', 'govdelivery-cta'); ?></legend>
            <label for="gd-email-topic"><?php esc_html_e('Email Address', 'govdelivery-cta'); ?></label>
            <input type="email" name="email" id="gd-email-topic" required aria-required="true" autocomplete="email" />
            <input type="submit" value="<?php echo $button; ?>" class="gd-cta-btn" />
        </fieldset>
    </form>
    <?php endif; ?>
</section>
<?php
    return ob_get_clean();
}

/**
 * Add the Settings page under "Settings" → "GovDelivery CTA".
 */
function govdelivery_cta_add_settings_page()
{
    add_options_page(
        __('GovDelivery CTA Settings', 'govdelivery-cta'),
        __('GovDelivery CTA', 'govdelivery-cta'),
        'manage_options',
        'govdelivery-cta',
        'govdelivery_cta_render_settings_page'
    );
}
add_action('admin_menu', 'govdelivery_cta_add_settings_page');

/**
 * Register the settings field and option.
 */
function govdelivery_cta_register_settings()
{
    register_setting(
        'govdelivery_cta_options',
        'govdelivery_cta_account_id',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        )
    );

    add_settings_section(
        'govdelivery_cta_main',
        __('GovDelivery Settings', 'govdelivery-cta'),
        '__return_null',
        'govdelivery_cta'
    );

    add_settings_field(
        'govdelivery_cta_account_id',
        __('Account ID', 'govdelivery-cta'),
        'govdelivery_cta_account_id_field',
        'govdelivery_cta',
        'govdelivery_cta_main'
    );
}
add_action('admin_init', 'govdelivery_cta_register_settings');

/**
 * Output the field HTML for the Account ID.
 */
function govdelivery_cta_account_id_field()
{
    $value = get_option('govdelivery_cta_account_id', '');
?>
<input type="text" name="govdelivery_cta_account_id" value="<?php echo esc_attr($value); ?>" class="regular-text" />
<p class="description"><?php esc_html_e('Your GovDelivery account ID (e.g., CASAND).', 'govdelivery-cta'); ?></p>
<?php
}

/**
 * Render the settings page HTML.
 */
function govdelivery_cta_render_settings_page()
{
    if (! current_user_can('manage_options')) {
        return;
    }
?>
<div class="wrap">
    <h1><?php esc_html_e('GovDelivery CTA Settings', 'govdelivery-cta'); ?></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields('govdelivery_cta_options');
            do_settings_sections('govdelivery_cta');
            submit_button();
            ?>
    </form>
</div>
<?php
}