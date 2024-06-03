(function($) { 
	"use strict";

	const toggle = $('.menu-toggle');
	toggle.on('click', function () {
		if (toggle.hasClass('ui-menu-visible')) {
			toggle.removeClass("ui-menu-visible");
			$('body').css({ overflow: 'auto' })
			$('.nav-menu').animate({ left: '-100%' }, 'slow');
		} else {
			toggle.addClass("ui-menu-visible");
			$('body').css({ overflow: 'hidden' })
			$('.nav-menu').animate({ left: 0 }, 'slow');
		}
	})

	function DesktopMenu() {
		$(".nav-menu ul ").css({ display: "none" }); // Opera Fix
		$(".nav-menu").css({ left: "0" }); 
		$('.submenu-toggle').off('click');
		$('.submenu-toggle').remove();
		//reset

		$(".nav-menu li").hover(function () {

			$('.menu-item-has-children').on('mouseenter mouseleave', function (e) {
				if ($('ul', this).length) {
					var elm = $('.sub-menu', this);
					var off = elm.offset();
					var l = off.left;
					var w = elm.width() ? elm.width() : 305;
					var docW = $('body').width();

					var isEntirelyVisible = l + w <= docW;

					if (!isEntirelyVisible) {
						$(this).addClass('uicore-edge');
					}
				}
			});


			$(this).find('ul:first').css({ visibility: "visible", display: "none" }).show(200);



		}, function () {
			$(this).find('ul:first').css({ visibility: "hidden" });
		});
	}
	function MobileMenu() {
		$(".nav-menu ul ").css({ display: "none" }); 
		$(".nav-menu li").off('hover');
		$('.menu-item-has-children').off('mouseenter mouseleave');
		$('.nav-menu').css({ left: '-100%' })
		//reset


		var toggle = $( "<div class='submenu-toggle'>+</div>" )

		$(".menu-item-has-children").append(toggle);

		$('.submenu-toggle').on('click', function(){
			if(!$(this).hasClass('active')){
				$(this).prev(".sub-menu").css({ visibility: "visible", display: "none" }).slideDown()
				$(this).html('-')
				$(this).addClass('active')
			}else{
				$(this).prev(".sub-menu").css({ visibility: "hidden" }).slideUp()
				$(this).html('+')
				$(this).removeClass('active')
			}
			
		})
	}

	$(document).ready(function () {
		if (jQuery(window).width() > 1000) {
			DesktopMenu(); 
		}else{
			MobileMenu(); 
		}
		$(window).on('resize', function(){
			var win = $(this); //this = window
			if (win.height() < 1000) { 
				MobileMenu();
			}
			if (win.width() >= 1000) { 
				DesktopMenu(); 
			}
	  });

	});

})(jQuery);
