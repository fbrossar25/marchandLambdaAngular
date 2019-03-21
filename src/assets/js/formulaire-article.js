$('#error').hide();

$('#imageUrl').blur(event => {
    $('#imgThumbnail').attr('src', $('#imageUrl').val());
});

$.validator.messages.required = 'Ce champ est requis';
$.validator.messages.url = 'Ce champ doit être une URL valide';

$('#addArticleForm').validate({
    rules:{
        name:{
            remote: '/article-disponible'
        },
        imageUrl:{
            url: true
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