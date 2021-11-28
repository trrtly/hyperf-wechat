<?php

declare(strict_types=1);
/**
 * This file is part of trrtly/wechat.
 *
 * (c) trrtly <328602875@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Trrtly\Wechat;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'wechat',
                    'description' => 'wechat config.', // 描述
                    'source' => __DIR__ . '/../publish/wechat.php',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/wechat.php', // 复制为这个路径下的该文件
                ],
            ],
        ];
    }
}
