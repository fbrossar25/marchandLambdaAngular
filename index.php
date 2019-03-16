<?php
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

require_once 'vendor/autoload.php';
require_once 'bootstrap-doctrine.php';

session_start();

$clientRepository = $entityManager->getRepository('Client');

$LOADER = new Twig_Loader_Filesystem('src/templates/');
$TWIG = new Twig_Environment($LOADER, array(
    'cache' => false
));
$TWIG->addGlobal('session',$_SESSION);

function load_template($template){
    global $TWIG;
    return $TWIG->loadTemplate("$template.twig.html");
}

function emailDisponible($email){
    global $clientRepository;
    return $clientRepository->count(['email' => $email]) === 0;
}

function connect($email, $pass){
    global $clientRepository;
    $client = $clientRepository->findOneBy(['email' => $email]);
    if($client !== null && password_verify($pass, $client->getPass())){
        return $client;
    }else{
        return false;
    }
}

function enregistrer_utilisateur(array $data){
    global $entityManager;
    $client = new Client();
    $client->setPrenom($data['firstName']);
    $client->setNom($data['lastName']);
    $client->setEmail($data['email']);
    $client->setPass(password_hash($data['password'], PASSWORD_BCRYPT));
    $client->setCommune($data['city']);
    $client->setVoie($data['street']);
    $client->setNumeroVoie($data['streetNo']);
    $client->setTelephone($data['phoneNumber']);
    $client->setCodePostal($data['postalCode']);
    try{
        $entityManager->persist($client);
        $entityManager->flush();
    }catch (\Doctrine\ORM\OptimisticLockException | \Doctrine\ORM\ORMException $e) {
        return false;
    }
    return $client;
}

$app = new \Slim\App([
    'settings'=> [
        'displayErrorDetails' => true,
    ],
    'notFoundHandler' => function ($c) {
        return function ($req, $resp) use ($c) {
            $response = new \Slim\Http\Response(404);
            return $response->write(load_template('404')->render([]));
        };
    }
]);

$app->redirect('/', '/accueil', 303);

$app->get('/accueil', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('accueil')->render([]));
});

$app->get('/connexion', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('connexion')->render([]));
});

$app->post('/connexion', function(Request $req, Response $resp, array $args){
    $data = $req->getParsedBody();
    $client = connect($data['email'], $data['password']);
    if($client !== null && $client !== false){
        $_SESSION['email'] = $client->getEmail();
        $_SESSION['admin'] = $client->getAdmin();
        header('Refresh:0, url=/accueil');
    }else{
        return $resp->write(load_template('connexion')->render([
            'erreur' => true,
            'email' => $data['email']
        ]));
    }
});

$app->get('/deconnexion', function(Request $req, Response $resp, array $args){
    unset($_SESSION['email']);
    unset($_SESSION['admin']);
    header('Refresh:0; url=/accueil');
});

$app->get('/inscription', function(Request $req, Response $resp, array $args){
    return $resp->write(load_template('inscription')->render([]));
});

$app->post('/inscription', function(Request $req, Response $resp, array $args){
    $client = enregistrer_utilisateur($req->getParsedBody());
    if($client !== null && $client !== false){
        $_SESSION['email'] = $client->getEmail();
        header('Refresh:0; url=/accueil');
    }else{
        return $resp->write(load_template('erreur')->render([
            'message'=>"Erreur lors de l'inscription, veuillez ré-essayer ultérieurement"
        ]));
    }
});

$app->get('/email-disponible', function(Request $req, Response $resp, array $args){
    return $resp->withJson(emailDisponible($req->getParam('email')));
});

$app->get('/email-existe', function(Request $req, Response $resp, array $args){
    return $resp->withJson(!emailDisponible($req->getParam('email')));
});

$app->run();
