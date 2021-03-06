<?php
if (is_user_logged_in()) {
    get_header();
    the_post();
    ?>

    <div class="container pdt50">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="titles-section">
                    <h1><?php
                        if (is_archive('post-type-archive-tribe_events')) {
                            echo 'Calendar';
                        } else {
                            the_title();
                        }
                        ?></h1>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>

  <?php
    get_footer();
} else {
    get_header('login');
    get_template_part('login');
    get_footer('login');
}
?>