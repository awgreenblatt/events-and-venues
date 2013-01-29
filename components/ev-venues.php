<?php

add_action('init', 'ev_venues_scripts');
function ev_venues_scripts() {
    wp_register_script('ev-venue-model', plugins_url('js/models/venue-model.js', __FILE__),
        array('backbone', 'underscore'), false, true);
    wp_register_script('ev-venue-list-view', plugins_url('js/views/venue-list.js', __FILE__),
        array('ev-ui', 'jquery', 'backbone', 'underscore'), false, true);
    wp_register_script('ev-venue-details-view', plugins_url('js/views/venue-details.js', __FILE__),
        array('ev-ui', 'jquery', 'backbone', 'underscore'), false, true);
    wp_register_script('ev-venues', plugins_url('js/venues-app.js', __FILE__),
        array('ev-venue-model', 'ev-venue-list-view', 'ev-venue-details-view'),
        false, true);
}

add_action('wp_footer', 'ev_print_venues_scripts');
function ev_print_venues_scripts() {
    global $ev_add_venues_scripts;

    /* Test if the 'ev-venues' shortcode exists on the page first */
    if ($ev_add_venues_scripts) {
        wp_print_scripts(array('ev-venues'));
    }
}

add_shortcode('ev-venues', 'ev_venues_handler');
function ev_venues_handler($atts) {
    global $ev_add_venues_scripts;

    $ev_add_venues_scripts = true;

    return '<div id="ev-venues">
                <div class="ev-venues-list" style="float:left"></div>
                <div class="ev-venue-details" style="float:right"></div>
            </div>';
}
?>
