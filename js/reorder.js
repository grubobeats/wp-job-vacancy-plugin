/**
 * Created by vladan on 10/09/16.
 */

jQuery(document).ready(function(){
    console.log('I Dragise je stigao');

    var vp_sortdiaries = jQuery('ul#custom-type-list');
    var vp_animation = jQuery('#vp-loading-animation');
    var pageTitle = jQuery('div h2:first');
    vp_success_message = "<div id='message' class='updated'><p>" + WP_DIARY_LIST.success + "</p></div>";
    vp_error_message = "<div id='message' class='error'><p><strong>Error:</strong>" + WP_DIARY_LIST.error + "</p></div>";

    vp_sortdiaries.sortable({
        update: function(event, ui) {
            vp_animation.show();

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_post',
                    order: vp_sortdiaries.sortable('toArray'),
                    security: WP_DIARY_LIST.security
                },
                success: function( response ) {
                    //jQuery('div#message').remove();
                    vp_animation.hide();
                    if (true === response.success) {
                        pageTitle.after( vp_success_message );
                        jQuery('div#message').delay(1500).hide('slow');
                    } else {
                        pageTitle.after( vp_error_message );
                        jQuery('div#message').delay(1500).hide('slow');
                    }
                },
                error: function ( error ) {
                    //jQuery('div#message').remove();
                    console.log('error');
                    vp_animation.hide();
                    pageTitle.after( vp_error_message );
                }
            });
        }
    });

    jQuery('#submit-translation').click(function(){
        vp_animation.show();
        //save changes from translate
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'vp_save_translations',
                translation1: jQuery('#vp-active-until').val(),
                translation2: jQuery('#vp-job-description').val(),
                translation3: jQuery('#vp-pre-location').val(),
                translation4: jQuery('#vp-go-back').val(),
                translation5: jQuery('#vp-salary-range-text').val()
            },
            success: function( response ) {
                vp_animation.hide();
                pageTitle.after( vp_success_message );
                jQuery('div#message').delay(1500).hide('slow');
            },
            error: function ( error ) {
                console.log('error');
            }
        });
    });

});

