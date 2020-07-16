<?php

namespace App\Listeners;

use App\Events\UserBlock;
use App\Models\SysLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserEventSubscriber
{
    public function handleUserLogin($event)
    {
        $user = $event->user;

        (new SysLog(
            [
                'type' => SysLog::TYPE_LOGIN,
                'operator_id' => $user->id,
                'desc' => "用户{$user->name}登录系统"
            ]
        ))->save();
    }

    public function handUserBlock($event)
    {
        $user = $event->user;

        (new SysLog(
            [
                'type' => SysLog::TYPE_BLOCK,
                'operator_id' => $user->id,
                'desc' => "用户{$user->name}" . ($user->isBlocked() ? '被禁止登陆' : '被解禁')
            ]
        ))->save();
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@handleUserLogin'
        );

        $events->listen(
            UserBlock::class,
            'App\Listeners\UserEventSubscriber@handUserBlock'
        );
    }
}
