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

use EasyWeChat\Factory;
use EasyWeChat\Kernel\ServiceContainer;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Guzzle\CoroutineHandler;
use Psr\SimpleCache\CacheInterface;

class Wechat
{
    /**
     * @var \EasyWeChat\Payment\Application
     */
    public $payment;

    /**
     * @var \EasyWeChat\MiniProgram\Application
     */
    public $miniProgram;

    /**
     * @var \EasyWeChat\OpenPlatform\Application
     */
    public $openPlatform;

    /**
     * @var \EasyWeChat\OfficialAccount\Application
     */
    public $officialAccount;

    /**
     * @var \EasyWeChat\BasicService\Application
     */
    public $basicService;

    /**
     * @var \EasyWeChat\Work\Application
     */
    public $work;

    /**
     * @var \EasyWeChat\OpenWork\Application
     */
    public $openWork;

    /**
     * @var \EasyWeChat\MicroMerchant\Application
     */
    public $microMerchant;

    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        if ($cfg = $config->get('wechat.payment')) {
            $this->payment = $this->getPayment($container, $cfg);
        }
        if ($cfg = $config->get('wechat.mini_program')) {
            $this->miniProgram = $this->getMiniProgram($container, $cfg);
        }
        if ($cfg = $config->get('wechat.open_platform')) {
            $this->openPlatform = $this->getOpenPlatform($container, $cfg);
        }
        if ($cfg = $config->get('wechat.official_account')) {
            $this->officialAccount = $this->getOfficialAccount($container, $cfg);
        }
        if ($cfg = $config->get('wechat.basic_service')) {
            $this->basicService = $this->getBasicService($container, $cfg);
        }
        if ($cfg = $config->get('wechat.work')) {
            $this->work = $this->getWork($container, $cfg);
        }
        if ($cfg = $config->get('wechat.open_work')) {
            $this->openWork = $this->getOpenWork($container, $cfg);
        }
        if ($cfg = $config->get('wechat.micro_merchant')) {
            $this->microMerchant = $this->getMicroMerchant($container, $cfg);
        }
    }

    protected function getPayment(ContainerInterface $container, array $config)
    {
        $app = Factory::payment($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getMiniProgram(ContainerInterface $container, array $config)
    {
        $app = Factory::miniProgram($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getOpenPlatform(ContainerInterface $container, array $config)
    {
        $app = Factory::openPlatform($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getOfficialAccount(ContainerInterface $container, array $config)
    {
        $app = Factory::officialAccount($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getBasicService(ContainerInterface $container, array $config)
    {
        $app = Factory::basicService($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getWork(ContainerInterface $container, array $config)
    {
        $app = Factory::work($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getOpenWork(ContainerInterface $container, array $config)
    {
        $app = Factory::openWork($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function getMicroMerchant(ContainerInterface $container, array $config)
    {
        $app = Factory::microMerchant($config);
        $this->replaceHandlerAndCache($container, $app);

        return $app;
    }

    protected function replaceHandlerAndCache(ContainerInterface $container, ServiceContainer $app)
    {
        $handler = new CoroutineHandler();
        $config = $app['config']->get('http', []);
        $config['handler'] = $stack = HandlerStack::create($handler);
        $app->rebind('http_client', new Client($config));
        // 部分接口在请求数据时，会根据 guzzle_handler 重置 Handler
        $app['guzzle_handler'] = $handler;
        // 如果使用的是 OfficialAccount，则还需要设置以下参数
        if ($app instanceof \EasyWeChat\OfficialAccount\Application) {
            $app->oauth->setGuzzleOptions([
                'http_errors' => false,
                'handler' => $stack,
            ]);
        }
        // 替换默认缓存
        $app->rebind('cache', $container->get(CacheInterface::class));
    }
}
