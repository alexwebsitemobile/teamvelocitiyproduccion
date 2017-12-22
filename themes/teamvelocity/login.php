<div class="container-orange-fixed animated fadeIn">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <?php
                $logo_src = get_option('theme_options_logo_src');
                ?>
                <a class="logo" href="<?php echo home_url(); ?>">
                    <img src="<?php echo $logo_src; ?>" class="img-responsive" alt="<?php echo get_option('theme_options_logo_alt'); ?>">
                </a> 
            </div>
        </div>
    </div>
    <div class="box-login">
        <div class="text-login">
            <h1>Guest Area</h1>
            <p>Please enter the credentials to access the site.</p>
        </div>
        <?php get_template_part('templates/wp-login-form'); ?>
    </div>
</div>