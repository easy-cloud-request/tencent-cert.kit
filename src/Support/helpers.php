<?php

namespace EasyCloudRequest\TencentCert\Support;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * wrap output render style
 *
 * @param OutputInterface $output
 * @return void
 */
function wrap_gray_formatter(OutputInterface $output, string $color = 'gray')
{
    $outputStyle = new OutputFormatterStyle($color, '', ['bold', 'blink']);
    $output->getFormatter()->setStyle($color, $outputStyle);
};
