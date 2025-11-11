<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSet extends Model
{
    use HasFactory;
    protected $table = 'bot_training_sets';
    protected $fillable = ['name', 'description'];

    public function documents()
    {
        return $this->hasMany(TrainingDocument::class);
    }

    public function questions()
    {
        return $this->hasMany(TrainingData::class);
    }
}
