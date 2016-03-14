<?php
require 'vendor/autoload.php';

/* Exemple de fichier utilisant les logs */

$users = [
    [
        'name' => 'Kenny Katzgrau',
        'username' => 'katzgrau',
    ],
    [
        'name' => 'Dan Horrigan',
        'username' => 'dhrrgn',
    ],
];

$logger = new Katzgrau\KLogger\Logger(__DIR__.'/logs');
$logger->info('Returned a million search results');
$logger->error('Oh dear.');
$logger->debug('Got these users from the Database.', $users);

?>