$(document).ready(function() {
    $('.contact-with-user').click(function() {
        $.magnificPopup.open({
            items: {
                src: '#open-msg',
                type: 'inline',
                midClick: true
            }
        });
    });

	$('#open-msg-form button[type=button]').on('click', function(event) {
        event.stopPropagation();
        var form = $('#open-msg-form');
		var formId = '#' + form.attr('id');
		if (formId === '#open-msg-form') {
		    var message = $("#new-ad-message").val();
            var adType = form.attr('data-ad-type');
            var adId = form.attr('data-ad-id');
		    $.ajax('/user/ad-message/new', {
                dataType: "json",
                type: "POST",
                data: {
                    adType: adType,
                    adId: adId,
                    message: message,
                    _csrf: yii.getCsrfToken()
                },
                beforeSend: function () {
                    form.find('button[type=button]').prop('disabled', true);
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.result === 'success') {
                        form.parent().hide();
                        form.parent().parent().find('.popup-success').addClass('success-show');
                    } else {
                        alert("Ошибка отправки");
                    }
                },
                complete: function () {
                    form.find('button[type=submit]').prop('disabled', false);
                }
            });
        }

		return false;
	});

	$('.delete-msg').on('click', function () {
        var dialogId = $(this).attr('data-dialog-id');
        AutoBrest.alertPopup("Удалить диалог со всеми сообщениями?<br><a href='/user/ad-message/delete?id=" + dialogId + "'>Удалить</a>");
    });
});