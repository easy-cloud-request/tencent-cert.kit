<?php

namespace EasyCloudRequest\TencentCert\Command;

use EasyCloudRequest\TencentCert\Support\Cloud;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{

    const
        END_POINT = 'https://ssl.tencentcloudapi.com/';

    protected $cloud;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $inputs = ['ak', 'sk'];
        $values = [];
        foreach ($inputs as $i) {
            $v = $input->getOption($i);
            if (empty($v)) {
                throw new \Exception("The {$i} option param must can't be empty, use --ak 'your-{$i}-string' to fix it");
            }
            $values[$i] = $v;
        }

        $this->cloud = Cloud::create($values['ak'], $values['sk']);
    }

}
