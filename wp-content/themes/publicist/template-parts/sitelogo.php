<?php
$limg = $lsitetitle = $lcustomtxt = "";

$limg = publicist_options('opt_logoimg','url');
$lsitetitle = get_bloginfo('name');
$lcustomtxt = publicist_options('opt_customtxt');

if( publicist_options('opt_logotype') == "2" && $limg != "" ) { // Logo - Image
	?>
	<a class="image-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e('Logo',"publicist"); ?>">
		<img src="<?php echo esc_url( $limg ); ?>" alt="<?php esc_attr_e('Logo',"publicist"); ?>"/>
	</a>
	<?php
}
elseif( publicist_options('opt_logotype') == "1" && $lsitetitle != "" ) { // Logo - Site Title
	?>
	<a class="text-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo get_bloginfo($lsitetitle); ?>"><?php echo get_bloginfo( $lsitetitle ); ?></a>
	<?php
}
elseif( publicist_options('opt_logotype') == "3" && $lcustomtxt != "" ) { // Logo - Custom Text
	?>
	<a class="text-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr($lcustomtxt); ?>">
		<?php echo esc_attr( $lcustomtxt ); ?>
	</a>
	<?php
}
else {
	?>
	<a class="image-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e('Logo',"publicist"); ?>">
		<img src="<?php echo esc_url( PUBLICIST_IMGURI ); ?>/logo.png" alt="<?php esc_attr_e('Logo',"publicist"); ?>"/>
	</a>
	<?php
} ?>