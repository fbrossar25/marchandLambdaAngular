$('#error').hide();

$.validator.messages.required = 'Ce champ est requis';
$.validator.messages.email = 'Ce champ doit être une adresse email valide';

function invalidHandler(event, validator){
    if(validator.numberOfInvalids()){
        $('#error').show();
    }else{
        $('#error').hide();
    }
}

$('#subscriptionForm').validate({
    rules:{
        email: {
            required: true,
            email: true,
            remote: "email-disponible"
        },
        // firstName: "required",
        // lastName: "required",
        // password: "required",
        // confirm: "required",
        // city: "required",
        // postalCode: "required",
        // street: "required",
        // streetNo: "required"
    },
    messages:{
        email:{
            remote: "Un compte utilise déjà cet adresse email"
        }
    },
    invalidHandler: invalidHandler,
    errorClass: 'is-invalid',
    validClass: 'is-valid'
});