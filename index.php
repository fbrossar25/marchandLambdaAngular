<?php
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Doctrine\ORM\Tools\Pagination\Paginator as Paginator;

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

function articlesPage($page, $articlesParPage){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    //les pages sot indexées à partir de 1
    $query = $qb->select('a')
                ->from('Article', 'a')
                ->setFirstResult(($page-1) * $articlesParPage)
                ->setMaxResults($articlesParPage)
                ->getQuery();
    return $query->getArrayResult();
}

function nombrePage($articlesParPage){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    $query = $qb->select('count(a)')
        ->from('Article', 'a')
        ->getQuery();
    try {
        $res = $query->getSingleScalarResult();
        return ceil($res / $articlesParPage);
    } catch (\Doctrine\ORM\NonUniqueResultException $e) {
        return 0;
    }
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

$app->redirect('/', '/accueil', 301);

$app->get('/accueil', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('accueil')->render([
        'articles' => produitsAccueil()
    ]));
});

$app->get('/connexion', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('connexion')->render([
        'activePage' => 'connexion'
    ]));
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
            'email' => $data['email'],
            'activePage' => 'connexion'
        ]));
    }
});

$app->get('/deconnexion', function(Request $req, Response $resp, array $args){
    unset($_SESSION['email']);
    unset($_SESSION['admin']);
    return $resp->withRedirect('/accueil');
});

$app->get('/inscription', function(Request $req, Response $resp, array $args){
    return $resp->write(loadTemplate('inscription')->render([
        'activePage' => 'inscription'
    ]));
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
        $data = $req->getParsedBody() === null ? [] : $req->getParsedBody();
        $data = array_merge($data, ['activePage' => 'admin-article']);
        return $resp->write(loadTemplate('administration-article')
                    ->render($data));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Vous n'avez pas l'autorisation d'accéder à cette page"
        ]));
    }
});

$app->post('/administration/article', function(Request $req, Response $resp, array $args){
    if(estSessionAdmin()){
        return $resp->write(loadTemplate('administration-article')->render([
            'added' => ajouterArticle($req->getParsedBody()),
            'activePage' => 'admin-article'
        ]));
    }else{
        return $resp->write(loadTemplate('erreur')->render([
            'message' => "Vous n'avez pas l'autorisation d'accéder à cette page"
        ]));
    }
});

$app->get('/compte', function(Request $req, Response $resp, array $args){
    if(isset($_SESSION['email'])){
        $data = array_merge([
            'compte' => utilisateurActuel(),
            'activePage' => 'compte',
        ],$req->getParsedBody() === null ? [] : $req->getParsedBody());
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
                'compte' => utilisateurActuel(),
                'activePage' => 'compte'
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

$app->redirect('/catalogue/0', '/catalogue/1', 301);

$app->get('/catalogue/{page:[1-9][0-9]*}', function(Request $req, Response $resp, array $args){
    $articlesParPages = 5;
    $nombrePages = nombrePage($articlesParPages);
    $page = intval($args['page']);
    //On affiche la dernière page si le nombre de page donné est plus grand que le nombre de pages
    if($page > $nombrePages){
        $page = $nombrePages;
    }
    return $resp->write(loadTemplate('catalogue')->render([
        'activePage' => 'catalogue',
        'articles' => articlesPage($page, $articlesParPages),
        'pagination' => [
            'currentPage' => $page,
            'alwaysShowFirstAndLast' => true,
            'lastPage' => $nombrePages
        ]
    ]));
});


$app->run();
