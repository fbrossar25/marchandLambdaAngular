<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    require_once 'vendor/autoload.php';
    require_once 'bootstrap.php';
    $clientRepository = $entityManager->getRepository('Client');

    $LOADER = new Twig_Loader_Filesystem('src/templates/');
    $TWIG = new Twig_Environment($LOADER, array(
        'cache' => false
    ));

    function load_template($template){
        global $TWIG;
        return $TWIG->loadTemplate("$template.twig.html");
    }
    
    $app = new \Slim\App([
        'settings'=> [
            'displayErrorDetails' => true,
        ]
    ]);
    $app->redirect('/', '/accueil', 303);
    $app->redirect('/home', '/accueil', 303);
    //pourquoi / reidirige systémtiquement vers /home ??
    $app->get('/accueil', function(Request $req, Response $resp, array $args){
        return $resp->write(load_template('accueil')->render([]));
    });
    $app->get('/connexion', function(Request $req, Response $resp, array $args){
        return $resp->write(load_template('connexion')->render([]));
    });
    $app->get('/inscription', function(Request $req, Response $resp, array $args){
        return $resp->write(load_template('inscription')->render([
            'erreurs' => ['Foo', 'Bar']
            ]));
    });
    $app->post('/inscription-validation', function(Request $req, Response $resp, array $args){
        return $resp->write(load_template('inscription-validation')->render([]));
    });
    $app->run();
?>