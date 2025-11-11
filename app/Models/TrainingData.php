<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingData extends Model
{
    use HasFactory;
    protected $table = 'bot_training_data';

    protected $fillable = ['training_set_id', 'question', 'answer', 'keywords', 'context', 'source'];

    public function trainingSet()
    {
        return $this->belongsTo(TrainingSet::class);
    }
}
