<?php

use Doctrine\ORM\{EntityManager, Exception\ORMException, Mapping\UnderscoreNamingStrategy, ORMSetup};
use PremiumKickBot\Database;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required('BOT_TOKEN')->notEmpty();
$dotenv->required('BOT_USERNAME')->notEmpty();
$dotenv->required('DELETE_MESSAGES')->isBoolean();
$dotenv->required('RESTRICT_MEMBERS')->isBoolean();
$dotenv->required('REPORT_ERRORS')->isInteger();
$dotenv->ifPresent('DEV_MODE')->isBoolean();

$devMode = filter_var($_ENV['DEV_MODE'] ?? false, FILTER_VALIDATE_BOOLEAN);
$config = ORMSetup::createAnnotationMetadataConfiguration([__DIR__ . "/src/DB"], $devMode);

$conn = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/bot.db',
];

$namingStrategy = new UnderscoreNamingStrategy(CASE_LOWER);
$config->setNamingStrategy($namingStrategy);

try {
    $entityManager = EntityManager::create($conn, $config);
} catch (ORMException $e) {
    echo 'Could not create the entity manager: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

$database = new Database($entityManager);