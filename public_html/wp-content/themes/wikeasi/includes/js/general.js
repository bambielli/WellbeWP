jQuery( document ).ready( function() {
/*-----------------------------------------------------------------------------------*/
/* Add alt-row styling to tables */
/*-----------------------------------------------------------------------------------*/

	jQuery( '.entry table tr:odd').addClass( 'alt-table-row' );

/*-----------------------------------------------------------------------------------*/
/* Center Nav Sub Menus */
/*-----------------------------------------------------------------------------------*/

	jQuery('.center .nav li ul').each(function(){
	
		li_width = jQuery(this).parent('li').width();
		li_width = li_width / 4;
		
		jQuery(this).css('margin-left', - li_width);
	});

/*-----------------------------------------------------------------------------------*/
/* Implement Superfish Navigation Dropdowns */
/*-----------------------------------------------------------------------------------*/
	
	if( jQuery().superfish ) {
		jQuery( 'ul.nav' ).superfish({
			delay: 200,
			animation: {opacity:'show', height:'show'},
			speed: 'fast',
			dropShadows: false
		});
	}

/*-----------------------------------------------------------------------------------*/
/* Table Of Cotents - Add "Hide" link and Logic */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.table_of_contents h4' ).length ) {
		jQuery( '.table_of_contents h4' ).each( function ( i ) {
			jQuery( this ).append( ' <small>[<a href="#" class="to-hide toggle">' + woo_localized_data.txt_hide + '</a>]</small>' );
		});
		
		jQuery( '.table_of_contents .toggle' ).click( function ( e ) {	
			if ( jQuery( this ).hasClass( 'to-hide' ) ) {
				jQuery( this ).text( woo_localized_data.txt_show ).parents( '.table_of_contents' ).find( 'ol' ).hide();
			} else if ( jQuery( this ).hasClass( 'to-show' ) ) {
				jQuery( this ).text( woo_localized_data.txt_hide ).parents( '.table_of_contents' ).find( 'ol' ).show();
			}
			
			jQuery( this ).toggleClass( 'to-show' ).toggleClass( 'to-hide' );
			
			return false;
		});
	}
	
/*-----------------------------------------------------------------------------------*/
/* Post Revisions - Add "Toggle" link and Logic */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.content-revisions' ).length ) {
		jQuery( '.content-revisions' ).each( function ( i ) {
			jQuery( this ).before( ' <p class="content-revisions-note">' + woo_localized_data.txt_revisions + ': (<a href="#" class="to-show toggle">' + woo_localized_data.txt_show + '</a>)</p>' );
			jQuery( this ).hide();
		});
		
		jQuery( '.content-revisions-note .toggle' ).click( function ( e ) {	
			if ( jQuery( this ).hasClass( 'to-hide' ) ) {
				jQuery( this ).text( woo_localized_data.txt_show ).parent().next( '.content-revisions' ).hide();
			} else if ( jQuery( this ).hasClass( 'to-show' ) ) {
				jQuery( this ).text( woo_localized_data.txt_hide ).parent().next( '.content-revisions' ).show();
			}
			
			jQuery( this ).toggleClass( 'to-show' ).toggleClass( 'to-hide' );
			
			return false;
		});
	}
	
/*-----------------------------------------------------------------------------------*/
/* References - ToolTip Logic */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.reference-link-in-content' ).length ) {
		jQuery( '.reference-link-in-content' ).each( function ( e ) {
			// Setup the reference data.
			var org_elem = jQuery( this );
			var referenceID = org_elem.attr( 'href' );
			var referenceContent = jQuery( referenceID ).html();

			jQuery( this ).tipTip({
				content: referenceContent,
				defaultPosition: 'top', 
				keepAlive: true
			});
		});
	}

