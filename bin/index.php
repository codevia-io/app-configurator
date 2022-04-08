<?php

use Codevia\AppConfigurator\Command\EditCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application('Codevia backend configurator');
$command = new EditCommand();

$app->add($command);
$app->setDefaultCommand($command->getName());

$app->run();
