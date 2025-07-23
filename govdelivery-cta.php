<?php

/**
 * Plugin Name: GovDelivery CTA (Call-to-Action)
 * Description: Accessible, translatable CTA block for GovDelivery subscriptions.
 * Version: 1.0.0
 * Author: Arnold De Guzman
 * Text Domain: govdelivery-cta
 */

if (! defined('ABSPATH')) {
    exit;
}

function govdelivery_cta_block_init()
{
    register_block_type_from_metadata(
        __DIR__ . '/',
        [
            'render_callback' => 'govdelivery_cta_render_callback'
        ]
    );
}

add_action('init', 'govdelivery_cta_block_init');

function govdelivery_cta_render_callback($attributes)
{
    $title      = esc_html($attributes['title'] ?? '');
    $desc       = esc_html($attributes['description'] ?? '');
    $button     = esc_html($attributes['buttonText'] ?? 'Subscribe');
    $actionType = esc_attr($attributes['actionType'] ?? 'account');
    $topicId    = esc_attr($attributes['topicId'] ?? '');

    ob_start(); ?>
<section class="govdelivery-cta" aria-labelledby="govdelivery-cta-title">
    <h2 id="govdelivery-cta-title"><?php echo $title; ?></h2>
    <p><?php echo $desc; ?></p>

    <?php if ($actionType === 'account') : ?>
    <form action="https://public.govdelivery.com/accounts/CASAND/subscribers/qualify" method="post"
        accept-charset="UTF-8">
        <fieldset>
            <legend>Email Updates</legend>
            <label for="gd-email-account">Email Address</label>
            <input type="email" name="email" id="gd-email-account" required aria-required="true" />
            <input type="submit" value="<?php echo $button; ?>" class="gd-cta-btn" />
        </fieldset>
    </form>
    <?php else : ?>
    <form action="https://public.govdelivery.com/accounts/CASAND/subscribers/qualify" method="post"
        accept-charset="UTF-8">
        <input type="hidden" name="topic_id" value="<?php echo $topicId; ?>" />
        <fieldset>
            <legend>Email Updates</legend>
            <label for="gd-email-topic">Email Address</label>
            <input type="email" name="email" id="gd-email-topic" required aria-required="true" />
            <input type="submit" value="<?php echo $button; ?>" class="gd-cta-btn" />
        </fieldset>
    </form>
    <?php endif; ?>
</section>
<?php
    return ob_get_clean();
}

add_action('init', function () {
    error_log('GovDelivery CTA Plugin Initialized');
});