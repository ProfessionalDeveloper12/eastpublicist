<?php

$video_upload = '';
$video_height = '250';
$check_video  = 'true';
$video_mute = 'no';
extract( $atts );


$video_classes = apply_filters( 'kc-el-class', $atts );

if( !empty( $video_width ) )
	$video_height = intval( $video_width ) / 1.77;


if( isset( $_GET['kc_action'] ) && $_GET['kc_action'] === 'live-editor' )
	$is_live = true;
else
	$is_live = false;

$video_link = ( !empty( $video_link ) ) ? $video_link : 'https://www.youtube.com/watch?v=iNJdPyoqt8U'; //default video

//Check youtube video url
$pattern = '~
	^(?:https?://)?              # Optional protocol
	 (?:www\.)?                  # Optional subdomain
	 (?:youtube\.com|youtu\.be)  # Mandatory domain name
	 /watch\?v=([^&]+)           # URI with video id as capture group 1
	 ~x';

$has_match = preg_match( $pattern, $video_link, $matches );

$video_attributes = array();

$video_classes = array_merge(
	$video_classes,
	array(
		'kc_shortcode',
		'kc_video_play',
		'kc_video_wrapper'
	)
);

if ( !empty( $wrap_class ) )
	$video_classes[] = $wrap_class;

if ( !empty( $atts['css'] ) )
	$video_classes[] = $atts['css'];

$video_attributes[] = 'class="'. esc_attr( implode(' ', $video_classes ) ) .'"';

if( !$is_live && empty( $video_upload ) ){
	$video_attributes[] = 'data-video="'. esc_attr( $video_link ) .'"';
	$video_attributes[] = 'data-width="'. esc_attr( $video_width ) .'"';
	$video_attributes[] = 'data-height="'. esc_attr( $video_height ) .'"';
	$video_attributes[] = 'data-fullwidth="'. esc_attr( $full_width ) .'"';
	$video_attributes[] = 'data-autoplay="'. esc_attr( $auto_play ) .'"';
	$video_attributes[] = 'data-loop="'. esc_attr( $loop ) .'"';
	$video_attributes[] = 'data-control="'. esc_attr( $control ) .'"';
	$video_attributes[] = 'data-related="'. esc_attr( $related ) .'"';
	$video_attributes[] = 'data-showinfo="'. esc_attr( $showinfo ) .'"';
	$video_attributes[] = 'data-kc-video-mute="' . esc_attr( $video_mute ) . '"';
}

if( $check_video === 'true' ) {

?>

	<div <?php echo implode(' ', $video_attributes ); ?>>
		<?php if ( $is_live ): ?>

			<div style="height:<?php echo esc_attr( $video_height ); ?>px; width:<?php echo esc_attr( $video_width ); ?>" class="disable-view-element">
				<h3><?php echo esc_html__( 'For best perfomance, the video map has been disabled in this editing mode.', 'kingcomposer' ); ?></h3>
			</div>

		<?php elseif ( !empty( $video_upload ) ): ?>
			<?php
				$autoplay = '';
				if( $auto_play == 'yes' )
					$autoplay = ' autoplay';
			?>
			<video width="<?php echo esc_attr( $video_width ); ?>" height="<?php echo esc_attr( $video_height ); ?>" controls<?php echo esc_attr( $autoplay ); ?>>
				<source src="<?php echo esc_url( $video_upload ); ?>" type="video/mp4">
				<?php echo esc_html__( 'Your browser does not support the video tag.', 'kingcomposer' ); ?>
			</video>

		<?php endif ?>
	</div>

<?php

} else {
	echo esc_html__('KingComposer error: Video format url incorrect', 'kingcomposer');
}
