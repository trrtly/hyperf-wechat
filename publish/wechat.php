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
return [
    'payment' => [
        'app_id' => env('WECHAT_PAYMENT_APPID'),
        'mch_id' => env('WECHAT_PAYMENT_MCH_ID'),
        'key' => env('WECHAT_PAYMENT_KEY'),
        'cert_path' => env('WECHAT_PAYMENT_CERT_PATH'),
        'key_path' => env('WECHAT_PAYMENT_KEY_PATH'),
        'notify_url' => env('WECHAT_PAYMENT_NOTIFY_URL'),
    ],
    'mini_program' => [
        'app_id' => env('WECHAT_MINI_PROGRAM_APPID'),
        'secret' => env('WECHAT_MINI_PROGRAM_SECRET'),
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => env('WECHAT_MINI_PROGRAM_RESPONSE_TYPE', 'array'),
        'log' => [
            //            'level' => env('WECHAT_MINI_PROGRAM_LOG_DEBUG', 'debug'),
            //            'file' => env('WECHAT_MINI_PROGRAM_LOG_FILE', __DIR__ . '/wechat.log'),
        ],
    ],
    'open_platform' => [
        'app_id' => env('WECHAT_OPEN_PLATFORM_APPID'),
        'secret' => env('WECHAT_OPEN_PLATFORM_SECRET'),
        'token' => env('WECHAT_OPEN_PLATFORM_TOKEN'),
        'aes_key' => env('WECHAT_OPEN_PLATFORM_AES_KEY'),
    ],
    'official_account' => [
        'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID'),
        'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET'),
        'token' => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN'),
        'encoding_aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_ENCODING_AES_KEY'),
    ],
    'basic_service' => [
        'app_id' => env('WECHAT_BASIC_SERVICE_APPID'),
        'secret' => env('WECHAT_BASIC_SERVICE_SECRET'),
        'token' => env('WECHAT_BASIC_SERVICE_TOKEN'),
    ],
    'work' => [
        'corp_id' => env('WECHAT_WORK_APPID'), // corpid
        'secret' => env('WECHAT_WORK_SECRET'), // secret
        'agent_id' => env('WECHAT_WORK_AGENT_ID'), // 如果有 agend_id 则填写
        'response_type' => 'array',
    ],
    'open_work' => [
        'corp_id' => env('WECHAT_OPEN_WORK_APPID'), // 服务商的 corpid
        'secret' => env('WECHAT_OPEN_WORK_SECRET'), // 服务商的secret，在服务商管理后台可见
        'suite_id' => env('WECHAT_OPEN_WORK_SUITE_ID'), // 以ww或wx开头应用id
        'suite_secret' => env('WECHAT_OPEN_WORK_SUITE_SECRET'), // 应用secret
        'token' => env('WECHAT_OPEN_WORK_TOKEN'), // 应用的Token
        'aes_key' => env('WECHAT_OPEN_WORK_AES_KEY'), // 应用的EncodingAESKey
        'reg_template_id' => env('WECHAT_OPEN_WORK_REG_TEMPLATE_ID'), // 注册定制化模板ID
        'redirect_uri_install' => env('WECHAT_OPEN_WORK_REDIRECT_URI_INSTALL'), // 安装应用的回调url（可选）
        'redirect_uri_single' => env('WECHAT_OPEN_WORK_REDIRECT_URI_SINGLE'), // 单点登录回调url （可选）
        'redirect_uri_oauth' => env('WECHAT_OPEN_WORK_REDIRECT_URI_OAUTH'), // 网页授权第三方回调url （可选）
    ],
    'micro_merchant' => [
        // 必要配置
        'mch_id' => env('WECHAT_MICRO_MERCHANT_MCH_ID'), // 服务商的商户号
        'key' => env('WECHAT_MICRO_MERCHANT_KEY'), // API 密钥
        'apiv3_key' => env('WECHAT_MICRO_MERCHANT_APIV3_KEY'), // APIv3 密钥
        // API 证书路径(登录商户平台下载 API 证书)
        'cert_path' => env('WECHAT_MICRO_MERCHANT_CERT_PATH'), // XXX: 绝对路径！！！！
        'key_path' => env('WECHAT_MICRO_MERCHANT_KEY_PATH'), // XXX: 绝对路径！！！！
        // 以下两项配置在获取证书接口时可为空，在调用入驻接口前请先调用获取证书接口获取以下两项配置,如果获取过证书可以直接在这里配置，也可参照本文档获取平台证书章节中示例
        'serial_no' => env('WECHAT_MICRO_MERCHANT_SERIAL_NO'), // 获取证书接口获取到的平台证书序列号
        'certificate' => env('WECHAT_MICRO_MERCHANT_CERTIFICATE'), // 获取证书接口获取到的证书内容
        // 以下为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'appid' => env('WECHAT_MICRO_MERCHANT_APPID'), // 服务商的公众账号 ID
    ],
];
