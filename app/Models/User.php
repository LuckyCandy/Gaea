<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * User is admin or not
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->is_admin == 1;
    }

    /**
     * Is user blocked
     * @return bool
     */
    public function isBlocked()
    {
        return $this->is_block == 1;
    }

    /**
     * Block user
     * @return $this
     */
    public function block()
    {
        $this->is_block = 1;
        return $this;
    }

    /**
     * Unblock user
     * @return $this
     */
    public function unblock()
    {
        $this->is_block = 2;
        return $this;
    }

    /**
     * Set user password
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = bcrypt($password);
    }

}
