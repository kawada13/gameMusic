<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferRecord extends Model
{
    protected $table = 'transfer_records';
    
    protected $fillable = [
        'user_id', 'price'
    ];

    // リレーション
    Public function user()
    {
      return $this->belongsTo('App\User');
    }
}
