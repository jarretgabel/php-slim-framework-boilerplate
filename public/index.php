<?php

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  date_default_timezone_set('America/New_York');

  require_once "../app/data/seed.php";
  require_once "../app/vendor/autoload.php";

  $GLOBALS['ARI'] = array(
    "settings" => array(
      "env" => "development",
      "baseUrl" => "/"
    )
  );

  /*
   * MtHaml config
   */
  $mt = new MtHaml\Environment('twig', array('enable_escaper' => false));
  $fs = new Twig_Loader_Filesystem(array('../app/templates'));
  $loader = new MtHaml\Support\Twig\Loader($mt, $fs);

  /*
   * Twig config
   */
  $twig = new Twig_Environment($loader, array());
  $twig->addExtension(new MtHaml\Support\Twig\Extension($mt));

  /*
   * Slim config
   */
  $app = new \Slim\Slim(array(
    'templates.path' => '../app/',
    'mode' => $GLOBALS['ARI']['settings']['env']
  ));
  $app->twig = $twig;

  /**
   * Production environment config
   */
  $app->configureMode('production', function() use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => true
    ));
  });

  /**
   * Development environment config
   */
  $app->configureMode('development', function() use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true
    ));
  });

  $app->notFound(function() use($app) {
    echo $app->twig->render("main/errors/error-404.html");
  });

  $app->get('/', $ref = function() use($app) {
    echo $app->twig->render("main/index.haml", array(
      'baseUrl' => $GLOBALS['ARI']['settings']['baseUrl']
    ));
  });

  $app->get('/api/data/', function() use ($app) {
    $data = array();
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->body('{ "results": ' . json_encode($data) . '}');
  });

  $app->run();

?>
