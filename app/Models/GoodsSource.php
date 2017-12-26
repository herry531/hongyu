<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: wenlongh
 * Date: 2017/6/12
 * Time: 17:01
 * Author: wenlongh <wenlongh@qq.com>
 */
class GoodsSource extends Base
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'goods_source';

    public function img()
    {
        return $this->belongsTo(File::class, 'img_id');
    }

}