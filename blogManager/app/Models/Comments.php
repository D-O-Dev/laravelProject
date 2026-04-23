<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //

    protected $fillable = [
        'user_id',
        'article_id',
        'slug',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Articles::class);
    }

}
