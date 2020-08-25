$(document).ready(function() {
	var widthForMobile = 580;

	function popupHandler(e, popup) {
		if ($(window).width() > widthForMobile) {
			openPopup(popup);
		} else {
			var $popup = $('.user-in-drop--translate, .menu-mobile');
			if ($popup.is(':visible')) {
				$popup.hide();
				$('body').removeClass('no-scroll');
			}
			openPopupForMobile(event, popup);
		}
	}
	
	function openPopup(popup) {
		$.magnificPopup.open({
			items: {
				src: $(popup),
				type:'inline',
			},
		});
	}

	function openPopupForMobile(e, popup) {
		e.preventDefault();
		var $content = $('.content');
		var $popupWrap = $content.find('.popup-wrap');

		if ($popupWrap) {
			$('body').append($popupWrap);
			$popupWrap.addClass('mfp-hide');
		}

		$content.append($(popup));
		$(popup).removeClass('mfp-hide');
		$('.content-right').hide();
	}

	$('.popup-wrap-close').click(function() {
		var $popupWrap = $('.content').find('.popup-wrap');

		if ($popupWrap) {
			$('body').append($popupWrap);
			$popupWrap.addClass('mfp-hide');
			$('.content-right').show();
		}
	});
	
	/* Popup sign-in */
	$('.popup-link, .sign-in-link').click(function(e) {
		popupHandler(e, '#sign-in-pop-up');
	});

	/* Popup reg-in */
	$('.pop-up-register, .reg-in-link').click(function(e) {
		popupHandler(e, '#reg-in-pop-up');
	});

	/* Popup password-forg */
	$('.forget-pass').click(function(e) {
		popupHandler(e, '#password-forg-pop-up');
	});

	$('form#sign-in').on('submit', function () {
		$form = $(this);
		$.ajax({
			url: '/login',
			dataType: "json",
			type: "POST",
			data: $form.serialize(),
			beforeSend: function () {
				$form.find('.sign-in-button').prop('disabled', true).html('<img src="/images/ajax-loader2.gif">');
				$form.yiiActiveForm('resetForm');
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					document.location.href = "http://" + window.location.host + window.location.pathname;
				} else {
					if ('errors' in data) {
						$.each(data.errors, function (key, val) {
							$form.yiiActiveForm('updateAttribute', 'loginform-' + key, val);
						});
					}
				}
			},
			complete: function () {
				$form.find('.sign-in-button').prop('disabled', false).html('Войти');
			}
		});
		return false;
	});

	/*  Popup password-forg-msg */

	$('#password-forg-msg-pop-up a').click(function(e) {
		popupHandler(e, '#password-rec-pop-up');
	});

	/* Popup add advert */

	$('a.menu-mobile-btn').click(function(e) {
		popupHandler(e, '#add-advert-pop-up');
	});

	$('.add-advert-btn a').click(function() {
		$("#add-advert-pop-up")}).magnificPopup({
		type:'inline',
	});

	$('.add-advert-icon a').click(function() {
		$("#add-advert-pop-up")}).magnificPopup({
		type:'inline',
	});

		$('.add-advert a').click(function() {
		$("#add-advert-pop-up")}).magnificPopup({
		type:'inline',
	});
	

	/*  Popup password-forg-msg */

	/*$('#password-forg-btn').click(function() {
		$("#password-forg-msg-pop-up")}).magnificPopup({
		type:'inline',
		midClick: true
	});*/
	$('form#password-forg').on('submit', function () {
		$form = $(this);
		$.ajax({
			url: '/password-reset-request',
			dataType: "json",
			type: "POST",
			data: $form.serialize(),
			beforeSend: function () {
				$form.find('input[type=submit]').prop('disabled', true).html('<img src="/images/ajax-loader2.gif">');
				$form.yiiActiveForm('resetForm');
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					$.magnificPopup.open({
						items: {
							src: '#password-forg-msg-pop-up',
							type: 'inline',
							midClick: true
						}
					});
				} else {
					if ('errors' in data) {
						$.each(data.errors, function (key, val) {
							$form.yiiActiveForm('updateAttribute', 'passwordresetrequestform-' + key, val);
						});
					}
				}
			},
			complete: function () {
				$form.find('input[type=submit]').prop('disabled', false).html('Ok');
			}
		});
		return false;
	});

	/* Popup reg-msg */

	/*$(".reg-in-button").click(function(){

	 $("#reg-msg-pop-up")}).magnificPopup({
	 type:'inline',
	 midClick: true
	 });*/
	$("form#reg-in").on('submit', function () {
		$form = $(this);
		$.ajax({
			url: '/signup',
			dataType: "json",
			type: "POST",
			data: $form.serialize(),
			beforeSend: function () {
				$form.find('button[type=submit]').prop('disabled', true).html('<img src="/images/ajax-loader2.gif">');
				$form.yiiActiveForm('resetForm');
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					$.magnificPopup.open({
						items: {
							src: '#reg-msg-pop-up',
							type: 'inline',
							midClick: true
						}
					});
				} else {
					if ('errors' in data) {
						$.each(data.errors, function (key, val) {
							$form.yiiActiveForm('updateAttribute', 'signupform-' + key, val);
						});
					}

					$('#signupform-verifycode-image').yiiCaptcha('refresh');
				}
			},
			complete: function () {
				$form.find('button[type=submit]').prop('disabled', false).html('Зарегистрироваться');
			}
		});
		return false;
	});

	$('label[for="reg-in-captcha-numbers"]').on('click', function () {
		$('#signupform-verifycode-image').yiiCaptcha('refresh');
	});

	/* Popup password-change */

	$(".userblock-password").click(function(){
		$("#password-change-pop-up")}).magnificPopup({
		type:'inline',
		midClick: true
	});

	/* Popup password-change-msg

	$("#password-change-btn").click(function(){
		$("#password-change-msg-pop-up")}).magnificPopup({
		type:'inline',
		midClick: true
	});*/

	/* Popup select mark */

	$(document).on('click', ".select-part-mark a, .select-mark a", function(){
		$.magnificPopup.open({
			items: {
				src: '#select-mark-pop-up',
				type: 'inline',
				midClick: true
			}
		});
	});

	/*$(document).on('click', ".select-mark a", function(){
		$.magnificPopup.open({
			items: {
				src: '#select-mark-pop-up',
				type: 'inline',
				midClick: true
			}
		});

		$("#select-mark-pop-up")}).magnificPopup({
		type:'inline',
		midClick: true
	});*/

	$(document).on('click', ".select_mark_pop-up_content ul li a", function(){
		event.stopPropagation();
		var brandId = $(this).attr('data-id');

		AutoBrest.loadModels(brandId);
		$("#add-part-form")
			.find("input#addpartform-brand_id")
			.val(brandId)
			.end()
            .find("input#addpartform-model_id")
            .val('');
		$("#add-auto")
			.find("input#addcarform-brand_id")
			.val(brandId)
            .end()
            .find("input#addcarform-model_id")
            .val('');
		$("#add-wheel").find("#addwheelform-auto-brand-id").val(brandId);

		$('.middle-btn-group.select-mark').removeClass('has-error');
		$('.middle-btn-group.select-mark').find('.form-group').removeClass('has-error');
		$('.middle-btn-group.select-mark').find('.help-block-error').text('');

		$('.select-part-mark a span').html($(this).find(".mark_auto").text());
		$('.select-mark a span').html($(this).find(".mark_auto").html().split('<')[0]);
		// Need for adding new advert
        $('.form-add .select-model a span').html('Выберите модель авто').parent().parent().removeClass('filled');
		

		if ($(".select_mark_pop-up_content ul li").hasClass('active_mark')){
			$(".select_mark_pop-up_content ul li").removeClass('active_mark');
		}

		$(this).parent().addClass('active_mark');
		$.magnificPopup.close();
	});

	/* Popup select model */

	$(document).on('click', ".select-part-model a, .select-model a", function(){
		$.magnificPopup.open({
			items: {
				src: '#select-model-pop-up',
				type: 'inline',
				midClick: true
			}
		});
	});

	/*$(document).on('click', ".select-model a", function(){
		$("#select-model-pop-up")}).magnificPopup({
		type:'inline',
		midClick: true
	});*/

	$(document).on("click", ".select_model_pop-up_content ul li a", function(){
		event.stopPropagation();
		$('.select-part-model a span').html($(this).find(".model_auto").text());
		$('.select-model a span').html($(this).find(".model_auto").html().split('<')[0]);

		if ($(".select_model_pop-up_content ul li").hasClass('active_model')){
			$(".select_model_pop-up_content ul li").removeClass('active_model');
		}

		$(this).parent().addClass('active_model');
		$.magnificPopup.close();
		$("#add-part-form")
			.find("input#addpartform-model_id")
			.val($(this).attr('data-model-id'));
		$("#add-auto")
			.find("input#addcarform-model_id")
			.val($(this).attr('data-model-id'));
		
		$('.middle-btn-group.select-model').removeClass('has-error');
		$('.middle-btn-group.select-model').find('.form-group').removeClass('has-error');
		$('.middle-btn-group.select-model').find('.help-block-error').text('');
	});

	/* Click on table string */

	$(document).on("click", ".select-group-result tr", function(){
		if ($(this).hasClass('active-block')) {
			$(this).removeClass('active-block');
			$(this).next('tr').find('.more-info').toggle();
		} else if (!$(this).find('.more-info').length) {
			$('.select-group-result').find('.active-block').removeClass('active-block').next('tr').find('.more-info').hide();
			$(this).addClass('active-block');
			$(this).next('tr').find('.more-info').toggle();
		}
	});

	$('.group-sort li').click(function() {
		$(this).parent().find('li').removeClass("active");
		$(this).toggleClass('active');
	});

	$(document).on("click", ".select-group-result-mobile-item", function(){
		if ($(this).hasClass('open')) {
			console.log(111);
			$(this).removeClass('open');
			$(this).next('.more-info-mobile').toggle();
		} else if (!$(this).find('.more-info-mobile').length) {
			$('.select-group-result-mobile').find('.open').removeClass('open').next('.more-info-mobile').hide();
			$(this).addClass('open');
			$(this).next('.more-info-mobile').toggle();
		}
	});

	$('.select-item').click(function() {
		$(this).parent().find('.select-options').slideToggle();
	});

	$('.select-options .select-option-value').click(function() {
		$(this).parent().parent().find('.select-item').find('.select-option-value').html($(this).html());
		$(this).parent().hide();
	});

	/* -------------------------- */

	$('.select-fuel-option').click(function() {
		$(this).parent().find('.select-fuel-option').removeClass('active');
		$(this).toggleClass('active');
	});

	$('.select-body-option').click(function() {
		$(this).parent().find('.select-body-option').removeClass('active');
		$(this).toggleClass('active');
	});

	$('.add-part-category-list-item-inner').click(function() {
		$(this).next().slideToggle();
	});

	$('.subcategory-item > span').click(function() {
		$(this).toggleClass('active');
		$(this).next().slideToggle();
	});

	$(".add-part-category-list").on('click', '.addblock-form-subcategory-element-item-img', function() {
		$(this).next().click();
	});

	$(document).on('change', '.addblock-form-subcategory-element-item-file', function(evt) {
		var tgt = evt.target || window.event.srcElement,
			files = tgt.files;
		var $imgEl = $(this).parent().find(".addblock-form-subcategory-element-item-img");
		// FileReader support
		if (FileReader && files && files.length) {
			var fr = new FileReader();
			fr.onload = function () {
				$imgEl.html('<img src="' + fr.result + '">');
			};
			fr.readAsDataURL(files[0]);
		}

		// Not supported
		else {
			alert("Ваш браузер не поддерживает загрузку изображений.");
		}
	});

	$('.userblock-about-photo').click(function() {
		$(this).next().click();
	});

	$('body').on('click','.check', function(event) {
		$(this).toggleClass('active');
		if (!$(this).hasClass('inp')) {
			$(this).parents('.addblock-form-subcategory-element').toggleClass('active');
		}
		if ($(this).hasClass('active')) {
			$(this).find('input[type=hidden]').val('1');
			if ($(this).hasClass('inp')) {
				$(this).parent().find('div.check-inp').hide();
				$(this).parent().find('input.check-inp').show();
			}

		} else {
			$(this).find('input[type=hidden]').val('0');
			$(this).parents('.addblock-form-subcategory-element').removeClass('active');
			if ($(this).hasClass('inp')) {
				$(this).parent().find('div.check-inp').show();
				$(this).parent().find('input.check-inp').removeClass('focus').val($(this).parent().find('input.check-inp').data('value')).hide();
			}
			if ($(this).parent().parent().hasClass("add-form-subcategory-element-other")) {
				$(this).parent().addClass('active');
			}
		}
		var $partsItems = $(".add-part-category-list").find(".check.active");
		if ($partsItems.length) {
			$('.parts-empty-error').text('');
		}
	});

	$('body').on('change keyup','input.check-inp', function(event) {
		event.preventDefault();
		if($(this).val().length>2){
			$(this).parent().addClass('active');
			$(this).parents('.addblock-form-subcategory-element').addClass('active');
			if(!$(this).parents('.addblock-form-subcategory-element').next().hasClass('addblock-form-subcategory-element-other')){
				$(this).parents('.addblock-form-subcategory-elements').append('<div class="addblock-form-subcategory-element addblock-form-subcategory-element-other">'+
													'<div class="addblock-form-subcategory-element-item">'+
														'<div class="check inp"><input type="hidden"/></div>'+
														'<div class="check-inp">Прочее</div>'+
														'<input type="text" class="check-inp pi-name" placeholder ="Введите название детали" />'+
													'</div>'+
													'<div class="addblock-form-subcategory-element-item">'+
														'<div class="addblock-form-subcategory-element-item-table">'+
															'<div class="addblock-form-subcategory-element-item-cell descr">'+
																'<textarea placeholder="Введите описание детали" class="addblock-form-subcategory-element-item-name"></textarea>'+
															'</div>'+
															'<div class="addblock-form-subcategory-element-item-cell photo">'+
																'<div class="addblock-form-subcategory-element-item-filebtn tooltip-has">'+
																	'<div class="photo-btn-delete">x</div>'+
																	'<div class="addblock-form-subcategory-element-item-img">+ Фото 1</div>'+
																	'<input type="file" class="addblock-form-subcategory-element-item-file" />'+
																	'<div class="tooltip-popup">Прикрепить главное фото</div>' +
																'</div>'+
																'<div class="addblock-form-subcategory-element-item-filebtn tooltip-has">'+
																	'<div class="photo-btn-delete">x</div>'+
																	'<div class="addblock-form-subcategory-element-item-img">+ Фото 2</div>'+
																	'<input type="file" class="addblock-form-subcategory-element-item-file" />'+
																	'<div class="tooltip-popup">Прикрепить фото</div>' +
																'</div>'+
																'<div class="addblock-form-subcategory-element-item-filebtn tooltip-has">'+
																	'<div class="photo-btn-delete">x</div>'+
																	'<div class="addblock-form-subcategory-element-item-img">+ Фото 3</div>'+
																	'<input type="file" class="addblock-form-subcategory-element-item-file" />'+
																	'<div class="tooltip-popup">Прикрепить фото</div>' +
																'</div>'+
															'</div>'+
															'<div class="addblock-form-subcategory-element-item-cell price tooltip-has">'+
																'<input type="text" placeholder ="Цена" class="addblock-form-subcategory-element-item-price" />'+
																'<div class="tooltip-popup">Укажите цену или по умолчанию</br>она будет стоять <span>договорная</span></div>' +
																'<div class="addblock-form-subcategory-element-item-cell currency">'+
																	'<span>руб</span>'+
																'</div>'+
															'</div>'+
														'</div>'+
													'</div>'+
												'</div>');
			}
		} else {
			$(this).parent().removeClass('active');
			$(this).parents('.addblock-form-subcategory-element').removeClass('active');
		}
	});

	$(document).on('click', '.show-dop-list', function() {
		$('.select-part-list-dop').slideToggle();
	});

	$('.add-part-category-list-item-inner').click(function() {
		var $nextItem = $(this).parent().next().find('span').first();

		if ($(this).hasClass('active')) {
			$nextItem.parent().css('border-top', 'none');
		} else {
			$nextItem.parent().css('border-top', '1px solid #e7e7e7');
		}
	});

	$('.add-part-category-list-item-inner').click(function() {
		$(this).toggleClass('active');
	});

	$('.small-btn-group .small-btn').click(function() {
		$(this).parent().find('.small-btn').removeClass('active');
		$(this).addClass('active');

		$(this).parent().find("input[type=radio]").prop("checked", false);
		$(this).find("input[type=radio]").prop("checked", true);
	});

	$('.middle-btn').click(function() {
		$(this).parent().find('.middle-btn').removeClass('active');
		$(this).addClass('active');

		$(this).parent().find("input[type=radio]").prop("checked", false);
		$(this).find("input[type=radio]").prop("checked", true);
	});

	$('.add-photo-img').click(function() {
		$(this).next().click();
	});

	$('.car-photo-file-input').on("change", function (e) {
		/*var tgt = evt.target || window.event.srcElement,
			files = tgt.files;
		var $imgEl = $(this).parent().parent().find(".add-photo-img");
		// FileReader support
		if (FileReader && files && files.length) {
			var fr = new FileReader();
			fr.onload = function () {
				$imgEl.html('<img src="' + fr.result + '">');
			};
			fr.readAsDataURL(files[0]);
		}

		// Not supported
		else {
			alert("Ваш браузер не поддерживает загрузку изображений.");
		}*/

        var $boxLoader = AutoBrest.getBoxLoader(),
			$fileInput = $(this),
			$box = $fileInput.parent();

        $box.prepend($boxLoader);
        setBoxEmpty($box);

        var files = $(e.target)[0].files;
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', uploadProgress, false);
        xhr.onreadystatechange = stateChange;
        xhr.open('POST', '/main/photo/upload');
        var formData = new FormData();
        formData.append("file", files[0]);
        formData.append("_csrf", yii.getCsrfToken());
        formData.append("type", $box.attr('data-type'));
        xhr.send(formData);

        function uploadProgress(event) {
            var percent = parseInt(event.loaded / event.total * 100);
            $boxLoader.find(".box-uploader-progress").css('width', percent + '%');
        }

        function stateChange(event) {
            if (event.target.readyState === 4) {
                if (event.target.status === 200) {
                    var data = jQuery.parseJSON(event.target.responseText);
                    if (data.result === 'error') {
                        $box.find('.add-photo-img').html('<p>' + data.message + '</p>');
                    } else {
                        $box.find('.add-photo-img').html('<img src="/tmp/' + data.file.name + '">');
                        $box.find('input[type=hidden]').val(data.file.name);
                        if (!$('#photo-error-block').hasClass('hidden')) { $('#photo-error-block').addClass('hidden'); }
                    }
                } else {
                    console.log("error");
                }
                $boxLoader.remove();
            }
        }

        function setBoxEmpty($box) {
            $box.find('.add-photo-img').html('<p>Прикрепить фото ' + $box.attr('data-num') + '</p>');
            $box.find('.photo-btn-delete').removeClass('shown');
            $box.find('input[type=hidden]').val('');
        }
	});

	$('.search-icon').click(function() {
		$('.search').toggleClass('opened');

		setTimeout(function() {
			$('#search input[type="text"').focus();
		}, 1000)
	});

	$('#search input[type="text"').on('blur', function() {
		$('.search').toggleClass('opened');
	})

	$('.menu li:last-child').click(function() {
		// $(this).parent().find('li').removeClass('opened');
		// $(this).toggleClass('opened');
		$('.dropmenu').slideToggle();
		$('.dropmenu').mouseleave(function() {
			// $('.menu li').removeClass();
			$(this).slideUp();
		});
	});

	$('.user-in').click(function() {
		var $content = $('.user-in-drop');

		if ($(window).width() < widthForMobile) {
			var $popup = $('.menu-mobile');

			if ($popup.is(':visible')) {
				$popup.hide();
			} else {
				$('body').toggleClass('no-scroll');
			}

			$content.toggleClass('user-in-drop--translate');
		} else {
			$content.mouseleave(function() {
				$(this).slideUp();
			});
			$content.slideToggle();
		}
	});

	$('.user-in-drop-close').click(function() {
		$('body').removeClass('no-scroll');
		$('.user-in-drop').removeClass('user-in-drop--translate')
	});

	$('.nav-mobile').click(function() {
		var $popup = $('.user-in-drop--translate');

		if ($popup.is(':visible')) {
			$popup.removeClass('user-in-drop--translate');
		} else {
			$('body').toggleClass('no-scroll');
		}

		$('.menu-mobile').toggle();
		
	});

	$('a.gallery-item').fancybox();

	/* Мои добавления */

	var urlParams = window
		.location
		.search
		.replace('?','')
		.split('&')
		.reduce(
			function(p,e){
				var a = e.split('=');
				p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
				return p;
			},
			{}
		);

	if (urlParams['showLogin'] == '1') {
		$.magnificPopup.open({
			items: {
				src: '#sign-in-pop-up',
				type: 'inline',
				midClick: true
			}
		});
	}

	// Изменение имени
	var isChangeUsernameActive = false;
	$("#cab-username-input").on('change', function () {
		if (!isChangeUsernameActive) {
			var username = $(this).val();
			$.ajax({
				url: '/user/cabinet/set-new-username',
				dataType: "json",
				type: "POST",
				data: {username: username, _csrf: yii.getCsrfToken()},
				beforeSend: function () {
					isChangeUsernameActive = true;
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result == 'success') {
						alert("Имя сохранено");
					} else {
						alert("Ошибка сохранения");
					}
				},
				complete: function () {
					isChangeUsernameActive = false;
				}
			});
		}
	});

	// Изменение пароля
	$("#password-change-btn").click(function(){
		var $form = $("#password-change");
		var $submitButton = $form.find('inout[type=submit]');
		var oldPassword = $form.find('input[name=oldPassword]').val();
		var newPassword = $form.find('input[name=newPassword]').val();
		var newPasswordVerify = $form.find('input[name=newPasswordVerify]').val();

		$.ajax({
			url: '/user/cabinet/set-new-password',
			dataType: "json",
			type: "POST",
			data: $form.serialize(),
			beforeSend: function () {
				$submitButton.prop('disabled', true).html('<img src="/images/ajax-loader2.gif">');
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					$.magnificPopup.open({
						items: {
							src: '#password-change-msg-pop-up',
							type: 'inline',
							midClick: true
						}
					});
				} else {
					alert("Ошибка сохранения");
				}
			},
			complete: function () {
				$submitButton.prop('disabled', false).html('Подтвердить');
			}
		});
	});

	// Изменение города
	var isChangeCityActive = false;
	$("#username-city").on('change', function () {
		if (!isChangeCityActive) {
			var city = $(this).val();
			$.ajax({
				url: '/user/cabinet/set-new-city',
				dataType: "json",
				type: "POST",
				data: {city: city, _csrf: yii.getCsrfToken()},
				beforeSend: function () {
					isChangeCityActive = true;
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result == 'success') {
						alert("Город сохранён");
					} else if (message in data) {
						alert(data.message);
					} else {
						alert("Какая-то ошибка");
					}
				},
				complete: function () {
					isChangeCityActive = false;
				}
			});
		}
	});

	// Изменение телефона
	var isChangePhoneActive = false;
	$("#user-phone").on('change', function () {
		changePhone();
	});
    $("#user-phone_operator").on('change', function () {
        changePhone();
    });

	function changePhone() {
        if (!isChangePhoneActive) {
            var phone = $("#user-phone").val();
            var phone_operator = $("#user-phone_operator").val();
            $.ajax({
                url: '/user/cabinet/set-new-phone',
                dataType: "json",
                type: "POST",
                data: {phone: phone, phone_operator: phone_operator, _csrf: yii.getCsrfToken()},
                beforeSend: function () {
                    isChangePhoneActive = true;
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.result === 'success') {
                        $("#user-phone").val(data.phone);
                        alert("Телефон сохранён");
                    } else if (message in data) {
                        alert(data.message);
                    } else {
                        alert("Какая-то ошибка");
                    }
                },
                complete: function () {
                    isChangePhoneActive = false;
                }
            });
        }
    }

	// Изменение времени звонка
	var isChangeCallTimeActive = false;
	$("#user-calltime-from").on('change', function () {
		changeCalltime();
	});
	$("#user-calltime-to").on('change', function () {
		changeCalltime();
	});
	var changeCalltime = function () {
		if (!isChangeCallTimeActive) {
			var calltime_from = $("#user-calltime-from").val();
			var calltime_to = $("#user-calltime-to").val();
			$.ajax({
				url: '/user/cabinet/set-new-calltime',
				dataType: "json",
				type: "POST",
				data: {from: calltime_from, to: calltime_to, _csrf: yii.getCsrfToken()},
				beforeSend: function () {
					isChangeCallTimeActive = true;
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result == 'success') {
						//alert("Телефон сохранён");
					} else if (message in data) {
						alert(data.message);
					} else {
						alert("Какая-то ошибка");
					}
				},
				complete: function () {
					isChangeCallTimeActive = false;
				}
			});
		}
	};

	// Смена аватарки
	var isChangeAvatarActive = false;
	$("#user-photo-input").on('change', function () {
		if (!isChangeAvatarActive) {
			var fd = new FormData;
			fd.append('avatar', $(this).prop('files')[0]);
			fd.append('_csrf', yii.getCsrfToken());
			$.ajax({
				url: '/user/cabinet/set-new-avatar',
				dataType: "json",
				processData: false,
				contentType: false,
				type: "POST",
				data: fd,
				beforeSend: function () {
					isChangeAvatarActive = true;
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result == 'success') {
						$(".userblock-about-img > img").attr('src', data.image);
						$('.user-in-icon').attr('style', 'background-image:url(' + data.image + ');background-size:40px 40px;')
					} else if (data.message) {
						alert(data.message);
					} else {
						alert("Какая-то ошибка");
					}
				},
				complete: function () {
					isChangeAvatarActive = false;
				}
			});
		}
	});
	
	// Добавление запчастей
	var isAddPartAjaxActive = false;
	$('#add-part-form').on('click', '.publish-add', function () {
		var $form = $('#add-part-form');
		var $partsItems = $(".add-part-category-list").find(".check.active");		
		
		if (isAddPartAjaxActive) {
			$("#add-part-form").yiiActiveForm('validate', false);
			return false;
		}

		var $brandBlock = $("#addpartform-brand_id");
		var $modelBlock = $("#addpartform-model_id");
		var $typeBlock = $("input[name='AddPartForm[fuel_id]']");
		var $engineBlock = $("select[name*='engine_volume']");
		var $yearBlock = $("#addpartform-year");
		var $bodyBlock =  $("input[name='AddPartForm[body_style]']");

		var emptyField = false;
		if (!$brandBlock.val()) {
			$brandBlock.closest('.middle-btn-group').addClass('has-error');
			$brandBlock.next().text('Укажите марку авто')
			emptyField = true;
		}

		if (!$modelBlock.val()) {
			$modelBlock.closest('.middle-btn-group').addClass('has-error');
			$modelBlock.next().text('Укажите модель авто');
			emptyField = true;
		}

		if (!$("input[name='AddPartForm[fuel_id]']:checked").length) {
			var $parent = $typeBlock.closest('.middle-btn-group');
			$parent.addClass('has-error');
			$parent.find('.help-block-error').text('Выберите тип двигателя');
			emptyField = true;
		}

		if (!$engineBlock.val()) {
			var $parent = $engineBlock.closest('.middle-btn-group');
			$parent.addClass('has-error');
			$parent.find('.help-block-error').text('Выберите объём двигателя');
			emptyField = true;
		}

		if (!$yearBlock.val()) {
			var $parent = $yearBlock.closest('.middle-btn-group');
			$parent.addClass('has-error');
			$parent.find('.help-block-error').text('Укажите год выпуска авто');
			emptyField = true;
		}

		if (!$("input[name='AddPartForm[body_style]']:checked").length) {
			var $parent = $bodyBlock.closest('.middle-btn-group');
			$parent.addClass('has-error');
			$parent.find('.help-block-error').text('Выберите тип кузова авто');
			emptyField = true;
		}		
		
		if (!$partsItems.length) {
			$('.parts-empty-error').text('Вы не добавили ни одной детали!');
			emptyField = true;
		}

		if (emptyField) return false;

		var errors = false;
		var formData = new FormData($form[0]);
		$.each($partsItems, function (k, item) {
			var catId = $(item).parent().parent().parent().parent().attr("data-cat-id");
			var piName = $(item).parent().find("input.pi-name").val();
			var $li = $(item).parent().parent();
			var piDescr = $li.find(".descr > input").val();

			//var piPhoto;
			var piPhotoInputs = $li.find(".photo input");
			$.each(piPhotoInputs, function (f, input) {
				if (input.files[0]) {
                    formData.append("AddPartForm[photo]["+k+"][]", input.files[0]);
				}
            });
			//[0].files[0];

			var piPrice = $li.find(".price > input").val();

			if (!catId || !piName) {
				alert("Вы не указали название детали, либо цену детали. Проверьте введённые данные");
				errors = true;
				return false;
			}

			formData.append("AddPartForm[category_id]["+k+"]", catId);
			formData.append("AddPartForm[name]["+k+"]", piName);
			formData.append("AddPartForm[description]["+k+"]", piDescr);
			/*if (piPhoto) {
				formData.append("AddPartForm[photo]["+k+"]", piPhoto);
			}*/
			formData.append("AddPartForm[price]["+k+"]", piPrice);
		});

		if (errors === false) {
			$.ajax({
				url: "/parts/add",
				type: "POST",
				processData: false,
				contentType: false,
				data: formData,
				beforeSend: function () {
					isAddPartAjaxActive = true;
					$.magnificPopup.open({
						items: {
							src: '#loader-pop-up',
							type: 'inline',
							midClick: true
						}
					});
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result === 'success') {
						window.location = "/parts";
					} else if (data.message) {
						alert(data.message);
					}
				},
				complete: function () {
					isAddPartAjaxActive = false;
					$.magnificPopup.close();
				}
			});
		}

		return false;
	});

	$(".parts-adverts").on("click", ".delete-advert", function () {
		if (confirm("Вы уверены что хотите удалить это объявление?")) {
			var $partElement = $(this).parent().parent();
			var id = $(this).attr("data-part-id");
			$.ajax({
				url: "/main/parts/" + id + "/remove",
				type: "post",
				beforeSend: function () {
					Loader.show();
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result === 'success') {
						$partElement.fadeOut("slow", function() {
							$(this).hide();
						});
					} else if (data.message) {
						alert(data.message);
					} else {
						alert("Какая-то ошибка");
					}
				},
				complete: function () {
					Loader.close();
				}
			});
		}
	});

	// Выводит попап с колесом загрузки по центру
	var Loader = {
		show: function () {
            $.magnificPopup.close();
			$.magnificPopup.open({
				items: {
					src: '#loader-pop-up',
					type: 'inline',
					midClick: true
				}
			});
		},
		close: function () {
			$.magnificPopup.close();
		}
	};

	$.each($("time"), function (k, el) {
		var el = $(el);
		var timeLeftAttr = el.attr("data-time-left");
		if (typeof timeLeftAttr !== typeof undefined && timeLeftAttr !== false) {
			var text = el.attr("data-ago") ? moment(timeLeftAttr * 1000).fromNow() : moment().to(moment(timeLeftAttr * 1000), true);
            el.text(text);
		}
	});

    $(document).on("click", ".complaint-link", function () {
        var $link = $(this);
        var ad_id = $link.attr("data-ad-id");
        var ad_type = $link.attr("data-ad-type");
        var $form = $("#complain-msg-form");
        $form.find("input[name=ad_id]").val(ad_id);
        $form.find("input[name=ad_type]").val(ad_type);
        $.magnificPopup.open({
            items: {
                src: '#complain-msg',
                type: 'inline',
                midClick: true
            }
        });
	});

    $(document).on("click", "#complain-msg-btn", function () {
        var $form = $("#complain-msg-form");
        var ad_id = $form.find("input[name=ad_id]").val();
        var ad_type = $form.find("input[name=ad_type]").val();
        var message = $form.find("textarea[name=msg-text]").val();

        $.ajax({
            url: '/main/default/send-complaint',
            type: "post",
            data: {ad_type: ad_type, ad_id: ad_id, message: message},
            beforeSend: function () {
                $form.find('button[type=button]').prop('disabled', true);
            },
            success: function(data, textStatus, jqXHR) {
                if (data.result == 'success') {
                    $form.parent().hide();
                    $form.parent().parent().find('.popup-success').addClass('success-show');
                } else if (data.message) {
                    AutoBrest.alertPopup(data.message);
                } else {
                    AutoBrest.alertPopup("Какая-то ошибка");
                }
            },
            complete: function () {
                $form.find('button[type=submit]').prop('disabled', false);
            }
        });
    });

	// Продлить объявление
	$(document).on("click", ".ad-prolong-link", function () {
        var $link = $(this);
		var isGroup = $link.attr('data-group');
        var ad_id = $link.attr("data-ad-id");
        var ad_type = $link.attr("data-ad-type");

		if (!confirm(isGroup ? "Продлить все объявления этой группы?" : "Продлить это объявление на 30 дней?")) {
			return;
		}

		$.ajax({
			url: '/main/default/prolong',
			type: "post",
			data: {ad_type: ad_type, ad_id: ad_id, is_group: isGroup},
			beforeSend: function () {
				Loader.show();
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result === 'success') {
					alert(isGroup ? "Объявления будут активны ещё 30 дней." : "Объявление будет активно ещё 30 дней.");
					$link.removeClass("ad-prolong-link").addClass("not-active");
					$link.parent().find("p > time").text("30 дней");
				} else if (data.message) {
					alert(data.message);
				} else {
					alert("Какая-то ошибка");
				}
			},
			complete: function () {
				Loader.close();
			}
		});
	});

	// Клик по "показать телефон"
	$(document).on("click", ".show-phone-link", function () {
		var ad_id = $(this).attr("data-ad-id");
		var ad_type = $(this).attr("data-ad-type");
		var $link = $(this);
		$.ajax({
			url: '/main/default/get-phone',
			type: "post",
			data: {ad_type: ad_type, ad_id: ad_id},
			beforeSend: function () {
				$link.html('<img src="/images/ajax-loader2.gif">');
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					$link.parent().html(data.html);
					$link.remove();
				} else if (data.message) {
					alert(data.message);
					$link.html("Показать телефон");
				} else {
					alert("Какая-то ошибка");
					$link.html("Показать телефон");
				}
			},
			error: function () {
				$link.html("Показать телефон");
			}
		});
	});

});

