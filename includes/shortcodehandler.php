<?php
function ba_get_avatar($ba_id, $ba_style_class)
{
    $ba_size  = '96';
    $ba_email = '';
    if (is_numeric($ba_id)) {
        $ba_user = get_userdata($ba_id);
        if ($ba_user) {
            $ba_email = $ba_user->user_email;
        }
    }
    $ba_avatar_default = get_option('avatar_default');
    if (empty($avatar_default))
        $ba_default = 'mystery';
    else
        $ba_default = $ba_avatar_default;
    if (!empty($ba_email))
        $ba_email_hash = md5(strtolower(trim($ba_email)));
    if (is_ssl()) {
        $ba_host = 'https://secure.gravatar.com';
    } else {
        if (!empty($ba_email))
            $ba_host = sprintf("http://%d.gravatar.com", (hexdec($ba_email_hash[0]) % 2));
        else
            $ba_host = 'http://0.gravatar.com';
    }
    if ('mystery' == $ba_default)
        $ba_default = "$ba_host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$ba_size}";
    elseif ('blank' == $ba_default)
        $ba_default = $ba_email ? 'blank' : includes_url('images/blank.gif');
    elseif ('gravatar_default' == $ba_default)
        $ba_default = "$ba_host/avatar/?s={$ba_size}";
    elseif (!empty($ba_email) && 'gravatar_default' == $ba_default)
        $ba_default = '';
    elseif (empty($ba_email))
        $ba_default = "$ba_host/avatar/?d=$ba_default&amp;s={$ba_size}";
    if (!empty($ba_email)) {
        $ba_print = "$ba_host/avatar/";
        $ba_print .= $ba_email_hash;
        $ba_print .= '?s=' . $ba_size;
        $ba_print .= '&amp;d=' . urlencode($ba_default);
        $rating = get_option('avatar_rating');
        if (!empty($rating))
            $ba_print .= "&amp;r={$rating}";
        $ba_print = str_replace('&#038;', '&amp;', esc_url($ba_print));
        $avatar   = "<img src='{$ba_print}' class='" . $ba_style_class . "'/>";
    } else {
        $avatar = "<img src='{$ba_default}' class='" . $ba_style_class . "'/>";
    }
    return $avatar;
}
//Handles the shortcode
add_shortcode('bauthors', 'bashortcode');

