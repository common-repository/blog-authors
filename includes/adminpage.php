<?php
add_action('admin_menu', 'ba_admin_menu');
add_action('in_admin_footer', 'ba_inline_js');
add_action('wp_ajax_ba_deleter_code', 'ba_deleter_code');
add_action('wp_ajax_ba_arrow_handler', 'ba_arrow_handler');
//Adds a stylesheet to the admin pages
function ba_register_styles_css() {
    add_action('admin_enqueue_scripts', 'ba_enqueue_admin_css');
}
function ba_enqueue_admin_css() {
    wp_enqueue_style('ba_register_styles_css', plugins_url('ba_styles.css', __FILE__));
}
//Adds the color picker to the admin pages
function ba_register_color_picker() {
    add_action('admin_enqueue_scripts', 'ba_enqueue_color_picker');
}
function ba_inline_js() {
    //This script manages the up, down and delete arrows on the table
?>
<script type="text/javascript"> 


		ajax_url = "<?php
    echo admin_url('admin-ajax.php');
?>";
			(function ($) {
				$(document).on('click', '.del_btn', function () {
                var del_id = $(this).attr('rel');
                var my_data = {
                    action: 'ba_deleter_code', 
                    delete_id: del_id,
					dataType: 'JSON'
                };
				jQuery(this).after('<div class="ajaxload"><div>');
                $.post(ajax_url, my_data, function (data) {
					var objprase=$.parseJSON(data); 
                    if (objprase['true'] == 'true') {
                        $('#' + del_id).remove();
						$('#blog-table').replaceWith( objprase.htmlcode );
                    } else {
                        alert("Could not be deleted");
                    }
                    jQuery('.ajaxload').remove();
                });
            });
            timeout: 50000
        })(jQuery);
			(function ($) {
			 $(document).on('click', '.arrow-up', function () {
                var user_id = $(this).attr('rel');
                var my_data = {
                    action: 'ba_arrow_handler', 
                    move_id: user_id,
					dataType: 'JSON',
					arrow_status: 'up'
                };
                $.post(ajax_url, my_data, function (data) {
				   var objprase=$.parseJSON(data); 
                    if (objprase['true'] == 'true') {
						$('#blog-table').replaceWith( objprase.htmlcode );
                    } else {
                    }
                });
			});
        })(jQuery);			
		(function ($) {
			 $(document).on('click', '.arrow-down', function () {
                var user_id = $(this).attr('rel');
                var my_data = {
                    action: 'ba_arrow_handler',
                    move_id: user_id,
					dataType: 'JSON',
					arrow_status: 'down'
                };
                $.post(ajax_url, my_data, function (data) {
				   var objprase=$.parseJSON(data);
                    if (objprase['true'] == 'true') {
						$('#blog-table').replaceWith( objprase.htmlcode );
                    } else {
                    }
                });
			});
        })(jQuery);
    </script>
    <?php
}
//Manages the up and down arrows that change the user order
function ba_arrow_handler() {
    global $wpdb;
    $ba_table       = $wpdb->prefix . "blog_authors";
    //run to check if the author exists
    $ba_checkifrows = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', $_POST['move_id']));
    if (isset($_POST['move_id']) && !empty($_POST['move_id']) && ($_POST['arrow_status'] == 'up' || $_POST['arrow_status'] == 'down') && $ba_checkifrows > 0) {
        //fetch the results from the mysql database to see if it can actually move
        $ba_lastuser  = $wpdb->get_var("SELECT MAX(ordr) AS ordr FROM $ba_table");
        //fetch the results to see what the users order is
        $ba_userorder = $wpdb->get_var($wpdb->prepare('SELECT ordr FROM ' . $ba_table . ' WHERE id = %d', $_POST['move_id']), 0, 0);
        if (1 == $ba_lastuser) {
            die();
        }
        //if the user is first but not last and arrow is down
        else if (($ba_userorder == 1) && ($ba_userorder != $ba_lastuser) && ($_POST['arrow_status'] == 'down')) {
            $ba_truetest = $wpdb->query($wpdb->prepare("UPDATE fmmzl_blog_authors INNER JOIN (SELECT MIN(fmmzl_blog_authors.ordr) ordr_prec, curr.ordr ordr_curr FROM fmmzl_blog_authors INNER JOIN (SELECT ordr FROM fmmzl_blog_authors WHERE id=%d) curr ON fmmzl_blog_authors.ordr>curr.ordr GROUP BY curr.ordr) cp ON fmmzl_blog_authors.ordr IN (cp.ordr_prec, cp.ordr_curr) SET ordr = CASE WHEN ordr=cp.ordr_curr THEN ordr_prec ELSE ordr_curr END", $_POST['move_id']));
        }
        //if the user is last but not first and arrow is up
        else if (($ba_userorder == $ba_lastuser) && ($ba_userorder != 1) && ($_POST['arrow_status'] == 'up')) {
            $ba_truetest = $wpdb->query($wpdb->prepare("UPDATE fmmzl_blog_authors INNER JOIN (SELECT MAX(fmmzl_blog_authors.ordr) ordr_prec, curr.ordr ordr_curr FROM fmmzl_blog_authors INNER JOIN (SELECT ordr FROM fmmzl_blog_authors WHERE id=%d) curr ON fmmzl_blog_authors.ordr<curr.ordr GROUP BY curr.ordr) cp ON fmmzl_blog_authors.ordr IN (cp.ordr_prec, cp.ordr_curr) SET ordr = CASE WHEN ordr=cp.ordr_curr THEN ordr_prec ELSE ordr_curr END", $_POST['move_id']));
        }
        //if the user is not first and is not last and arrow is down
        else if (($ba_userorder != $ba_lastuser) && ($ba_userorder != 1) && ($_POST['arrow_status'] == 'down')) {
            $ba_truetest = $wpdb->query($wpdb->prepare("UPDATE fmmzl_blog_authors INNER JOIN (SELECT MIN(fmmzl_blog_authors.ordr) ordr_prec, curr.ordr ordr_curr FROM fmmzl_blog_authors INNER JOIN (SELECT ordr FROM fmmzl_blog_authors WHERE id=%d) curr ON fmmzl_blog_authors.ordr>curr.ordr GROUP BY curr.ordr) cp ON fmmzl_blog_authors.ordr IN (cp.ordr_prec, cp.ordr_curr) SET ordr = CASE WHEN ordr=cp.ordr_curr THEN ordr_prec ELSE ordr_curr END", $_POST['move_id']));
        }
        //if the user is not first and is not last and arrow is up
        else if (($ba_userorder != $ba_lastuser) && ($ba_userorder != 1) && ($_POST['arrow_status'] == 'up')) {
            $ba_truetest = $wpdb->query($wpdb->prepare("UPDATE fmmzl_blog_authors INNER JOIN (SELECT MAX(fmmzl_blog_authors.ordr) ordr_prec, curr.ordr ordr_curr FROM fmmzl_blog_authors INNER JOIN (SELECT ordr FROM fmmzl_blog_authors WHERE id=%d) curr ON fmmzl_blog_authors.ordr<curr.ordr GROUP BY curr.ordr) cp ON fmmzl_blog_authors.ordr IN (cp.ordr_prec, cp.ordr_curr) SET ordr = CASE WHEN ordr=cp.ordr_curr THEN ordr_prec ELSE ordr_curr END", $_POST['move_id']));
        }
    }
    if ($ba_truetest = TRUE) {
        $ba_truetest2 = 'true';
    } else {
        $ba_truetest2 = 'false';
    }
    $ba_htmlreturn = ba_table_return();
    $ba_datapasser = array(
        "true" => utf8_encode($ba_truetest2),
        "htmlcode" => utf8_encode($ba_htmlreturn)
    );
    echo json_encode($ba_datapasser);
    die();
}
//Manages the deleting by removing the user and bringing down the row values for the user after
function ba_deleter_code() {
    global $wpdb;
    global $wp_roles;
    $ba_table = $wpdb->prefix . "blog_authors";
    if (isset($_POST['delete_id']) && !empty($_POST['delete_id'])) {
        $result = $wpdb->delete($ba_table, array(
            'ordr' => $_POST['delete_id']
        ), array(
            '%d'
        ));
        if ($result !== false) {
            //Get the ID of the last user by the order number so it can be used to clear his meta
            $ba_userid = $wpdb->get_var($wpdb->prepare("SELECT id FROM %s WHERE ordr = %d", $ba_table, $_POST['delete_id']));
            $ba_names  = array(
                "facebookba",
                "flickrba",
                "googleba",
                "linkedinba",
                "twitterba",
                "youtubeba",
                "userrole"
            );
            for ($i = 0; $i < count($ba_names); $i++) {
                $meta_type  = 'user';
                $user_id    = $ba_userid;
                $meta_key   = $ba_names[$i];
                $meta_value = '';
                $delete_all = true;
                delete_metadata($meta_type, $user_id, $meta_key, $meta_value, $delete_all);
            }
            $wpdb->query($wpdb->prepare("Update $ba_table SET  ordr = ordr - 1  WHERE ordr >%d", $_POST['delete_id']));
        }
    }
    $ba_htmlreturn = ba_table_return();
    $ba_datapasser = array(
        "true" => utf8_encode('true'),
        "htmlcode" => utf8_encode($ba_htmlreturn)
    );
    echo json_encode($ba_datapasser);
    die();
}
//Returns the HTML of the Display Options interface
function ba_edit_author_interface() {
    if (!is_admin()) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    wp_enqueue_script("jquery");
?>
    <div class="wrap">  
<?php
    global $wpdb;
    $ba_table  = $wpdb->prefix . "blog_authors";
    $ba_myrows = NULL;
    if (!empty($_POST['babutton'])) {
        if (username_exists($_POST["Uname"])) {
            $ba_myrows = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', username_exists($_POST["Uname"])));
            if ($ba_myrows < 1) {
                $ba_user_count = $wpdb->get_var("SELECT MAX(ordr) AS ordr FROM $ba_table") + 1;
                $wpdb->insert($ba_table, array(
                    'id' => username_exists($_POST["Uname"]),
                    'ordr' => $ba_user_count
                ));
                echo "<div id='message' class='updated'><p>User Added</p></div>";
            } else {
                echo "<div id='message' class='error'><p>User already exists</p></div>";
            }
        } else if ($_POST['Uname'] == "") {
            echo "<div id='message' class='error'><p>You did not enter anything!</p></div>";
        } else if (username_exists($_POST["Uname"])) {
            echo "<div id='message' class='error'><p>Username does not exist!</p></div>";
        } else {
            echo "<div id='message' class='error'><p>Something you entered is not right!</p></div>";
        }
    } else if (isset($_POST['addgroup'])) {
        $args      = array(
            'blog_id' => $GLOBALS['blog_id'],
            'role' => $_POST['groupselected']
        );
        $blogusers = get_users($args);
        if (sizeof($blogusers) <= 100) {
            $ba_user_count = $wpdb->get_var("SELECT MAX(ordr) AS ordr FROM $ba_table") + 1;
            foreach ($blogusers as $user) {
                $ba_myrows = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', $user->ID));
                if ($ba_myrows < 1) {
                    $wpdb->insert($ba_table, array(
                        'id' => $user->ID,
                        'ordr' => $ba_user_count
                    ));
                    $ba_user_count = $ba_user_count + 1;
                }
            }
            echo "<div id='message' class='updated'><p>Group Added</p></div>";
        } else {
            echo "<div id='message' class='error'><p>Can't add group with over 100 users</p></div>";
        }
    } else if (!empty($_POST['deleteall'])) {
        $wpdb->query('TRUNCATE TABLE ' . $ba_table);
        echo "<div id='message' class='updated'><p>All Users Delted</p></div>";
    }
    echo '<h1>Blog Authors</h1><div class="surface"><i>To add the Blog Authors to a page use the shortcode [bauthors]</i><h2 style="width: 15em;

border-bottom: 3px skyblue solid;">Users to Display as Authors</h2>';
?>
<form method="post" action=""> 
  <table>
    <tr>
      <td align="right">Username:</td>
      <td align="left"><input type="text" size="60" maxlength="60" name="Uname"></td>
    </tr>
  </table>
  <input type="submit" name="babutton" id="userid-submit" style="width: 22em" class="button" value="Submit">
</form>
 </td>
   </tr>
</table>

<br>

Add Users By Group

<form method="post" action=""> 
  <select name="groupselected">

    <?php
    foreach (get_editable_roles() as $role_name => $role_info):
        echo '<option value="' . $role_name . '">' . $role_name . '</option>';
    endforeach;
?>
</select>



  <input type="submit" name="addgroup" id="group-submit" style="width: 22em" class="button" value="Add Group">
</table>
</form>


<h2 style="width: 15em; 
border-bottom: 3px skyblue solid;">Users and Order</h2>
<?php
    echo ba_table_return();
    echo '<form method="post" action=""> 
  <input type="submit" name="deleteall" id="userid-submit" style="width: 22em" class="button" value="Delete All">
</table>';
    echo '</div>';
}
//Displays the settings for the admin page
function display_settings() {
    if (!is_admin()) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    wp_enqueue_script("jquery");
    wp_enqueue_style('ba_register_styles_css');
?>
<div class="wrap">  
<h1>Authors Page Options</h1>
<div class="surface">
<form method="post" action="options.php"><?php
    $ba_page = 'blog-authors';
    settings_fields($ba_page);
    do_settings_sections($ba_page);
    submit_button();
?>
			</form>
        </div>
	</div>
	
<?php
}
//Displays the authors card page settings
function ba_display_author_card() {
    if (!is_admin()) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    wp_enqueue_script("jquery");
    wp_enqueue_style('ba_register_styles_css');
?>
<div class="wrap">  
<h1>Author Card Options</h1><div class="surface">
<form method="post" action="options.php"><?php
    $ba_page = 'blog-card';
    settings_fields($ba_page);
    do_settings_sections($ba_page);
    submit_button();
?>
			</form>
        </div>
	</div>
<?php
}
function ba_display_embed_settings() {
    if (!is_admin()) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
?>
<div class="wrap">  
<h1>Embed Settings</h1><div class="surface">
<form method="post" action="options.php"><?php
    $ba_page = 'ba-embed-settings';
    settings_fields($ba_page);
    do_settings_sections($ba_page);
    submit_button();
?>
			</form>
        </div>
	</div>
<?php
}
//Adds the Blog Authors admin section
function ba_admin_menu() {
    $menu     = add_menu_page('Blog Authors', 'Blog Authors', 'manage_options', 'blog-authors', 'ba_edit_author_interface', '');
    $submenu1 = add_submenu_page('blog-authors', 'Authors Page', 'Authors Page', 'manage_options', 'blog-authors-manage', 'display_settings');
    $submenu2 = add_submenu_page('blog-authors', 'Author Card', 'Author Card', 'manage_options', 'blog-authors-author-card', 'ba_display_author_card');
    $submenu3 = add_submenu_page('blog-authors', 'Embed Settings', 'Embed Settings', 'manage_options', 'blog-authors-embed-code', 'ba_display_embed_settings');
    //Add stylesheet to all admin pages
    add_action('load-' . $menu, 'ba_register_styles_css');
    add_action('load-' . $submenu1, 'ba_register_styles_css');
    add_action('load-' . $submenu2, 'ba_register_styles_css');
    add_action('load-' . $submenu3, 'ba_register_styles_css');
    //Add script to authors page and author card
    add_action('load-' . $submenu1, 'ba_register_color_picker');
    add_action('load-' . $submenu2, 'ba_register_color_picker');
}
//Returns the HTML of the updated table
function ba_table_return() {
    global $wpdb;
    $ba_table          = $wpdb->prefix . "blog_authors";
    $ba_container      = '<table id="blog-table" border="1"><tr><td align="center">UserName</td><td align="center">User ID</td><td align="center">Order</td> <td align="center"></td><td align="center">Move Up/Down</td></tr>';
    $ba_authorsdbquery = $wpdb->get_results('SELECT * FROM ' . $ba_table . ' ORDER BY ordr', ARRAY_A);
    foreach ($ba_authorsdbquery as $row) {
        $ba_container = $ba_container . '<tr id="' . $row['ordr'] . '"><td>' . get_userdata($row['id'])->user_login . '</td>';
        $ba_container = $ba_container . '<td>' . $row['id'] . '</td>';
        $ba_container = $ba_container . '<td id="' . $row['ordr'] . '">' . $row['ordr'] . '</td>';
        $ba_container = $ba_container . '<td><button class="del_btn" rel="' . $row['ordr'] . '">Delete</button></td>';
        $ba_lastuser  = $wpdb->get_var("SELECT MAX(ordr) AS ordr FROM $ba_table");
        $ba_container = $ba_container . '<td>';
        if ($row['ordr'] == 1 && $row['ordr'] != $ba_lastuser) {
            $ba_container = $ba_container . '<div class="arrow-down" rel="' . $row['id'] . '"></div></td>';
        } else if ($row['ordr'] == $ba_lastuser && $row['ordr'] != 1) {
            $ba_container = $ba_container . '<div class="arrow-up" rel="' . $row['id'] . '"></div></td>';
        } else if ($row['ordr'] != 1) {
            $ba_container = $ba_container . '<div class="arrow-up" rel="' . $row['id'] . '"></div><br><div class="arrow-down" rel="' . $row['id'] . '"></div></td>';
        }
    }
    $ba_container = $ba_container . '</table>';
    return $ba_container;
}
?>