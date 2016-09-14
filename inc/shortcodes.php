<?php
/*
 * Description: Custom shortcodes
 * @author: Vladan Paunovic - http://www.givemejobtaday.com
 * @info:
 *      File: shortcodes.php
 *      Edited: 13.09.16 at 9:46h
 */

function vp_shortcode_one($atts) {

    if (!isset($atts['location'])) {
        return "<p class='job-error'>You must provide location</p>";
    }

    $vp_pre_location = get_option('vp-translation3','Current job oppens in');
    $vp_active_until = get_option('vp-translation1', 'Active Until');

    $atts = shortcode_atts(
        array(
            'title' => $vp_pre_location,
            'count' => 5,
            'location' => '',
            'pagination' => false
        ), $atts
    );

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = array(
        'post_type'         => 'job',
        'post_status'       => 'publish',
        'no_found_rows'     => $atts['pagination'],
        'posts_per_page'    => $atts['count'],
        'paged'             => $paged,
        'author'            => get_current_user_id(),
        'orderby'           => 'menu_order',
        'tax_query'         => array(
            array(
                'taxonomy'  => 'location',
                'field'     => 'slug',
                'terms'     => $atts['location']
            )
        )
    );

    $jobs_by_location = new WP_Query( $args );

    if($jobs_by_location->have_posts()) {
        $location = str_replace( '-', ' ', $atts['location'] );

        $display_by_location = "<div id=\"jobs-by-location\">";
        $display_by_location .= "<h4>" . $atts['title'] . " " . ucwords($location);
        $display_by_location .= "<ul>";

        while ($jobs_by_location->have_posts()) :

            $jobs_by_location->the_post();

            $deathline = get_post_meta(get_the_ID(), 'vp-date', true);
            $title = get_the_title();
            $slug = get_permalink();

            $display_by_location .= "<li class=\"job-listing\">";
            $display_by_location .= sprintf('<a href="%s">%s</a>&nbsp&nbsp', $slug, $title);
            $display_by_location .= "<span>" . $vp_active_until . " </span>";
            $display_by_location .= "<span>" . $deathline . "</span>";
            $display_by_location .= "</li>";

        endwhile;

        $display_by_location .= "</ul></div>";
    } else {
        $display_by_location = "You don't have job vacancies available in this city.";
    }

    wp_reset_postdata();

    // Pagination
    if ( $jobs_by_location->max_num_pages > 1  && is_page() ) {
        $display_by_location .= '<nav class="prev-next-posts">';

        $display_by_location .= '<div class="next-posts-link">';
        $display_by_location .= get_previous_posts_link( __( '<span class="meta-nav">Previous</span>') );
        $display_by_location .= '</div>';

        $display_by_location .= '<div call="nav-pervious">';
        $display_by_location .= get_next_posts_link( __( '<span class="meta-nav">Next</span>'  ), $jobs_by_location->max_num_pages );
        $display_by_location .= '</div';

        $display_by_location .= '</nav>';
    }

    return $display_by_location;
}

add_shortcode('jobs_listing', 'vp_shortcode_one');