jQuery(document).ready(function() {
//on page load get hash
if (!window.location.hash) {jQuery('ul#filters a[href="#"]').parent('li').addClass('current');}

else {
filterVal = window.location.hash.substr(1);
			jQuery('ul#filters a[href="' + filterVal + '"]').parent('li').addClass('current');
			jQuery(postsContainer + ' div.post').each(function() {
				if(!jQuery(this).hasClass(filterVal)) {
					jQuery(this).fadeOut('normal').addClass('hidden');
				} else {
					jQuery(this).fadeIn('slow').removeClass('hidden');
				}
			});
		}



//clicking function
	jQuery('ul#filters a').click(function() {
		jQuery(this).css('outline','none');
		jQuery('ul#filters .current').removeClass('current');
		jQuery(this).parent().addClass('current');
	
		var filterVal = jQuery(this).attr('href');
		window.location.hash = filterVal;
		if(filterVal == '#') {
			jQuery(postsContainer + ' div.hidden').fadeIn('slow').removeClass('hidden');
			window.location.hash = '';

		} else {
			
			jQuery(postsContainer + ' div.post').each(function() {
				if(!jQuery(this).hasClass(filterVal)) {
					jQuery(this).fadeOut('normal').addClass('hidden');
				} else {
					jQuery(this).fadeIn('slow').removeClass('hidden');
				}
			});
		}
		
		return false;
	});
});
