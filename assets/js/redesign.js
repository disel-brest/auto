$(document).ready(function() {

	$('.list-dop').click(function() {
		$(this).parent().find('.list-dop').removeClass('active');
		$(this).addClass('active');
	});

	$('.userbar li').click(function() {
		$(this).parent().find('li').removeClass('active');
		$(this).addClass('active');
	});

	$('.block-radio').click(function() {
		$(this).parent().find('.block-radio').removeClass('active');
		$(this).addClass('active');
	});

	$('.add-block .select-large-radius select, .add-block .select-middle-radius select, .add-block .select-small-radius select').change(function() {
		if($(this).val()) {
			$(this).parent().addClass('filled');
			var $group = $(this).closest('.middle-btn-group')
			$group.removeClass('has-error');
			$group.find('.help-block-error').text('');
		} else {
			$(this).parent().removeClass('filled');
		}
	});

	$('.add-block .input-large-radius input, .add-block .input-middle-radius input').change(function() {
		if($(this).val()) {
			$(this).parent().addClass('filled');
		} else {
			$(this).parent().removeClass('filled');
		}
	});

	$('.select_mark_pop-up_content').click(function() {
		$('.select-mark .select-large-radius span').parent().parent().addClass('filled');
	});

	$('.select_model_pop-up_content').click(function() {
		$('.select-model .select-large-radius span').parent().parent().addClass('filled');
	});

	$('.field-description textarea').change(function() {
		if($(this).val()) {
			$(this).addClass('filled');
		} else {
			$(this).removeClass('filled');
		}
	});



	$('.car-photo-file-input').change(function() {
		if($(this).val()) {
			$(this).parents('.add-photo').find('.photo-btn-delete').addClass('shown');
		}
	});

	$('.add-photo .photo-btn-delete').click(function() {
		$(this).parent().find('.car-photo-file-input').val('');
        $(this).parent().find('input[name*=photo]').val('');
		$(this).parent().find('.add-photo-img').find('img').remove();
		$(this).parent().find('.add-photo-img').html("<p >Прикрепить фото " + $(this).parent().attr('data-num') + "</p>");
		$(this).removeClass('shown');
	});

	$(document).on('change', '.addblock-form-subcategory-element-item-file', function() {
		if($(this).val()) {
			$(this).parent().find('.photo-btn-delete').addClass('shown');
		}
	});

	$(document).on('click', '.addblock-form-subcategory-element-item-filebtn .photo-btn-delete', function() {
		$(this).parent().find('.addblock-form-subcategory-element-item-file').val('');
		$(this).parent().find('.addblock-form-subcategory-element-item-img').find('img').remove();
		$(this).parent().find('.addblock-form-subcategory-element-item-img').text("+");
		$(this).removeClass('shown');
	});

	if($('select.form-control').val()) {
		$('select.form-control').parent().addClass('filled');
	}

	if($('.select-mark .select-large-poluradius span').text() === 'Выберите марку') {
		$('.select-mark .select-large-poluradius').removeClass('filled');
	}

	if($('.select-mark .select-large-poluradius span').text() === 'Диски для') {
		$('.select-mark .select-large-poluradius').removeClass('filled');
	}

	if($('.input-large-poluradius input').val()) {
		$('.input-large-poluradius').addClass('filled');
	}

	if($('.input-middle-poluradius input').val()) {
		$('.input-middle-poluradius').addClass('filled');
	}


	if($('.field-description textarea').val()) {
		$('.field-description textarea').addClass('filled');
	}

	var blockRadios = $('.block-radio');
	$.each(blockRadios, function(index, value) {
		if($(value).find('input:checked').val()) {
			$(value).addClass('active');
		}
	})

	$('.popup-wrap .check_block label').click(function() {
		$(this).toggleClass('active');
	});

	$(document).on('mouseover', '.select-part-item a', function() {
		var $tooltip = $(this).parent().find('.part-tooltip');
		$tooltip.addClass('show');
		var width = $(this).width();
		$tooltip.css('left', width + 30 + "px");
	})

	$(document).on('mouseleave', '.select-part-item a', function() {
		$(this).parent().find('.part-tooltip').removeClass('show');
	})
	$(document).on('mouseover', '.tooltip-has', function() {
		var $tooltip = $(this).find('.tooltip-popup');
		var leftPoint = -($tooltip.outerWidth() - $(this).outerWidth())/2;
		$tooltip.css('left', leftPoint + 'px');
	});

	$(document).on('click', '.select-group-mobile-btn', function(e) {
		e.preventDefault();
		$('.select-group-options').addClass('popup');
	});

	$(document).on('click', '.select-group-options-mobile-close', function() {
		$('.select-group-options').removeClass('popup');
	});

	$(document).on('click', '.select-group-options-toggle', function() {	
		$('.select-group-options').toggleClass('filter');
	});

	$(document).on('click', '.part-list-btn', function() {	
		$('.select-group-options').removeClass('filter');
		$('.select-list-title').text('Выберите категорию автозапчастей');
	});

	$(document).on('click', '.part-search-btn', function() {	
		$('.select-group-options').addClass('filter');
		$('.select-list-title').text('Поиск автозапчастей');
	});

	$('.linked-group .middle-btn').click(function() {
		var $formGroup = $(this).closest('.form-group');
		if ($formGroup.hasClass('has-error')) {
			$formGroup.removeClass('has-error');
			$formGroup.find('.help-block-error').text('');
		}
	});

	$('.field-addpartform-fuel_id').click(function($this) {
		var $block = $('.field-addpartform-fuel_id');
		if ($("input[name='AddPartForm[fuel_id]']:checked").length) {
			$block.removeClass('has-error');
			$block.find('.help-block-error').text('');
		}
	});

	$('.field-addpartform-body_style').click(function($this) {
		var $block = $('.field-addpartform-body_style');
		if ($("input[name='AddPartForm[body_style]']:checked").length) {
			$block.removeClass('has-error');
			$block.find('.help-block-error').text('');
		}
	});

});

