<?php
/**
 * Plugin Name:       Custom User Avatar
 * Plugin URI:        https://wordpress.org/plugins/custom-user-avatar/
 * Description:       Adds a new field to the user edit screen where you can define a custom avatar.
 * Version:           1.0
 * Requires at least: 4.2.0
 * Requires PHP:      8.0
 * Author:            WPExplorer
 * Author URI:        https://www.wpexplorer.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       custom-user-avatar
 * Domain Path:       /languages/
 */

/*
Custom User Avatar is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Custom User Avatar is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Custom User Avatar. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Prevent direct access to this file.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Custom User Avatars Class.
 */
if ( ! class_exists( 'Custom_User_Avatar' ) ) {
	class Custom_User_Avatar {

		/**
		 * User meta field name.
		 */
		private const FIELD_KEY = 'custom_avatar';

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_filter( 'user_contactmethods', [ $this, 'filter_user_contactmethods' ] );
				add_filter( 'user_profile_picture_description', [ $this, 'filter_user_profile_picture_description' ], 10, 2 );
			}
			add_filter( 'pre_get_avatar_data', [ $this, 'filter_pre_get_avatar_data' ], 10, 2 );
		}

		/**
		 * Hooks into the "user_contactmethods" filter.
		 */
		public function filter_user_contactmethods( $fields ): array {
			$methods[ self::FIELD_KEY ] = esc_html__( 'Custom Avatar (ID or URL)', 'custom-user-avatar' );
			return $methods;
		}

		/**
		 * Hooks into "user_profile_picture_description".
		 */
		public function filter_user_profile_picture_description( $description, $profile_user ): string {
			if ( defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE ) {
				if ( get_user_meta( $profile_user->ID, self::FIELD_KEY, true ) ) {
					$description = sprintf(
						esc_html__( '%sYou can change your profile picture using the "Custom Avatar" field above%s.', 'custom-user-avatar' ),
						'<a href="#custom_avatar">',
						'</a>'
					);
				} elseif ( is_string( $description ) ) {
					$description .=  '<br>' . sprintf(
						esc_html__( '%sOr using the "Custom Avatar" field above%s.', 'custom-user-avatar' ),
						'<a href="#custom_avatar">',
						'</a>'
					);
				}
			}
			return (string) $description;
		}

		/**
		 * Hooks into the "pre_get_avatar_data" filter.
		 */
		public function filter_pre_get_avatar_data( $args, $id_or_email ): array {
			// Process the user identifier.
			$user = false;
			if ( is_numeric( $id_or_email ) ) {
				$user = get_user_by( 'id', absint( $id_or_email ) );
			} elseif ( $id_or_email instanceof WP_User ) {
				$user = $id_or_email;
			} elseif ( $id_or_email instanceof WP_Post ) {
				$user = get_user_by( 'id', (int) $id_or_email->post_author );
			} elseif ( $id_or_email instanceof WP_Comment && is_avatar_comment_type( get_comment_type( $id_or_email ) ) ) {
				if ( is_numeric( $id_or_email->user_id ) ) {
					$user = get_user_by( 'id', (int) $id_or_email->user_id );
				} elseif( is_email( $id_or_email->user_id ) ) {
					$user = get_user_by( 'email', $id_or_email->user_id );
				}
			}

			// Check for and assign custom user avatars.
			if ( $user && $custom_avatar = get_user_meta( $user->ID, self::FIELD_KEY, true ) ) {
				if ( is_numeric( $custom_avatar ) ) {
					if ( wp_attachment_is_image( get_post( $custom_avatar ) ) ) {
						$args['url'] = esc_url( wp_get_attachment_url( $custom_avatar ) );
					}
				} elseif ( $this->url_is_image( $custom_avatar ) && $safe_avatar_url = esc_url( (string) $custom_avatar ) ) {
					$args['url'] = $safe_avatar_url;
				}
			}

			// Return avatar args.
			return $args;
		}

		/**
		 * Helper function to make sure a given URL is an image.
		 */
		private function url_is_image( $url ): bool {
			$valid_extensions = [ 'jpg', 'jpeg', 'png', 'webp', 'avif', 'gif' ];
			foreach ( $valid_extensions as $extension ) {
				if ( str_ends_with( $url, $extension ) ) {
					return true;
				}
			}
			return false;
		}

	}

	new Custom_User_Avatar;

}
