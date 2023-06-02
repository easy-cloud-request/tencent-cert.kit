<?php
use EasyCloudRequest\TencentCert\Command\ApplyCommand;
use EasyCloudRequest\TencentCert\Command\DownloadCommand;
use EasyCloudRequest\TencentCert\Command\ListCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

require 'vendor/autoload.php';

$app = new Application();
$app->getDefinition()
    ->addOptions([
        new InputOption('ak', 'ak', InputOption::VALUE_REQUIRED, 'access key'),
        new InputOption('sk', 'sk', InputOption::VALUE_REQUIRED, 'secret key')
    ]);

$app->add(new ListCommand());
$app->add(new DownloadCommand());
$app->add(new ApplyCommand());

$app->setName('cert');
$app->setVersion('0.0.1');
$app->run();
