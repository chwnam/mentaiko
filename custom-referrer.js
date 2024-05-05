jQuery(function ($) {
    const profile = $('#mentaiko-user-profile')
        , referrer = $('fieldset[name="referrer"]', profile)
        , group = $('.rwmb-select', referrer)
        , imageWrapper = $('.rwmb-field.rwmb-image-wrapper', profile)
        , imageInput = $('.rwmb-input', imageWrapper)
        , fileInput = $('input[type="file"]', imageInput)

    const p = $('<p>', {
        'class': 'description',
        text: '학교 지인으로 선택한 경우 지정된 이미지로만 표시됩니다.'
    }).appendTo(imageWrapper)

    group.on('change', () => {
        const option = group.find('option:selected')
            , label = option.closest('optgroup').attr('label')

        if ('학교 지인' === label) {
            fileInput.removeAttr('required')
            imageInput.hide()
            p.show()
        } else {
            fileInput.attr('required', 'required')
            imageInput.show()
            p.hide()
        }
    }).trigger('change')
})
