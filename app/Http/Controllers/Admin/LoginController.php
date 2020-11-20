<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {

        return view('web.login.index');
    }

    public function dologin(Request $request)
    {
        if($request->isMethod('post')){

            //检测验证码
            $rules =[
                'captcha' => 'captcha'
            ];
            $validate = Validator::make($request->all(), $rules); // 此处验证码只能使用validator检测
            if($validate->fails()){
                return jsonReturn(-1,'验证码错误');
            }


            $res = Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password]);  //自带验证，里面传验证字段
            if($res){

                return jsonReturn(200,'登录成功');
            }else{
                //登录失败,信息内容存入闪存,页面判断session即可
                $request->session()->flash("errormsg","用户名或密码错误");
                return jsonReturn(-1,'用户名或密码错误');
            }
        }else{
            return jsonReturn(-1,'请求错误');
        }

    }

    public function logout()
    {
        Auth::guard("admin")->logout();
        return jsonReturn(200,'退出登录成功');
    }


}
