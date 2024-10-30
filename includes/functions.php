<?php
add_action('admin_init', 'authors_register_settings');
//Loads the color picker
function mw_enqueue_color_picker($hook_suffix) {
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
//Registers settings for the blog authors page
function authors_register_settings() {
    $ba_name    = 'ba_settings';
    $ba_page    = 'blog-authors';
    $ba_section = 'default';
    add_settings_section('ba_settings_section', '', 'ba_setting_title', $ba_page);
    $ba_settingids      = ba_settingids('blog-authors');
    $ba_settingname     = array(
        'Display Style',
        'Number of Card Columns',
        'Avatar Enabled',
        'Avatar Shape',
        'Display Role',
        'Social Networking Icons',
        'Font Color',
        'User Color',
        'Background Style',
        'Background Color',
        'Corner Style',
        'Display Recent Posts',
        'Shadow Enabled',
        'Shadow Color'
    );
    $ba_settingcallback = array(
        'display_style_callback',
        'card_columns_callback',
        'avatar_check_callback',
        'avatar_shape_callback',
        'display_role_callback',
        'social_networking_toggle_callback',
        'font_color_callback',
        'user_color_callback',
        'background_style_callback',
        'background_color_callback',
        'corner_style_callback',
        'recent_check_callback',
        'shadow_enabled_callback',
        'shadow_color_callback'
    );
    for ($i = 0; $i < count($ba_settingids); ++$i) {
        add_settings_field($ba_settingids[$i], $ba_settingname[$i], $ba_settingcallback[$i], $ba_page, 'ba_settings_section');
    }
    register_setting($ba_page, 'display_style', 'display_style_validate');
    register_setting($ba_page, 'card_columns', 'card_columns_validate');
    register_setting($ba_page, 'avatar_check', 'avatar_check_validate');
    register_setting($ba_page, 'avatar_shape', 'avatar_shape_validate');
    register_setting($ba_page, 'display_role', 'display_role_validate');
    register_setting($ba_page, 'social_networking', 'social_networking_validate');
    register_setting($ba_page, 'font_color');
    register_setting($ba_page, 'user_color');
    register_setting($ba_page, 'background_style');
    register_setting($ba_page, 'background_color');
    register_setting($ba_page, 'corner_style', 'corner_style_validate');
    register_setting($ba_page, 'recent_check');
    register_setting($ba_page, 'shadow_enabled');
    register_setting($ba_page, 'shadow_color');
}
function display_style_validate($ba_input) {
    if ($ba_input > 0 && $ba_input < 4) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('display_style');
    }
    return $ba_newinput;
}
function card_columns_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == 2 || $ba_input == 3) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('card_columns');
    }
    return $ba_newinput;
}
function avatar_check_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == null) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('avatar_check');
    }
    return $ba_newinput;
}
function avatar_shape_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == 2) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('avatar_shape');
    }
    return $ba_newinput;
}
function display_role_validate($ba_input) {
    if ($ba_input == 1 || $ba_input == null) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('display_role');
    }
    return $ba_newinput;
}
function social_networking_validate($ba_input) {
    if ($ba_input >= 0 && $ba_input < 4) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('social_networking');
    }
    return $ba_newinput;
}
function corner_style_validate($ba_input) {
    if ($ba_input == 1 || $ba_input = 2) {
        $ba_newinput = $ba_input;
    } else {
        $ba_newinput = get_option('corner_style');
    }
    return $ba_newinput;
}
function ba_validator($ba_value) {
    return $ba_value;
}
function ba_setting_title() {
    echo '<i>To add the Blog Authors to a page use the shortcode [bauthors]</i>';
}
function display_style_callback() {
    $ba_html = '<label class="imageradio"  for="radio_example_one1" style="background: url(' . plugins_url('/images/cards_2.png', __FILE__) . ') no-repeat 100%;
background-size: 100%; margin-top: 20px;"><input type="radio" id="radio_example_one1" class="imagebutton" name="display_style" value="1"' . checked(1, get_option('display_style', 1), false) . '/></label>';
    $ba_html .= '<label class="imageradio" for="radio_example_two2" style="background: url(' . plugins_url('/images/cards.png)', __FILE__) . ' no-repeat 100%;
background-size: 100%; margin-top: 20px;"><input type="radio" id="radio_example_two2" name="display_style" class="imagebutton" value="2"' . checked(2, get_option('display_style', 1), false) . '/></label>';
    $ba_html .= '<label class="imageradio" for="radio_example_three3" style="background: url(' . plugins_url('/images/rows.png)', __FILE__) . ' no-repeat 100%;
background-size: 100%; margin-top: 20px;"><input type="radio" id="radio_example_three3" name="display_style" class="imagebutton" value="3"' . checked(3, get_option('display_style', 1), false) . '/></label>';
    echo $ba_html;
}
function card_columns_callback() {
    if (get_option('display_style', 1) == 1 || get_option('display_style', 1) == 3) {
        $ba_disabled = ' disabled ';
    } else {
        $ba_disabled = "";
    }
    $ba_html = '<input type="radio" id="twoclumn" class="cardchecked" name="card_columns" value="1"' . checked(1, get_option('card_columns', 1), false) . $ba_disabled . '/>';
    $ba_html .= '<label for="twoclumn">Two</label>';
    $ba_html .= '<input type="radio" id="threecolumn" class="cardchecked" name="card_columns" value="2"' . checked(2, get_option('card_columns', 1), false) . $ba_disabled . '/>';
    $ba_html .= '<label for="threecolumn">Three</label>';
    $ba_html .= '<input type="radio" id="fourcolumn" class="cardchecked" name="card_columns" value="3"' . checked(3, get_option('card_columns', 1), false) . $ba_disabled . '/>';
    $ba_html .= '<label for="fourcolumn">Four</label>';
    echo $ba_html;
}
function avatar_check_callback() {
    echo '<input type="checkbox" id="avatar_check" name="avatar_check" value="1" ' . checked(1, get_option('avatar_check', 1), false) . '/>';
}
function avatar_shape_callback() {
    if (get_option('avatar_check', 1) != 1) {
        $ba_disabled = ' disabled ';
    } else {
        $ba_disabled = "";
    }
    $ba_html = '<label class="circlesquare"  for="circle" style="background: url(' . plugins_url('/images/64circle.png', __FILE__) . ') no-repeat;
"><input type="radio" id="circle" class="notchecked" name="avatar_shape" value="1"' . checked(1, get_option('avatar_shape', 1), false) . $ba_disabled . '/></label>';
    $ba_html .= '<label class="circlesquare"  for="square" style="background: url(' . plugins_url('/images/64square.png', __FILE__) . ') no-repeat;
"><input type="radio" id="square" class="notchecked" name="avatar_shape" value="2"' . checked(2, get_option('avatar_shape', 1), false) . $ba_disabled . '/></label>';
    echo $ba_html;
}
function display_role_callback() {
    echo '<input type="checkbox" id="display_role" name="display_role" value="1" ' . checked(1, get_option('display_role'), null, false) . '/>';
}
function social_networking_toggle_callback() {
    $ba_ids   = array(
        "flatsquare",
        "flatcircle",
        "flathexagon"
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
        $ba_html .= '<span class="listsocial"><input type="radio" id="' . $ba_ids[$ba_i] . '" class="socialradios" name="social_networking" value="' . strval($ba_i) . '"' . checked($ba_i, get_option('social_networking', 3), false) . '/>';
        $ba_html .= '<label for="' . $ba_ids[$ba_i] . '">' . $ba_images . '</label></span>';
    }
    $ba_html .= '<span class="listsocial"><input type="radio" id="nosocial" class="socialradios" name="social_networking" value="3"' . checked(3, get_option('social_networking', 3), false) . '/>';
    $ba_html .= '<label for="nosocial">Disabled</label></span>';
    echo $ba_html;
}
function font_color_callback() {
    $ba_html = '<input id="font_color" name="font_color" class="select_color" type="text" value="' . get_option('font_color', '#0a0a0a') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function user_color_callback() {
    $ba_html = '<input id="user_color" name="user_color" class="select_color" type="text" value="' . get_option('user_color', '#0a0a0a') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function background_style_callback() {
    $filenames = array(
        "bg-blue",
        "bg-green",
        "bg-yellow",
        "bg-gold",
        "bg-purple"
    );
    $ba_dir    = plugins_url('includes/images/', __DIR__);
    $ba_html   = "";
    $ba_html .= '<div class="checkboxes">';
    for ($ba_i = 0; $ba_i < 5; $ba_i++) {
        $ba_html .= '<div class="checkboxgroup">';
        $ba_images = '<img src="' . $ba_dir . $filenames[$ba_i] . '.jpg" style="width: 100px;" alt="Background image"/>';
        $ba_html .= '<label for="' . $filenames[$ba_i] . '">' . $ba_images . '</label>';
        $ba_html .= '<input type="radio" id="' . $filenames[$ba_i] . '" class="backgroundstyle" name="background_style" value="' . strval($ba_i) . '"' . checked($ba_i, get_option('background_style', 2), false) . '/>';
        $ba_html .= '</div>';
    }
    $ba_html .= '<div class="checkboxgroup">';
    $ba_html .= '<span class="listsocial"><input type="radio" id="solidcolor" class="backgroundstyle" name="background_style" value="5"' . checked(5, get_option('background_style', 2), false) . '/>';
    $ba_html .= '<label for="solidcolor">Solid Color <i>Choose Below</i></label></span>';
    $ba_html .= '</div>';
    $ba_html .= '</div>';
    echo $ba_html;
}
function background_color_callback() {
    $ba_html = '<input id="background_color" name="background_color" class="select_color" type="text" value="' . get_option('background_color', '#aee5da') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
function corner_style_callback() {
    $ba_html = '<label class="corner"  for="corner_style_one1" style="background: url(' . plugins_url('/images/curved-corner.png', __FILE__) . ') no-repeat;
"><input type="radio" id="corner_style_one1" class="cornerstyle" name="corner_style" value="1"' . checked(1, get_option('corner_style', 1), false) . '/></label>';
    $ba_html .= '<label class="corner" for="corner_style_two2" style="background: url(' . plugins_url('/images/no-corner.png)', __FILE__) . ' no-repeat;
"><input type="radio" id="corner_style_two2" name="corner_style" class="cornerstyle" value="2"' . checked(2, get_option('corner_style', 1), false) . '/></label>';
    echo $ba_html;
}
function recent_check_callback() {
    echo '<input type="checkbox" id="recent_check" name="recent_check" value="1" ' . checked(1, get_option('recent_check', 1), false) . '/>';
}
function shadow_enabled_callback() {
    echo '<input type="checkbox" id="shadow_enabled" name="shadow_enabled" value="1" ' . checked(1, get_option('shadow_enabled', 1), false) . '/>';
}
function shadow_color_callback() {
    $ba_html = '<input id="shadow_color" name="shadow_color" class="select_color" type="text" value="' . get_option('shadow_color', '#e1b703') . '" />
<div id="colorpicker"></div>';
    echo $ba_html;
}
?>