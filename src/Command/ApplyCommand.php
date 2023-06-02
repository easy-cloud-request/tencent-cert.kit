<?php

namespace EasyCloudRequest\TencentCert\Command;

use EasyCloudRequest\Core\Support\RequestBag;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApplyCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('apply')
            ->addArgument('domainName', InputArgument::REQUIRED, '域名')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, '验证方式：DNS_AUTO = 自动DNS验证，DNS = 手动DNS验证，FILE = 文件验证', 'DNS_AUTO')
            ->addOption('oid', 'oid', InputOption::VALUE_OPTIONAL, '原证书 ID，用于重新申请')
            ->setDescription('申请证书, 如果传 old id 则代表重新申请')
            ->setHelp('apply -m DNS_AUTO -oid xxx');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $method = $input->getOption('method');
        $params = [
            "Action" => 'ApplyCertificate',
            "Version" => '2019-12-05',
            'Region' => '',
            'DvAuthMethod' => $method,
            'DomainName' => $input->getArgument('domainName'),
        ];

        $oid = $input->getOption('oid');
        if (!empty($oid)) {
            $params['OldCertificateId'] = $oid;
        }

        $config = new RequestBag('GET', self::END_POINT, $params);
        $response = $this->cloud->requests($config);

        if ($response->code !== 200) {
            $output->writeln("请求失败: {" . json_encode($response) . "}");
            return 1;
        }

        $output->writeln("<info>请求成功: " . json_encode($response) . "<info>");
        return 0;
    }

}
