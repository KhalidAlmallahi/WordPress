<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: Limit and Faster WP Admin
 * Description: Reduces the queried fields inside WP_Query for WP_Post_List_Table screens
 * Author:      Khalid Almallahi <abukotsh@gmail.com>
 * AuthorURL:   http://abukotsh.com
 * License:     MIT
 */

add_filter( 'posts_fields', 'wcm_limit_post_fields_cb', 0, 2 );
function wcm_limit_post_fields_cb( $fields, $query )
{
  if (
		! is_admin()
    OR ! $query->is_main_query()
		OR ( defined( 'DOING_AJAX' ) AND DOING_AJAX )
		OR ( defined( 'DOING_CRON' ) AND DOING_CRON )
	)
		return $fields;

	$p = $GLOBALS['wpdb']->posts;
	return implode( ",", array(
		"{$p}.ID",
		"{$p}.post_title",
		"{$p}.post_date",
		"{$p}.post_author",
		"{$p}.post_name",
		"{$p}.comment_status",
		"{$p}.ping_status",
		"{$p}.post_password",
	) );
}