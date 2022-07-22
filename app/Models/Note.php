<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    // There are two way to implment 
    // 1 - 
    //protected $fillable = []; // implemt field by fild, I think so rsrsrs

    // 2 - 
    protected $guarded = []; // All field to assigment

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
