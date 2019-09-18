<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$id = isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : 0;

if ( FALSE === get_post_status( $id ) ) {
	echo '<script type="text/javascript">window.location.href="'.admin_url('/post.php?action=edit&post='.$id).'";</script>';
	return;	
}

$link = get_permalink( $id );

$data = get_post_meta($id , 'kc_data', true);

if( !is_array( $data ) )
	$data = array( "mode" => "kc", "classes" => "", "css" => "" );

$data["mode"] = 'kc';

if( !add_post_meta( $id , 'kc_data' , $data, true ) )
	update_post_meta( $id , 'kc_data' , $data );

if( strpos( $link, '?' ) === false )
	$link .= '?kc_action=live-editor';
else $link .= '&kc_action=live-editor';

?>
<div id="kc-live-frame-wrp">
	<iframe id="kc-live-frame" src="<?php echo esc_url( $link ); ?>"></iframe>
	<div id="kc-live-frame-resizer"></div>
</div>
<a href="https://www.youtube.com/watch?v=QQcSldFalnI" target=_blank id="kc-how-responsive-works">
	<?php _e('How responsive works?', 'kingcomposer'); ?>
</a>
<div style="height: 0px;width: 0px;overflow:hidden;">
	<?php wp_editor( '', 'kc-editor-preload', array( "wpautop" => false, "quicktags" => true ) ); ?>
</div>