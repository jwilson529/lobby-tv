<?php
/**
 * Register custom post types and taxonomies for Lobby TV.
 *
 * @package Lobby_Tv
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles registration of Lobby TV post types and taxonomies.
 */
class Lobby_Tv_Cpt {

	/**
	 * Register custom post types and taxonomies used by the plugin.
	 *
	 * @return void
	 */
	public function register_cpts_and_taxonomies() {
		$this->register_asset_post_type();
		$this->register_playlist_post_type();
		$this->register_channel_post_type();
		$this->register_screen_post_type();
		$this->register_location_taxonomy();
	}

	/**
	 * Register the top-level Lobby TV admin menu.
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		add_menu_page(
			esc_html__( 'Lobby TV', 'lobby-tv' ),
			esc_html__( 'Lobby TV', 'lobby-tv' ),
			'edit_posts',
			'lobby-tv',
			array( $this, 'redirect_to_asset_list' ),
			'dashicons-desktop',
			56
		);

		remove_submenu_page( 'lobby-tv', 'lobby-tv' );
	}

	/**
	 * Register the asset post type.
	 *
	 * @return void
	 */
	private function register_asset_post_type() {
		$labels = array(
			'name'          => esc_html__( 'Assets', 'lobby-tv' ),
			'singular_name' => esc_html__( 'Asset', 'lobby-tv' ),
			'menu_name'     => esc_html__( 'Assets', 'lobby-tv' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => 'lobby-tv',
			'menu_icon'          => 'dashicons-format-video',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'show_in_nav_menus'  => false,
			'show_in_admin_bar'  => false,
			'exclude_from_search' => true,
		);

		register_post_type( 'lobby_tv_asset', $args );
	}

	/**
	 * Register the playlist post type.
	 *
	 * @return void
	 */
	private function register_playlist_post_type() {
		$labels = array(
			'name'          => esc_html__( 'Playlists', 'lobby-tv' ),
			'singular_name' => esc_html__( 'Playlist', 'lobby-tv' ),
			'menu_name'     => esc_html__( 'Playlists', 'lobby-tv' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => 'lobby-tv',
			'menu_icon'         => 'dashicons-list-view',
			'supports'          => array( 'title' ),
			'has_archive'       => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'exclude_from_search' => true,
		);

		register_post_type( 'lobby_tv_playlist', $args );
	}

	/**
	 * Register the channel post type.
	 *
	 * @return void
	 */
	private function register_channel_post_type() {
		$labels = array(
			'name'          => esc_html__( 'Channels', 'lobby-tv' ),
			'singular_name' => esc_html__( 'Channel', 'lobby-tv' ),
			'menu_name'     => esc_html__( 'Channels', 'lobby-tv' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => 'lobby-tv',
			'menu_icon'         => 'dashicons-networking',
			'supports'          => array( 'title' ),
			'has_archive'       => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'exclude_from_search' => true,
		);

		register_post_type( 'lobby_tv_channel', $args );
	}

	/**
	 * Register the screen post type.
	 *
	 * @return void
	 */
	private function register_screen_post_type() {
		$labels = array(
			'name'          => esc_html__( 'Screens', 'lobby-tv' ),
			'singular_name' => esc_html__( 'Screen', 'lobby-tv' ),
			'menu_name'     => esc_html__( 'Screens', 'lobby-tv' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => 'lobby-tv',
			'menu_icon'         => 'dashicons-desktop',
			'supports'          => array( 'title' ),
			'has_archive'       => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'exclude_from_search' => true,
		);

		register_post_type( 'lobby_tv_screen', $args );
	}

	/**
	 * Register the location taxonomy.
	 *
	 * @return void
	 */
	private function register_location_taxonomy() {
		$labels = array(
			'name'          => esc_html__( 'Locations', 'lobby-tv' ),
			'singular_name' => esc_html__( 'Location', 'lobby-tv' ),
			'menu_name'     => esc_html__( 'Locations', 'lobby-tv' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);

		register_taxonomy( 'lobby_tv_location', array( 'lobby_tv_channel', 'lobby_tv_screen' ), $args );
	}

	/**
	 * Redirect the top-level menu to the asset list for convenience.
	 *
	 * @return void
	 */
	public function redirect_to_asset_list() {
		if ( current_user_can( 'edit_posts' ) ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=lobby_tv_asset' ) );
			exit;
		}

		wp_die( esc_html__( 'You do not have permission to access this page.', 'lobby-tv' ) );
	}
}
