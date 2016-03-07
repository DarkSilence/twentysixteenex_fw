<?php

function isGoogleBot() {

	$ua = $_SERVER['HTTP_USER_AGENT'];
	return stripos( $ua, 'Googlebot' ) !== FALSE || stripos( $ua, 'Mediapartners-Google' ) !== FALSE || stripos( $ua, 'AdsBot-Google' ) !== FALSE;

}

function isYandexBot() {

	return stripos( $_SERVER['HTTP_USER_AGENT'], 'http://yandex.com/bots' ) !== FALSE;

}

function isYandexOrigin() {

	return stripos( $_SERVER['HTTP_REFERER'], 'yandex' ) !== FALSE || stripos( $_SERVER['HTTP_REFERER'], 'ya.ru' ) !== FALSE;

}

// End.