<?php
// Plugin Name: Example Form Plugin
// Description: This is a form plugin.
// Version: 1.0

// Register the form
function example_form_plugin() {
  $content = '';
  $content .= '<br /><h2>Leave A Review</h2>';
  $content .= '<form method="post" action="">';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio1" value="0"/>';
  $content .= '<label class="form-check-label" for="inlineRadio1">0</label>';
  $content .= '</div>';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio2" value="1"/>';
  $content .= '<label class="form-check-label" for="inlineRadio2">1</label>';
  $content .= '</div>';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio3" value="2"/>';
  $content .= '<label class="form-check-label" for="inlineRadio3">2</label>';
  $content .= '</div>';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio4" value="3"/>';
  $content .= '<label class="form-check-label" for="inlineRadio4">3</label>';
  $content .= '</div>';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio5" value="4"/>';
  $content .= '<label class="form-check-label" for="inlineRadio5">4</label>';
  $content .= '</div>';

  $content .= '<div class="form-check form-check-inline">';
  $content .= '<input type="radio" name="inlineRadioOptions" class="form-check-input" id="inlineRadio6" value="5"/>';
  $content .= '<label class="form-check-label" for="inlineRadio6">5</label>';
  $content .= '</div>';

  
  $content .= '<br /><label for="your_email">Email</label>';
  $content .= '<input type="email" name="your_email" class="form-control" placeholder="Your email" required>';

  $content .= '<br /><label for="your_comment">Comment</label>';
  $content .= '<textarea name="your_comment" class="form-control" placeholder="Enter your comment" required></textarea>';

  $content .= '<br /><input type="submit" name="submit" class="btn btn-primary" value="Submit" />';
  $content .= '</form>';
  form_insert_data();
  return $content;
  
  // Insert Data in Table for plugin
}
add_shortcode( 'example_form', 'example_form_plugin' ); // [example_form]

function form_insert_data() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'review'; // Table name
  $form_rating = $_POST['inlineRadioOptions'];
  $form_email = $_POST['your_email'];
  $form_comment = $_POST['your_comment'];
  $id =get_the_id();
  if (isset($_POST['submit'])) {
    // print_r($table_name . 'test');
    $wpdb->insert(
      $table_name,
      array(
        'score' => $form_rating,  // score  = rating
        'email' => $form_email, // email = email
        'comment' => $form_comment,  // comment = comment
        'post_id' => $id
      ),
      array(
        '%s', // score
        '%s', // email
        '%s',  // comment
        '%d'
      )
      );
}
}

// Define absolute path to avoid  direct access
if ( !defined( 'ABSPATH' ) ) {
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
add_action('admin_menu', 'form_plugin_setup_menu');

// Add menu page
function form_plugin_setup_menu() {
    add_menu_page(
        'Form Plugin Page',
        'Form Plugin',
        'manage_options',
        'form_plugin',
        'form_code'
    );
}

function form_code() {
    echo '<div class="card">
    <div class="card-body">
      Use this Shortcode in any page you want to add a review form [example_form]
    </div>
  </div>';
}