/*-----------------------------------------------------------------------------------*/
/* Search - Suggest Logic */
/*-----------------------------------------------------------------------------------*/
	
	if ( jQuery( '.advanced-search-box-enabled.search-suggest-enabled form.auto-complete' ).length ) {
		jQuery( 'form.auto-complete' ).each( function ( i ) {
			var suggestURL = jQuery( this ).attr( 'action' );
			var delimiter = '?';
			if ( suggestURL.indexOf( '?' ) > -1 ) { delimiter = '&'; }

			suggestURL = suggestURL + delimiter + 'search-suggest=suggest';

			jQuery( this ).find( 'input#s' ).suggest( suggestURL, {
					onSelect: function () {
						if ( this.value.toLowerCase() == woo_localized_data.txt_no_results.toLowerCase() ) {
							jQuery( this ).attr( 'value', '' );
						}
						if ( jQuery( this ).attr( 'value' ) != '' ) {
							jQuery( this ).parents( 'form' ).submit();
						}
					}
				}
			);
			
			// Save the original button text for use down below.
			var searchBtnText = jQuery( this ).parent().find( 'button' ).text();
			
			// KeyPress event to show that the suggest is thinking.
			jQuery( this ).find( 'input#s' ).keyup( function ( e ) {
				if ( e.which == 13 ) { // 13 is the return/enter key.
					e.preventDefault();
				} else {	
					if ( jQuery( this ).val() != '' ) {
						jQuery( this ).addClass( 'searching' );
					} else {
						jQuery( this ).removeClass( 'searching' );
					}
				}
			});
			
			// Remove the loader if the focus shifts away from the search box.
			jQuery( this ).find( 'input#s' ).blur( function ( e ) {
				jQuery( this ).removeClass( 'searching' );
			});
		});
	}
	
/*-----------------------------------------------------------------------------------*/
/* Custom styling of the SELECT elements */
/*-----------------------------------------------------------------------------------*/

	if ( ! jQuery.browser.opera ) {
	
	    jQuery( 'select.select' ).each( function() {
	        var title = jQuery( this ).attr( 'title' );
	        if( jQuery( 'option:selected', this ).val() != '' ) title = jQuery( 'option:selected', this ).text();
	        jQuery( this )
	            .css( { 'z-index' : 10, 'opacity' : 0, '-khtml-appearance' : 'none' } )
	            .after( '<span class="select">' + title + '</span>' )
	            .change( function() {
	                val = jQuery( 'option:selected', this ).text();
	                jQuery( this ).next().text( val );
	                });
	    });
	
	}

/*-----------------------------------------------------------------------------------*/
/* AJAX Search */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.advanced-search-box-enabled.ajax-search-enabled #advanced-search-form' ).length ) {
		jQuery( '#advanced-search-form button' ).click( function ( e ) {
			var searchBtn = jQuery( this );
			
			searchBtn.attr( 'disabled', 'disabled' );
			
			// Get the search term.
			var searchTerm = jQuery( this ).parent().find( 'input#s' ).val();
			
			if ( searchTerm == '' ) {
				return false;
			}
			
			// Perform the AJAX search call.	
			jQuery.post(
				woo_localized_data.ajax_url, 
				{ 
					'action': 'ajax_search' , 
					'search_term' : searchTerm,
					'ajax_search_nonce' : woo_localized_data.ajax_search_nonce
				} ,
				function( response ) {
					jQuery( '#entries, #filter-bar' ).fadeTo( 'slow', 0.4, function () {
						
						var filterBar = jQuery( response )[0];
						var entriesDiv = jQuery( response )[1];
						
						if ( filterBar != null ) {
							jQuery( '#filter-bar .totals' ).html( jQuery( filterBar ).find( '.totals' ).html() );
							jQuery( '#filter-bar input[name="s"]' ).val( jQuery( filterBar ).find( 'input[name="s"]' ).val() );
						}
						
						if ( entriesDiv != null ) {
							jQuery( '#entries' ).html( jQuery( entriesDiv ).html() );
						}
						
						jQuery( '#entries, #filter-bar' ).fadeTo( 'slow', 1, function () {
							searchBtn.removeAttr( 'disabled' );
						});
					});					
				}	
			);
			
			return false;
		});
	}

