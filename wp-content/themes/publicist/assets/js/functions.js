/*------------------------------------
	Theme Name: Publicist
	Start Date :
	End Date : 
	Last change: 
	Version: 1.0
	Assigned to:
	Primary use:
---------------------------------------*/
/*	
	+ Responsive Caret*
	+ Expand Panel Resize*
	+ Sticky Menu*
	
	+ Document On Ready
		- Scrolling Navigation*
		- Set Sticky Menu*
		- Responsive Caret*
		- Expand Panel*
		- Collapse Panel*
	
	+ Window On Scroll
		- Set Sticky Menu
		
	+ Window On Resize
		- Expand Panel Resize
		
	+ Window On Load
		- Site Loader
		
*/

(function($) {
	
	"use strict";	
	
	/* + Responsive Caret* */
	function menu_dropdown_open(){
		var width = $(window).width();
		if($(".ownavigation .navbar-nav li.ddl-active").length ) {
			if( width > 991 ) {
				$(".ownavigation .navbar-nav > li").removeClass("ddl-active");
				$(".ownavigation .navbar-nav li .dropdown-menu").removeAttr("style");
			}
		} else {
			$(".ownavigation .navbar-nav li .dropdown-menu").removeAttr("style");
		}
	}
	
	/* + Expand Panel Resize* */
	function panel_resize(){
		var width = $(window).width();
		if( width > 991 ) {
			if($(".header_s .slidepanel").length ) {
				$(".header_s .slidepanel").removeAttr("style");
			}
		}
	}

	/* + Sticky Menu* */
	function sticky_header(){
		$(".ownavigation").sticky({topSpacing: 0});
	};
	
	/* + Document On Ready */
	$(document).on("ready", function() {

		/* - Scrolling Navigation* */
		var width	=	$(window).width();
		var height	=	$(window).height();
		
		if( $(".header_s.header-fix").length ) {
			sticky_header();
		}
		
		$('.navbar-nav li a[href*="#"]:not([href="#"]), .site-logo a[href*="#"]:not([href="#"])').on("click", function(e) {	
			var $anchor = $(this);			
			$("html, body").stop().animate({ scrollTop: $($anchor.attr("href")).offset().top - 49 }, 1500, "easeInOutExpo");			
			e.preventDefault();
		});

		/* - Responsive Caret* */
		$(".ddl-switch").on("click", function() {
			var li = $(this).parent();
			if ( li.hasClass("ddl-active") || li.find(".ddl-active").length !== 0 || li.find(".dropdown-menu").is(":visible") ) {
				li.removeClass("ddl-active");
				li.children().find(".ddl-active").removeClass("ddl-active");
				li.children(".dropdown-menu").slideUp();
			}
			else {
				li.addClass("ddl-active");
				li.children(".dropdown-menu").slideDown();
			}
		});
		
		/* - Expand Panel* */
		$( "[id*='slideit-']" ).each(function (index) { 
			index++;
			$("[id*='slideit-"+index+"']").on("click", function() {
				$("[id*='slidepanel-"+index+"']").slideDown(1000);
				$("header").animate({ scrollTop: 0 }, 1000);
				return false;
			});
		});

		/* - Collapse Panel * */
		$( "[id*='closeit-']" ).each(function (index) {
			index++;			
			$("[id*='closeit-"+index+"']").on("click", function() {
				$("[id*='slidepanel-"+index+"']").slideUp("slow");			
				$("header").animate({ scrollTop: 0 }, 1000);
				return false;
			});
		});
		
		/* Switch buttons from "Log In | Register" to "Close Panel" on click * */
		$( "[id*='toggle-']" ).each(function (index) { 
			index++;
			$("[id*='toggle-"+index+"'] a").on("click", function() {
				$("[id*='toggle-"+index+"'] a").toggle();
				return false;
			});
		});
		
		$(".widget select" ).wrap( "<div class='select_box'></div>" );
		
		$(document).on('click', '.case-history .pagination a', function(e){
			e.preventDefault();  
			var link = $(this).attr('href');  
			$('.case-history').load(link+' .case-history');
		});

	});	/* - Document On Ready /- */
	
	/* + Window On Scroll */
	$(window).on("scroll",function() {
		
	});

	/* + Window On Resize */ 
	$( window ).on("resize",function() {
		var width	=	$(window).width();
		var height	=	$(window).height();
		
		if( $(".header_s.header-fix").length ) {
			sticky_header();
		}
		
		/* - Expand Panel Resize */
		panel_resize();
		menu_dropdown_open();
	});
	
	/* + Window On Load */
	$(window).on("load",function() {
		/* - Site Loader* */
		if ( !$("html").is(".ie6, .ie7, .ie8") ) {
			$("#site-loader").delay(1000).fadeOut("slow");
		}
		else {
			$("#site-loader").css("display","none");
		}
	});

})(jQuery);