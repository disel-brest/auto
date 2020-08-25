function addPhone() {
    $('.templates .phoneRow').clone().appendTo(".phonesContainer");
}

function getCoords() {
    var city = $('#city-input').val();
    var street = $('#street-input').val();
    window.open('https://yandex.ru/maps/?text=' + city + ',' + street, '_blank', 'height=680,width=940');
}

var AdminPanel = AdminPanel || {};
AdminPanel = (function () {
    var listen = function () {
        $("#send-mail-btn").on('click', function () {
            var IDs = $('#users-grid').yiiGridView('getSelectedRows');
            var $form = $("#send-mail-form");
            var formName = $form.attr('data-form-name');
            $form.find('input[name*=users]').remove();
            $.each(IDs, function (k, id) {
                $form.append('<input type="hidden" name="' + formName + '[users][]" value="' + id + '">');
            });
            $form.submit();
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

            var $boxLoader = getBoxLoader(),
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

            function getBoxLoader() {
                return $('<div class="box-uploader">\n' +
                    '<div class="box-uploader-bg"></div>\n' +
                    '<img src="/images/spin.gif" style="top:calc(50% - 32px);left:calc(50% - 32px);">\n' +
                    '<div class="box-uploader-progress"></div>' +
                    '</div>');
            }
        });
    };

    function init() {
        listen();
    }

    return {
        init: init
    };
})();

window.addEventListener('load', function () {
    AdminPanel.init()
});