/*-----------------------------------------------------------------------------------*/
/* Search - KeyUp Content Fade Toggle */
/*-----------------------------------------------------------------------------------*/

	if ( jQuery( '.advanced-search-box-enabled.ajax-search-enabled #advanced-search-form input#s' ).length ) {
		jQuery( '#advanced-search-form input#s' ).keypress( function ( e ) {
			var currentValue = jQuery( this ).val();
			var fadeTo = 1;
			if ( currentValue != '' ) {
				fadeTo = 0.4;
			} else {
				fadeTo = 1;
			}
			
			jQuery( '#entries, #filter-bar' ).fadeTo( 'fast', fadeTo );
		});
		
		jQuery( '#advanced-search-form input#s' ).blur( function ( e ) {
			jQuery( '#entries, #filter-bar' ).fadeTo( 'fast', 1 );
		});
	}

}); // End jQuery( document ).ready()

jQuery( window ).load( function() {

/*-----------------------------------------------------------------------------------*/
/* Widget Lists - Accordion Logic */
/*-----------------------------------------------------------------------------------*/
	
	var accordionSelector = '#sidebar';
	var accordionSelectorSecondary = '#sidebar-secondary';
	
	woo_apply_accordion( accordionSelector );
	woo_apply_accordion( accordionSelectorSecondary );

}); // End jQuery( document ).ready()

/*-----------------------------------------------------------------------------------*/
/* Widget Lists - Accordion Function */
/*-----------------------------------------------------------------------------------*/

/**
 * woo_apply_accordion function.
 * 
 * @access public
 * @param string accordionSelector
 * @return void
 */
function woo_apply_accordion ( accordionSelector ) {
	if ( jQuery( accordionSelector + ' ul' ).length ) {
		// Hide submenus.
		jQuery( accordionSelector + ' li ul' ).hide();
		
		// Add toggle links.
		jQuery( accordionSelector + ' li' ).each( function () {
			if ( jQuery( this ).find( 'ul' ).length ) {
				// Add CSS classes to items that have children.
				jQuery( this ).addClass( 'has-children' );
		
				jQuery( this ).children( 'ul' ).before( '<a href="#" class="toggle">' + woo_localized_data.txt_toggle + '</a>' );
			}
		});
		
		// Make sure the path to the current item is fully open.
		jQuery( accordionSelector + ' li.current-menu-item, ' + accordionSelector + ' li.current-cat, ' + accordionSelector + ' li.current_page_item' ).parents( 'ul' ).addClass( 'open' ).slideDown();
		jQuery( accordionSelector + ' li.current-menu-item, ' + accordionSelector + ' li.current-cat, ' + accordionSelector + ' li.current_page_item' ).parents( 'ul' ).prev( '.toggle' ).addClass( 'open' );
		jQuery( accordionSelector + ' li.current-menu-item.has-children, ' + accordionSelector + ' li.current-cat.has-children, ' + accordionSelector + ' li.current_page_item.has-children' ).find( 'ul' ).addClass( 'open' ).slideDown();
		jQuery( accordionSelector + ' li.current-menu-item.has-children, ' + accordionSelector + ' li.current-cat.has-children, ' + accordionSelector + ' li.current_page_item.has-children' ).addClass( 'open' ).find( '.toggle' ).addClass( 'open' );
		
		// Toggle the submenus.
		jQuery( accordionSelector + ' li a.toggle' ).live( 'click', function () {
			jQuery( this ).parents( 'ul' ).find( 'a.open' ).removeClass( 'open' );
			jQuery( this ).parent( 'li' ).siblings().find( 'ul.open' ).slideUp().removeClass( 'open' );
			
			if ( jQuery( this ).next( 'ul' ).hasClass( 'open' ) ) {
				jQuery( this ).removeClass( 'open' );
				jQuery( this ).next( 'ul' ).slideUp().removeClass( 'open' );
			} else {
				jQuery( this ).addClass( 'open' );
				jQuery( this ).next( 'ul' ).slideDown().addClass( 'open' );
			}
			
			return false;
		});
	}
} // End woo_apply_accordion()

