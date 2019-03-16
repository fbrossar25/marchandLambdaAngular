$('#error').hide();

$.validator.messages.required = 'Ce champ est requis';

$('#subscriptionForm').validate({
    rules:{
        name:{
            remote: '/article-disponible'
        }
    },
    messages:{
        price:{
            min: 'Vous devez saisir un nombre > 0'
        },
        name:{
            remote: 'Ce produit existe déjà'
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