<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class SysLog extends Model
{
	const TYPE_LOGIN = 1; //登陆

	const TYPE_BLOCK = 2; //禁用or解禁
	
    protected $table = 'sys_log';

    protected $fillable = ['operator_id', 'desc', 'type'];

    public function getTypeAttribute($value)
    {
        return $value == self::TYPE_LOGIN ? '用户登陆' : '账户禁用|解禁';
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User','operator_id', 'id');
    }
}