function bashortcode(){
	wp_enqueue_script("jquery");
    wp_enqueue_style('design-style', plugins_url('stylesheet.css', __FILE__));
    wp_enqueue_script('ba-card-script', plugins_url('bacardscript.js', __FILE__));
    global $wpdb;
    global $post;
    $ba_table            = $wpdb->prefix . "blog_authors";
    $ba_authorsdbquery   = $wpdb->get_results('SELECT * FROM ' . $ba_table . ' ORDER BY ordr', ARRAY_A);
    $ba_displaystyle     = get_option('display_style', 1);
    $ba_avatarenabled    = get_option('avatar_check', 1);
    $ba_roledisplay      = get_option('display_role');
    $ba_columns          = get_option('card_columns') + 1;
    $ba_usercolor        = get_option('user_color');
    $ba_fontcolor        = get_option('font_color');
    $ba_cornerstatus     = get_option('corner_style', 1);
    $ba_background_style = get_option('background_style', 2);
    $ba_shadow_enabled   = get_option('shadow_enabled', 1);
    $ba_html             = "";
    $ba_number           = 1;
    $cornerstyle         = '';
    $ba_shadow_color     = "";
    if ($ba_shadow_enabled == 1) {
        $ba_color_shadow = get_option('shadow_color', '#e1b703');
        $ba_shadow_color = "-webkit-box-shadow: 0px 0px 5px 0px " . $ba_color_shadow . ";
-moz-box-shadow:    0px 0px 5px 0px " . $ba_color_shadow . ";
box-shadow:         0px 0px 5px 0px " . $ba_color_shadow . ";";
    }
    if ($ba_displaystyle == 1) {
        wp_enqueue_script('masonry-jquery', plugins_url('masonry.pkgd.min.js', __FILE__));
        wp_enqueue_script('images-loaded-jquery', plugins_url('imagesloaded.pkgd.min.js', __FILE__));
        wp_enqueue_script('user-script', plugins_url('script.js', __FILE__));
    }
    if ($ba_cornerstatus == 1) {
        $cornerstyle = 'curved';
    }
    if ($ba_background_style == 5) {
        $ba_backgroundcolor     = get_option('background_color', '#aee5da');
        $background_style_class = 'class="' . $cornerstyle . 'innercard"';
    } else {
        $filenames              = array(
            "bg-blue",
            "bg-green",
            "bg-yellow",
            "bg-gold",
            "bg-purple"
        );
        $background_style_class = 'class="' . $cornerstyle . $filenames[$ba_background_style] . '"';
        $ba_backgroundcolor     = '';
    }
    $ba_folder = get_option('social_networking', 3);
    if (!($ba_folder == 3)) {
        $ba_folders   = array(
            "squares",
            "circles",
            "hexagon"
        );
        $ba_dir       = plugins_url('includes/images/', __DIR__);
        $ba_names     = array(
            "facebook",
            "flickr",
            "google",
            "linkedin",
            "twitter",
            "youtube"
        );
        $ba_linknames = array(
            "facebook",
            "flickr",
            "plus.google",
            "linkedin",
            "twitter",
            "youtube"
        );
    }
    $avatarshape = get_option('avatar_shape');
    if ($avatarshape == 2) {
        $avatarshapehtml = 'square';
    } else {
        $avatarshapehtml = 'circle';
    }
    if ($ba_cornerstatus == 1) {
        $cornerstyle = 'class="curved"';
    } else {
        $cornerstyle = 'class="streightedge"';
    }
    if ($ba_displaystyle == 2 || $ba_displaystyle == 3) {
        if ($ba_cornerstatus == 1) {
            $cornerstyle = 'class="curved"';
        }
        if ($ba_displaystyle == 3) {
            $width = 'style="width: 100%;"';
        } else if ($ba_columns == 2) {
            $width = 'style="width: 50%;"';
        } else if ($ba_columns == 3) {
            $width = 'style="width: 33.333%;"';
        } else if ($ba_columns == 4) {
            $width = 'style="width: 25%;"';
        }
        if ($ba_authorsdbquery == NULL) {
            echo 'You need to add authors!';
        }
    }
    $ba_html .= '<div class="ba_container" >';
    foreach ($ba_authorsdbquery as $row) {
        //gets information
        $ba_id = (int) $row['id'];
        if ($ba_displaystyle == 2 || $ba_displaystyle == 3) {
            $ba_html .= '<div class="cardlayout" ' . $width . '>';
        } else {
            $width = "";
            $ba_html .= '<div class="auto" ' . $width . '>';
        }
        $ba_html .= '<div ' . $background_style_class . ' style="background-color: ' . $ba_backgroundcolor . '; ' . $ba_shadow_color . ';">';
        $ba_html .= '<div class="maintop">';
        if ($ba_avatarenabled == 1) {
            $ba_html .= '<div class="ba_avatarholder">';
            $ba_html .= ba_get_avatar($ba_id, $avatarshapehtml);
            $ba_html .= '</div>';
        }
        $ba_html .= '<span class="firstname" style="color:' . $ba_usercolor . '; ">';
        $ba_html .= get_userdata($ba_id)->user_firstname;
        $ba_html .= '</span>';
        $ba_html .= '<br>';
        if ($ba_roledisplay == 1) {
            $ba_html .= '<span class="role" style="color:' . $ba_usercolor . '; ">';
            $ba_html .= get_user_meta($ba_id, 'userrole', true);
            ;
            $ba_html .= '</span>';
        }
        $ba_html .= '<br>';
        if (!($ba_folder == 3)) {
            for ($i = 0; $i < count($ba_names); $i++) {
                $ba_empty = get_user_meta($ba_id, $ba_names[$i] . 'ba', true);
                if (!empty($ba_empty)) {
                    $ba_empty = "http://" . $ba_linknames[$i] . ".com/" . $ba_empty;
                    $ba_html .= '<a href="' . $ba_empty . '" rel="nofollow">';
                    $ba_html .= '<div class="ba' . $ba_folders[$ba_folder] . '" id="ba' . $ba_names[$i] . '"></div>';
                    $ba_html .= '</a>';
                }
            }
        }
        $ba_html .= '</div>';
        if (get_option('recent_check', 1) == 1) {
            $ba_html .= '<div class="tabContainer">';
            $ba_html .= '<div class="tabs">';
            $ba_html .= '<ul class="tabul">';
            $ba_html .= '<li class="mytabs" id="mytabs' . $ba_number . '">Bio</li>';
            $ba_number = $ba_number + 1;
            $ba_html .= '<li class="mytabs" id="mytabs' . $ba_number . '">Latest Posts</li>';
            $ba_html .= '</ul>';
            $ba_html .= '</div>';
            $ba_html .= '<div class="tabscontent">';
            $ba_number = $ba_number - 1;
            $ba_html .= '<div class="tabpage" id="tabpage_mytabs' . $ba_number . '">';
            $ba_html .= '<h2>About</h2>';
            $ba_html .= '<span class="discription" style="color:' . get_option('font_color', '#0a0a0a') . '; ">';
            $ba_html .= get_userdata($ba_id)->user_description;
            $ba_html .= '</span>';
            $ba_html .= '</div>';
            $ba_number = $ba_number + 1;
            $ba_html .= '<div class="tabpage_latest" id="tabpage_mytabs' . $ba_number . '">';
            $ba_html .= '<h2>Latest Posts</h2>';
            $args    = array(
                'posts_per_page' => 10,
                'offset' => 1,
                'orderby' => 'post_date',
                'post_type' => 'post',
                'author' => $ba_id
            );
            $myposts = get_posts($args);
            foreach ($myposts as $post):
                setup_postdata($post);
                $ba_html .= '<div class="recentpostsbox">';
                ob_start();
                echo '<a href="';
                the_permalink();
                echo '">';
                the_title();
                echo '</a>';
                $myStr = ob_get_contents();
                ob_end_clean();
                $ba_html .= $myStr;
                $ba_html .= '</div>';
                $ba_html .= '<hr class="linestyle">';
            endforeach;
            $ba_html .= '</div>';
            $ba_html .= '</div>';
            $ba_html .= '</div>';
            $ba_number = $ba_number + 1;
        } else {
            $ba_html .= '<div class="content">';
            $ba_html .= '<span class="discription" style="color:' . $ba_fontcolor . '; ">';
            $ba_html .= get_userdata($ba_id)->user_description;
            $ba_html .= '</span>';
            $ba_html .= '</div>';
        }
        $ba_html .= '</div>';
        $ba_html .= '</div>';
    }
    $ba_html .= '</div>';
    return $ba_html;
}
?>