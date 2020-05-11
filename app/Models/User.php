<?php
/**
 * Created by PhpStorm.
 * User: myxy9
 * Date: 2020/5/10
 * Time: 21:16
 */

namespace App\Models;


use App\Traits\Model\BaseModelTrait;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;
    public $table = 'user';
    use BaseModelTrait;
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 获取JWT中用户标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *  获取JWT中用户自定义字段
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}