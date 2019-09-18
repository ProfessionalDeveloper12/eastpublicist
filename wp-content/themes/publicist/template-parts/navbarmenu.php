<?php
if( has_nav_menu('publicist_primary') ) {
	wp_nav_menu( array(
		'theme_location' => 'publicist_primary',
		'container' => false,
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth' => 10,
		'menu_class' => 'navbar-nav',
		'walker' => new publicist_nav_walker
	));
}
else {
	?><h3 class="menu-setting-info"><a href="<?php echo esc_url( admin_url("/nav-menus.php") ); ?>"><?php esc_html_e( 'Set The Menu', "publicist" );?></a></h3><?php
} ?>