var AutoBrest = {
	loadModels: function(brandId) {
		var $contentBlock = $('#select-model-pop-up .select_model_pop-up_content');
		var $button = $("#select-model-pop-up").find(".popup-footer > button");
		var cc = $('#ccId').text(),
			ac = $('#acId').text();
		$.ajax({
			url: '/main/default/get-models',
			dataType: "json",
			type: "POST",
			data: {brand_id: brandId, _csrf: yii.getCsrfToken(), cc: cc, ac: ac},
			beforeSend: function () {
				/*$.magnificPopup.open({
				 items: {
				 src: '#select-model-pop-up',
				 type: 'inline',
				 midClick: true
				 }
				 });*/
				$contentBlock.empty().append('<div class="center-block" style="width:50px;"><img src="/images/ajax-loader.gif"></div>');
				$button.hide();
			},
			success: function(data, textStatus, jqXHR) {
				if (data.result == 'success') {
					$contentBlock.empty();
					var html = "<ul>";
					$.each(data.models, function (key, model) {
						html = html + '<li><a href="javascript:void(0)" data-model-id="'+model.id+'"><span class="model_auto">'
							+ model.name
							+ (model.count.length > 0 ? '<span class="counter">' + model.count + '</span>' : '' )
							+ '</span></a></li>';
					});
					html = html + "</ul>";
					$contentBlock.append(html);
					$button.show();
				} else if (message in data) {
					alert(data.message);
				} else {
					alert("Какая-то ошибка");
				}
			},
			error: function () {
				$contentBlock.empty();
				alert("Ошибка при загрузке с сервера");
			}
		});
	},
    loadTireModels: function(brandSelector, modelSelector) {
		var brandId = brandSelector.val();
		if (brandId) {
            $.ajax({
                url: '/main/tires/get-models',
                dataType: "json",
                type: "POST",
                data: {id: brandId, _csrf: yii.getCsrfToken()},
                beforeSend: function () {
                    modelSelector.empty();
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.result == 'success') {
                        modelSelector.empty().append("<option>Все</option>");
                        $.each(data.items, function (key, model) {
                            modelSelector.append('<option value="' + model.id + '">' + model.name + '</option>');
                        });
                    } else if (message in data) {
                        alert(data.message);
                    } else {
                        alert("Какая-то ошибка");
                    }
                },
                error: function () {
                    modelSelector.empty().append("<option>Все</option>");
                    alert("Ошибка при загрузке с сервера");
                }
            });
		} else {
            modelSelector.empty().append("<option>Все</option>");
		}
    },
	alertPopup: function (message) {
		$("#alert-pop-up").find(".alert-message").html(message);
        $.magnificPopup.open({
            items: {
                src: '#alert-pop-up',
                type: 'inline',
                midClick: true
            }
        });
    },
    getBoxLoader: function () {
        return $('<div class="box-uploader">\n' +
            '<div class="box-uploader-bg"></div>\n' +
            '<img src="/images/spin.gif" style="top:calc(50% - 32px);left:calc(50% - 32px);">\n' +
            '<div class="box-uploader-progress"></div>' +
            '</div>');
    },
    scrollTo: function ($el, plus) {
		if (!plus) { plus = 0; }
        $('html, body').animate({
            scrollTop: $el.offset().top + plus
        }, 800);
    },
    photoValidate: function () {
        var $photos = $('input[id*="photo-hidden-"');
        var isNotEmpty = false;
        $.each($photos, function (k, photo) {
            if ($(photo).val().length > 0) {
                isNotEmpty = true;
            }
        });
        if (!isNotEmpty) {
            $('#photo-error-block').removeClass('hidden');
            AutoBrest.scrollTo($('#photo-error-block').parent());
        } else {
            $('#photo-error-block').addClass('hidden');
        }
        return isNotEmpty;
    }
};