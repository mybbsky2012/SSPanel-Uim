<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserSubscribeLog;
use App\Utils\QQWry;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class SubscribeLogController extends BaseController
{
    public static $details =
    [
        'field' => [
            'id' => '事件ID',
            'user_name' => '用户名',
            'user_id' => '用户ID',
            'email' => '用户邮箱',
            'subscribe_type' => '获取的订阅类型',
            'request_ip' => '请求IP',
            'location' => 'IP归属地',
            'request_time' => '请求时间',
            'request_user_agent' => '客户端标识符',
        ],
    ];

    /**
     * 后台订阅记录页面
     *
     * @param array     $args
     */
    public function index(Request $request, Response $response, array $args): ResponseInterface
    {
        return $response->write(
            $this->view()
                ->assign('details', self::$details)
                ->display('admin/subscribe.tpl')
        );
    }

    /**
     * 后台订阅记录页面 AJAX
     *
     * @param array     $args
     */
    public function ajaxSubscribeLog(Request $request, Response $response, array $args): ResponseInterface
    {
        $length = $request->getParam('length');
        $page = $request->getParam('start') / $length + 1;
        $draw = $request->getParam('draw');

        $subscribes = UserSubscribeLog::orderBy('id', 'desc')->paginate($length, '*', '', $page);
        $total = UserSubscribeLog::count();

        $QQWry = new QQWry();
        foreach ($subscribes as $subscribe) {
            $subscribe->location = $subscribe->location($QQWry);
        }

        return $response->withJson([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'subscribes' => $subscribes,
        ]);
    }
}
