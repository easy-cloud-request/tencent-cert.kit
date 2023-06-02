<?php

namespace EasyCloudRequest\TencentCert\Command;

use EasyCloudRequest\Core\Support\RequestBag;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use EasyCloudRequest\TencentCert\Support;

class DownloadCommand extends BaseCommand
{

    protected $dir = './ssl';

    protected function configure()
    {
        $this
            ->setName('download')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'the cert files storage path', './ssl')
            ->addArgument('id', InputArgument::REQUIRED, 'The id of the cert')
            ->setDescription('download the cert');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        Support\wrap_gray_formatter($output, 'gray');
        if ($dir = $input->getOption('path')) {
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
        }
        $this->dir = $dir;
        $idString = $input->getArgument('id');

        $items = explode(',', $idString);
        $output->writeln("<comment>===> Total: ".count($items)." <comment>");
        foreach($items as $id) {
            $this->single($id, $input, $output);
            $output->writeln("");
        }

        return 0;
    }

    protected function single(string $id, InputInterface $input, OutputInterface $output)
    {
        try {
            $name = $this->getInfo($id);
            $output->writeln("<gray>===> Fetching cert file: {$name}...<gray>");
            $content = $this->getCertContent($id);

            $filename = "{$name}.zip";
            $filepath = "{$this->dir}/{$filename}";

            file_put_contents($filepath, $content);
            $output->writeln("<info>===> {$filepath} 下载成功<info>");
        } catch (\Exception $e) {
            $output->writeln("<red>===> {$e->getMessage()}<red>");
            return 1;
        }

        return 0;
    }

    protected function getCertContent(string $id)
    {
        $config = new RequestBag(
            'GET',
            self::END_POINT,
            [
                "Action" => 'DownloadCertificate',
                "Version" => '2019-12-05',
                'Region' => '',
                'CertificateId' => $id,
            ],
        );
        $response = $this->cloud->requests($config);

        if ($response->code !== 200) {
            throw new \Exception("请求失败: {".json_encode($response)."}");
        }

        if (empty($response->data['Response']['Content'])) {
            throw new \Exception("请求失败: {" . json_encode($response) . "}");
        }

        return base64_decode($response->data['Response']['Content']);
    }

    protected function getInfo(string $id)
    {
        $requestBag = new RequestBag(
            'GET',
            self::END_POINT,
            [
                "Action" => 'DescribeCertificate',
                "Version" => '2019-12-05',
                'Region' => '',
                'CertificateId' => $id,
            ],
        );
        $response = $this->cloud->requests($requestBag);

        if (empty($response->data['Response']['Domain'])) {
            throw new \Exception("获取 {$id} 对应的证书信息失败");
        }

        return $response->data['Response']['Domain'];
    }

}
