<?php

/**
 * Use this space to configure your database and ORM as you please
 * We suggest pushing your database manager into the container ($container)
 * so it can be injected into any controllers that need it.
 * 
 * The default database setup uses Doctrine and adds to the container as "db"
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


$container->db([function ($c) {
    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $dbConfig = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src", __DIR__ . '/src/App/Entity'), $isDevMode);
    $dbConfig->setEntityNamespaces([
      'Planck' => 'Planck\App\Entity',
    ]);
    // the connection configuration
    $dbParams = array(
        'dbname' => 'c9',
        'user' => getenv('C9_USER'),
        'password' => '',
        'host' => getenv('IP'),
        'driver' => 'pdo_mysql',
    );
    
    $entityManager = EntityManager::create($dbParams, $dbConfig);

    return $c->share('db', $entityManager);
}]);