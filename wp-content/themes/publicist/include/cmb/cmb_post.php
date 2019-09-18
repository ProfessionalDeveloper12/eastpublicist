<?php
// Start with an underscore to hide fields from custom fields list
$prefix = 'publicist_cf_';

/* Post : Video */
$cmb_post_video = new_cmb2_box( array(
	'id'            => $prefix . 'metabox_post_video',
	'title'         => esc_html__( 'Video Post Options', "publicist" ),
	'object_types'  => array( 'post' ), // Post type
	'context'       => 'normal',
	'priority'      => 'high',
	'show_names'    => true, // Show field names on the left
) );

$cmb_post_video->add_field( array(
	'name'             => 'Choose Video Source',
	'desc'             => 'Select an option',
	'id'               => $prefix . 'post_video_source',
	'type'             => 'select',
	'default'          => 'video_link',
	'options'          => array(
		'video_link' => esc_html__( 'Video Link', "publicist" ),
		'video_embed_code'   => esc_html__( 'Video Embed Code', "publicist" ),
		'video_upload_local'   => esc_html__( 'Upload Local File', "publicist" ),
	),
) );

$cmb_post_video->add_field( array(
	'name'             => 'Video URL',
	'id'               => $prefix . 'post_video_link',
	'desc' => 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.',
	'type' => 'oembed',
) );

$cmb_post_video->add_field( array(
	'name'             => 'Video embed code',
	'id'               => $prefix . 'post_video_embed',
	'desc'               => 'Paste video embed code',
	'type'             => 'textarea_code',
	'options' => array( 'disable_codemirror' => true ),
) );

$cmb_post_video->add_field( array(
	'name'             => 'Upload Local File',
	'id'               => $prefix . 'post_video_local',
	'desc'               => 'Support .mp4 file format only',
	'type'             => 'file',
) );

/* Post : Gallery */
$cmb_post_gallery = new_cmb2_box( array(
	'id'            => $prefix . 'metabox_post_gallery',
	'title'         => esc_html__( 'Gallery Post Options', "publicist" ),
	'object_types'  => array( 'post' ), // Post type
	'context'       => 'normal',
	'priority'      => 'high',
	'show_names'    => true, // Show field names on the left
) );

$cmb_post_gallery->add_field( array(
	'name' => esc_html__( 'Post Gallery', 'publicist' ),
	'id'   => $prefix . 'post_gallery',
	'type' => 'file_list',
) );

/* Post : Audio */
$cmb_post_audio = new_cmb2_box( array(
	'id'            => $prefix . 'metabox_post_audio',
	'title'         => esc_html__( 'Audio Post Options', "publicist" ),
	'object_types'  => array( 'post' ), // Post type
	'context'       => 'normal',
	'priority'      => 'high',
	'show_names'    => true, // Show field names on the left
) );

$cmb_post_audio->add_field( array(
	'name'             => 'Choose Audio Source',
	'desc'             => 'Select an option',
	'id'               => $prefix . 'post_audio_source',
	'type'             => 'select',
	'default'          => 'soundcloud_link',
	'options'          => array(
		'soundcloud_link' => esc_html__( 'Soundcloud Link', "publicist" ),
		'soundcloud_iframe' => esc_html__( 'Soundcloud Iframe', "publicist" ),
		'audio_upload_local'   => esc_html__( 'Upload Local File', "publicist" ),
	),
) );

$cmb_post_audio->add_field( array(
	'name'             => 'Soundcloud URL',
	'id'               => $prefix . 'post_soundcloud_url',
	'type'             => 'textarea_code',
) );

$cmb_post_audio->add_field( array(
	'name'             => 'Soundcloud Iframe',
	'id'               => $prefix . 'post_soundcloud_iframe',
	'type'             => 'textarea_code',
	'options' => array( 'disable_codemirror' => true ),
) );

$cmb_post_audio->add_field( array(
	'name'             => 'Upload Local File',
	'id'               => $prefix . 'post_audio_local',
	'desc'               => 'Support .mp3 file format only',
	'type'             => 'file',
) );
?>