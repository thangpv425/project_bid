<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hash extends Model {
    protected $table = 'hashs';
    protected $fillable = [
        'hash_key',
        'expire_at',
        'user_id',
        'type'
    ];


    public function userId() {
       $this->belongsTo('App\User', 'user_id');
    }
}
