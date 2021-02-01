<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';

    //  Relación de uno a muchos inversa
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    //  Relación de uno a muchos inversa
    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }
}
