$(document).ready(function () {
    // form masks
    $('.mask-cpfcnpj').keydown(function () {
        try {
            $('.mask-cpfcnpj').unmask();
        } catch (e) {
            console.log(e);
        }

        const tamanho = $('.mask-cpfcnpj').val().length;

        if (tamanho < 11) {
            $('.mask-cpfcnpj').mask('999.999.999-99');
        } else {
            $('.mask-cpfcnpj').mask('99.999.999/9999-99');
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
    $('form').submit(function() {
        let message = $(this)
            .find('button:submit')
            .attr('data-message');

        if (message == null) {
            message = 'Por favor, aguarde...';
        }

        $(this)
            .find('button:submit')
            .attr('disabled', 'disabled')
            .text(message);

        return true;
    })

    // bootstrap components
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })

    $('.confirmAction').click(function(e) {
        e.preventDefault();
        const message = $(this).attr('data-message') || 'Você tem certeza?';
        const url = $(this).attr('href');
        const res = confirm(message);

        if (res == true) {
            window.location.href = url;
        }
    });

    // lazy loading images
    // $('.lazy').lazy({
    //     scrollDirection: 'vertical',
    //     effect: 'fadeIn',
    //     visibleOnly: true,
    //     onError: function (element) {
    //         console.log('error loading ' + element.data('src'));
    //     }
    // });

    $('.onchange-submit').change(function() {
        this.form.submit();
    })

    // busca cep
    $('#inputZipcode').blur(function () {
        const zipcode = $(this).val();
        const url = 'https://viacep.com.br/ws/' + zipcode + '/json';
        const stringLoading = 'carregando...';

        $('#inputStreet').val(stringLoading);
        $('#inputDistrict').val(stringLoading);
        $('#inputCity').val(stringLoading);
        $('#inputState').val(stringLoading);

        fetch(url)
            .then(response => response.json())
            .then(response => {
                if (!('erro' in response)) {
                    $('#inputStreet').val(response.logradouro);
                    $('#inputDistrict').val(response.bairro);
                    $('#inputCity').val(response.localidade);
                    $('#inputState').val(response.uf);
                    $('#inputNumber').focus();
                } else {
                    $('#inputStreet').val('').focus();
                    $('#inputDistrict').val('');
                    $('#inputCity').val('');
                    $('#inputState').val('');

                    alert('Este CEP (' + zipcode + ') não foi encontrado na base dados, por favor, informe os dados do endereço manualmente.');
                }
            })
            .catch(error => console.error(error))
    });
});
