jQuery(document).ready(function ($) {
    var add_form = $('#as-form');
    const textOnButton = $('#text-on-button');

    // Сброс значений полей
    function resetFormMessages() {
        $('#as-form select, #as-form input').removeClass('error');
        $('.error-address, .error-phone, .error-regions, .error-is-client-yes, .message-success').remove();
        textOnButton.text('ЗАМОВИТИ');
    }

    // resetFormMessages();

    // Отправка значений полей
    var options = {
        url: feedback_object.url,
        data: {
            action: 'feedback_action',
            nonce: feedback_object.nonce
        },
        type: 'POST',
        dataType: 'json',
        beforeSubmit: function (xhr) {
            // При отправке формы меняем надпись на кнопке
            textOnButton.text('ВІДПРАВКА...');
        },
        success: function (request, xhr, status, error) { 
            console.log(request);           
            if (request.success === true) {
                // Если все поля заполнены, отправляем данные и меняем надпись на кнопке
                add_form.after('<div class="message-success"><h2 class="success-text">' + request.data + '</h2></div>').slideDown();
                textOnButton.text('УСПІШНО!');
                $('#as-form')[0].reset();
                setTimeout(() => {
                    resetFormMessages();
                }, 3000);
            } else {
                // Если поля не заполнены, выводим сообщения и меняем надпись на кнопке
                $.each(request.data, function (key, val) {
                    $('.as-' + key).before('<span class="error-' + key + '">' + val + '</span>');
                    $('.as-' + key).one( 'focus', (event) => {
                        $('.as-' + key).prev().remove();
                    })
                    if (key === 'is-client-yes'){
                        $('.as-is-client-no').one('focus', () => {
                            $('.as-is-client-yes').prev().remove();  
                        })
                    }               
                });
                textOnButton.text('ПОМИЛКА!');
            }
            // При успешной отправке сбрасываем значения полей
            
        },
        error: function (request, status, error) {
            alert('Помилка! Спробуйте перезавантажити сторінку і відправити замовлення ще раз.<br />' + error );
        }
    };
    // Отправка формы
    add_form.ajaxForm(options);
});