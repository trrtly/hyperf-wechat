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
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class Wechat
{
    /**
     * @param array $config
     * @return \EasyWeChat\OfficialAccount\Application
     */
    public static function officialAccount(array $config)
    {
        return Context::getOrSet(
            'wechatOfficialAccount:' . md5(serialize($config)),
            function () use ($config) {
                $request = ApplicationContext::getContainer()->get(RequestInterface::class);
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
                $app = Factory::officialAccount($config);
                self::replaceHandlerAndCache($app);
                $app->rebind('request', $req);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\Payment\Application
     */
    public static function payment(array $config)
    {
        return Context::getOrSet(
            'wechatPayment:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::payment($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\MiniProgram\Application
     */
    public static function miniProgram(array $config)
    {
        return Context::getOrSet(
            'wechatMiniProgram:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::miniProgram($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\OpenPlatform\Application
     */
    public static function openPlatform(array $config)
    {
        return Context::getOrSet(
            'wechatOpenPlatform:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::openPlatform($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\BasicService\Application
     */
    public static function basicService(array $config)
    {
        return Context::getOrSet(
            'wechatBasicService:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::basicService($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\Work\Application
     */
    public static function work(array $config)
    {
        return Context::getOrSet(
            'wechatWork:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::work($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\OpenWork\Application
     */
    public static function openWork(array $config)
    {
        return Context::getOrSet(
            'wechatOpenWork:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::openWork($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    /**
     * @param array $config
     * @return \EasyWeChat\MicroMerchant\Application
     */
    public static function microMerchant(array $config)
    {
        return Context::getOrSet(
            'wechatMicroMerchant:' . md5(serialize($config)),
            function () use ($config) {
                $app = Factory::microMerchant($config);
                self::replaceHandlerAndCache($app);
                return $app;
            }
        );
    }

    protected static function replaceHandlerAndCache(ServiceContainer $app)
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
        $app->rebind('cache', ApplicationContext::getContainer()->get(CacheInterface::class));
    }
}
