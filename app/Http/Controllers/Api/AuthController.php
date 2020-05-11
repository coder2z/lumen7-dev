<?php
/**
 * Created by PhpStorm.
 * User: myxy9
 * Date: 2020/5/11
 * Time: 9:39
 */

namespace App\Http\Controllers\Api;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;

/**
 * @author AdamTyn
 * @description <用户认证>控制器
 */
class AuthController extends Controller
{
    /**
     * 用户模型
     * @var \App\Models\UserModel
     */
    private static $userModel = null;

    /**
     * 认证器
     * @var mixed
     */
    private $auth = null;

    /**
     * 当前时间戳
     * @var int|string
     */
    protected $currentDateTime;

    /**
     * @author AdamTyn
     *
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->initialAuth();
        empty(self::$userModel) ? (self::$userModel = (new \App\Models\User)) : true;
        $this->currentDateTime = time();
    }

    /**
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ];
    }

    /**
     * @author AdamTyn
     * @description 用户登录
     *
     * @param \Illuminate\Http\Request;
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        try {
            if (!$token = $this->auth->attempt(['name' => 'admin', 'password' => '123456'])) {
                return json_fail('用户密码错误');
            }
            return json_success('登录成功', $this->respondWithToken($token));
        } catch (\Exception $exception) {
            logError($exception->getMessage() . ' at' . $this->currentDateTime);
            return json_fail('无法响应请求，服务端异常');
        }
    }

    /**
     * @author AdamTyn
     * @description 查询用户信息
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function userInfo()
    {
        try {
            return json_success('获取成功', $this->auth->user());
        } catch (\Exception $exception) {
            logError($exception->getMessage() . ' at' . $this->currentDateTime);
            return json_fail('无法响应请求，服务端异常');
        }
    }

    /**
     * @author AdamTyn
     * @description 用户退出
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function logout()
    {
        try {
            return json_success('注销成功', $this->auth->logout());
        } catch (\Exception $exception) {
            logError($exception->getMessage() . ' at' . $this->currentDateTime);
            return json_fail('无法响应请求，服务端异常');
        }
    }

    /**
     * @author AdamTyn
     * @description 用户刷新Token
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken()
    {
        try {
            if ($token = $this->auth->refresh(true, true)) {
                return json_success('刷新成功', $this->respondWithToken($token));
            } else {
                return json_fail('刷新失败');
            }
        } catch (\Exception $exception) {
            logError($exception->getMessage() . ' at' . $this->currentDateTime);
            return json_fail('无法响应请求，服务端异常');
        }
    }

    /**
     * @author AdamTyn
     * @description 初始化认证器
     *
     * @return void
     */
    private function initialAuth()
    {
        $this->auth = app('auth');
    }
}