$('#error').hide();

$.validator.messages.required = 'Ce champ est requis';
$.validator.messages.email = 'Ce champ doit être une adresse email valide';
$.validator.messages.minlength = 'Au moins 6 caractères requis';

$('#subscriptionForm').validate({
    rules:{
        email: {
            required: true,
            email: true,
            remote: 'email-existe'
        },
        confirm:{
            equalTo: '#password'
        }
    },
    messages:{
        email:{
            remote: "Ce compte n'existe pas"
        },
        confirm:{
            equalTo: 'Les mots de passes de correspondent pas'
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