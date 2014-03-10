//Placeholders
(function(a){a(function(){var b=Modernizr.input.placeholder;if(!b){var c=a("input[placeholder]"),d=c.length,e,f="placeholder";while(d--)c[d].value=c[d].value?c[d].value:c.eq(d).addClass(f).attr("placeholder"),c.eq(d).focus(function(){var b=a(this);this.value==b.attr("placeholder")&&(b.removeClass(f),this.value="")}).blur(function(){var b=a(this);this.value==""&&(b.addClass(f),this.value=b.attr("placeholder"))}),function(b){a(b.form).bind("submit",function(){b.value==a(b).attr("placeholder")&&(b.value="")})}(c[d])}})})(jQuery)

jQuery.noConflict();

jQuery.fn.extend({
scrollToMe: function () {
    var x = jQuery(this).offset().top - 100;
    jQuery('html,body').animate({scrollTop: x}, 500);
}});


jQuery(window).load(function(){
    var $ = jQuery;

	//cart dropdown
	var config = {
	     over: function(){
            $('.cart-top .summary').addClass('active');
            $('.cart-top .details').css({opacity:0}).show().animate({opacity:1}, 200);
         },
	     timeout: 200, // number = milliseconds delay before onMouseOut
	     out: function(){
		    $('.cart-top .details').animate({opacity:0}, 200 ,function(){ $(this).hide(); $('.cart-top .summary').removeClass('active'); });
	     }
	};
	$("div.cart-top").hoverIntent( config );	

	//fix menu behavior
	$('#nav li').hover(
		function(){
			$(this).children('div').addClass('shown-sub');
		},
		function(){
			$(this).children('div').removeClass('shown-sub');
		}
	);

	//fix description height
	$('#nav li.menu-category-description').each(function(){
		var height = 0;
		$(this).parent().children('li').each(function(){
			if ( $(this).height() > height )
				height = $(this).height();
		});
		$(this).height( height );
	});

	$('#newsletter').focus( function() {
	    if( $(this).val() == $(this).attr('title') ) { $(this).val(''); }
	  })
	  .blur( function() {
		if( $(this).val() == '' ) { $(this).val( $(this).attr('title') ); }
	});

	$('.banners a, .products-list a.product-image, .mini-products-list a.product-image, ' +
        '.main-view a.product-image, .more-views a.product-image, .data-table a.product-image').each(function(){
		$(this).prepend('<div class="border"/>');
		$('div.border',this).css({
			width: $(this).width()-12,
			height: $(this).height()-12
		});
	});

    //fix grid items height
	$('ul.products-grid').each(function(){
		var h=null;
		$('li', this).each(function(){
			if ( $(this).height() > h ) h = $(this).height();
		});
		$('li', this).height(h);
	});

    //grid buttons animation
	$('.products-grid li.item, .related li.item, .box-up-sell li.item, .cart .crosssell li.item').hover(
		function(){
			$('.add-to-cart, .add-to-wishlist', this).css({opacity:0}).show();
			$('.add-to-cart', this).animate({opacity:1, top: '+=10'}, 400);
			$('.add-to-wishlist', this).animate({opacity:1, top: '-=10'}, 400);
		},
		function(){
			$('.add-to-cart', this).animate({opacity:0, top: '-=10'}, 400);
			$('.add-to-wishlist', this).animate({opacity:0, top: '+=10'}, 400);
		}
	);

    //add review link on product page open review tab
    $('div.product-view p.no-rating a, div.product-view .rating-links a:last-child').click(function(){
        $('ul.product-tabs li').removeClass('active');
        $('#product_tabs_review_tabbed').addClass('active');
        $('.product-tabs-content').hide();
        $('#product_tabs_review_tabbed_contents').show();
        $('#product_tabs_review_tabbed').scrollToMe();
        return false;
    });

    //fancybox
	if ( $.fancybox )
    $("#main-img").fancybox({
        'overlayShow': true,
        'hideOnOverlayClick':true,
        'hideOnContentClick':true,
        'enableEscapeButton':true,
        'showCloseButton':true,
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic'
    });

	$("div.more-views a").click(function(){
		$("#main-img").attr('href', $(this).attr('href') );
		$("#main-img img").attr('src', $(this).attr('rel') );
		return false;
	});

});
