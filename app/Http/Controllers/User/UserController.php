<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 上午 10:19
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'register', 'store', 'index', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['register']
        ]);
    }

    public function register()
    {
        return view('user.reg');
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(30);
        return view('user.show', compact('user','statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $this->sendConfirmEmail($user);
        session()->flash('success', '验证邮件已发送至您的注册邮箱，请登录邮箱进行验证');
        return redirect('/');
//        \Auth::login($user);
//        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
//        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('user.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('user.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    protected function sendConfirmEmail($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '704273241@qq.com';
        $name = 'ricefur';
        $to = $user->email;
        $subject = "感谢注册 Social Blog 应用！请确认你的邮箱。";
        \Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message
                ->from($from, $name)
                ->to($to)
                ->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        \Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}