<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class topics extends Model
{
    //
    use SoftDeletes;

    protected $table = 'topics';


    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function news()
    {
        return $this->hasMany(news::class, 'topic_id');
    }
}
