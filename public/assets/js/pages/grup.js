(function () {
    const formGrup = document.querySelector('#formGrup');
    // Form validation for Add new record
    if (formGrup) {
        const fv = FormValidation.formValidation(formGrup, {
            fields: {
                kode_grup: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Grup Harus Disii !'
                        },

                        stringLength: {
                            max: 3,
                            message: 'Kode Grup Max. 3 Karakter'
                        },
                    }
                },

                nama_grup: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Grup Harus Diisi !'
                        },
                    },

                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });
    }
})();



