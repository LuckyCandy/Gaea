<?php

namespace App\Http\Controllers\Api;

use App\Classes\QueryFilter;
use App\Events\UserBlock;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function user()
    {
        return response()->jsr(200, auth()->user());
    }

    public function getById(User $user)
    {
        return response()->jsr(200, $user);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = auth()->user();
        $params = $request->only(['name', 'password', 'new_password']);
        $user->name = $request->get('name');
        if (Arr::get($params, 'password')) {
            $user->password = bcrypt($params['new_password']);
        }

        return $user->save() ? response()->jsr() : response()->jsr(500, [], '修改信息失败');
    }

    public function list(Request $request)
    {
        $params = QueryFilter::get($request->all(), ['name', 'email', 'is_block']);
        $builder = DB::table('users');
        foreach ($params as $key => $value) {
            if ($key == 'is_block') {
                $builder->where('is_block', $value);
            } else {
                $builder->where($key, 'like', "%{$value}%");
            }
        }

        $total = $builder->count();
        return response()->jsr(200, [
            'total' => $total,
            'list' => $builder->forPage($request->get('page', 1), $request->get('page_size', 1))->get()
        ]);
    }

    public function blockOrNot(User $user)
    {
        $user->isBlocked() ? $user->unblock() : $user->block();

        Event::dispatch(new UserBlock($user));
        return $user->save() ? response()->jsr() : response()->jsr(500, [], '操作失败');
    }

    public function resetPassword(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'bail|required|email',
        ])->validate();
        /* 可改为queue */
        Artisan::call('notify:pass', ['email' => $request->get('email')]);

        return response()->jsr();
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'bail|required|email',
        ])->validate();

        $result = Artisan::call('user:create', [
            'email' => $request->get('email'),
            '--admin' => false
        ]);

        return $result ?  response()->jsr() : response()->jsr(500, [], '创建用户失败');
    }
}
