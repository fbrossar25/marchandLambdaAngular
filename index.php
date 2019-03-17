<?php
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

require_once 'vendor/autoload.php';
require_once 'bootstrap-doctrine.php';

session_start();

$clientRepository = $entityManager->getRepository('Client');
$articleRepository = $entityManager->getRepository('Article');

$LOADER = new Twig_Loader_Filesystem('src/templates/');
$TWIG = new Twig_Environment($LOADER, array(
    'cache' => false
));
$TWIG->addGlobal('session',$_SESSION);

function loadTemplate($template){
    global $TWIG;
    return $TWIG->loadTemplate("$template.html.twig");
}

function estSessionAdmin(){
    global $clientRepository;
    if(isset($_SESSION['email'])){
        $client = $clientRepository->findOneBy(['email' => $_SESSION['email']]);
        return $client !== null && $client->getAdmin();
    }
    return false;
}

function emailDisponible($email){
    global $clientRepository;
    return $clientRepository->count(['email' => $email]) === 0;
}

function articleDisponible($article){
    global $articleRepository;
    return $articleRepository->count(['nom' => $article]) === 0;
}

function connecter($email, $pass){
    global $clientRepository;
    $client = $clientRepository->findOneBy(['email' => $email]);
    if($client !== null && password_verify($pass, $client->getPass())){
        return $client;
    }else{
        return false;
    }
}

function enregistrerUtilisateur(array $data){
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
    if(emailDisponible($client->getEmail())){
        try{
            $entityManager->persist($client);
            $entityManager->flush();
        }catch (\Doctrine\ORM\OptimisticLockException | \Doctrine\ORM\ORMException $e) {
            return false;
        }
        return $client;
    }else{
        return false;
    }
}

function mettreAJourCompte(array $data){
    global $entityManager;
    $client = utilisateurActuel();
    if($client !== false && password_verify($data['pass'], $client->getPass())){
        $client->setPrenom($data['firstName']);
        $client->setNom($data['lastName']);
        $client->setEmail($data['email']);
        if(!empty($data['newPassword'])){
            $client->setPass(password_hash($data['newPassword'], PASSWORD_BCRYPT));
        }
        $client->setCommune($data['city']);
        $client->setVoie($data['street']);
        $client->setNumeroVoie($data['streetNo']);
        $client->setTelephone($data['phoneNumber']);
        $client->setCodePostal($data['postalCode']);
        if($data['email'] === $_SESSION['email'] || emailDisponible($client->getEmail())){
            try{
                $entityManager->flush();
            }catch (\Doctrine\ORM\OptimisticLockException | \Doctrine\ORM\ORMException $e) {
                return false;
            }
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function utilisateurActuel(){
    global $clientRepository;
    if(isset($_SESSION['email'])){
        $client = $clientRepository->findOneBy(['email' => $_SESSION['email']]);
        return $client === null ? false : $client;
    }else{
        return false;
    }
}

function ajouterArticle(array $data){
    global $entityManager;
    $article = new Article();
    $article->setNom($data['name']);
    $article->setDescription($data['description']);
    $article->setPrix($data['price']);
    if(articleDisponible($article->getNom())){
        try {
            $entityManager->persist($article);
            $entityManager->flush();
            return true;
        } catch (\Doctrine\ORM\ORMException $e) {
            return false;
        }
    }else{
        return false;
    }
}

function produitsAccueil(){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    $qb->select('a')
        ->from('Article', 'a')
        ->setMaxResults(6);
    return $qb->getQuery()->getArrayResult();
}

$app = new \Slim\App([
    'settings'=> [
        'displayErrorDetails' => true,
    ],
    'notFoundHandler' => function ($c) {
        return function ($req, $resp) use ($c) {
            $response = new \Slim\Http\Response(404);
            return $response->write(loadTemplate('404')->render([]));
        };
    }
]);

$app->redirect('/', '/accueil', 303);

$app->get('/accueil', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('accueil')->render([
        'articles' => produitsAccueil()
    ]));
});

$app->get('/connexion', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('connexion')->render([]));
});

$app->post('/connexion', function(Request $req, Response $resp, array $args){
    $data = $req->getParsedBody();
    $client = connecter($data['email'], $data['password']);
    if($client !== null && $client !== false){
        $_SESSION['email'] = $client->getEmail();
        $_SESSION['admin'] = $client->getAdmin();
        header('Refresh:0, url=/accueil');
    }else{
        return $resp->write(loadTemplate('connexion')->render([
            'erreur' => true,
            'email' => $data['email']
        ]));
    }
});

$app->get('/deconnexion', function(Request $req, Response $resp, array $args){
    unset($_SESSION['email']);
    unset($_SESSION['admin']);
    return $resp->withRedirect('/accueil');
});

$app->get('/inscription', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('inscription')->render([]));
});

$app->post('/inscription', function(Request $req, Response $resp, array $args){
    $client = enregistrerUtilisateur($req->getParsedBody());
    if($client !== null && $client !== false){
        $_SESSION['email'] = $client->getEmail();
        return $resp->withRedirect('/accueil');
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message'=>"Erreur lors de l'inscription, veuillez ré-essayer ultérieurement"
        ]));
    }
});

$app->get('/administration/article', function(Request $req, Response $resp, array $args){
    if(estSessionAdmin()){
        return $resp->write(loadTemplate('administration-article')
                    ->render($req->getParsedBody() === null ? [] : $req->getParsedBody()));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Vous n'avez pas l'autorisation d'accéder à cette page"
        ]));
    }
});

$app->post('/administration/article', function(Request $req, Response $resp, array $args){
    if(estSessionAdmin()){
        return $resp->write(loadTemplate('administration-article')->render(['added' => ajouterArticle($req->getParsedBody())]));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Vous n'avez pas l'autorisation d'accéder à cette page"
        ]));
    }
});

$app->get('/compte', function(Request $req, Response $resp, array $args){
    if(isset($_SESSION['email'])){
        $data = array_merge(['compte' => utilisateurActuel()],$req->getParsedBody() === null ? [] : $req->getParsedBody());
        return $resp->write(loadTemplate('compte')
            ->render($data));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Veuillez vous reconnecter et ré-essayer"
        ]));
    }
});

$app->post('/compte', function(Request $req, Response $resp, array $args){
    if(isset($_SESSION['email'])){
        return $resp->write(loadTemplate('compte')
            ->render([
                'erreur' => !mettreAJourCompte($req->getParsedBody()),
                'compte' => utilisateurActuel()
            ]));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Veuillez vous reconnecter et ré-essayer"
        ]));
    }
});

$app->get('/article-disponible', function(Request $req, Response $resp, array $args){
    return $resp->withJson(articleDisponible($req->getParam('name')));
});

$app->get('/email-disponible', function(Request $req, Response $resp, array $args){
    return $resp->withJson(emailDisponible($req->getParam('email')));
});

$app->get('/email-changement-disponible', function(Request $req, Response $resp, array $args){
    if(isset($_SESSION['email'])){
        $email = filter_var($req->getParam('email'), FILTER_SANITIZE_EMAIL);
        if($email === $_SESSION['email']){
            //On autorise le client à ne pas changer son email
            return $resp->withJson(true);
        }else{
            return $resp->withJson(emailDisponible($email));
        }
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Veuillez vous reconnecter et ré-essayer"
        ]));
    }
});

$app->get('/email-existe', function(Request $req, Response $resp, array $args){
    return $resp->withJson(!emailDisponible($req->getParam('email')));
});

$app->run();
