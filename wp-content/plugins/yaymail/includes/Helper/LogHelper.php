<?php

namespace YayMail\Helper;

defined( 'ABSPATH' ) || exit;


class LogHelper {



	public static function writeLog( $message, $type_log = 'error', $name = 'log' ) {

		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		if ( ! is_string( $message ) ) {
			$message = print_r( $message, true );
		}

		$folder = YAYMAIL_PLUGIN_PATH . '/includes/Logs';
		if ( ! file_exists( $folder ) ) {
			@mkdir( $folder, 0755 );
			$wp_filesystem->chmod( $folder, 0755 );

		}

		$filename = $folder . DIRECTORY_SEPARATOR . $name . '.txt';

		clearstatcache(); // Remove filesize cache

		$handle = $wp_filesystem->get_contents( $filename, 'a' );
		if ( filesize( $filename ) == 0 ) {
			$wp_filesystem->put_contents( $handle, self::getSystemStats() );
		}

		$wp_filesystem->put_contents( $handle, current_time( 'mysql' ) . ' [' . strtoupper( $type_log ) . '] ' . $message . PHP_EOL );
		fclose( $handle );
	}
	private static function getSystemStats() {
		global $wpdb;

		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$server_software = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] ) : '';
		$system_stats    = '====  SYSTEM STATS  ====' . PHP_EOL;
		$system_stats   .= 'WordPress Version: ' . get_bloginfo( 'version' ) . PHP_EOL;
		$system_stats   .= 'PHP Version: ' . phpversion() . PHP_EOL;
		$system_stats   .= 'MySQL Version: ' . $wpdb->db_version() . PHP_EOL;
		$system_stats   .= 'Website Name: ' . get_bloginfo() . PHP_EOL;
		$system_stats   .= 'Theme: ' . wp_get_theme() . PHP_EOL;
		$system_stats   .= 'WordPress URL: ' . site_url() . PHP_EOL;
		$system_stats   .= 'Site URL: ' . home_url() . PHP_EOL;
		$system_stats   .= 'Multisite: ' . ( is_multisite() ? 'yes' : 'no' ) . PHP_EOL;
		$system_stats   .= 'PHP Extensions: ' . json_encode( get_loaded_extensions() ) . PHP_EOL;
		$system_stats   .= 'Server Info: ' . $server_software . PHP_EOL;
		$system_stats   .= 'WP Memory Limit: ' . WP_MEMORY_LIMIT . PHP_EOL;
		$system_stats   .= 'WP Admin Memory Limit: ' . WP_MAX_MEMORY_LIMIT . PHP_EOL;
		$system_stats   .= 'PHP Memory Limit: ' . ini_get( 'memory_limit' ) . PHP_EOL;
		$system_stats   .= 'Max Execution Time: ' . ini_get( 'max_execution_time' ) . PHP_EOL;
		$system_stats   .= 'Open BaseDir: ' . ini_get( 'open_basedir' ) . PHP_EOL;
		$system_stats   .= 'WordPress Plugins: ' . json_encode( get_plugins() ) . PHP_EOL;
		$system_stats   .= 'WordPress Active Plugins: ' . json_encode( get_site_option( 'active_plugins' ) ) . PHP_EOL;
		$system_stats   .= '====  SYSTEM STATS  ====' . PHP_EOL . PHP_EOL;
		return $system_stats;
	}
	public static function getMessageException( $ex, $ajax = false ) {
		$message  = 'SYSTEM ERROR: ' . $ex->getCode() . ' : ' . $ex->getMessage();
		$message .= PHP_EOL . $ex->getFile() . '(' . $ex->getLine() . ')';
		$message .= PHP_EOL . $ex->getTraceAsString();
		self::writeLog( $message );
		if ( $ajax ) {
			wp_send_json_error( array( 'mess' => $message ) );
		}

	}

	// writeLog use show content when save email, save
	public static function writeLogContent( $content = '', $tailName = 'html' ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$name     = 'log-' . current_time( 'timestamp' );
		$folder   = YAYMAIL_PLUGIN_PATH . '/includes/Logs';
		$filename = $folder . DIRECTORY_SEPARATOR . $name . '.' . $tailName;
		$handle   = $wp_filesystem->get_contents( $filename, 'a' );
		$wp_filesystem->put_contents( $handle, print_r( $content, true ) );
		fclose( $handle );
	}
}
