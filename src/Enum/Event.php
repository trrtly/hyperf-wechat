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
namespace Trrtly\Wechat\Enum;

/**
 * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Receiving_event_pushes.html
 */
class Event
{
    // 关注事件
    public const SUBSCRIBE = 'subscribe';

    // 取消关注事件
    public const UNSUBSCRIBE = 'unsubscribe';

    // 用户已经关注公众号，则微信会将带场景值扫描事件推送给开发者
    public const SCAN = 'SCAN';

    // 上报地理位置事件
    public const LOCATION = 'LOCATION';

    // 点击菜单拉取消息时的事件推送
    public const CLICK = 'CLICK';

    // 点击菜单跳转链接时的事件推送
    public const VIEW = 'VIEW';

    // 扫码推事件的事件推送
    public const SCANCODE_PUSH = 'scancode_push';

    // 扫码推事件且弹出“消息接收中”提示框的事件推送
    public const SCANCODE_WAITMSG = 'scancode_waitmsg';

    // 弹出系统拍照发图的事件推送
    public const PIC_SYSPHOTO = 'pic_sysphoto';

    // 弹出拍照或者相册发图的事件推送
    public const PIC_PHOTO_OR_ALBUM = 'pic_photo_or_album';

    // 弹出微信相册发图器的事件推送
    public const PIC_WEIXIN = 'pic_weixin';

    // 弹出地理位置选择器的事件推送
    public const LOCATION_SELECT = 'location_select';

    // 发送模板消息推送通知
    public const TEMPLATESENDJOBFINISH = 'TEMPLATESENDJOBFINISH';
}
