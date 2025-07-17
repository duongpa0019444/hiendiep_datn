<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    const UPDATED_AT = null; // ✅ Tắt riêng updated_at
    protected $fillable = [
        'title',
        'content',
        'target_role',
        'created_by',
        'class_id', // nếu bạn có dùng
    ];

        public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
