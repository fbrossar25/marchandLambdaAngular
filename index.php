<?php
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

require_once 'vendor/autoload.php';
require_once 'bootstrap-doctrine.php';

$clientRepository = $entityManager->getRepository('Client');

$LOADER = new Twig_Loader_Filesystem('src/templates/');
$TWIG = new Twig_Environment($LOADER, array(
    'cache' => false
));

function load_template($template){
    global $TWIG;
    return $TWIG->loadTemplate("$template.twig.html");
}

function emailDisponible($email){
    global $clientRepository;
    return $clientRepository->count(['email' => $email]) === 0;
}

function enregistrer_utilisateur(array $data){
    return false;
}

$app = new \Slim\App([
    'settings'=> [
        'displayErrorDetails' => true,
    ]
]);

$app->redirect('/', '/accueil', 303);

$app->get('/accueil', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('accueil')->render([]));
});

$app->get('/connexion', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('connexion')->render([]));
});

$app->get('/inscription', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('inscription')->render([]));
});

$app->post('/inscription-validation', function(Request $req, Response $resp, array $args){
    if(enregistrer_utilisateur($req->getParsedBody())){
        return $resp->write(load_template('inscription-validation')->render([]));
    }else{
        return $resp->write(load_template('erreur')->render([
            'message'=>"Erreur lors de l'inscription, veuillez ré-essayer ultérieurement"
        ]));
    }
});

$app->get('/email-disponible', function(Request $req, Response $resp, array $args){
    return $resp->withJson(emailDisponible($req->getParam('email')));
});

$app->run();
