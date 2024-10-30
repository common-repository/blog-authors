<?php
add_action('admin_init', 'ba_authors_card_register_settings');
//Function to check which version of the color picker to load
function ba_enqueue_color_picker($hook_suffix) {
    global $wp_version;
    if (wp_style_is('wp-color-picker', 'registered')) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    } else {
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('farbtastic');
    }
    wp_enqueue_script('ba-color-picker', plugin_dir_url(__FILE__) . '/scripts.js');
}
//Registers the authors card settings
function ba_authors_card_register_settings() {
    $ba_name    = 'ba_settings';
    $ba_page    = 'blog-card';
    $ba_section = 'default';
    add_settings_section('ba_card_settings_section', '', 'ba_card_setting_title', $ba_page);
    $ba_settingids      = ba_settingids('blog-card');
    $ba_settingname     = array(
        'Author Card Enabled',
        'Avatar Enabled',
        'Avatar Shape',
        'Display Role',
        'Social Networking Icons',
        'Font Color',
        'User Color',
        'Background Style',
        'Background Color',
        'Border Style',
        'Border Color',
        'Corner Style',
        'Display Recent Posts',
        'Shadow Enabled',
        'Shadow Color'
    );
    $ba_settingcallback = array(
        'card_enable_callback',
        'card_avatar_check_callback',
        'card_avatar_shape_callback',
        'card_display_role_callback',
        'card_social_networking_toggle_callback',
        'card_font_color_callback',
        'card_user_color_callback',
        'card_background_style_callback',
        'card_background_color_callback',
        'card_border_style_callback',
        'card_border_color_callback',
        'card_corner_style_callback',
        'card_recent_check_callback',
        'card_shadow_enabled_callback',
        'card_shadow_color_callback'
    );
    for ($i = 0; $i < count($ba_settingids); ++$i) {
        add_settings_field($ba_settingids[$i], $ba_settingname[$i], $ba_settingcallback[$i], $ba_page, 'ba_card_settings_section');
    }
    register_setting($ba_page, 'card_enable');
    register_setting($ba_page, 'card_avatar_check', 'ba_card_avatar_check_validate');
    register_setting($ba_page, 'card_avatar_shape', 'card_avatar_shape_validate');
    register_setting($ba_page, 'card_display_role', 'card_display_role_validate');
    register_setting($ba_page, 'card_social_networking', 'card_social_networking_validate');
    register_setting($ba_page, 'card_font_color');
    register_setting($ba_page, 'card_user_color');
    register_setting($ba_page, 'card_background_style');
    register_setting($ba_page, 'card_background_color');
    register_setting($ba_page, 'card_border_style');
    register_setting($ba_page, 'card_border_color');
    register_setting($ba_page, 'card_corner_style');
    register_setting($ba_page, 'card_recent_check');
    register_setting($ba_page, 'card_shadow_enabled');
    register_setting($ba_page, 'card_shadow_color');
}
function ba_card_avatar_check_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == null) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('card_avatar_check');
    }
    return $ba_newinput;
}
function card_avatar_shape_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == 2) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('card_avatar_shape');
    }
    return $ba_newinput;
}
function card_display_role_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == null) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('card_display_role');
    }
    return $ba_newinput;
}
function card_social_networking_validate($ba_input) {
    if ($ba_input >= 0 && $ba_input < 4) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('card_social_networking');
    }
    return $ba_newinput;
}
function ba_card_setting_title() {
    echo '';
}
function card_enable_callback() {
    echo '<input type="checkbox" id="card_enable" name="card_enable" value="1" ' . checked(1, get_option('card_enable', 1), false) . '/>';
}
function card_avatar_check_callback() {
    echo '<input type="checkbox" id="card_avatar_check" name="card_avatar_check" value="1" ' . checked(1, get_option('card_avatar_check', 1), false) . '/>';
}
function card_avatar_shape_callback() {
    if (!(get_option('card_avatar_check', 1) == 1)) {
        $ba_disabled = ' disabled ';
    } else {
        $ba_disabled = "";
    }
    $ba_html = '<label class="circlesquare"  for="card_circle" style="background: url(' . plugins_url('/images/64circle.png', __FILE__) . ') no-repeat;
"><input type="radio" id="card_circle" class="notchecked" name="card_avatar_shape" value="1"' . checked(1, get_option('card_avatar_shape', 1), false) . $ba_disabled . '/></label>';
    $ba_html .= '<label class="circlesquare"  for="card_square" style="background: url(' . plugins_url('/images/64square.png', __FILE__) . ') no-repeat;
"><input type="radio" id="card_square" class="notchecked" name="card_avatar_shape" value="2"' . checked(2, get_option('card_avatar_shape', 1), false) . $ba_disabled . '/></label>';
    echo $ba_html;
}
function card_display_role_callback() {
    echo '<input type="checkbox" id="card_display_role" name="card_display_role" value="1" ' . checked(1, get_option('card_display_role'), null, false) . '/>';
}
function card_social_networking_toggle_callback() {
    $ba_ids   = array(
        "cardflatsquare",
        "cardflatcircle",
        "cardflathexagon"
    );
    $folders  = array(
        "square",
        "circle",
        "hexagon"
    );
    $ba_names = array(
        "facebook",
        "flickr",
        "google",
        "linkedin",
        "twitter",
        "youtube"
    );
    $ba_dir   = plugins_url('includes/images/', __DIR__);
    $ba_html  = "";
    for ($ba_i = 0; $ba_i < 3; $ba_i++) {
        $ba_images = '<img src="' . $ba_dir . $folders[$ba_i] . '.png" alt="Social" width="194.5" height="32"/>';
        $ba_html .= '<span class="listsocial"><input type="radio" id="' . $ba_ids[$ba_i] . '" class="socialradios" name="card_social_networking" value="' . strval($ba_i) . '"' . checked($ba_i, get_option('card_social_networking', 3), false) . '/>';
        $ba_html .= '<label for="' . $ba_ids[$ba_i] . '">' . $ba_images . '</label></span>';
    }
    $ba_html .= '<span class="listsocial"><input type="radio" id="nosocial" class="socialradios" name="card_social_networking" value="3"' . checked(3, get_option('card_social_networking', 3), false) . '/>';
    $ba_html .= '<label for="nosocial">Disabled</label></span>';
    echo $ba_html;
}
function card_font_color_callback() {
    $ba_html = '<input id="card_font_color" name="card_font_color" class="select_color" type="text" value="' . get_option('card_font_color', '#0a0a0a') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function card_user_color_callback() {
    $ba_html = '<input id="card_user_color" name="card_user_color" class="select_color" type="text" value="' . get_option('card_user_color', '#0a0a0a') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function card_background_style_callback() {
    $filenames = array(
        "bg-blue",
        "bg-green",
        "bg-yellow",
        "bg-gold",
        "bg-purple"
    );
    $ba_html   = "";
    $ba_dir    = plugins_url('includes/images/', __DIR__);
    $ba_html .= '<div id="checkboxes">';
    for ($ba_i = 0; $ba_i < 5; $ba_i++) {
        $ba_html .= '<div class="checkboxgroup">';
        $ba_images = '<img src="' . $ba_dir . $filenames[$ba_i] . '.jpg" style="width: 100px;" alt="Background image"/>';
        $ba_html .= '<label for="' . $filenames[$ba_i] . '">' . $ba_images . '</label>';
        $ba_html .= '<input type="radio" id="' . $filenames[$ba_i] . '" class="backgroundstyle" name="card_background_style" value="' . strval($ba_i) . '" ' . checked($ba_i, get_option('card_background_style', 0), false) . '/>';
        $ba_html .= '</div>';
    }
    $ba_html .= '<div class="checkboxgroup">';
    $ba_html .= '<span class="checkbox"><input type="radio" id="solidcolor" class="backgroundstyle" name="card_background_style" value="5" ' . checked(5, get_option('card_background_style', 0), false) . '/>';
    $ba_html .= '<label for="solidcolor">Solid Color <i>Choose Below</i></label></span>';
    $ba_html .= '</div>';
    $ba_html .= '</div>';
    echo $ba_html;
}
function card_background_color_callback() {
    $ba_html = '<input id="card_background_color" name="card_background_color" class="select_color" type="text" value="' . get_option('card_background_color', '#aee5da') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function card_border_style_callback() {
    $ba_html = '<ul><li><label class="card_border_style"  for="card_border_style_one1"><input type="radio" id="card_border_style_one1" class="card_border_style" name="card_border_style" style="float: left;" value="1"' . checked(1, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: none; float: left;">None</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_two2"><input type="radio" id="card_border_style_two2" name="card_border_style" class="card_border_style" style="float: left;" value="2"' . checked(2, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: solid; float: left;">Solid</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_three3"><input type="radio" id="card_border_style_three3" name="card_border_style" class="card_border_style" style="float: left;" value="3"' . checked(3, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: dashed; float: left;">Dashed</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_four4"><input type="radio" id="card_border_style_four4" name="card_border_style" class="card_border_style" style="float: left;" value="4"' . checked(4, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: double; float: left;">Double</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_five5"><input type="radio" id="card_border_style_five5" name="card_border_style" class="card_border_style" style="float: left;" value="5"' . checked(5, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: groove; float: left;">Groove</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_six6"><input type="radio" id="card_border_style_six6" name="card_border_style" class="card_border_style" style="float: left;" value="6"' . checked(6, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: ridge; float: left;">Ridge</div></label></li>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_seven7"><input type="radio" id="card_border_style_seven7" name="card_border_style" class="card_border_style" style="float: left;" value="7"' . checked(7, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: inset; float: left;">Inset</div></label></li><ul>';
    $ba_html .= '<br>';
    $ba_html .= '<br>';
    $ba_html .= '<li><label class="card_border_style" for="card_border_style_eight8"><input type="radio" id="card_border_style_eight8" name="card_border_style" class="card_border_style" style="float: left;" value="8"' . checked(8, get_option('card_border_style', 1), false) . ' /><div style="width: 200px; height 100px;
border: outset; float: left;">Outset</div></label></li></ul>';
    echo $ba_html;
}
function card_border_color_callback() {
    $ba_html = '<input id="card_border_color" name="card_border_color" class="select_color" type="text" value="' . get_option('card_border_color', '#aee5da') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function card_corner_style_callback() {
    $ba_html = '<label class="corner"  for="card_corner_style_one1" style="background: url(' . plugins_url('/images/curved-corner.png', __FILE__) . ') no-repeat;
"><input type="radio" id="card_corner_style_one1" class="cornerstyle" name="card_corner_style" value="1"' . checked(1, get_option('card_corner_style', 1), false) . '/></label>';
    $ba_html .= '<label class="corner" for="card_corner_style_two2" style="background: url(' . plugins_url('/images/no-corner.png)', __FILE__) . ' no-repeat;
"><input type="radio" id="card_corner_style_two2" name="card_corner_style" class="cornerstyle" value="2"' . checked(2, get_option('card_corner_style', 1), false) . '/></label>';
    echo $ba_html;
}
function card_recent_check_callback() {
    echo '<input type="checkbox" id="card_recent_check" name="card_recent_check" value="1" ' . checked(1, get_option('card_recent_check', 1), false) . '/>';
}
function card_recent_color_callback() {
    $ba_html = '<input id="card_recent_color" name="card_recent_color" class="select_color" type="text" value="' . get_option('card_recent_color', '#aee5da') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function card_shadow_enabled_callback() {
    echo '<input type="checkbox" id="card_shadow_enabled" name="card_shadow_enabled" value="1" ' . checked(1, get_option('card_shadow_enabled', 1), false) . '/>';
}
function card_shadow_color_callback() {
    $ba_html = '<input id="card_shadow_color" name="card_shadow_color" class="select_color" type="text" value="' . get_option('card_shadow_color', '#aee5da') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
?>