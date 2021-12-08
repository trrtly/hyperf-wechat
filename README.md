# hyperf easywechat

针对 hyperf 的 [easywechat](https://github.com/w7corp/easywechat) 组件.

## 安装

```bash
composer require trrtly/wechat
```

## 示例

```php

use Trrtly\Wechat\Wechat;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\ConfigInterface;

class Example
{
    public function transferToBalance(ConfigInterface $config)
    {
        $app = Wechat::payment($config->get('wechat.payment'));
        $response = $app->transfer->toBalance([
            'partner_trade_no' => '1233455', // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => '王小帅', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => 10000, // 企业付款金额，单位为分
            'desc' => '理赔', // 企业付款操作说明信息。必填
        ]);
        var_dump($response);
    }
}
```
