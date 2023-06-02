<?php

namespace EasyCloudRequest\TencentCert\Command;

use EasyCloudRequest\Core\Support\RequestBag;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('list')
            ->addOption('provider', 'p', InputOption::VALUE_OPTIONAL, 'cloud provider', 'tencent')
            ->setDescription('print cloud provider cert list')
            ->setHelp('php cli.php list --provider tencent');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->cloud->requests($this->config());
        $rows = $this->wrapResponse($response->data);

        $table = new Table($output);
        $table
            ->setHeaders(['证书ID', '域名', '证书类型', '状态', '证书启用时间', '证书失效时间'])
            ->setRows($rows);

        $table->render();

        return 0;
    }

    protected function wrapResponse(array $data)
    {
        $ret = [];
        foreach($data['Response']['Certificates'] as $item) {
            if (empty($item['Domain'])) {
                continue;
            }
            $ret[] = [
                $item['CertificateId'],
                $item['Domain'],
                $item['CertificateType'],
                $item['StatusName'],
                $item['CertBeginTime'],
                $item['CertEndTime'],
            ];
        }
        return $ret;
    }

    /**
     * request config
     * @return RequestBag
     */
    protected function config(): RequestBag
    {
        return new RequestBag(
            'GET',
            self::END_POINT,
            [
                "Action" => 'DescribeCertificates',
                "Version" => '2019-12-05',
                'Region' => '',
            ],
        );
    }
}
