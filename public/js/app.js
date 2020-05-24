// form masks
$(".mask-cpfcnpj").keydown(function () {
    try {
        $(".mask-cpfcnpj").unmask();
    } catch (e) { }

    var tamanho = $(".mask-cpfcnpj").val().length;

    if (tamanho < 11) {
        $(".mask-cpfcnpj").mask("999.999.999-99");
    } else {
        $(".mask-cpfcnpj").mask("99.999.999/9999-99");
    }
});

$('.mask-phone').mask(function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
}, {
    onKeyPress: function (val, e, field, options) {
        field.mask(MaskBehavior.apply({}, arguments), options);
    }
});

$('.mask-time').mask('00:00');

$('.mask-date').mask('00/00/0000');

$('.mask-zipcode').mask('00000-000');

$('.mask-cpf').mask('000.000.000-00', {
    reverse: true
});

$('.mask-cnpj').mask('00.000.000/0000-00', {
    reverse: true
});

$('.mask-money').mask('000.000.000.000.000,00', {
    reverse: true
});

$('.mask-plaque').mask('AAA-0000');

// form validation
$('form').submit(function(e) {
    $(this)
        .find('button:submit')
        .attr('disabled', 'disabled')
        .text('Por favor, aguarde...');

    return true;
})

// bootstrap components
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
