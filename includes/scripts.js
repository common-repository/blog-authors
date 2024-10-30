jQuery(document).ready(function() {
    "use strict";
    if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {
        jQuery('.select_color').wpColorPicker();
    } else {
        jQuery('#colorpicker').farbtastic('.select_color');
    }
    jQuery(".imageradio").change(function() {
        if (jQuery('#radio_example_two2').is(':checked')) {
            jQuery(".cardchecked").attr('disabled', null);
        } else {
            jQuery(".cardchecked").attr('disabled', 'disabled');
        }
    });
    jQuery("#avatar_check").change(function() {
        if (!this.checked) {
            jQuery("#square").attr('disabled', 'disabled');
            jQuery("#circle").attr('disabled', 'disabled');
        }
        if (this.checked) {
            jQuery("#square").attr('disabled', null);
            jQuery("#circle").attr('disabled', null);
        }
    });
    jQuery("#card_avatar_check").change(function() {
        if (!this.checked) {
            jQuery("#card_circle").attr('disabled', 'disabled');
            jQuery("#card_square").attr('disabled', 'disabled');
        }
        if (this.checked) {
            jQuery("#card_circle").attr('disabled', null);
            jQuery("#card_square").attr('disabled', null);
        }
    });
});