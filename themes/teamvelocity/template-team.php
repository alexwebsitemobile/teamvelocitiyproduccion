<?php
/* Template Name: Meet our team */
if (is_user_logged_in()) {
    get_header();
    ?>

<div class="container pdt50">
    <div class="row">
        <div class="col-xs-12 text-center">
            <div class="titles-section">
                <h1><?php the_title(); ?></h1>
                <hr>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="post-content">
                <?php the_content(); ?>
                <div class="row mgt30">
                    <?php
                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'team',
                    );
                    $team_post = get_posts($args);
                    ?>
                    <?php
                    foreach ($team_post as $post) : setup_postdata($post);
                        $id = $post->ID;
                        ?>

                        <div class="col-sm-4 text-center">
                            <article class="member">
                                <a href="#" data-toggle="modal" data-target="#myModal<?php echo $id; ?>">
                                    <?php
                                    the_post_thumbnail('portfolio-image', array('class' => 'img-responsives'));
                                    ?>
                                </a>

                                <h3><?php the_title(); ?></h3>

                                <div class="">
                                    <?php echo rwmb_meta('position_member'); ?>
                                </div>
                            </article>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6 text-center-xs tg-verticalmiddle">
                                                <?php
                                                the_post_thumbnail('portfolio-image', array('class' => 'img-responsives'));
                                                ?>
                                            </div>
                                            <div class="col-sm-6 text-left tg-verticalmiddle">
                                                <div class="text-member">
                                                    <h3><?php the_title(); ?></h3>
                                                    <h4><?php echo rwmb_meta('position_member'); ?></h4>
                                                    <hr>
                                                    <i class="fa fa-at"></i> <a href="mailto:<?php echo rwmb_meta('email_member'); ?>"><?php echo rwmb_meta('email_member'); ?></a><br>
                                                    <i class="fa fa-phone"></i> <a href="tel:<?php echo rwmb_meta('phone_member'); ?>"><?php echo rwmb_meta('phone_member'); ?></a>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 mgt30">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
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