<?php
if (is_user_logged_in()) {
    get_header();
    ?>

    <div class="container pdt50">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="titles-section">
                    <h1>Resources</h1>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="boxes-five">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        ?>  
                        <div class="box text-center">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('blog-image', array('class' => 'img-responsives')); ?>
                            </a>
                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                        </div>
                        <?php
                    }
                }
                ?>
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