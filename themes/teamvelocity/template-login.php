<?php 
/* Template Name: Login */
if (is_user_logged_in()) {
    wp_redirect(home_url());
    die;
} else {
    get_header('login');
    get_template_part('login');
    get_footer('login');
}
?>