<?php
/**
 * Created by PhpStorm.
 * User: vladan
 * Date: 09.09.16
 * Time: 13:02
 */


function vp_add_custom_meta() {
    add_meta_box(
        'wp_meta',
        'Add New Diary',
        'vp_meta_callback',
        'job',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'vp_add_custom_meta' );

function vp_save_data( $post_id ) {
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $wp_nounce_post = $_POST['vp_diaries_nounce'];
    $is_valid_nonce = ( isset($wp_nounce_post) && wp_verify_nonce($wp_nounce_post, basename(__FILE__))) ? 'true' : 'false';

    if ($is_autosave || $is_revision) {
        return;
    }

    if (isset( $_POST['date'] )) {
        update_post_meta($post_id, 'vp-date', sanitize_text_field($_POST['date']));
    }

    if (isset( $_POST['vp-salary-from'] )) {
        update_post_meta($post_id, 'vp-salary-from', sanitize_text_field($_POST['vp-salary-from']));
    }

    if (isset( $_POST['vp-salary-to'] )) {
        update_post_meta($post_id, 'vp-salary-to', sanitize_text_field($_POST['vp-salary-to']));
    }

    if ( isset( $_POST[ 'vp-description' ] ) ) {
        update_post_meta( $post_id, 'vp-description', stripslashes( $_POST[ 'vp-description' ] ) );
    }

}

add_action('save_post', 'vp_save_data');


/*
 * Rendering HTML to "Job Vacancies" page
 */
function vp_meta_callback( $post ) {
    wp_nonce_field(basename(__FILE__), 'vp_diaries_nounce');
    $vp_data = get_post_meta($post->ID);

    $data_date_end = ( !empty( $vp_data['vp-date'] ) ) ? esc_attr( $vp_data['vp-date'][0] ) : "";
    $data_salary_from = ( !empty( $vp_data['vp-salary-from'] ) ) ? esc_attr( $vp_data['vp-salary-from'][0] ) : "";
    $data_salary_to = ( !empty( $vp_data['vp-salary-to'] ) ) ? esc_attr( $vp_data['vp-salary-to'][0] ) : "";

    ?>

    <!-- Active Until-->
    <div class="base">
        <div class="meta-row">
            <div class="meta-head">
                <label for="date" class="row-title">Active until</label>
            </div>
            <div class="meta-data">
                <input type="text" class="datepicker" name="date" id="date" value="<?= $data_date_end ?>">
            </div>
        </div>
    </div>
    <hr>
    <!-- /Active Until-->
    <!-- Salary Range-->
    <label for="base" class="row-title">Salary range</label>
    <div style="padding: 10px 0;"></div>
    <div class="base">
        <div class="meta-row">
            <div class="meta-head">
                <label for="vp-salary-from" class="row-title">From</label>
            </div>
            <div class="meta-data">
                <input type="text" name="vp-salary-from" id="vp-salary-from" value="<?= __($data_salary_from) ?>">
            </div>
        </div>
    </div>
    <div class="base">
        <div class="meta-row">
            <div class="meta-head">
                <label for="vp-salary-to" class="row-title">To</label>
            </div>
            <div class="meta-data">
                <input type="text" name="vp-salary-to" id="vp-salary-to" value="<?= __($data_salary_to) ?>">
            </div>
        </div>
    </div>
    <hr>
    <!-- /Salary Range-->
    <!-- Wordpress Text Editor -->
    <div class="base">
        <div class="meta-row">
            <div class="meta-head">
                <label for="diary-description" class="row-title">Job description</label>
            </div>
        </div>
    </div>
    <div class="base">
        <?php
        // Wordpress Text Editor
        $content = get_post_meta($post->ID, 'vp-description', true);
        $editor_id = 'vp-description';
        $settings = array(
            'textarea_rows' => 8,
            'textarea_name' => $editor_id,
            'drag_drop_upload' => true,
            'quicktags' => true,
            'tinymce' => true,
        );

        wp_editor( $content, $editor_id, $settings );
        ?>
    </div>
    <!-- /Wordpress Text Editor -->
    <?php
}
