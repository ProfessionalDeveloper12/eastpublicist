<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class kc_ajax{

	public function __construct(){

		$ajax_events = array(

			'get_welcome' 		=> false,
			'get_thumbn' 		=> true,
			'get_featured' 		=> true,
            'get_thumbn_size' 	=> true,
            'install_online_preset'	=> true,

			'load_profile'		=> false,
			'download_profile'	=> false,
			'create_profile'	=> false,
			'rename_profile'	=> false,
			'delete_profile'	=> false,
			'delete_section'	=> false,
			'update_section'	=> false,
			'instant_save'		=> false,
			'suggestion'		=> false,
			'tmpl_storage'		=> false,
			'kcp_access'		=> false,
			'revoke_domain'		=> false,
			'download_pro'		=> false,
			'update_plugin'		=> false,
			'add_font'			=> false,
			'update_font'		=> false,
			'delete_font'		=> false,
			'load_sections'		=> false,
			'load_section'		=> false,
			'push_section'		=> false,
			'update_option'		=> false,
			'update_mapper'		=> false,
			'enable_optimized'	=> false,
			'switch_off'		=> false,
			'share_section'		=> false,
			'load_element_via_ajax'	=> false,

			'installed_extensions'	=> false,
			'store_extensions'	=> false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {

			add_action( 'wp_ajax_kc_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_kc_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );
			}
		}
	}

	public function get_welcome(){

		$data = array(
			'message' => __('Hello, I\'m King Composer!', 'kingcomposer')
		);

		wp_send_json( $data );
	}

	public function get_thumbn(){
		global $kc;
		$id = !empty( $_GET['id'] ) ? esc_attr($_GET['id']) : '';
		$size = !empty( $_GET['size'] ) ? esc_attr($_GET['size']) : 'medium';
		$type = !empty( $_GET['type'] ) ? esc_attr($_GET['type']) : '';

		if ($type == 'filter_url') {
			header( 'location: '.kc_attach_url(KC_SITE.urldecode($id)));
			exit;
		}

		if( $id == '' || $id == 'undefined' )
		{
			header( 'location: '.$kc->apply_filters('kc_default_image', KC_URL.'/assets/images/get_start.jpg') );
			exit;
		}

		if( $type == 'post_featured' )
		{


			$img = get_the_post_thumbnail_url( $id, $size );
			if (strpos($source, site_url()) !== false) {
				$meta = get_post_meta( $id, 'kc_data', true );
				if (!empty($meta) && isset($meta['thumbnail']))
					$img = $meta['thumbnail'];
			}
			if( !empty( $img ) ){
				header( 'location: '.$img );
			}else{
				header( 'location: '. $kc->apply_filters('kc_section_default_image', KC_URL.'/assets/images/get_start_section.jpg'));
			}

			exit;

		}

		$img = wp_get_attachment_image_src( $id, $size );

		if( !empty( $img[0] ) )
		{
			header( 'location: '.$img[0] );
		}
		else
		{
			header( 'location: '. $kc->default_image());
		}
		exit();
	}

    public function get_thumbn_size( $abc ){
		global $kc;
        $imid = !empty( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : '';
        $size = !empty( $_GET['size'] ) ? esc_attr( $_GET['size'] ) : '';

        if( empty($imid) || $imid == 'undefined' )
        {
            header( 'location: '. $kc->default_image());
            exit;
        }

        $img = wp_get_attachment_image_src( $imid, 'full' );

        if( !empty($img[0]) )
        {
	        if ( !empty($size) )
          		$re_img = kc_tools::createImageSize( $img[0], $size);
          	if (!empty($re_img))
          		header( 'location: '.$re_img );
          	else header( 'location: '.$img[0] );
        }
        else
        {
            header( 'location: '.KC_URL.'/assets/images/default.jpg' );
        }
    }

	public function download_profile(){

		$name = isset( $_GET['name'] ) ? $_GET['name'] : '';

		if( empty( $name ) ){
			echo '[]';
			exit;
		}

		$name = sanitize_title( esc_attr( $name ) );

		if( get_option( 'kc-profile-'.$name ) !== false ){

			$data = get_option( 'kc-profile-'.$name, true );

			if( isset( $data[1] ) && !empty( $data[1] ) )
				echo base64_decode( $data[1] );
			else echo '[]';

		}else echo '[]';

		exit;

	}

	public function load_profile(){

		global $kc;
		$profile_section_paths = $kc->get_profile_sections();

		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
		$name = str_replace( array('..'), array( '' ), esc_attr( $name )  );

		$data = '';
		$slug = sanitize_title( $name );

		if( $name == '' ){

			$result = array(
				'message' =>  esc_html__('Error #623! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);

		}
		else{

			if( isset( $profile_section_paths[ $name ] ) && is_file( untrailingslashit( ABSPATH ).$profile_section_paths[ $name ] ) ){

				$profile = $kc->get_data_profile( $name );

				if( $profile !== false ){

					if( isset( $profile[0] ) && !empty( $profile[0] ) && $profile[0] !== null )
						$name = $profile[0];
					if( isset( $profile[1] ) && !empty( $profile[1] ) && $profile[1] !== null )
						$slug = $profile[1];
					if( isset( $profile[2] ) && !empty( $profile[2] ) && $profile[2] !== null )
						$data = $profile[2];

				}else{

					$message = esc_html__('Error #795! opening file Permission denied', 'kingcomposer').': '.
								$profile_section_paths[ $name ];
					wp_send_json(
						array( 'message' => $message, 'status' => 'fail' )
					);

					return;

				}

			}
			else if( get_option( 'kc-profile-'.$name ) !== false ){

				$getDB =  get_option( 'kc-profile-'.$name, true );

				$slug = $name;
				if( isset( $getDB[0] ) && !empty( $getDB[0] ) && $getDB[0] !== null )
					$name = $getDB[0];
				else $name = '';

				if( isset( $getDB[1] ) && !empty( $getDB[1] ) && $getDB[1] !== null )
					$data = $getDB[1];
				else $data = base64_encode('');

			}
			else{

				$message = esc_html__('Error #528! profile not found', 'kingcomposer').': '.$name;
				wp_send_json(
					array( 'message' => $message, 'status' => 'fail' )
				);
				return;

			}

		}

		$result = array(

			'message' => '<div class="mgs-c-status"><i class="et-happy"></i></div><h1 class="mgs-t02">'.
						 esc_html__('Your sections profile has been downloaded successful', 'kingcomposer').'</h1>'.
						 '<h2>'.esc_html__('Now you can use sections from new profile', 'kingcomposer').'</h2>',
			'status' => 'success',
			'name' => $name,
			'slug' => $slug,
			'data' => $data

		);

		wp_send_json( $result );

		exit;

	}

	public function create_profile(){

		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';

		if( $name == '' ){

			$result = array(
				'message' =>  esc_html__('Error #140! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);

		}else{

			$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';

			if( get_option( 'kc-profile-'.$slug ) === false ){

				add_option( 'kc-profile-'.$slug, array( $name, $data ), null, 'no' );

				$result = array(
					'message' => __('Your sections profile has been created successful', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'slug' => $slug
				);

			}else{

				$result = array(
					'message' =>  esc_html__('Error #101! The name must not be empty', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);
			}

		}

		wp_send_json( $result );

		exit;

	}

	public function rename_profile(){


		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';

		if( $name == '' ){

			$result = array(
				'message' =>  esc_html__('Error #197! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);

		}else{

			$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';

			if( get_option( 'kc-profile-'.$slug ) === false ){

				$result = array(
					'message' => __('Error #501! could not find profile', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);

			}else{

				$data_db = get_option( 'kc-profile-'.$slug, true );

				$data_db[0] = $name;

				update_option( 'kc-profile-'.$slug, $data_db );


				$result = array(
					'message' =>  esc_html__('The profile has been changed', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'slug' => $slug
				);

			}

		}

		wp_send_json( $result );

		exit;

	}

	public function delete_profile(){


		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : '';

		if( $slug == '' ){

			$result = array(
				'message' =>  esc_html__('Error #167! The slug must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);

		}else{

			if( get_option( 'kc-profile-'.$slug ) === false ){

				$result = array(
					'message' => __('Error #723! could not find profile', 'kingcomposer'),
					'status' => 'fail',
					'slug' => $slug
				);
			}else{

				delete_option( 'kc-profile-'.$slug );

				$result = array(
					'message' =>  esc_html__('The profile has been deleted', 'kingcomposer'),
					'status' => 'success',
					'slug' => $slug
				);
			}


		}

		wp_send_json( $result );

		exit;

	}

	public function update_section(){

		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : '';

		if( $slug == '' ){

			$result = array(
				'message' =>  esc_html__('Error #193! The slug must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);

		}else{

			$id =  !empty( $_POST['id'] ) ? $_POST['id'] : '';
			$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';

			if( !empty( $data ) )
				$data = json_decode( base64_decode( $data ) );

			if( get_option( 'kc-profile-'.$slug ) === false ){

				global $kc;
				$profile = $kc->get_data_profile( $slug );

				if( $profile !== false ){

					$profile_data = json_decode( base64_decode( $profile[2] ) );
					$found = false;

					foreach( $profile_data as $key => $value ){
						if( $value->id == $id ){
							$profile_data[ $key ] = $data;
							$found = true;
						}
					}

					if( $found === false )
						array_push( $profile_data, $data );

					$data = base64_encode( json_encode( $profile_data ) );

				}else{

					$data = base64_encode( json_encode( array( $data ) ) );

				}

				add_option( 'kc-profile-'.$slug, array( $name, $data ) , null, 'no' );

				$result = array(
					'message' =>  esc_html__('The section has been updated', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'data' => $data,
					'slug' => $slug
				);


			}
			else
			{

				$data_db = get_option( 'kc-profile-'.$slug, true );

				$from_db = json_decode( base64_decode( $data_db[1] ) );

				if( is_array( $from_db ) ){

					$found = false;

					if( is_array( $from_db ) ){
						foreach( $from_db as $key => $val ){

							if( $val->id == $id ){
								$from_db[ $key ] = $data;
								$found = true;
							}

						}
					}

					if( !$found )
						array_push( $from_db, $data );

				}else{
					$from_db = array( $data );
				}

				$from_db = base64_encode( json_encode( $from_db ) );

				update_option( 'kc-profile-'.$slug, array( $data_db[0], $from_db ) );


				$result = array(
					'message' =>  esc_html__('The section has been updated', 'kingcomposer'),
					'status' => 'success',
					'name' => $data_db[0],
					'data' => $from_db,
					'slug' => $slug
				);

			}

		}

		wp_send_json( $result );

		exit;


	}

	public function delete_section(){

		$name =  isset( $_POST['name'] ) ? $_POST['name'] : '';
		$id =  isset( $_POST['id'] ) ? $_POST['id'] : '';
		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
		$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';

		if( get_option( 'kc-profile-'.$slug ) === false ){

			$sections = json_decode( base64_decode( $data ) );

			if( is_array( $sections ) ){

				$data = array();

				foreach( $sections as $key => $value ){

					if( !isset( $value->id ) )
						$value->id = rand( 100000, 1000000 );

					if( $value->id != $id )
						array_push( $data, $value );
				}

				$data = base64_encode( json_encode( $data ) );

				add_option( 'kc-profile-'.$slug, array( $name, $data ) , null, 'no' );

				$result = array(
					'message' =>  esc_html__('The section has been removed', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'data' => $data,
					'slug' => $slug
				);

			}else{

				$result = array(
					'message' =>  esc_html__('Error profile data structure #416', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);

			}

		}else{

			$data_db = get_option( 'kc-profile-'.$slug, true );

			$sections = @json_decode( base64_decode( $data_db[1] ) );

			if( is_array( $sections ) ){

				$data = array();

				foreach( $sections as $key => $value ){

					if( !isset( $value->id ) )
						$value->id = rand( 100000, 1000000 );

					if( $value->id != $id )
						array_push( $data, $value );

				}

				$data_db[1] = base64_encode( json_encode( $data ) );

				update_option( 'kc-profile-'.$slug, $data_db );


				$result = array(
					'message' =>  esc_html__('The section has been removed', 'kingcomposer'),
					'status' => 'success',
					'name' => $data_db[0],
					'data' => $data_db[1],
					'slug' => $slug
				);

			}else{

				$result = array(
					'message' =>  esc_html__('Error profile data structure #426', 'kingcomposer'),
					'status' => 'fail',
					'name' => $data_db[0],
					'slug' => $slug
				);

			}

		}

		wp_send_json( $result );

		exit;

	}

	public function update_option(){

        check_ajax_referer( 'kc-nonce', 'security' );

        $data = json_decode(base64_decode($_POST['options']), true);

        if( count( $data ) >0 ){
            foreach( $data as $k => $v ){
                echo $k;
                if( !empty( $k ))
                    update_option( $k, $v );
            }
        }

        $result = array(
            'message' =>  esc_html__('Update options successful', 'kingcomposer'),
            'status' => 'success',
        );

        wp_send_json( $result );
    }

	public function update_mapper(){

        check_ajax_referer( 'kc-mapper-nonce', 'security' );

        if (empty($_POST['tag']) || empty($_POST['task']) || (empty($_POST['data']) && $_POST['task'] == 'update'))
        {
	        wp_send_json(array(
	            'message' =>  esc_html__('Error: Missing data', 'kingcomposer'),
	            'stt' => 0,
	        ));
	        exit;
        }

        $data = json_decode(base64_decode($_POST['data']), true);

        $tag = $_POST['tag'];
        $task = $_POST['task'];

        $data = apply_filters('kc_update_mapper', $data, $tag, $task);

        $datas = get_option('kc_shortcodes_mapper', true);

        if (!$datas)
	        add_option('kc_shortcodes_mapper', array(), null, 'no');

        if (!is_array($datas))
	        $datas = array();

        if ($task == 'update' && is_array($data))
        {
	        $datas[$tag] = $data;
	        update_option('kc_shortcodes_mapper', $datas);
	        wp_send_json(array(
	            'message' =>  esc_html__('Update maps successful', 'kingcomposer'),
	            'stt' => 1,
	        ));
			exit;
        }

        if ($task == 'import' && is_array($data))
        {
	        update_option('kc_shortcodes_mapper', $data);
	        wp_send_json(array(
	            'message' =>  esc_html__('Update maps successful', 'kingcomposer'),
	            'stt' => 1,
	        ));
			exit;
        }

        if ($task == 'delete') {

	        unset ($datas[$tag]);

	        update_option('kc_shortcodes_mapper', $datas);
	        wp_send_json(array(
	            'message' =>  esc_html__('Update maps successful', 'kingcomposer'),
	            'stt' => 1,
	        ));
			exit;
        }

      	wp_send_json(array(
            'message' =>  esc_html__('Error: Incorrect data structure', 'kingcomposer'),
            'stt' => 0,
        ));
		exit;

    }

	public function instant_save(){

		check_ajax_referer( 'kc-nonce', 'security' );

        $addition_check = false;

		if (!isset( $_POST['id'] ) || !isset( $_POST['title'] ) || !isset( $_POST['content']))
		{
			echo $this->msg( __('Error: Invalid Post ID', 'kingcomposer'), 0 );
			exit;
		}

		$id = esc_attr( $_POST['id'] );

		if (get_post_status( $id ) === false)
		{
			echo $this->msg( __('Error: Post not exist', 'kingcomposer'), 0 );
			exit;
		}

		global $wpdb, $kc, $post;

		$get_post = get_post( $id );

        $addition_check = apply_filters('kc_before_instant_save', $addition_check, $id );

		if (!isset( $get_post ) || $kc->user_can_edit( $get_post ) === false || $addition_check == true)
		{
			echo $this->msg( __('Error: You do not have permission to edit this post', 'kingcomposer'), 0 );
			exit;
		}

		if (isset( $_POST['live_editor'])
			&& $_POST['live_editor'] == 'yes'
			&& $kc->check_pdk() === 3
			&& class_exists( 'kc_pro' )
		)
		{
			echo -3;
			exit;
		}

		$args = sanitize_post (array(

			'ID'           => $_POST['id'],
			'post_title'   => stripslashes( $_POST['title'] ),
			'post_content' => stripslashes($_POST['content']),

		), 'db' );

		$data = array(
			'post_content_filtered' => $args['post_content']
		);
		/*
		if (current_user_can ('publish_pages'))
			$data['post_status']  = 'publish';
		*/
		/*
		* Save the raw first
		*/
		$result = $wpdb->update(

		    $wpdb->prefix.'posts',

		    $data,

		    array( 'ID' => $id )
		);

		if ( false !== $result)
		{
            echo $this->msg( __('Your content has been saved Successful', 'kingcomposer'), 1 );
            kc_process_save_meta($id, $_POST['meta']);
        }
        else
        {
        	echo $this->msg( __('Error: could not save the content.', 'kingcomposer'), 0 );
		}

        /*
	    *	after save the raw content, we'll process cache content
	    */

        /*=====================================================*/

		/*
		*	Process content before save
		*/

		require_once KC_PATH.'/includes/kc.front.php';

		$content_processed = '';
		if (!empty($args['post_content']))
		{

			$ext = '<style type="text/css" id="kc-basic-css">'.kc_basic_layout_css().'</style>';
			/*$ext .= '<p class="kc-off-notice">'.__('Notice: You are using wrong way to display KC Content', 'kingcomposer').', <a href="http://docs.kingcomposer.com/do-shortcode-for-kc-content" target=_blank>Correct It Now</a></p>';*/

			$content_processed = $kc->do_shortcode ($args['post_content']);

			/*
			* 	we don't have body class if the plugin was disabled
			*/
			if (!empty($content_processed))
			{
				$content_processed = $content_processed;
				$content_processed = str_replace(
					array("\n", 'body.kc-css-system'),
					array("", 'html body'),
					$content_processed
				);
			}
		}


		// reset data after save the raw
		$data = array('post_content' => $content_processed);

		// Save post from live editor

		if (isset( $_POST['task']  ) && $_POST['task'] == 'frontend')
		{

			$wpdb->update(

			    $wpdb->prefix.'posts',

			    $data,

			    array( 'ID' => $id )
			);

			exit;

		}

		// Save post from backend editor

		$data['post_title'] = stripslashes( $args['post_title'] );

		$result = $wpdb->update(

		    $wpdb->prefix.'posts',

		    $data,

		    array( 'ID' => $id )
		);


		exit;

	}

	public function suggestion(){

		check_ajax_referer( 'kc-nonce', 'security' );

		$field_name = isset($_POST['field_name']) ? esc_attr($_POST['field_name']) : 'kc_std';

		if (has_filter('kc_autocomplete_'.$field_name))
		{
			$data = apply_filters ('kc_autocomplete_'.$field_name, $_POST);
			$data['__session'] = isset($_POST['session']) ? $_POST['session'] : '';
			wp_send_json ($data);
			exit;
		}

		$data = array( '__session' => isset($_POST['session']) ? $_POST['session'] : '' );

		$args = array(
			's' => isset( $_POST['s'] ) ? $_POST['s'] : '',
		    'post_type' => !empty( $_POST['post_type'] ) ? esc_attr( $_POST['post_type'] ) : 'any',
		    'category' => isset( $_POST['category'] ) ? esc_attr( $_POST['category'] ) : '',
		    'category_name' => isset( $_POST['category_name'] ) ? esc_attr( $_POST['category_name'] ) : '',
		    'numberposts' => !empty( $_POST['numberposts'] ) ? esc_attr( $_POST['numberposts'] ) : 120,
		);

		if( isset( $_POST['taxonomy'] ) && !empty( $_POST['taxonomy'] ) ){

			$taxonomyObj = get_taxonomy(esc_attr( $_POST['taxonomy'] ));

			if( isset( $taxonomyObj ) && isset( $taxonomyObj->object_type ) && isset( $taxonomyObj->object_type[0] ) )
				$args['post_type'] = $taxonomyObj->object_type[0];

			$terms = get_terms( array(
			    'taxonomy' => esc_attr($_POST['taxonomy']),
			    'hide_empty' => true,
			));
			$list_terms = array();
			foreach( $terms as $k => $term ){

				if( !isset( $data[ $_POST['taxonomy'] ] ) )
					$data[ $_POST['taxonomy'] ] = array();

				$data[ $_POST['taxonomy'] ][] = $term->slug.':'.esc_html(str_replace( array(':',','), array('',''), $term->name));

			}
	    }else{

			if ( 0 === strlen( $args['s'] ) ) {
				unset( $args['s'] );
			}
			add_filter( 'posts_search', 'kc_filter_search', 500, 2 );
			$posts = get_posts( $args );

			if ( is_array( $posts ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					if( !isset( $data[ $post->post_type ] ) )
						$data[ $post->post_type ] = array();
					$data[ $post->post_type ][] = $post->ID.':'.esc_html(str_replace( array(':',','), array('',''), $post->post_title));
				}
			}

		}

		wp_send_json( $data );
		exit;

	}

	public function tmpl_storage(){

		check_ajax_referer( 'kc-nonce', 'security' );

		global $kc;
		$kc->convert_paramTypes_cache();

		require_once KC_PATH.'/includes/kc.templates.php';
		do_action('kc_tmpl_storage');
		
		echo '<!----END_KC_TMPL---->';
		
		exit;

	}

	public function kcp_access(){

		check_ajax_referer( 'kc-verify-nonce', 'security' );

		$license = isset( $_POST['license'] ) ? esc_html( $_POST['license'] ) : '';

		if( strlen( $license ) != 41 )
		{
			echo '-2';
			exit;
		}

		global $kc;
		$data = $kc->kcp_remote($license, 'kcp_access');

		if( $data === false )
		{
			echo '-2';
			exit;
		}

		wp_send_json( $data );

		exit;

	}

	public function revoke_domain(){

		check_ajax_referer( 'kc-verify-nonce', 'security' );

		global $kc;
		$pdk = $kc->get_pdk();

		$license = $pdk['key'];

		if( strlen( $license ) != 41 )
		{
			$data = array('stt' => 0, 'message' => 'Error');
		}
		else
		{
			$data = $kc->kcp_remote( $license, 'revoke_domain' );
		}
		
		wp_send_json( $data );

		exit;

	}

	public function download_pro(){

		check_ajax_referer( 'kc-pro-download', 'security' );

		$skin_args = array(
			'type'   => 'web',
			'title'  => 'Install KC Pro!',
			'url'    => (is_ssl() ? 'https' : 'http').'://kingcomposer.com/downloads/kc_pro.zip?kc_store_action=download',
			'nonce'  => 'install-plugin_kc_pro',
			'plugin' => '',
			'api'    => null,
			'extra'  => array('slug' => 'kc_pro'),
		);

		if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin = new Plugin_Installer_Skin( $skin_args );
		$upgrader = new Plugin_Upgrader( $skin );

		echo '<div class="kc-pro-download-result">';

		if( $upgrader->install($skin_args['url']) === true ){

			$result = activate_plugin('kc_pro/kc_pro.php');

			if ( is_wp_error( $result ) ) {
				echo '<p>'.$result->get_error_message().'</p>';
			}else{
				echo '<h3 class="active-success">Active plugin successfully, reloading the page...</h3>';
			}
		}

		echo '<br/><br /></div>';

		exit;

	}

	public function update_plugin(){

		check_ajax_referer( 'kc-nonce-update', 'security' );

		if (!isset($_POST['slug']) || ($_POST['slug'] != 'kingcomposer' && $_POST['slug'] != 'kc_pro'))
		{
			echo '-1';
			exit;
		}

		$slug = esc_attr ($_POST['slug']);
		$base = $slug.'/'.$slug.'.php';
		$update_plugin = get_site_transient( 'update_plugins' );

		if (!isset($update_plugin->response[$base]))
		{
			echo '-1';
			exit;
		}

		$package = $update_plugin->response[$base]->package;

		$skin_args = array(
			'type'   => 'web',
			'title'  => 'Install '.$slug,
			'url'    => $package,
			'nonce'  => 'install-plugin_'.$slug,
			'plugin' => '',
			'api'    => null,
			'extra'  => array('slug' => $slug),
		);

		if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin = new Plugin_Installer_Skin( $skin_args );
		$upgrader = new Plugin_Upgrader();

		echo '<div class="kc-pro-download-result">';

		if( $upgrader->upgrade($package) === true ){

			$result = activate_plugin($base);

			if ( is_wp_error( $result ) ) {
				echo '<p>'.$result->get_error_message().'</p>';
			}else{
				echo '<h3 class="active-success">Active plugin successfully, reloading the page...</h3>';
			}
		}

		echo '<br/><br /></div>';

		exit;

	}

	public function add_font(){

		check_ajax_referer( 'kc-fonts-nonce', 'security' );

		$kc_fonts = get_option('kc-fonts');

		if( !is_array( $kc_fonts ) ){
			$kc_fonts = array();
			add_option('kc-fonts', $kc_fonts);
		}

		$family = esc_attr($_POST['family']);
		$subsets = esc_attr($_POST['subsets']);
		$variants = esc_attr($_POST['variants']);

		$data = array(
				'message' => '',
				'stt' => 0,
				'data' => $kc_fonts
			);

		if( empty( $family ) ){

			$data['message'] = __('Error, missing font family', 'kingcomposer');

		}else if( isset( $kc_fonts[$family] ) ){

			$data['message'] = __('Error, font family already exists', 'kingcomposer');

		}else if( count($kc_fonts) >= 9 ){

			$data['message'] = __('Error, You have added too much fonts. Your page will load very slowly.', 'kingcomposer');

		}else{

			$kc_fonts[$family] = array( $subsets, $variants );
			update_option('kc-fonts', $kc_fonts);

			$data['message'] = 'Your font has been added successful';
			$data['stt'] = 1;
			$data['data'] = $kc_fonts;

		}

		wp_send_json( $data );
		exit;

	}

	public function update_font(){

		check_ajax_referer( 'kc-fonts-nonce', 'security' );

		if( get_option('kc-fonts') === false ){
			add_option('kc-fonts', $_POST['datas'], null, 'no');
		}else{
			update_option('kc-fonts', $_POST['datas']);
		}

		$data = array(
				'message' => __('Your settings have been updated', 'kingcomposer'),
				'stt' => 1,
				'data' => $_POST['datas']
			);

		wp_send_json( $data );
		exit;

	}

	public function delete_font(){

		check_ajax_referer( 'kc-fonts-nonce', 'security' );

		$kc_fonts = get_option('kc-fonts');

		if( !is_array( $kc_fonts ) ){
			$kc_fonts = array();
			add_option('kc-fonts', $kc_fonts, null, 'no');
		}

		$family = esc_attr($_POST['family']);

		$data = array(
				'message' => '',
				'stt' => 0,
				'data' => $kc_fonts
			);

		if( empty( $family ) ){

			$data['message'] = __('Error, missing font family', 'kingcomposer');

		}else if( !isset( $kc_fonts[$family] ) ){

			$data['message'] = __('Error, font family does not exists', 'kingcomposer');

		}else{

			unset( $kc_fonts[$family] );
			update_option('kc-fonts', $kc_fonts);

			$data['message'] = 'Your font has been deleted successful';
			$data['stt'] = 1;
			$data['data'] = $kc_fonts;

		}

		wp_send_json( $data );
		exit;

	}

	public function load_sections(){

		check_ajax_referer( 'kc-nonce', 'security' );

        $msg_return = 'Successful';

		if( isset( $_POST['isdelete'] ) && !empty( $_POST['isdelete'] ) ){
		    global $post;
            $datap = $post;
            $post = get_post( $_POST['isdelete'] );
            setup_postdata( $post );
            if( current_user_can('edit_post') ){
                @wp_delete_post( esc_attr($_POST['isdelete']) );
            }else{
                $msg_return = 'You do not have permission to remove this section';
            }

            setup_postdata( $datap );
		}

		$data = array(
			'message' => 'Error: Unknow',
			'stt' => 0,
			'data' => array(
				's' => isset($_POST['s']) ? esc_attr($_POST['s']) : '',
				'term' => isset($_POST['term']) ? esc_attr($_POST['term']) : '',
				'paged' => isset($_POST['paged']) ? esc_attr($_POST['paged']) : 1,
				'per_page' => isset($_POST['per_page']) ? esc_attr($_POST['per_page']) : 10,
				'type' => isset($_POST['type']) ? esc_attr($_POST['type']) : 'kc-section',
				'cols' => isset($_POST['cols']) ? esc_attr($_POST['cols']) : 2,
				'items' => array(),
				'terms' => array(),
				'total' => 0,
				'count' => 0,
			)
		);

		global $kc;
		$prebuilt = $kc->get_prebuilt_templates('registered');

		if (empty($_POST['type']) || strpos($_POST['type'], 'prebuilt-templates-') === 0) {
			if (count($prebuilt) > 0)
				$data['data']['type'] = 'prebuilt-templates-('.count($prebuilt).')';
			else $data['data']['type'] = 'kc-section';
		}

		if ($data['data']['type'] == 'prebuilt-templates-('.count($prebuilt).')') {
			$data = $kc->get_prebuilt_templates('load_sections', $data);
			wp_send_json( $data );
			exit;
		}

		$taxonomies = get_object_taxonomies( array( 'post_type' => $data['data']['type'] ) );

		$data['data']['terms'] = array();
		foreach( $taxonomies as $taxonomy ){
			$data['data']['terms'] = $this->get_terms( 0, '', $taxonomy, $data['data']['terms'] );
		}

		$query = array(
			'post_type' => $data['data']['type'],
			'posts_per_page' => $data['data']['per_page'],
			'post_status'  => 'publish'
		);

		$query['paged'] = $data['data']['paged'];

		if( !empty( $data['data']['term'] ) && strpos( $data['data']['term'], '|' ) !== false ){

			$term = explode( '|', $data['data']['term'] );

			$query['tax_query'] = array(
		        array(
		            'taxonomy' => $term[1],
		            'field' => 'term_id',
		            'terms' => $term[0],
		        )
		    );
		}

		if( !empty( $data['data']['s'] ) )
			$query['s'] = $data['data']['s'];

		$sections = new WP_Query($query);

		if ($sections->have_posts()) :

	    	while ($sections->have_posts()) :  $sections->the_post();

				$terms = get_the_terms( get_the_ID(), implode(',',$taxonomies) );

				$term_slugs = array();
				if( $terms && ! is_wp_error($terms) ){

					foreach ($terms as $term) {
					    $term_slugs[] = $term->slug;
					}

				}

				$categories = implode( ', ', $term_slugs);

				$meta = get_post_meta( get_the_ID(), 'kc_data', true );
				$preview = get_the_post_thumbnail_url();
				
				if (!empty($meta) && isset($meta['thumbnail']) && !empty($meta['thumbnail']))
					$preview = $meta['thumbnail'];

				$data['data']['items'][] = array(
					'title' => html_entity_decode( get_the_title() ),
					'preview' => $preview,
					'date' => get_the_date('F d, Y'),
					'categories' => $term_slugs,
					'id' => get_the_ID()
				);

			endwhile;

			$data['message'] = $msg_return;
			$data['stt'] = 1;
			$data['data']['total'] = $sections->max_num_pages;
			$data['data']['count'] = $sections->found_posts;

		else:

			$data['message'] = $kc->apply_filters('kc_section_blank', '<span style="font-size: 50px;">\\(^Д^)/</span><br /><br /><span style="font-size: 16px">'.__('Oops, there are no section found. You can create section from rows or ', 'kingcomposer').' <a href="#" onclick="window.location.href=jQuery(\'a.kc-add-new-section\').attr(\'href\');">Add New</a></span><br /><br /><img src="'.KC_URL.'/assets/images/new_section.png" width="836" /><br /><span class="kc-notice" style="font-size: 14px;width: inherit">'.__('You can use the prebuilt templates from export *.xml via the method', 'kingcomposer').' <strong>kc_prebuilt_template()</strong> <a href="http://docs.kingcomposer.com/prebuilt-templates/" target=_blank>Read More</a></span>');

		endif;

		wp_reset_query();

		wp_send_json( $data );

		exit;

	}

	public function load_section(){

		check_ajax_referer( 'kc-nonce', 'security' );

		$data = array(
			'message' => 'Error: Unknow',
			'stt' => 0,
			'data' => ''
		);

		$id = isset($_POST['id']) ? $_POST['id'] : '';

		if (isset($_POST['xml_pack']) && !empty($_POST['xml_pack'])) {

			global $kc;
			$pack = esc_attr($_POST['xml_pack']);
			$registered_pack = $kc->get_prebuilt_templates();

			if (!isset($registered_pack[$pack])) {
				$data = array(
					'message' => 'Error: The template pack does not exist or invalid name',
					'stt' => 0,
					'data' => ''
				);
			} else {

				$post = kc_get_template_xml($registered_pack[$pack], $id);

				if ($post[0] === null) {
					$data = array(
						'message' => 'Error: The content returns null',
						'stt' => 0,
						'data' => ''
					);
				}else{
					$data = array(
						'message' => 'Successful',
						'stt' => 1,
						'data' => $post[0],
						'meta' => $post[1]
					);
				}
			}

		} else {

			$content = stripslashes_deep( kc_raw_content( $id ));

			if ( FALSE === get_post_status( $id ) ){
				$data['message'] = __( 'Error: The section does not exist or has been removed', 'kingcomposer' );
			}else if( empty($content) || empty($id) ){
				$data['message'] = __( 'Error: The section content is empty', 'kingcomposer' );
			}else{
				$data['stt'] = 1;
				$data['message'] = 'Successful';
				$data['data'] = $content;
			}

		}
		wp_send_json( $data );
		exit;

	}

	public function push_section(){

		check_ajax_referer( 'kc-nonce', 'security' );

		$data = array(
			'message' => 'Error: Unknow',
			'stt' => 0,
			'data' => ''
		);

		$id = isset($_POST['id']) ? esc_attr( $_POST['id'] ) : '';
		$content = isset($_POST['content']) ? $_POST['content'] : '';
		$overwrite = isset($_POST['overwrite']) ? $_POST['overwrite'] : false;

		if( $overwrite != 'true' ){
			$content = get_post_field('post_content', $id).$content;
		}

		if ( FALSE === get_post_status( $id ) ){
			$data['message'] = __( 'Error: The section does not exist or has been removed', 'kingcomposer' );
		}else{

			$arg = sanitize_post( array( 'ID' => $id, 'post_content' => $content ), 'db' );
			$post_id = wp_update_post( $arg );

			if (is_wp_error($post_id)) {
				$data['message'] = '';
				$errors = $post_id->get_error_messages();
				foreach ($errors as $error) {
					$data['message'] .= $error;
				}
			}else{

				$data['stt'] = 1;
				$data['message'] = 'Successful';
				$data['data'] = '';

				$meta = get_post_meta( $id , 'kc_data', true );
				if( !is_array( $data ) ){
					$meta = array( "mode" => "kc", "classes" => "", "css" => "" );
				}else $meta['mode'] = 'kc';

				if( get_post_meta( $id, 'kc_data' ) === false )
					add_post_meta( $id, 'kc_data' , $meta );
				else update_post_meta( $id , 'kc_data' , $meta );

			}
		}

		wp_send_json( $data );
		exit;

	}

	public function load_element_via_ajax(){

		check_ajax_referer( 'kc-nonce', 'security' );

		if( !isset( $_POST['model'] ) || !isset( $_POST['code'] ) ){
			wp_send_json( array( 'status' => '-1' ) );
			exit;
		}

		require_once KC_PATH.'/includes/kc.front.php';

		global $kc, $kc_front, $post;

		if (isset( $_POST['ID'] ) && isset($post))
			$post->ID = $_POST['ID'];

		$code = isset( $_POST['code'] ) ? trim( base64_decode( esc_attr( $_POST['code'] ) ) ) : '';

		$model = isset( $_POST['model'] ) ? esc_attr( $_POST['model'] ) : '';

		$pattern_filter = get_shortcode_regex( array('kc_row') );
		$atts = preg_replace( "/$pattern_filter/", '$3', $code );
		$atts = shortcode_parse_atts( $atts );

		if( is_array( $atts ) && isset( $atts['__section_link'] ) ){

			$sid = $atts['__section_link'];
			$code = kc_raw_content( $sid );
			$title = get_post_field('post_title', $sid );

			if( !empty( $code ) ){

				wp_send_json( array(
					'status' => '1',
					'model' => $model,
					'html' => $code,
					'__section_link' => $sid,
					'__section_title' => $title
				));

				exit;

			}else{
				wp_send_json( array(
					'status' => '0',
					'model' => $model,
					'html' => '',
					'message' => __('The content is empty, please edit section to add content', 'kingcomposer'),
					'__section_link' => $sid,
					'__section_title' => $title
				));
				exit;
			}

		}

		$code = $kc_front->do_filter_shortcode($code);
		$code = trim( $code );

		$code = do_shortcode( $code );

		if( empty( $code ) ){
			$code = '<div class="kc-infinite-loop">'.__('The content is empty', 'kingcomposer').'</div>';
		}

		wp_send_json( array(
			'status' => '1',
			'model' => $model,
			'html' => '<!--kc s '.$model.'-->'.$code.'<!--kc e '.$model.'-->',
			'css' => $kc_front->get_global_css(),
			'callback' => $kc->live_js_callback
		));

		exit;

	}

	public function install_online_preset(){

		$data = isset($_POST['kc-online-preset-data']) ? esc_attr($_POST['kc-online-preset-data']) : '';
		$link = isset($_POST['kc-online-preset-link']) ? esc_url($_POST['kc-online-preset-link']) : '';
		$link = str_replace( 'http://features.kingcomposer.com/', 'https://kingcomposer.com/presets/', $link);
		$callback = '
		<script type="text/javascript">
			top.kc.cfg.preset_link = "'.$link.'";
			top.kc.backbone.push(\''.str_replace( "\n", '\'+"\n"+\'', base64_decode($data)).'\');
			top.kc.tools.popup.close_all();
		</script>';

		echo $callback;

		exit;

	}

	public function enable_optimized(){

		check_ajax_referer( 'kc-nonce', 'security' );

		require_once KC_PATH.'/includes/kc.optimized.php';

		$settings = isset($_POST['settings']) ? $_POST['settings'] : array();
		$id = isset($_POST['id']) ? $_POST['id'] : 0;

		$settings = array_merge(array("enable" => "", "global" => "", "dvanced" => ""), (array)$settings);

		$optimized = new kc_optimized();
		$data = array(
			'msg' => __('Error message', 'kingcomposer'),
			'stt' => 0
		);

		if (isset($settings['clear_cache']) && $settings['clear_cache'] == 'on') {

			if (!is_dir(ABSPATH.'optimized') || $optimized->delete_cache()) {
				$data = array(
					'msg' => '<i class="fa-check-square"></i> '.__('All cache have been successfully deleted', 'kingcomposer'),
					'stt' => 1
				);
			}else $data['msg'] = '<i class="fa-warning"></i> '.__('Your cache were cleaned or could not delete cache', 'kingcomposer');

			wp_send_json( $data );
			exit;

		}

		if ($settings['enable'] == 'on') {
			// Enable optimized
			$data = $optimized->check_htaccess($settings['advanced']);
			if ($data['stt'] == 1) {

				if (get_option('kc_optimized') === false)
					add_option('kc_optimized', $settings, null, 'no');
				else update_option('kc_optimized', $settings);

			}
		} else {
			if ($optimized->deactive()) {

				$data = array(
					'msg' => __('Deactive successful', 'kingcomposer'),
					'stt' => 1
				);

				if (get_option('kc_optimized') === false)
					add_option('kc_optimized', $settings, null, 'no');
				else update_option('kc_optimized', $settings);

			} else {
				$data = array(
					'msg' => __('Could not deactive, please check the writable permission', 'kingcomposer'),
					'stt' => 0
				);
			}
		}

		wp_send_json( $data );
		exit;

	}

	public function switch_off() {
		
		global $wpdb;
		
		$id = $_POST['id'];
		$mode = $_POST['mode'];
		/*
		$wpdb->update(

			$wpdb->prefix.'posts',

			array(
				'ID' => $id,
				'post_content_filtered' => ''
			),

			array( 'ID' => $id )
		);
		*/
		kc_process_save_meta($id, array('mode' => $mode));
		
		echo 'success';
		exit;
		
	}

	public function share_section(){

		check_ajax_referer( 'kc-nonce', 'security' );

		$data = array(
			'msg' => __('Unknow reason', 'kingcomposer'),
			'stt' => 0
		);

		$args = array(
			'id' => isset($_POST['id']) ? esc_attr($_POST['id']) : '',
			'label' => isset($_POST['label']) ? esc_attr(sanitize_title($_POST['label'])) : '',
			'name' => isset($_POST['name']) ? esc_attr($_POST['name']) : '',
			'email' => isset($_POST['email']) ? esc_attr($_POST['email']) : '',
			'thumbnail' => isset($_POST['thumbnail']) ? esc_attr($_POST['thumbnail']) : '',
			'source' => KC_SITE
		);

		if (empty($args['id']) || empty($args['name']) || empty($args['email']) || empty($args['content'])) {
			$data = array(
				'msg' => __('Your content is empty', 'kingcomposer'),
				'stt' => 0
			);
		}

		$args['content'] = kc_raw_content($args['id']);

		$response = wp_remote_post((is_ssl() ? 'https' : 'http').'://hub.kingcomposer.com/submit/', array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'body' => $args,
		));

		if (is_wp_error($response)) {
		  	$data = array('msg' => $response->get_error_message(), 'stt' => 0);
		} else {

			$body = wp_remote_retrieve_body($response);

			if (!empty($body) && $body == 1) {
				$data = array(
					'msg' => __('Thank you for submitting!', 'kingcomposer'),
					'stt' => 1
				);
			}else{
				$data = array('msg' => $body, 'stt' => 0);
			}
		}

		wp_send_json( $data );
		exit;

	}


	public function installed_extensions () {

		check_ajax_referer( 'kc-nonce', 'security' );
		
		$task = isset($_POST['task']) ? esc_attr($_POST['task']) : '';
		$name = isset($_POST['name']) ? esc_attr($_POST['name']) : '';
		$path = untrailingslashit(ABSPATH).KDS.'wp-content'.KDS.'uploads'.KDS.'kc_extensions'.KDS;
		$data = array('msg' => 'Unknow', 'stt' => 0);

		if ( empty($task) || empty($name) || !in_array($task, array('active', 'deactive', 'delete'))) {
			$data = array('msg' => 'Invalid action', 'stt' => 0);
		} else {

			$extensions = (array) get_option( 'kc_active_extensions', array() );
			$path = $path.$name.KDS;

			switch ($task) {

				case 'active' :

					if (file_exists($path.'index.php')) {
						require_once($path.'index.php');
						$ex_class = 'kc_extension_'.str_replace('-', '_', sanitize_title($name));

						if (class_exists($ex_class)) {

							$extensions[$name] = 1;
							if (!add_option('kc_active_extensions', $extensions, null, 'no'))
								update_option('kc_active_extensions', $extensions);
							$data = array('msg' => 'Successful', 'stt' => 1);

						}else{
							$data = array(
								'msg' => 'Could not find the PHP classname "'.$ex_class.'" in the extenstion "/'.$name.KDS.'index.php"',
								'stt' => 0
							);
						}
					} else {
						$data = array(
							'msg' => 'Could not find the extension file /'.$name.KDS.'index.php',
							'stt' => 0
						);
					}

				break;
				case 'deactive' :

					unset($extensions[$name]);
					update_option('kc_active_extensions', $extensions);
					$data = array('msg' => 'Successful', 'stt' => 1);

				break;
				case 'delete' :

					if (is_dir($path) && kc_remove_dir($path)) {
						unset($extensions[$name]);
						update_option('kc_active_extensions', $extensions);
						$data = array('msg' => 'Successful', 'stt' => 1);
					}else $data = array('msg' => 'Could not delete extension', 'stt' => 0);

				break;
			}
		}

		wp_send_json( $data );
		exit;

	}
	
	public function store_extensions() {
		
		global $kc;
		
		check_ajax_referer( 'kc-nonce', 'security' );
		
		$pdk = $kc->get_pdk();
		$msg = array();
		$id = $_POST['id'];
		
		/*
		*	Start downloading extentions
		*/
		
		
		$ex_path = WP_CONTENT_DIR.KDS.'uploads'.KDS.'kc_extensions'.KDS;
		$file = time();
		$file = $ex_path.$file.'.zip';
		
		if (!is_dir($ex_path) && !mkdir($ex_path, 07555)) {
			$msg['status'] = 'error';
			$msg['errors'] = array('Error: Could not create extensions folder');
			echo json_encode($msg);
			exit;
		}
		
		$ref = array(
			"referer=".$_SERVER['HTTP_HOST'],
			"domain=".$pdk['domain'],
			"date=".$pdk['date'],
			"key=".$pdk['key'],
			"id=".$id
		);
		
		$ref = implode('&', $ref);
		
		$download = download_url((is_ssl() ? 'https' : 'http').'://extensions.kingcomposer.com/download/?'.$ref);
		
		if (is_wp_error($download)) {
			$data = 0;
		} else {
			$data = file_put_contents($file, file_get_contents($download));
		}
		
		/*
		*	End downloading extentions
		*/
		
		if ($data === 0) {		
			$msg['status'] = 'error';
			$msg['errors'] = array($file.'Error: Could not download file, make sure that the fopen() funtion on your server is enabled');
		} else if ($data < 250) {
			$msg['status'] = 'error';
			$erro = @file_get_contents($file);
			$msg['errors'] = array('Error: '.$erro);
		} else if (!class_exists('ZipArchive')) {
			$msg['status'] = 'error';
			$msg['errors'] = array('Error: Your server does not support ZipArchive to extract extensions');
		} else {
			$zip = new ZipArchive;
			$res = $zip->open($file);
			
			if ($res === TRUE) {
				
				$zip->extractTo($ex_path);
				$zip->close();
				
				if (is_dir($ex_path.'__MACOSX'))
					kc_remove_dir($ex_path.'__MACOSX');
				
				$msg['status'] = 'success';
				
			} else {
				$msg['status'] = 'error';
				$msg['errors'] = array($this->main->lang('Error: Could not open file').$file);
			}
		}
		
		@unlink($file);
		
		echo json_encode($msg);
		exit;
		
	}
	
	public function msg( $s = '', $t = 1 ){
		if( $t == 1 )
			return '<h3 class="mesg success"><i class="et-happy"></i><br />'.$s.'</h3>';
		else return '<h3 class="mesg error"><i class="et-sad"></i><br />'.$s.'</h3>';
	}

	private function get_terms( $parent = 0, $spacing = '', $taxonomy, $data = array() ){

		$terms = get_terms( array(
		    'taxonomy' => $taxonomy,
		    'hide_empty' => false,
		    'parent' => $parent
		));

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		    foreach ( $terms as $term ){
			    $data[] = array( 'name' => $spacing.$term->name, 'id' => $term->term_id, 'taxonomy' => $term->taxonomy );
			    $data = $this->get_terms( $term->term_id, $spacing.' - ', $taxonomy, $data );
		    }
		}

		return $data;

	}

}

#Start kc_Ajax
new kc_ajax();
