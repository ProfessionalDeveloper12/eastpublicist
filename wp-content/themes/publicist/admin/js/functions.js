(function($) {
	
	"use strict";
	
	/* Event - Document Ready */
	$(document).on("ready",function() {
		
		if( $('[id*="_cf_metabox_page"]').length && $('[class*="-owlayout"]').length ) {

			$('[class*="-owlayout"] li input[type="radio"]:checked').parent().addClass("selected");
			
			$('[class*="-owlayout"] li input[type=radio]').on("change", function(e) {

				e.preventDefault();

				if( $(this).attr("name") == "rosespa_cf_page_owlayout" ) {
					$('[class*="-owlayout"] li input[name="rosespa_cf_page_owlayout"]').parent().removeClass("selected");
				}
				else if( $(this).attr("name") == "rosespa_cf_sidebar_owlayout" ) {
					$('[class*="-owlayout"] li input[name="rosespa_cf_sidebar_owlayout"]').parent().removeClass("selected");
				}
				else {
				}

				$(this).parent().addClass("selected");
			});
		}
		
		if( $('.cmb2-id-publicist-cf-sidebar-owlayout').length ) {

			if( $('#publicist_cf_sidebar_owlayout4:checked').length ) {
				$('.cmb2-id-publicist-cf-widget-area').slideUp(500);
			}
			$('[id*="publicist_cf_sidebar_owlayout"]').live('change', function() {

				if( $('#publicist_cf_sidebar_owlayout4:checked').length ) {
					$('.cmb2-id-publicist-cf-widget-area').slideUp(500);
				}
				else {
					$('.cmb2-id-publicist-cf-widget-area').slideDown(500);
				}
			});
		}
		
		/* - Add Anchor link for button */
		$(".redux-action_bar").each(function() {
			$(this).find(".fa-save,#redux_save").wrapAll('<a class="control_button save" title="Save Changes"></a>');
			$(this).find(".fa-undo,#redux-defaults-section").wrapAll('<a class="control_button reset" title="Reset Section"></a>');
			$(this).find(".fa-refresh,#redux-defaults").wrapAll('<a class="control_button resetall" title="Reset All"></a>');
		});
		
		/* Disable : Page Editor */
		if( $('body.post-type-page #postdivrich').length && $('select#page_template').length ) {

			/* Sidebar Layout */
			if( $('select#page_template').val() != 'default' ) {
				$('body.post-type-page #postdivrich').slideUp(500);
			}

			$('select#page_template').live('change', function() {

				/* Sidebar Layout */
				if( $('select#page_template').val() != 'default' ) {
					$('body.post-type-page #postdivrich').slideUp(500);
				}
				else {
					$('body.post-type-page #postdivrich').slideDown(500);
					$(window).scrollTop($(window).scrollTop()+1);
				}

			});
		}
	
		/* Post : Formats */
		if( $('#post-formats-select').length ) {

			if( $('input[id="post-format-video"]').is(':checked') ) {
				$('#publicist_cf_metabox_post_video').slideDown(500); /* Enable Video */
				$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
				$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
			}
			else if( $('input[id="post-format-quote"]').is(':checked') ) {
				$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
				$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
				$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
			}
			else if( $('input[id="post-format-gallery"]').is(':checked') ) {
				$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
				$('#publicist_cf_metabox_post_gallery').slideDown(500); /* Enable Gallery */
				$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
			}
			else if( $('input[id="post-format-audio"]').is(':checked') ) {
				$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
				$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
				$('#publicist_cf_metabox_post_audio').slideDown(500); /* Enable Audio */
			}
			else {
				$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
				$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
				$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
			}
 
			/* On Change : Event */
			$('#post-formats-select').live('change', function() {

				var highlight_css = '"border":"2px solid green", "-webkit-transition":"all 0.3s ease 0s", "-moz-transition":"all 0.3s ease 0s", "-o-transition":"all 0.3s ease 0s", "-ms-transition":"all 0.3s ease 0s", "transition":"all 0.3s ease 0s"';
				
				if( $('input[id="post-format-video"]').is(':checked') ) {
					$('#publicist_cf_metabox_post_video').slideDown(500); /* Enable Video */
					$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
					$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */

					$("#publicist_cf_metabox_post_video").css({"border":"2px solid green", "-webkit-transition":"all 0.3s ease 0s", "-moz-transition":"all 0.3s ease 0s", "-o-transition":"all 0.3s ease 0s", "-ms-transition":"all 0.3s ease 0s", "transition":"all 0.3s ease 0s"});
					$('html, body').animate({ scrollTop: $("#publicist_cf_metabox_post_video").offset().top }, 'slow' );
				}
				else if( $('input[id="post-format-audio"]').is(':checked') ) {
					$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
					$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
					$('#publicist_cf_metabox_post_audio').slideDown(500); /* Enable Audio */
				
					$("#publicist_cf_metabox_post_audio").css({"border":"2px solid green", "-webkit-transition":"all 0.3s ease 0s", "-moz-transition":"all 0.3s ease 0s", "-o-transition":"all 0.3s ease 0s", "-ms-transition":"all 0.3s ease 0s", "transition":"all 0.3s ease 0s"});
					$('html, body').animate({ scrollTop: $("#publicist_cf_metabox_post_audio").offset().top }, 'slow' );
				}
				else if( $('input[id="post-format-quote"]').is(':checked') ) {
					$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
					$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
					$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
				}
				else if( $('input[id="post-format-gallery"]').is(':checked') ) {
					$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
					$('#publicist_cf_metabox_post_gallery').slideDown(500); /* Enable Gallery */
					$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */

					$("#publicist_cf_metabox_post_gallery").css({"border":"2px solid green", "-webkit-transition":"all 0.3s ease 0s", "-moz-transition":"all 0.3s ease 0s", "-o-transition":"all 0.3s ease 0s", "-ms-transition":"all 0.3s ease 0s", "transition":"all 0.3s ease 0s"});
					$('html, body').animate({ scrollTop: $("#publicist_cf_metabox_post_gallery").offset().top }, 'slow' );
				} 
				else {
					$('#publicist_cf_metabox_post_video').slideUp(500); /* Disable Video */
					$('#publicist_cf_metabox_post_gallery').slideUp(500); /* Disable Gallery */
					$('#publicist_cf_metabox_post_audio').slideUp(500); /* Disable Audio */
				}
			});
		}

		/* Post : Video */
		if( $('#publicist_cf_post_video_source').val() == 'video_link' ) {
			$('.cmb2-id-publicist-cf-post-video-link').slideDown(500); /* Enable Video Link */
			$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500); /* Disable Embed */
			$('.cmb2-id-publicist-cf-post-video-local').slideUp(500); /* Disable Video Local */
		}
		else if ( $('#publicist_cf_post_video_source').val() == 'video_embed_code' ) {
			$('.cmb2-id-publicist-cf-post-video-link').slideUp(500); /* Disable Video Link */
			$('.cmb2-id-publicist-cf-post-video-embed').slideDown(500); /* Enable Embed */
			$('.cmb2-id-publicist-cf-post-video-local').slideUp(500); /* Disable Video Local */
		}
		else if ( $('#publicist_cf_post_video_source').val() == 'video_upload_local' ) {
			$('.cmb2-id-publicist-cf-post-video-link').slideUp(500); /* Disable Video Link */
			$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500); /* Disable Embed */
			$('.cmb2-id-publicist-cf-post-video-local').slideDown(500); /* Enable Video Local */
		}
		else {
			$('.cmb2-id-publicist-cf-post-video-link').slideUp(500); /* Disable Video Link */
			$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500); /* Disable Embed */
			$('.cmb2-id-publicist-cf-post-video-local').slideUp(500); /* Disable Video Local */
		}

		/* On Change : Event */
		$('#publicist_cf_post_video_source').live('change', function() {

			if( $('#publicist_cf_post_video_source').val() == 'video_link' ) {
				$('.cmb2-id-publicist-cf-post-video-link').slideDown(500); /* Enable Video Link */
				$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500); /* Disable Embed */
				$('.cmb2-id-publicist-cf-post-video-local').slideUp(500); /* Disable Video Local */
			}
			else if ( $('#publicist_cf_post_video_source').val() == 'video_embed_code' ) {
				$('.cmb2-id-publicist-cf-post-video-link').slideUp(500); /* Disable Video Link */
				$('.cmb2-id-publicist-cf-post-video-embed').slideDown(500); /* Enable Embed */
				$('.cmb2-id-publicist-cf-post-video-local').slideUp(500); /* Disable Video Local */
			}
			else if ( $('#publicist_cf_post_video_source').val() == 'video_upload_local' ) {
				$('.cmb2-id-publicist-cf-post-video-link').slideUp(500); /* Disable Video Link */
				$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500); /* Disable Embed */
				$('.cmb2-id-publicist-cf-post-video-local').slideDown(500); /* Enable Video Local */
			}
			else {
				$('.cmb2-id-publicist-cf-post-video-link').slideUp(500);
				$('.cmb2-id-publicist-cf-post-video-embed').slideUp(500);
				$('.cmb2-id-publicist-cf-post-video-local').slideUp(500);
			}
		});

		/* Post : Audio */
		if( $('#publicist_cf_post_audio_source').val() == 'soundcloud_link' ) {
			$('.cmb2-id-publicist-cf-post-soundcloud-url').slideDown(500); /* Enable Soundcloud URL */
			$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
			$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideUp(500); /* Disable Audio iframe */
		}
		else if ( $('#publicist_cf_post_audio_source').val() == 'soundcloud_iframe' ) {
			$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideDown(500); /* Enable Audio iframe */
			$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Disable Soundcloud URL */
			$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
		}
		else if ( $('#publicist_cf_post_audio_source').val() == 'audio_upload_local' ) {
			$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Disable Soundcloud URL */
			$('.cmb2-id-publicist-cf-post-audio-local').slideDown(500); /* Enable Audio Local */
			$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideUp(500); /* Disable Audio iframe */
		}
		else {
			$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Enable Soundcloud URL */
			$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
		}

		/* On Change : Event */
		$('#publicist_cf_post_audio_source').live('change', function() {
			if( $('#publicist_cf_post_audio_source').val() == 'soundcloud_link' ) {
				$('.cmb2-id-publicist-cf-post-soundcloud-url').slideDown(500); /* Enable Soundcloud URL */
				$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
				$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideUp(500); /* Disable Audio iframe */
			}
			else if ( $('#publicist_cf_post_audio_source').val() == 'soundcloud_iframe' ) {
				$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideDown(500); /* Enable Audio iframe */
				$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Disable Soundcloud URL */
				$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
			
			}
			else if ( $('#publicist_cf_post_audio_source').val() == 'audio_upload_local' ) {
				$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Disable Soundcloud URL */
				$('.cmb2-id-publicist-cf-post-audio-local').slideDown(500); /* Enable Audio Local */
				$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideUp(500); /* Disable Audio iframe */
			}
			else {
				$('.cmb2-id-publicist-cf-post-soundcloud-url').slideUp(500); /* Disable Soundcloud URL */
				$('.cmb2-id-publicist-cf-post-audio-local').slideUp(500); /* Disable Audio Local */
				$('.cmb2-id-publicist-cf-post-soundcloud-iframe').slideUp(500); /* Disable Audio iframe */
			}
		});
		
		/* Page : Metabox */
		if( $('#publicist_cf_metabox_page').length) {
			
			/* Page Header */
			if( $('select#publicist_cf_page_title').val() != 'enable' ) {
				$('.cmb2-id-publicist-cf-page-header-img').slideUp(500);
				$('.cmb2-id-publicist-cf-page-header-title').slideUp(500);
			}

			$('#publicist_cf_page_title').live('change', function() {

				/* Header Background Image */
				if( $('select#publicist_cf_page_title').val() == 'disable' ) {
					$('.cmb2-id-publicist-cf-page-header-img').slideUp(500);
					$('.cmb2-id-publicist-cf-page-header-title').slideUp(500);
				}
				else {
					$('.cmb2-id-publicist-cf-page-header-img').slideDown(500);
					$('.cmb2-id-publicist-cf-page-header-title').slideDown(500);
				}
			});

			/* Sidebar Layout - Page */
			if( $('select#publicist_cf_sidebar_layout').val() == 'no_sidebar' ) {
				$('.cmb2-id-publicist-cf-widget-area').slideUp(500);
			}

			$('select#publicist_cf_sidebar_layout').live('change', function() {

				/* Sidebar Layout - Page */
				if( $('select#publicist_cf_sidebar_layout').val() == 'no_sidebar' ) {
					$('.cmb2-id-publicist-cf-widget-area').slideUp(500);
				}
				else {
					$('.cmb2-id-publicist-cf-widget-area').slideDown(500);
				}

			});
		}
	});
})(jQuery);