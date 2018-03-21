<?php
/**
 * Create the premium notice message
 *
 * @since 2.5.1
 *
 * @uses get_option()
 */
function vidbg_premium_notice() {
	$class = 'notice notice-success vidbg-premium-notice is-dismissible';
	$message = __( 'Video Background Pro now plays video backgrounds on supported mobile devices! <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">Check out Video Background Pro</a>', 'video-background' );
	$is_dismissed = get_option( 'vidbgpro-notice-dismissed' );

	if( empty( $is_dismissed ) ) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}
add_action( 'admin_notices', 'vidbg_premium_notice' );

/**
 * Ajax handler to permanently dismiss notice
 *
 * @since 2.5.1
 *
 * @uses update_option()
 */
function vidbg_dismiss_premium_notice() {
	update_option( 'vidbgpro-notice-dismissed', 1 );
}
add_action( 'wp_ajax_vidbg_dismiss_premium_notice', 'vidbg_dismiss_premium_notice' );
?>
