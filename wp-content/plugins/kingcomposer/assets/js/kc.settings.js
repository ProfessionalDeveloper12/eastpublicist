(function($){
	
	$(document).ready(function($) {
	    
	    $('.nav-tab-wrapper a').on( 'click', function(e) {
	        var clicked = $(this).attr('href');
	        if( clicked.indexOf('#') == -1 )
	        	return true;
	        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
	        $(this).addClass('nav-tab-active').blur();
	        $('.group').hide();
	        $(clicked).fadeIn();
	        
	        if (typeof(localStorage) != 'undefined' ) {
	            localStorage.setItem('kc_settings_active_tab', clicked );
	        }
	        e.preventDefault();
	    });
	    
	    $('.kc-update-link-ajax').on('click', function(e){
		    
		    var wrp = $(this).parent(),
		    	slug = $(this).data('slug');
		    	
		    wrp.html('<i class="dashicons dashicons-update kc-spin"></i> Updating, please wait...');
	
		    $.post({
		    	url: ajaxurl, 
		    	data: {
			    	'action': 'update-plugin',
					'slug': slug,
					'plugin': slug+'/'+slug+'.php',
				    '_ajax_nonce': $('#kc-nonce-updates').val()
			    },
			    wrp: wrp,
			    method: 'POST',
			    dataType: 'json',
				success: function (result) {
					
					if (result == '-1' || result == '0')
					{
						this.wrp.html('<span style="color:maroon"><i class="dashicons dashicons-no"></i> Update failed</span>');
						this.wrp.parent().after('<p><strong>Console:</strong><br />Invalid sercurity sessition or do wrong way.</p>');
					}
					else if (result.success === true)
					{
						this.wrp.parent().addClass('success');
						this.wrp.html('<span style="color:green"><i class="dashicons dashicons-yes"></i> Update successful</span>');
					}
					else
					{
						this.wrp.html('<span style="color:maroon"><i class="dashicons dashicons-no"></i> Update failed</span>');
						this.wrp.parent().after('<div class="kc-download-failed-mesg">'+result.data.errorMessage+'</div>');
					}
					
				}
			});
			
			e.preventDefault();
			return false;
			
	    });
	    
	    $('p.radio').on('click',function(e){
	        if( e.target.tagName != 'INPUT' ){
	        	var inp = $(this).find('input').get(0);
	        	if( inp.disabled == true )
		        	e.preventDefault();
				else if( inp.checked == true )
	        		inp.checked = false;
	        	else inp.checked = true;	
	        }	
	    });
	    
	    if (typeof(localStorage) != 'undefined'){
	        
	        activeTab = localStorage.getItem('kc_settings_active_tab');
	        
	        if (activeTab === undefined)
	        	activeTab = '#kc_general_setting';
	
	        $('.nav-tab-wrapper a[href="'+activeTab+'"]').trigger('click');
	        
	    }
	    
	    if (window.location.href.indexOf('#') > -1 && $('a[href="#'+window.location.href.split('#')[1]+'"]').length > 0)
	    	$('a[href="#'+window.location.href.split('#')[1]+'"]').trigger('click');
	    	
	    $('#kc-pro-settings-re-active').on('click',function(){
			$('input[name="re-active-kc-pro"]').val(1);
		});
		
		$('#kc-pro-settings-larger-video').on('click',function(){
			$('.kc-pro-settings').append( $('#kc-pro-settings-download-wrp').addClass('align-center').get(0) );
			$('#kc-pro-settings-video-frame').attr({height:500, width: 860});
			$(this).remove();
			return false;
		});
		
		$('#kc-pro-license-inp').on('keydown',function(e){
			if( e.keyCode == 13 ){
				e.preventDefault();
				return false;
			}
		});
		
		$('#kc-pro-settings-process-download').on('click',function(){
			
			$(this).off('click').attr({disabled : true})
				.removeClass('button-primary')
				.html('<i class="dashicons dashicons-update kc-spin"></i> KC Pro! is now installing...');
	
			$.post( ajaxurl, {
					'action': 'kc_download_pro',
					'security': $('#kc-nonce-download').val()
				},function (result) {
					
					var pd = $('#kc-pro-settings-process-download'),
						pdw = $('#kc-pro-settings-download-wrp'),
						msg = '', err = '';
					
					if( result == '-1' || result == '0' ){
						msg = '<span style="color:maroon"><i class="dashicons dashicons-no"></i> Installation failed</span>';
						err = '<div class="kc-download-failed-mesg"><strong>Console:</strong><br />Invalid sercurity sessition or do wrong way.</div>';
					}else if( result.toString().indexOf( 'active-success' ) > -1 ){
						msg = '<span style="color:green"><i class="dashicons dashicons-yes"></i> Installation successful</span>, reloading...';
						window.location.href = window.location.href.toString().split('#')[0];
					}else{
						msg = '<span style="color:maroon"><i class="dashicons dashicons-no"></i> Installation failed</span>';
						msg = '<div class="kc-download-failed-mesg">'+result+'</div>';
					}
					
					pd.html(msg);
					pdw.append(err);
					pdw.find('.kc-download-failed-mesg a').remove();
				}
			);
				
			return false;
		});
		
		$('#kc_product_license .see-key').on('click', function(){
			$(this).parent().find('input.kc-license-key').attr({type:'text'});
			$(this).remove();
		});
		
	    $('#kc-settings-verify-btn').on('click', function(){
							
			var wrp = $(this).closest('#kc_product_license');
			
			var sercurity = wrp.find('input[name="sercurity"]').val(),
				license = wrp.find('input.kc-license-key').val().toString();
			
			if (license.length !== 41)
			{
				wrp.find('.kc-license-notice').html('<div class="kc-notice"><p><i class="fa-warning"></i> Your license code is invalid. Please try with another one.</p></div>');
				return false;
			}
			
			wrp.find('.kc-license-notice').animate({opacity:0});
			$(this).attr({disabled:true}).removeClass('button-primary').html('<i class="dashicons dashicons-update kc-spin"></i> verifying your license...');
			
			jQuery.post(
	
				ajaxurl,
			
				{
					'action': 'kc_kcp_access',
					'security': sercurity,
					'license': license
				},
			
				function (result) 
				{
					
					$('#kc-settings-verify-btn').attr({disabled:false}).addClass('button-primary').html('<i class="dashicons dashicons-admin-network"></i> Verify your license now');
					wrp.find('.kc-license-notice').animate({opacity:1});
					if( result === -1 || result === 0 || result.stt === -1 || result.stt === 0 ){
						wrp.find('.kc-license-notice').html('<div class="kc-notice kc-msg-error"><p><i class="fa-times"></i> Invalid security session or server is busy! Please reload the page and try again.</p></div>');
					}else if( result == -2 ){
						wrp.find('.kc-license-notice').html('<div class="kc-notice kc-msg-error"><p><i class="fa-times"></i> Your license code is invalid (code -2)</p></div>');
					}else{
						if( result.stt == 1 ){
							wrp.find('.kc-license-notice').html('<div class="kc-notice kc-msg-success"><p><i class="fa-check-circle"></i> Your domain has been actived successful.</p></div>');
							wrp.find('span.unverified').removeClass('unverified').addClass('verified').html('<i class="dashicons dashicons-yes"></i>Verified');
						}else{
							wrp.find('.kc-license-notice').html('<div class="kc-notice kc-msg-error"><p><i class="fa-times"></i> '+result.stt+'</p></div>');		
						}
					}
				}
			);
			
			return false;
			
		});
	    
	    $('#kc-revoke-license').on('click', function(){
		    
		    if (confirm ("WARNING:\n\nPlease note that you will not be able to use this license key for this website again.\n\nAre you sure that you want to revoke license?") ){
			   	
			   	if (confirm ("You'll need another license key if you want to verify this website again\n\nAre you sure?") ){
				   	
				   	var wrp = $(this).closest('#kc_product_license'),
				   		sercurity = wrp.find('input[name="sercurity"]').val();
					
					wrp.find('.kc-license-notice').animate({opacity:0});
					$(this).attr({disabled:true}).removeClass('button-primary').html('<i class="fa-spinner fa-spin fa-fw"></i> Processing...');
					
					jQuery.post(
			
						ajaxurl,
					
						{
							'action': 'kc_revoke_domain',
							'security': sercurity,
						},
					
						function (result) 
						{
							
							$('#kc-settings-verify-btn').attr(
								{ disabled: false}
							).addClass (
								'button-primary'
							).html(
								'Verify your license'
							);
							
							wrp.find ('.kc-license-notice')
								.animate ({opacity : 1});
								
							if (result === -1 || result === 0 || result.stt === -1 || result.stt === 0)
							{
								wrp.find ('.kc-license-notice')
									.html('<div class="kc-notice kc-msg-error">\
											<p><i class="fa-times"></i> \
												Invalid security session or server is busy! Please reload the page and try again.\
											</p></div>');
							}
							else
							{
								wrp.find ('.kc-license-notice')
									.html ('<div class="kc-notice kc-msg-success">\
												<p><i class="fa-check-circle"></i> \
													Revoke successful, now you can use your license key for another website.\
												</p></div>');
												
								wrp.find ('span.verified')
									.removeClass ('verified')
									.addClass ('unverified')
									.html ('<i class="dashicons dashicons-no"></i>Unverified');
							}
							
							$('#kc-revoke-license').remove();
							 wrp.find('input.kc-license-key').val('');
							
						}
					);
				}
		    }
		    
	    });
	    
	    /* START EXTENSIONS */
	    
	    $('#kc-extensions-list #the-list .row-actions a').on('click', function(e){
		    
		    var $this = $(this),
		 	   	task = $(this).attr('class').trim(),
		    	name = $(this).closest('tr[data-extension]').data('extension');
		    
		     if ($this.closest('.row-actions').find('i.kc-spin').length > 0) {
			    e.preventDefault;
			    return false;
		    }
		    
		    if (task == 'delete') {
			    if (!confirm("Are you sure that you want to delete this extension?\nWarning: This action cannot be restored.\n")){
				    e.preventDefault;
					return false;
			    }
		    }
		    
		    $this.prepend('<i class="dashicons dashicons-update kc-spin"></i>');
		    
		    $.post({
		    	url: ajaxurl, 
		    	data: {
			    	'action': 'kc_installed_extensions',
					'name': name,
					'task': task,
				    'security': $('#kc-nonce').val()
			    },
			    method: 'POST',
			    dataType: 'json',
				success: function (result) {
					
					if (result == '-1' || result == '0')
					{
						alert('Error: Invalid sercurity sessition or do wrong way.');
					}
					else if (result.stt == 1)
					{
						switch (task) {
							case 'active' : 
								$this.closest('tr[data-extension]').removeClass('inactive').addClass('active');
							break;
							case 'deactive' : 
								$this.closest('tr[data-extension]').removeClass('active').addClass('inactive');
							break;
							case 'delete' : 
								$this.closest('tr[data-extension]').remove();
							break;
						}
					}
					else
					{
						alert('Error: '+result.msg);
					}
					
					$this.closest('.row-actions').find('i.kc-spin').remove();
					
				}
			});
		    
		    e.preventDefault;
		    return false;
	    });
	    
	    $('a[href="#upload-extension"]').on('click', function(e) {
		    $(this).parent().addClass('show-upload-view');
		    e.preventDefault();
	    });
	    
	    $('a.install-now').on('click', function(e) {
		    
		    var _this = $(this),
		    	id = this.getAttribute('href').trim().replace('#', ''),
		    	verify = this.getAttribute('data-verify');
		    
		    if (verify != '1') {
		   		$('#kc-extension-notice').css({opacity: 0, display: 'inline-block'}).animate({opacity: 1}, 250);
		   		$('#kc-extension-notice-body').css({opacity: 0, top: '55%'}).animate({opacity: 1, top: '50%'}, 200);
		    } else {
			    if (_this.data('installed') == true) {
				    
			  		$(this).addClass('disabled').html('<i class="dashicons dashicons-update kc-spin"></i> Processing..');
				    var task = _this.hasClass('button-primary') ? 'active' : 'deactive';
				    
					$.post({
				    	url: ajaxurl, 
				    	data: {
					    	'action': 'kc_installed_extensions',
							'name': id,
							'task': task,
						    'security': $('#kc-nonce').val()
					    },
					    method: 'POST',
					    dataType: 'json',
						success: function (result) {
							
							if (result == '-1' || result == '0')
							{
								alert('Error: Invalid sercurity sessition or do wrong way.');
							}
							else if (result.stt == 1)
							{
								if (task == 'active') {
									_this.removeClass('disabled').
										  addClass('button-link-delete').
										  removeClass('button-primary').
										  html('Deactive');
								} else {
									_this.removeClass('disabled').
										  removeClass('button-link-delete').
										  addClass('button-primary').
										  html('Active Now');
								}
							}
						}
					});
					
					e.preventDefault();
					return;
					
			    };
			    
			    $(this).addClass('disabled').html('<i class="dashicons dashicons-update kc-spin"></i> Installing..');
			    
			    $.post({
			    	url: ajaxurl, 
			    	data: {
				    	'action': 'kc_store_extensions',
						'id': id,
						'task': 'download',
					    'security': $('#kc-nonce').val()
				    },
				    method: 'POST',
				    dataType: 'json',
					success: function (result) {
						
						if (result.status) {
							if (result.status == 'error') {
								alert(result.errors.join("\n"));
								_this.html('Install Now').removeClass('disabled');
							} else if (result.status == 'success') {
								_this.html('Active Now').
									  removeClass('disabled').
									  addClass('button-primary').
									  attr({'data-installed': 'true'});
							}
						}
							
					}
				});
		    }
		    
		    e.preventDefault();
	    });
	    
	    $('.bulkactions button.action').on('click', function(e) {
		    var action = $(this).parent().find('select').val();
		    alert(action);
		    e.preventDefault();
	    });
	    
	    $('#kc-extension-notice a[href="#close"]').on('click', function(e){
		    $('#kc-extension-notice-body').animate({opacity: 0, top: '55%'}, 200);
		    $('#kc-extension-notice').animate({opacity: 0}, 250, function(){this.style.display = 'none';});
	    });
	    
	});
	
	$(window).on('message', function(e) {
		if (e.originalEvent.data.action !== undefined && e.originalEvent.data.action == 'kcpush') {
			
			localStorage.setItem('KC_RowNewSection', atob(e.originalEvent.data.content) );
			window.location = window.location.href.split('admin.php')[0]+'post-new.php?kc_action=content_from_row&post_type=page';
			
		}
	});
	
})(jQuery);