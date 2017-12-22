<?php
if (is_user_logged_in()) {
    get_header();
    the_post();
    ?>

<div class="container-white pdt50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="titles-section">
                        <h2><?php echo rwmb_meta('title_ceo'); ?></h2>
                        <h4><?php echo rwmb_meta('subtitle_ceo'); ?></h4>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 tg-verticalmiddle text-center-xs">
                    <?php the_post_thumbnail('full', array('class' => 'img-responsives mgb20')); ?>
                </div>
                <div class="col-sm-7 tg-verticalmiddle">
                    <div class="post-content post-content-big">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-white pdt50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="titles-section">
                        <h1><?php echo rwmb_meta('title_pillars'); ?></h1>
                        <hr>
                    </div>
                    <div class="boxes">
                        <?php
                        $boxes_id = rwmb_meta('boxes_id');
                        if (!empty($boxes_id)) {
                            foreach ($boxes_id as $boxes_group) {
                                $bg = $boxes_group['bg_boxes'];
                                ?>
                                <div class="box-gutter" style="background: <?php echo $bg; ?>">
                                    <div class="service-box">
                                        <?php
                                        $image_ids = isset($boxes_group['icon_img']) ? $boxes_group['icon_img'] : array();
                                        foreach ($image_ids as $image_id) {
                                            $image = RWMB_Image_Field::file_info($image_id, array('size' => 'thumbnail'));
                                            echo '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '">';
                                        }
                                        ?>
                                        <h3><?php echo $boxes_group['title_boxes']; ?></h3>
                                    </div><!-- /.service-box -->
                                </div><!-- /.col-sm-4 -->
                                <?php
                            }
                        }
                        ?>
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