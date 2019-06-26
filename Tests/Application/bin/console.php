#!/usr/bin/env php
<?php

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

set_time_limit(0);

use Sulu\Bundle\CommunityBundle\Tests\Application\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;

$input = new ArgvInput();
$env = $input->getParameterOption(['--env', '-e'], getenv('SYMFONY_ENV') ?: 'dev');
$debug = '0' !== getenv('SYMFONY_DEBUG') && !$input->hasParameterOption(['--no-debug', '']) && 'prod' !== $env;

if ($debug) {
    Debug::enable();
}

$kernel = new Kernel($env, $debug, $suluContext);
$application = new Application($kernel);
$application->run($input);

// register all commands available by our bundles
$adminPool = $kernel->getContainer()->get('sulu_admin.admin_pool');
foreach ($adminPool->getCommands() as $command) {
    $application->add($command);
}