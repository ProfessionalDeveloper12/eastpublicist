<?php
/* Widget Register / UN-register */
function publicist_manage_widgets() {

	/* Popular Posts */
	require_once("popular_post.php");
	register_widget( 'Publicist_Widget_Popular' );
	
	/* Trending Posts */
	require_once("trending_post.php");
	register_widget( 'Publicist_Widget_Trending' );
}
add_action( 'widgets_init', 'publicist_manage_widgets' );