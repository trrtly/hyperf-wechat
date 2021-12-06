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
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property \EasyWeChat\OfficialAccount\Application $officialAccount
 */
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

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        $this->container = $container;
        $this->config = $config;
        if ($cfg = $config->get('wechat.payment')) {
            $this->payment = $this->getPayment($cfg);
        }
        if ($cfg = $config->get('wechat.mini_program')) {
            $this->miniProgram = $this->getMiniProgram($cfg);
        }
        if ($cfg = $config->get('wechat.open_platform')) {
            $this->openPlatform = $this->getOpenPlatform($cfg);
        }
        if ($cfg = $config->get('wechat.basic_service')) {
            $this->basicService = $this->getBasicService($cfg);
        }
        if ($cfg = $config->get('wechat.work')) {
            $this->work = $this->getWork($cfg);
        }
        if ($cfg = $config->get('wechat.open_work')) {
            $this->openWork = $this->getOpenWork($cfg);
        }
        if ($cfg = $config->get('wechat.micro_merchant')) {
            $this->microMerchant = $this->getMicroMerchant($cfg);
        }
    }

    public function __get($id)
    {
        if ($id !== 'officialAccount') {
            throw new \RuntimeException(sprintf('property %s not exists in %s', $id, __CLASS__));
        }
        return $this->getOfficialAccount();
    }

    protected function getPayment(array $config)
    {
        $app = Factory::payment($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getMiniProgram(array $config)
    {
        $app = Factory::miniProgram($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getOpenPlatform(array $config)
    {
        $app = Factory::openPlatform($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getOfficialAccount()
    {
        $request = $this->container->get(RequestInterface::class);
        $get = $request->getQueryParams();
        $post = $request->getParsedBody();
        $cookie = $request->getCookieParams();
        $uploadFiles = $request->getUploadedFiles() ?? [];
        $server = $request->getServerParams();
        $xml = $request->getBody()->getContents();
        $files = [];
        /** @var \Hyperf\HttpMessage\Upload\UploadedFile $v */
        foreach ($uploadFiles as $k => $v) {
            $files[$k] = $v->toArray();
        }
        $req = new Request($get, $post, [], $cookie, $files, $server, $xml);
        $req->headers = new HeaderBag($request->getHeaders());
        $app = Factory::officialAccount($this->config->get('wechat.official_account'));
        $this->replaceHandlerAndCache($app);
        $app->rebind('request', $req);
        return $app;
    }

    protected function getBasicService(array $config)
    {
        $app = Factory::basicService($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getWork(array $config)
    {
        $app = Factory::work($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getOpenWork(array $config)
    {
        $app = Factory::openWork($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function getMicroMerchant(array $config)
    {
        $app = Factory::microMerchant($config);
        $this->replaceHandlerAndCache($app);

        return $app;
    }

    protected function replaceHandlerAndCache(ServiceContainer $app)
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
        $app->rebind('cache', $this->container->get(CacheInterface::class));
    }
}
