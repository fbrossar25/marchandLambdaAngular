$('#error').hide();

$.validator.messages.required = 'Ce champ est requis';
$.validator.messages.email = 'Ce champ doit être une adresse email valide';
$.validator.messages.minlength = 'Au moins 6 caractères requis';

$('#editAccount').validate({
    rules:{
        email: {
            required: true,
            email: true,
            remote: 'email-changement-disponible'
        },
        confirm:{
            equalTo: '#newPassword'
        },
        newPassword:{
            equalTo: '#newPassword'
        }
    },
    messages:{
        email:{
            remote: 'Un compte utilise déjà cet adresse email'
        },
        confirm:{
            equalTo: 'Les nouveaux mots de passes de correspondent pas'
        }
    },
    invalidHandler: (event, validator) => {
        if(validator.numberOfInvalids()){
            $('#error').show();
        }else{
            $('#error').hide();
        }
    },
    errorClass: 'is-invalid',
    validClass: 'is-valid'
});