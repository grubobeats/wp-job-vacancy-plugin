<?php
get_header();

/**
 * Created by PhpStorm.
 * User: vladan
 * Date: 12.09.16
 * Time: 12:22
 */

// This is simple fetching post meta

$job_active_date = get_post_meta( get_the_ID(), 'vp-date' );
$job_description = get_post_meta( get_the_ID(), 'vp-description' );
$job_salary_from = get_post_meta( get_the_ID(), 'vp-salary-from' );
$job_salary_to = get_post_meta( get_the_ID(), 'vp-salary-to' );

// Translate Captions
$vp_active_until = get_option('vp-translation1', 'Active Until');
$vp_job_description = get_option('vp-translation2','Job Description');
$vp_go_back = get_option('vp-translation4','<- Go Back');
$vp_salary_range_text = get_option('vp_salary_range_text','Salary range');
?>

<div class="container">
    <div class="job-listing">
        <div class="job-data">
            <h1><?php the_title(); ?></h1>
            <p class="job-date"><?= $vp_active_until ?> <?= $job_active_date[0]; ?></p>
        </div>
        <div class="job-data">
            <h3><?= $vp_job_description ?></h3>
            <?= $job_description[0]; ?>
        </div>
        <div class="job-salary">
            <?php
            if ( (isset($job_salary_from[0]) && !empty($job_salary_from[0])) && (isset($job_salary_to[0]) && !empty($job_salary_to[0])) ) {
                ?>
                <h3><?= $vp_salary_range_text ?></h3>
                <ul class="vp-salary-from-to">
                    <li class="vp-salary-data"><?= $job_salary_from[0] ?></li>
                    <li class="vp-salary-data"> - </li>
                    <li class="vp-salary-data"><?= $job_salary_to[0] ?></li>
                </ul>
                <?php
            }
            ?>
        </div>
        <a href="javascript:history.go(-1)"><?= $vp_go_back ?></a>
    </div>
    <div style="padding-bottom: 30px;"></div>

</div>












<?php get_footer(); ?>
