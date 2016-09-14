<?php

function vp_add_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=job',
        'Settings',
        'Settings',
        'manage_options',
        'list_jobs',
        'vp_settings_callback'
    );
}
add_action( 'admin_menu', 'vp_add_submenu_page' );

function vp_reorder_diaries() {
    //verify user intent
    check_ajax_referer( 'wp-diary-order', 'security' ); // this comes from wp_localize_script() in index.php
    //capability check to ensure use caps
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.' ) );
    }
    $order   = $_POST['order'];
    $counter = 0;
    foreach ( $order as $item_id ) {
        $post = array(
            'ID'         => (int) $item_id,
            'menu_order' => $counter,
        );
        wp_update_post( $post );
        $counter ++;
    }
    wp_send_json_success($_POST['order']);
}

add_action('wp_ajax_save_post', 'vp_reorder_diaries');

// Save Translations

function vp_save_translations() {
    //capability check to ensure use caps
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.' ) );
    }

    $translation1   = $_POST['translation1'];
    $translation2   = $_POST['translation2'];
    $translation3   = $_POST['translation3'];
    $translation4   = $_POST['translation4'];
    $translation5   = $_POST['translation5'];

    if (isset( $translation1 )) {
        update_option( 'vp-translation1', sanitize_text_field($translation1));
    }
    if (isset( $translation2 )) {
        update_option( 'vp-translation2', sanitize_text_field($translation2));
    }
    if (isset( $translation3 )) {
        update_option( 'vp-translation3', sanitize_text_field($translation3));
    }
    if (isset( $translation4 )) {
        update_option( 'vp-translation4', sanitize_text_field($translation4));
    }
    if (isset( $translation5 )) {
        update_option( 'vp_salary_range_text', sanitize_text_field($translation5));
    }

    $dataArr = array(
        $translation1,
        $translation2,
        $translation3,
        $translation4,
        $translation5,
    );

    wp_send_json_success($dataArr);
}

add_action('wp_ajax_vp_save_translations', 'vp_save_translations');


function vp_settings_callback() {

    $args = array(
        'post_type' => 'job',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'post_per_page' => 50,
        'post_status' => 'publish',
    );

    $diary_listing = new WP_Query($args);

    //Translate Captions
    $vp_active_until = get_option('vp-translation1', 'Active Until');
    $vp_job_description = get_option('vp-translation2','Job Description');
    $vp_pre_location = get_option('vp-translation3','Current job oppens in');
    $vp_go_back = get_option('vp-translation4','<- Go Back');
    $vp_salary_range_text = get_option('vp_salary_range_text','Salary range');
    ?>

    <div id="pre-diary-sort">
        <div id="diary-settings">
            <h1>Job Vacancies Plugin</h1>
            <hr>
            <h2></h2>
            <img src="<?= esc_url( admin_url() . 'images/loading.gif' ) ?>" alt="" id="vp-loading-animation">
            <h3>Translate Captions</h3>
            <hr>
            <div id="diary-settings1" class="diary-settings-fields">
                <div class="meta-row">
                    <div class="meta-head">
                        <label for="vp-active-until" class="row-title">"<?= $vp_active_until ?>"</label>
                    </div>
                    <div class="meta-data">
                        <input type="text" name="vp-active-until" id="vp-active-until" value="<?= $vp_active_until ?>">
                    </div>
                </div>
            </div>
            <div id="diary-settings2" class="diary-settings-fields">
                <div class="meta-row">
                    <div class="meta-head">
                        <label for="vp-job-description" class="row-title">"<?= $vp_job_description ?>"</label>
                    </div>
                    <div class="meta-data">
                        <input type="text" name="vp-job-description" id="vp-job-description" value="<?= $vp_job_description ?>">
                    </div>
                </div>
            </div>
            <div id="diary-settings3" class="diary-settings-fields">
                <div class="meta-row">
                    <div class="meta-head">
                        <label for="vp-pre-location" class="row-title">"<?= $vp_pre_location ?>"</label>
                    </div>
                    <div class="meta-data">
                        <input type="text" name="vp-pre-location" id="vp-pre-location" value="<?= $vp_pre_location ?>">
                    </div>
                </div>
            </div>
            <div id="diary-settings4" class="diary-settings-fields">
                <div class="meta-row">
                    <div class="meta-head">
                        <label for="vp-salary-range-text" class="row-title">"<?= $vp_salary_range_text ?>"</label>
                    </div>
                    <div class="meta-data">
                        <input type="text" name="vp-salary-range-text" id="vp-salary-range-text" value="<?= $vp_salary_range_text ?>">
                    </div>
                </div>
            </div>
            <div id="diary-settings5" class="diary-settings-fields">
                <div class="meta-row">
                    <div class="meta-head">
                        <label for="vp-go-back" class="row-title">"<?= $vp_go_back ?>"</label>
                    </div>
                    <div class="meta-data">
                        <input type="text" name="vp-go-back" id="vp-go-back" value="<?= $vp_go_back ?>">
                    </div>
                </div>
            </div>

            <div id="diary-settings3" class="diary-settings-fields">
                <p class="submit"><input type="submit" name="submit" id="submit-translation" class="button button-primary" value="Save Changes"  /></p>
            </div>
        </div>
    </div>

    <hr></hr>

    <div id="diary-sort">
        <div>
            <h2><?php _e('Sort Job Vacancies', 'meta-boxes') ?></h2>

            <?php if ($diary_listing->have_posts()) : ?>
                <p><?php _e('Note: this only effects the listings from shortcodes', 'meta-boxes') ?></p>
                <ul id="custom-type-list">
                    <?php while ($diary_listing->have_posts()) : $diary_listing->the_post(); ?>
                        <li id="<?php the_id(); ?>"><span class="dashicons dashicons-move"></span> <?php the_title(); ?></li>
                        <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p><?php _e('You have no Diaries to sort', 'meta-boxes') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container vp-down">
        <hr>
        <div class="copyright">
            <p>Plugin Developed by <a href="http://www.givemejobtoday.com" target="_blank">Vladan Paunovic</a></p>
        </div>
        <div class="copyright">
            <p>If you think that some features are missing here <a href="mailto:vladan.paunovic.bg@gmail.com" target="_blank">contact me</a> and I will update plugin in order to make it better.</p>
        </div>
        <div class="copyright">
            <p>And if you like it you can always buy me a beer.</p>
        </div>
    </div>


<?php
}

