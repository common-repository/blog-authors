<!DOCTYPE html>
<html>
    <head>      
  </head> 
  <body>
<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
global $wpdb;
    $ba_table  = $wpdb->prefix . "blog_authors";
    $ba_myrows = NULL;
        $ba_id = $_GET['id'];
            $ba_myrows = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', $ba_id));
            if ($ba_myrows < 1) {
wp_die(__('Error. User is not added.'));
}

?>
<link rel="stylesheet" id="design-style-css" href="<?php echo plugins_url('/stylesheet.css', __FILE__); ?>" type="text/css" media="all">
<?php

         global $post;
        $ba_background_style = get_option('card_background_style', 0);
        $ba_border_style = get_option('card_border_style', 1);
        $ba_background_color = get_option('card_border_color', '#aee5da');
        $ba_avatar_check = get_option('card_avatar_check', 1);
        $ba_user_color = get_option('card_user_color', '#0a0a0a');
        $ba_shadow_enabled = get_option('card_shadow_enabled',1);
        $ba_cornerstatus = get_option('card_corner_style', 1);
        $ba_number = 1;
        $cornerstyle         = '';
        if ($ba_cornerstatus == 1) {
            $cornerstyle = 'curved';
        }
        $shadowcolor = "";
        if($ba_shadow_enabled == 1){
        $ba_color_shadow = get_option('card_shadow_color', '#aee5da');
                $ba_shadow_color = "-webkit-box-shadow: 0px 0px 5px 0px " . $ba_color_shadow . ";
-moz-box-shadow:    0px 0px 5px 0px " . $ba_color_shadow . ";
box-shadow:         0px 0px 5px 0px " . $ba_color_shadow . ";";
        }
        if ($ba_background_style == 5) {
            $ba_backgroundcolor  = 'background-color:' . get_option('card_background_color', '#aee5da') . '; ';
            $background_style_id = 'class="' . $cornerstyle . 'innercard"';
        } else {
            $filenames           = array(
                "bg-blue",
                "bg-green",
                "bg-yellow",
                "bg-gold",
                "bg-purple"
            );
            $background_style_id = 'class="' . $cornerstyle . $filenames[$ba_background_style] . '"';
            $ba_backgroundcolor  = '';
        }
        if ($ba_border_style != 1) {
            $borederstyles = array(
                "",
                "solid",
                "dashed",
                "double",
                "groove",
                "ridge",
                "inset",
                "outset"
            );
            $border        = 'border:' . $borederstyles[$ba_border_style - 1] . '; ' . 'border-color:' . $ba_background_color . '; ';
        }
        $border = "";
        $avatarshape = get_option('card_avatar_shape');
        if ($avatarshape == 2) {
            $avatarshapehtml = 'square';
        } else {
            $avatarshapehtml = 'circle';
        }
        $ba_html  = '<div ' . $background_style_id;
        $ba_html .= ' style="';
        $ba_html .= $border;
        $ba_html .= ' ';
        $ba_html .= $ba_backgroundcolor;
        $ba_html .= ' ';
        $ba_html .= $ba_shadow_color;
        $ba_html .= '"';
        $ba_html .= '>';
        $ba_html .= '<div class="ba_card_inner">';
        $ba_html .= '<div class="ba_card_top">';
        if ($ba_avatar_check == 1) {
            $ba_html .= '<div class="ba_avatarholder">';
            $ba_html .= ba_get_avatar($ba_id, $avatarshapehtml);
            $ba_html .= '</div>';
        }
        $ba_html .= '<div class="ba_card_main">';
        $ba_html .= '<span class="firstname" style="color:' . $ba_user_color . '; ">';
        $ba_html .= get_userdata($ba_id)->user_firstname;
        $ba_html .= '</span>';
        $ba_html .= '<br>';
        if (checked(1, get_option('card_display_role'), false)) {
            $ba_html .= '<span class="role" style="color:' . $ba_user_color . '; ">';
            $ba_html .= get_user_meta($ba_id, 'userrole', true);
            $ba_html .= '</span>';
        }
        $ba_html .= '<br>';
        $ba_folder = get_option('card_social_networking', 3);
        if ($ba_folder != 3) {
            $ba_folders = array(
                "squares",
                "circles",
                "hexagon"
            );
            $ba_dir     = plugins_url('includes/images/', __DIR__);
            $ba_names   = array(
                "facebook",
                "flickr",
                "google",
                "linkedin",
                "twitter",
                "youtube"
            );
	    $ba_linknames        = array(
	        "facebook",
	        "flickr",
	        "plus.google",
	        "linkedin",
	        "twitter",
	        "youtube"
	    );
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
        $ba_html .= '</div>';
        if (get_option('card_recent_check', 1) == 1) {
            $ba_html .= '<div id="tabContainer">';
            $ba_html .= '<div class="tabs">';
            $ba_html .= '<ul class="tabul">';
            $ba_html .= '<li class="mytabs" id="mytabs'. $ba_number .'">Bio</li>';
            $ba_number = $ba_number + 1;
            $ba_html .= '<li class="mytabs" id="mytabs'. $ba_number .'">Latest Posts</li>';
            $ba_html .= '</ul>';
            $ba_html .= '</div>';
            $ba_html .= '<div class="tabscurved">';
            $ba_number = $ba_number - 1;
            $ba_html .= '<div class="tabpage" id="tabpage_mytabs'. $ba_number .'">';
            $ba_html .= '<h2>About</h2>';
            $ba_html .= '<span class="discription" style="color:' . get_option('card_font_color', '#0a0a0a') . '; ">';
            $ba_html .= get_userdata($ba_id)->user_description;
            $ba_html .= '</span>';
            $ba_html .= '</div>';
            $ba_number = $ba_number + 1;
            $ba_html .= '<div class="tabpage_latest" id="tabpage_mytabs'. $ba_number .'">';
            $ba_html .= '<h2>Latest Posts</h2>';
            $args    = array(
                'posts_per_page' => 10,
                'offset' => 1,
                'orderby'          => 'post_date',
                'post_type'        => 'post',
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
        } else {
                    $ba_html .= '<div class="tabscurved" id="tabscontent">';
            $ba_html .= '<span class="discription" style="color:' . get_option('card_font_color', '#0a0a0a') . '; ">';
            $ba_html .= get_userdata($ba_id)->user_description;
            $ba_html .= '</span>';
                    $ba_html .= '</div>';
        }
        $ba_html .= '</div>';
        $ba_html .= '</div>';
        $ba_html .= '</div>';
            echo $ba_html;

?>
<script type="text/javascript" src="<?php echo site_url( '/wp-includes/js/jquery/jquery.js?ver=1.11.0'); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('/bacardscript.js', __FILE__); ?>"></script>
  </body>
  </html>