<?php

if ( ! function_exists( 'comment_form' ) ) :
	/**
	 * Documentation for function.
	 */
	function comment_form( $order ) {
		if ( true === $order || strtolower( $order ) === strtolower( get_option( 'comment_order', 'asc' ) ) ) {
			comment_form(
				array(
					'logged_in_as' => null,
					'title_reply'  => null,
				)
			);
		}
	}
endif;
