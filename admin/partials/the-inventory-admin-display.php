<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h2><?php echo __( 'Options', 'the-inventory' ); ?></h2>

<label><?php echo __( 'API Key', 'the-inventory' ); ?></label>
<input name="key" type="text"></input>

<button onclick="sync()" name="sync"><?php echo __( 'Sync', 'the-inventory' ); ?></button>
<span><small>Latest update: </small> </span>
