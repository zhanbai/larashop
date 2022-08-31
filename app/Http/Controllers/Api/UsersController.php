<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $smsCodeKey = 'smsCode_' . $request->phone;
        $smsCode = Cache::get($smsCodeKey);

        if (empty($smsCode)) {
            return fail('验证码已失效', 401);
        }

        if (!hash_equals($smsCode, $request->sms_code)) {
            // 返回401
            return fail('验证码错误', 401);
        }

        $user = User::where('phone', $request->phone)->first();

        if (empty($user)) {
            $user = User::create([
                'name' => $request->phone,
                'phone' => $request->phone,
            ]);
        }

        // 清除验证码缓存
        Cache::forget($smsCodeKey);
        
        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function show(User $user, Request $request)
    {
        return success(new UserResource($user));
    }

    public function me(Request $request)
    {
        return success((new UserResource($request->user()))->showSensitiveFields());
    }

    public function update(UserRequest $request)
    {
        $user = $request->user();

        $attributes = $request->only(['name', 'email']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return success((new UserResource($user))->showSensitiveFields());
    }

    public function updateToken()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroyToken()
    {
        auth('api')->logout();
        return success();
    }

    protected function respondWithToken($token)
    {
        return success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
