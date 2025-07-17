<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    use HasFactory;
    protected $table = 'contact';
   protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];



    protected $fillable = [
        'assigned_to', 'pl_content', 'status', 'name', 'phone', 'message'
    ];
    public $timestamps = false;


    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}


?>