<?php

namespace EasyCloudRequest\TencentCert\Support;

use EasyCloudRequest\Core\SimpleCloud;
use EasyCloudRequest\Tencent\Gateway;

class Cloud
{
    protected function config(string $ak, string $sk): array
    {
        return [
            'default' => Gateway::class,
            'gateway' => [
                'tencent' => [
                    'ak' => $ak,
                    'sk' => $sk,
                ]
            ],
            'http_options' => [
                "http_errors" => false,
                "proxy" => [],
                "verify" => false,
                "timeout" => 120,
                "connect_timeout" => 60,
            ]
        ];
    }

    public static function create(string $ak, string $sk)
    {
        $self = new self();
        return new SimpleCloud($self->config($ak, $sk));
    }

}
