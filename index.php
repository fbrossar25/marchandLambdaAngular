<?php
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'vendor/autoload.php';
require_once 'bootstrap-doctrine.php';

session_start();

$clientRepository = $entityManager->getRepository('Client');
$articleRepository = $entityManager->getRepository('Article');

$LOADER = new FilesystemLoader('src/templates/');
$TWIG = new Environment($LOADER, array(
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
    $client->setAdmin(false);
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

function mettreAJourArticle(array $data){
    global $entityManager, $articleRepository;
    if(estSessionAdmin()){
        $article = $articleRepository->find($data['id']);
        if($article !== null){
            $article->setNom($data['nom']);
            $article->setPrix($data['prix']);
            $article->setDescription($data['description']);
            $article->setUrlImage($data['imageUrl']);
            try{
                $entityManager->flush();
            }catch (\Doctrine\ORM\OptimisticLockException | \Doctrine\ORM\ORMException $e) {
                return false;
            }
            return true;
        }
    }
    return false;
}

function utilisateurActuel(){
    global $clientRepository;
    if(isset($_SESSION['email'])){
        $client = $clientRepository->findOneBy(['email' => $_SESSION['email']]);
        return $client !== null ? $client : false;
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
    $article->setUrlImage($data['imageUrl']);
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

function articlesPage($page, $articlesParPage, $filtre='', $prixMin=-1, $prixMax=-1, $ordreColonne='nom', $ordre='ASC'){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    $params = [];
    $qb->select('a')
        ->from('article', 'a');
    if(!empty($filtre)){
        //filtre par nom en ignorant la casse
        $qb->where("UPPER(a.nom) LIKE :filtre");
        $params['filtre'] = '%'.addcslashes(strtoupper($filtre), '%_').'%';
    }
    if($prixMin > $prixMax){
        $temp = $prixMax;
        $prixMax = $prixMin;
        $prixMin = $temp;
    }
    if(is_numeric($prixMin) && $prixMin > 0){
        $qb->andWhere('a.prix >= :min');
        $params['min'] = $prixMin;
    }
    if(is_numeric($prixMax) && $prixMax > 0){
        $qb->andWhere('a.prix <= :max');
        $params['max'] = $prixMax;
    }
    $qb->orderBy($ordreColonne === 'prix' ? 'a.prix' : 'a.nom', $ordre === 'DESC' ? 'DESC' : 'ASC');
    $qb->setParameters($params);
    $qb->setFirstResult(($page - 1) * $articlesParPage);
    $qb->setMaxResults($articlesParPage);
    $query = $qb->getQuery();
    return $query->getArrayResult();
}

function nombrePage($articlesParPage, $filtre='', $prixMin=-1, $prixMax=-1){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    $params = [];
    $qb->select('COUNT(a.idArticle)')
        ->from('article', 'a');
    if(!empty($filtre)){
        $qb->where("UPPER(a.nom) LIKE :filtre");
        $params['filtre'] = '%'.addcslashes(strtoupper($filtre), '%_').'%';
    }
    if($prixMin > $prixMax){
        $temp = $prixMax;
        $prixMax = $prixMin;
        $prixMin = $temp;
    }
    if(is_numeric($prixMin) && $prixMin > 0){
        $qb->andWhere('a.prix >= :min');
        $params['min'] = $prixMin;
    }
    if(is_numeric($prixMax) && $prixMax > 0){
        $qb->andWhere('a.prix <= :max');
        $params['max'] = $prixMax;
    }
    $qb->setParameters($params);
    $query = $qb->getQuery();
    try {
        $res = intval($query->getSingleScalarResult());
        return ceil($res / $articlesParPage);
    } catch (\Doctrine\ORM\NonUniqueResultException $e) {
        error_log($e);
        return 0;
    }
}

function supprimerArticle($id){
    global $entityManager;
    $qb = $entityManager->createQueryBuilder();
    $qb->delete()->from('article', 'a')->where('a.idArticle = :id')->setParameter('id', $id);
    try{
        return $qb->getQuery()->getResult() > 0;
    }catch(Doctrine\ORM\Query\QueryException $e){
        error_log($e);
        return false;
    }
}

function articleModificationDisponible($id,$nom){
    global $articleRepository;
    $article = $articleRepository->find($id);
    if($article !== null){
        if($article->getNom() === $nom){
            //Le nom n'as pas changé
            return true;
        }else{
            //Le nom est libre
            return $articleRepository->count(['nom' => $nom]) === 0;
        }
    }
    return false;
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
        $data = ['activePage' => 'admin-article'];
        //Récupération des données existantes dans la requêtes
        if(is_array($req->getParsedBody())){
            $data = array_merge($data, $req->getParsedBody());
        }
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
        $data = [
            'compte' => utilisateurActuel(),
            'activePage' => 'compte',
        ];
        //Récupération des données existantes dans la requêtes
        if(is_array($req->getParsedBody())){
            $data = array_merge($data, $req->getParsedBody());
        }
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

$app->redirect('/catalogue/', '/catalogue/1', 301);
$app->redirect('/catalogue/0', '/catalogue/1', 301);

$app->get('/catalogue/{page:[1-9][0-9]*}', function(Request $req, Response $resp, array $args){
    $parametres = [];
    $articlesParPages = 5;
    $page = intval($args['page']);
    $filtre = $req->getParam('filtre');
    $tri =  $req->getParam('tri');
    $sens = $req->getParam('sens');
    $prixMin =  $req->getParam('prixMin');
    $prixMax = $req->getParam('prixMax');
    if(!empty($filtre)){
        $parametres['filtre'] = $filtre;
    }
    if(!empty($tri)){
        $parametres['tri'] = $tri;
    }
    if(!empty($sens)){
        $parametres['sens'] = $sens;
    }
    if(!empty($prixMin)){
        $parametres['prixMin'] = $prixMin;
    }
    if(!empty($prixMax)){
        $parametres['prixMax'] = $prixMax;
    }
    $nombrePages = nombrePage($articlesParPages, $filtre, $prixMin, $prixMax);
    //On affiche la dernière page si le nombre de page donné est plus grand que le nombre de pages
    if($page > $nombrePages){
        $page = $nombrePages;
    }
    $paginationParams =  [
        'currentPage' => $page,
        'alwaysShowFirstAndLast' => true,
        'lastPage' => $nombrePages,
        'currentFilters' => $parametres
    ];
    return $resp->write(loadTemplate('catalogue')->render([
        'activePage' => 'catalogue',
        'articles' => $nombrePages > 0 ? articlesPage($page, $articlesParPages, $filtre, $prixMin, $prixMax, $tri, $sens) : [],
        'pagination' => $paginationParams
    ]));
});

$app->post('/supprimer-article', function(Request $req, Response $resp, array $args){
    if(!estSessionAdmin()){
        return $resp->withStatus(403, "Vous n'avez pas l'autorisation d'effectuer cette opération");
    }
    $idArticle = intval($req->getParsedBody()['id']);
    if(!is_numeric($idArticle)){
        return $resp->withStatus(400, "L'id fournis n'est pas valide");
    }else if(supprimerArticle($idArticle)){
        return $resp->withStatus(200);
    }else{
        return $resp->withStatus(500, "La suppression à échouée");
    }
});

$app->post('/article-changement-disponible', function(Request $req, Response $resp, array $args){
    if(!estSessionAdmin()){
        return $resp->withStatus(403, "Vous n'avez pas l'autorisation d'effectuer cette opération");
    }
    $id = $req->getParsedBody()['id'];
    $nom = $req->getParsedBody()['name'];
    return $resp->withJson(articleModificationDisponible($id,$nom));
});

$app->post('/modifier-article', function(Request $req, Response $resp, array $args){
    if(!estSessionAdmin()){
        return $resp->withStatus(403, "Vous n'avez pas l'autorisation d'effectuer cette opération");
    }else if(mettreAJourArticle($req->getParsedBody())){
        return $resp->withStatus(200);
    }else{
        return $resp->withStatus(500, "La modification à échouée");
    }
});

$app->run();
