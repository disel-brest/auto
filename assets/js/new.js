$(document).ready(function() {

	/* Tabs */

	$(".tab_item").not(":first").hide();
	$(".tab").click(function() {
		$(".tab").removeClass("active").eq($(this).index()).addClass("active");
		$(".tab_item").hide().eq($(this).index()).fadeIn()
	}).eq(0).addClass("active");

	$('.tab').click(function() {
		$(this).parent().find('.tab').removeClass('active');
		$(this).addClass('active');
	});

	/* Dropdown list */

	$('.menu-list-item-value').click(function() {
		var $item = $(this).parent();
		var $list = $item.parent();

		if ($($item).hasClass('open')) {
			$item.removeClass('open');
			$item.find('.dropdown-list').slideUp();
		} else {
			$list.find('.menu-list-item').removeClass('open');
			$list.find('.dropdown-list').slideUp();
			$item.toggleClass('open');
			$item.find('.dropdown-list').slideToggle();
		}
	});

	$('.dropdown-list-item').click(function() {
		$('.menu-list').find('.dropdown-list-item').not(this).removeClass('active');
		
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
	});

	$(document).on('ready', function() {
		var items = $('.menu-list-item');

		items.each(function(index, item) {
			if ($(item).find('.dropdown-list-item').hasClass('active')) {
				$(item).addClass('open');
				$(item).find('.dropdown-list').slideDown();
			}
		});
	});

	$('a.company-gallery-item').fancybox();

	$('#mapTabLink').on('click', function () {
        setServicesMapLinks();
    });
    $('#listTabLink').on('click', function () {
        $.each($('#services-menu-list').find('a'), function (k, link) {
            var url = $(link).attr('href').replace("#map", "");
            $(link).attr('href', url);
        });
    });

    $(".lk-category-dropdown").on("change", function (e) {
		window.location.href = $(this).val();
    });
});

function setServicesMapLinks() {
    $.each($('#services-menu-list').find('a'), function (k, link) {
        var url = $(link).attr('href').replace("#map", "") + "#map";
        $(link).attr('href', url);
    });
}