<?php
if (is_user_logged_in()) {
    get_header();
    ?>

    <div class="container pdt50">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="titles-section">
                    <h1>News</h1>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    ?>  
                    <div class="col-sm-6">
                        <article class="post-news">
                            <header>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </header>
                            <figure>
                                <a href="<?php the_permalink(); ?>" class="entry-image text-center">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('blog-image', array('class' => 'img-responsives'));
                                    } else {
                                        echo '<img class="img-responsives" src="' . get_bloginfo('stylesheet_directory') . '/images/no-post.png" />';
                                    }
                                    ?>
                                    <?php ?>
                                </a>
                            </figure>
                            <footer>
                                <?php the_excerpt(); ?>
                                <div class="text-center mgt30">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-warning">
                                        Read More
                                    </a>
                                </div>
                            </footer>
                        </article>
                    </div>
                    <?php
                }
            }
            ?>
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