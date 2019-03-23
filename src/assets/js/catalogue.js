let ID_ARTICLE = -1;

$('#error').hide();

$.validator.messages.required = 'Ce champ est requis';
$.validator.messages.url = 'Ce champ doit être une URL valide';

$('#updateArticleForm').validate({
    rules:{
        name:{
            remote: {
                url: '/article-changement-disponible',
                type: 'post',
                data:{
                    nom: $('#updateArticleForm #name').val(),
                    id: () => {return ID_ARTICLE;}
                }
            }
        },
        imageUrl: {
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

$('#imageUrl').blur(event => {
    $('#imgThumbnail').attr('src', $('#imageUrl').val());
});

$('#admin-modale-suppression-erreur').hide();
$('#admin-modale-suppression').on('show.bs.modal', event => {
    //Récupération des infos de l'article
    let button = $(event.relatedTarget);
    let idArticle =  button.data('id');
    let nom = $(`#article-${idArticle} .card-title`).text();
    if(nom !== ''){
        $('#admin-modale-suppression-message').text(`Êtes-vous sur de vouloir supprimer l'article '${nom}' ?`);
    }

    $('#admin-modale-suppression-btn').on('click', event => {
        //Envoi de la demande de suppression
        $.post('/supprimer-article', {id: idArticle})
            .done(() => {
                location.reload(false);
            })
            .fail(() => {
                $('#admin-modale-suppression-erreur').show();
            });
    });
});

function getCardData(id){
    let cardBody = $(`#article-${id} .card-body`);
    return {
        id: id,
        nom: cardBody.find('.card-title').text(),
        prix: cardBody.find('h5').text().replace(/€/g, ''),
        description: cardBody.find('.card-text').text(),
        imageUrl: $(`#article-${id} .card-img`).attr('src')
    };
}

function getPostData(id){
    let form = $('#updateArticleForm');
    return {
        id: id,
        nom: form.find('#name').val(),
        prix: form.find('#price').val(),
        description: form.find('#description').val(),
        imageUrl: form.find('#imageUrl').val()
    };
}

$('#admin-modale-modification-erreur').hide();
$('#admin-modale-modification').on('show.bs.modal', event => {
    //Récupération des infos de l'article
    let button = $(event.relatedTarget);
    let idArticle = button.data('id');
    let data = getCardData(idArticle);

    //pour la validation du nom de l'article dans le formulaire de modification
    ID_ARTICLE = idArticle;

    //Pré-remplissage du formulaire de modification
    let form = $('#updateArticleForm');
    form.find('#name').val(data.nom);
    form.find('#price').val(data.prix);
    form.find('#description').text(data.description);
    form.find('#imageUrl').val(data.imageUrl);
    $('#imgThumbnail').attr('src', data.imageUrl);

    $('#admin-modale-modification-btn').on('click', event => {
        if($('#updateArticleForm').valid()){
            //Envoi de la demande de modification
            $.post('/modifier-article', getPostData(idArticle))
                .done(() => {
                    location.reload(false);
                })
                .fail(() => {
                    $('#admin-modale-modification-erreur').show();
                });
        }
    });
}).on('hidden.bs.modal', event => {
    $('#admin-modale-modification-erreur').hide();
    $('#updateArticleForm').trigger('reset');
    ID_ARTICLE = -1;
});