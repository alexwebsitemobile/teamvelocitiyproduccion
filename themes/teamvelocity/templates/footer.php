<?php
if (!is_page('login')) {
    ?>
    <?php
    $name = get_option('theme_options_name');
    $addr = get_option('theme_options_addr');
    $city = get_option('theme_options_city');
    $state = get_option('theme_options_state');
    $zip = get_option('theme_options_zip');
    $country = get_option('theme_options_country');
    $tel = get_option('theme_options_tel');
    $mail = get_option('theme_options_email');
    ?>


    <footer itemscope itemprop="http://schema.org/WPFooter" class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-right text-center-xs">
                    <p>
                        <?php echo $addr . ' ' . $city . ' ' . $zip . ', ' . $state; ?>
                    </p>
                    <p>
                        <a href="tel:<?php echo $tel; ?>"><?php echo $tel; ?></a> - <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>
                    </p>
                    <p>
                        &copy; Copyright - <?php echo date('Y'); ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

<?php } ?>

