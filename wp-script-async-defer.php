<?php
/*
 * Plugin Name: Script async and defer.
 * Plugin URI: https://github.com/kylereicks/script-async-defer
 * Description: Add async and defer parameters to script tags when the values are set via wp_script_add_data.
 * Version: 0.1.0
 * Author: Kyle Reicks
 * Author URI: https://github.com/kylereicks/
*/
namespace WordPress\Script\Async_Defer;

add_filter( 'script_loader_tag', __NAMESPACE__ . '\add_async_defer', 10, 3 );

/**
 * Add async and defer parameters to a script tag.
 *
 * Add async and defer parameters to script tags when the values are set via wp_script_add_data.
 *
 * wp_script_add_data( 'script-handle', 'async', true );
 * wp_script_add_data( 'script-handle', 'defer', true );
 *
 * @since 0.1.0
 *
 * @global \WP_Scripts $wp_scripts The global WP_Scripts object, containing registered scripts.
 *
 * @param string $tag The filtered HTML tag.
 * @param string $handle The handle for the registered script/style.
 * @param string $src The resource URL.
 * @return string The filtered HTML tag.
 */
function add_async_defer( $tag, $handle, $src ) {
	global $wp_scripts;

	if ( ! empty( $wp_scripts->registered[$handle]->extra['async'] ) ) {
		if ( preg_match( '/async([=\s]([\'\"])((?!\2).+?[^\\\])\2)?/', $tag, $match ) ) {
			$tag = str_replace( $match[0], 'async', $tag );
		} else {
			$tag = str_replace( '<script ', '<script async ', $tag );
		}
	}

	if ( ! empty( $wp_scripts->registered[$handle]->extra['defer'] ) ) {
		if ( preg_match( '/defer([=\s]([\'\"])((?!\2).+?[^\\\])\2)?/', $tag, $match ) ) {
			$tag = str_replace( $match[0], 'defer', $tag );
		} else {
			$tag = str_replace( '<script ', '<script defer ', $tag );
		}
	}

	return $tag;
}
