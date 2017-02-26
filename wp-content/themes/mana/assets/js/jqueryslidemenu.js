

var themetonmgamenu = {
	build: function(menu){
		jQuery(document).ready(function($){
			var $main_ul = $(menu + ">ul");
			var $main_li = $main_ul.find('ul').parent();

			$main_ul.find('>li').each(function(){
				jQuery(this).find('>ul').each(function(){
					jQuery(this).find('>li').eq(0).append('<span class="menu_arrow"></span>');
				});
			});

			$main_li.each(function(i){
				var $this = $(this);

				$this.hover(
					function(){
						var $targetul = $(this).children("ul:eq(0)");
						var target_width = parseInt($targetul.parent().outerWidth()/2);

						$targetul.parent().find('.menu_arrow').css({
							'left': target_width+'px'
						});

						if( $targetul.find('.menu_column').length > 0 ){
							$targetul.find('>li').addClass('row');
							$targetul.find('>li').css({ 'display':'block', 'width':'100%' });
							$targetul.find('.menu_column').addClass('col-lg-'+parseInt(12/$targetul.find('.menu_column').length)+' col-md-4 col-xxs-6 col-xs-12');
							$targetul.width( $targetul.find('.menu_column').length*230 );
							
							// mega menu set left pos, arrow pos
							var t_left = parseInt(($targetul.find('.menu_column').length*230-target_width)/2);
							$targetul.css({ 'left': '-'+t_left+'px' });
							$targetul.parent().find('.menu_arrow').css({
								'left': t_left+target_width+'px'
							});


							if( $targetul.parent().hasClass('fullwidth') ){
								var wpadin = parseInt(($(window).width() - $('#header > .container').width())/2);
								var lileft = $targetul.parent().offset().left;
								
								$targetul.css({
									'left': '-'+(lileft-wpadin)+'px',
									'width': $('#header > .container').width()+'px'
								});

								$targetul.parent().find('.menu_arrow').css({
									'left': parseInt(lileft-wpadin+target_width)+'px'
								});
							}
							else{
								var lileft = parseInt($targetul.parent().offset().left);
								if( $(window).width() < $targetul.width()/2+lileft ){
									var pos_dif = $targetul.width()/2+lileft - $(window).width();
									pos_dif = parseInt( pos_dif );
									$targetul.css({ 'left': '-'+(parseInt($targetul.width()/2) + pos_dif+target_width)+'px' });

									$targetul.parent().find('.menu_arrow').css({
										'left': (parseInt($targetul.width()/2) + pos_dif+target_width+target_width)+'px'
									});
								}
							}

							if( $('.wide_menu').length>0 && !$targetul.parent().hasClass('fullwidth') ){
								$targetul.css({ 'left': '0px' });
								$targetul.parent().find('.menu_arrow').css({
									'left': target_width+'px'
								});
							}
						}
						else{
							var lileft = parseInt($targetul.parent().offset().left);
							if( $(window).width() < $targetul.width()+lileft ){
								var pos_dif = $targetul.width()/2+lileft - $(window).width();
								pos_dif = parseInt( pos_dif );
								$targetul.css({ 'left': '-'+(parseInt($targetul.width()/2) + pos_dif+target_width)+'px' });

								$targetul.parent().find('.menu_arrow').css({
									'left': (parseInt($targetul.width()/2) + pos_dif+target_width+target_width)+'px'
								});

								$targetul.addClass('floar_right_menu');
							}
						}


						// calculate Submenu Padding-Top
						if( $('.wide_menu').length>0 ){ }
						else{
							var sub_top = parseInt(jQuery('#header').css('padding-bottom')) + parseInt((jQuery('#header > .container').outerHeight()-jQuery('.mainmenu').parent().outerHeight())/2+jQuery('.mainmenu').parent().outerHeight());
							jQuery('.mainmenu ul.menu > li > ul').css({
								'padding-top': sub_top+'px'
							});

							//var stuck = jQuery('#header').hasClass('stuck');
							jQuery(window).scroll(function(){
								var sub_top = parseInt(jQuery('#header').css('padding-bottom')) + parseInt((jQuery('#header > .container').outerHeight()-jQuery('.mainmenu').parent().outerHeight())/2+jQuery('.mainmenu').parent().outerHeight());
								jQuery('.mainmenu ul.menu > li > ul').css({
									'padding-top': sub_top+'px'
								});
							});
						}


						$targetul.fadeIn('fast');
					},
					function(){
						var $targetul = $(this).children("ul:eq(0)");
						$targetul.fadeOut('fast');
					}
				);
			});
		});
	}
}


themetonmgamenu.build('.mainmenu');