/*-----------------------------------------------------------------------------------*/
/* Plugin - Superfish Navigation Dropdowns */
/*-----------------------------------------------------------------------------------*/

;(function($){$.fn.superfish=function(op){var sf=$.fn.superfish,c=sf.c,$arrow=$(['<span class="',c.arrowClass,'"> &#187;</span>'].join( '')),over=function(){var $$=$(this),menu=getMenu($$);clearTimeout(menu.sfTimer);$$.showSuperfishUl().siblings().hideSuperfishUl()},out=function(){var $$=$(this),menu=getMenu($$),o=sf.op;clearTimeout(menu.sfTimer);menu.sfTimer=setTimeout(function(){o.retainPath=($.inArray($$[0],o.$path)>-1);$$.hideSuperfishUl();if(o.$path.length&&$$.parents(['li.',o.hoverClass].join( '')).length<1){over.call(o.$path)}},o.delay)},getMenu=function($menu){var menu=$menu.parents(['ul.',c.menuClass,':first'].join( ''))[0];sf.op=sf.o[menu.serial];return menu},addArrow=function($a){$a.addClass(c.anchorClass).append($arrow.clone())};return this.each(function(){var s=this.serial=sf.o.length;var o=$.extend({},sf.defaults,op);o.$path=$( 'li.'+o.pathClass,this).slice(0,o.pathLevels).each(function(){$(this).addClass([o.hoverClass,c.bcClass].join( ' ')).filter( 'li:has(ul)').removeClass(o.pathClass)});sf.o[s]=sf.op=o;$( 'li:has(ul)',this)[($.fn.hoverIntent&&!o.disableHI)?'hoverIntent':'hover'](over,out).each(function(){if(o.autoArrows)addArrow($( '>a:first-child',this))}).not( '.'+c.bcClass).hideSuperfishUl();var $a=$( 'a',this);$a.each(function(i){var $li=$a.eq(i).parents( 'li' );$a.eq(i).focus(function(){over.call($li)}).blur(function(){out.call($li)})});o.onInit.call(this)}).each(function(){var menuClasses=[c.menuClass];if(sf.op.dropShadows&&!($.browser.msie&&$.browser.version<7))menuClasses.push(c.shadowClass);$(this).addClass(menuClasses.join( ' '))})};var sf=$.fn.superfish;sf.o=[];sf.op={};sf.IE7fix=function(){var o=sf.op;if($.browser.msie&&$.browser.version>6&&o.dropShadows&&o.animation.opacity!=undefined)this.toggleClass(sf.c.shadowClass+'-off')};sf.c={bcClass:'sf-breadcrumb',menuClass:'sf-js-enabled',anchorClass:'sf-with-ul',arrowClass:'sf-sub-indicator',shadowClass:'sf-shadow'};sf.defaults={hoverClass:'sfHover',pathClass:'overideThisToUse',pathLevels:1,delay:800,animation:{opacity:'show'},speed:'normal',autoArrows:true,dropShadows:true,disableHI:false,onInit:function(){},onBeforeShow:function(){},onShow:function(){},onHide:function(){}};$.fn.extend({hideSuperfishUl:function(){var o=sf.op,not=(o.retainPath===true)?o.$path:'';o.retainPath=false;var $ul=$(['li.',o.hoverClass].join( ''),this).add(this).not(not).removeClass(o.hoverClass).find( '>ul').hide().css( 'visibility','hidden' );o.onHide.call($ul);return this},showSuperfishUl:function(){var o=sf.op,sh=sf.c.shadowClass+'-off',$ul=this.addClass(o.hoverClass).find( '>ul:hidden').css( 'visibility','visible' );sf.IE7fix.call($ul);o.onBeforeShow.call($ul);$ul.animate(o.animation,o.speed,function(){sf.IE7fix.call($ul);o.onShow.call($ul)});return this}})})(jQuery);