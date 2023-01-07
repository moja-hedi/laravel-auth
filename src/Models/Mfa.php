<?php

/**
 * Created by Reliese Model.
 */

namespace MojaHedi\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Mfa extends Model
{
    use SoftDeletes;

    protected $table = 'mfa';


    protected $fillable = [
        'id_user',
        'code',
        'expired_at'
    ];
}
