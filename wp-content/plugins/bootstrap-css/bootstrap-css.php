<?php
// Plugin Name: Bootstrap CSS
// Description: Adds Bootstrap CSS to the theme
// Version: 1.0
function demo_include_bootstrap() {
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
    wp_enqueue_style( 'bootstrap-js', '	https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js');

}
add_action('wp_enqueue_scripts','demo_include_bootstrap');
?>