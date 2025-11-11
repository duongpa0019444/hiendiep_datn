<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDocument extends Model
{
    use HasFactory;
    protected $table = 'bot_documents';

    protected $fillable = ['training_set_id', 'title', 'file_path', 'content'];

    public function trainingSet()
    {
        return $this->belongsTo(TrainingSet::class);
    }
}
