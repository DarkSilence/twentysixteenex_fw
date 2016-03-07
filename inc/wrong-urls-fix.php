<?php

// Workaround to fix such links http://site.ru/http://site.ru/
function check_current_url() {

	$site_url = get_site_url();

	if ( !$site_url ) return;

	$site_host = parse_url( $site_url, PHP_URL_HOST );
	
	if ( !$site_host ) return;

	$site_host = preg_quote( $site_host );

	if ( preg_match( '/\/http(?:\\:\/\/|%3A%2F%2F)'. $site_host .'/', $_SERVER['REQUEST_URI'] ) ) {

		$fm = array();
		if ( preg_match( '/\/(http(?:\\:\/\/|%3A%2F%2F)'. $site_host .'.*)/', $_SERVER['REQUEST_URI'], $fm ) && isset( $fm[1] ) && $fm[1] ) {

			header('HTTP/1.0 301 Moved Permanently');
			header('Location: '. urldecode( $fm[1] ) );
			die;

		}
	}
}

check_current_url();

// End.