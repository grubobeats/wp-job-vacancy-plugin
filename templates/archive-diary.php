<?php
get_header();

/**
 * Created by PhpStorm.
 * User: vladan
 * Date: 12.09.16
 * Time: 12:22
 */

//This is simple fetching post meta
$job_fetch_meta = get_post_meta( get_the_ID() );
$job_active_date = get_post_meta( get_the_ID(), 'vp-date' );
$job_description = get_post_meta( get_the_ID(), 'vp-description' );

?>
<pre>
    <?php var_dump($job_fetch_meta) ?>
</pre>
<div class="container">
    <div class="job-listing">
        <div class="job-data">
            <h1><?php the_title(); ?></h1>
            <p class="job-date">Active until: <?= $job_active_date[0]; ?></p>
        </div>
        <div class="job-data">
            <h3>Job description</h3>
            <p><?= $job_description[0]; ?></p>
        </div>
    </div>
</div>












<?php get_footer(); ?>
