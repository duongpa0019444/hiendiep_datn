<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'class_room';
    protected $fillable = [
        'room_name', 'Capacity', 'status', 'description', 'created_at', 'updated_at', 'name_class','start_time', 'end_time'
    ];
     public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = date('Y-m-d H:i:s', strtotime($value));
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = date('Y-m-d H:i:s', strtotime($value));
    }

   
}

?>