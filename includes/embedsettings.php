<?php
add_action('admin_init', 'authors_ba_embed_settings');
//Registers the embed settings
function authors_ba_embed_settings() {
    $ba_name    = 'ba_settings';
    $ba_page    = 'ba-embed-settings';
    $ba_section = 'default';
    add_settings_section('ba_embed_settings_section', '', 'ba_embed_settings_title', $ba_page);
    add_settings_field('embed_enabled', 'Embed Enabled', 'ba_embed_enabled_callback', $ba_page, 'ba_embed_settings_section');
    register_setting($ba_page, 'embed_enabled');
}
function ba_embed_enabled_callback($ba_input) {
    echo 'Would you like to enable the embed feature which allows authors to embed their card remotely? <input type="checkbox" id="embed_enabled" name="embed_enabled" value="1" ' . checked(1, get_option('embed_enabled', 1), false) . '/>';
    echo '<br><i>Note: Enabling this feature will allow anyone to see user information of users added in blog authors by visiting and adding the id of any user who has been added in blog authors to the embed link<br><br><br>Embeding: The embed code is available to added Blog Author users when editing their user profile and the embed option is enabled.</i>';
}
function ba_embed_settings_title() {
    echo '';
}
?>