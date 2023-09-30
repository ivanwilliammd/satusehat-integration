<?php

require_once __DIR__ . '/vendor/autoload.php';

use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client;
echo $client->token();
