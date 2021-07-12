<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'user_id', 'from_user_id', 'title'
    ];

    // リレーション
    Public function user()
    {
      return $this->belongsTo('App\User');
    }
}
