<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'avatar',
        'gender',
        'birth_date',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function class_student()
    {
        return $this->hasMany(classStudent::class, 'id', 'student_id');
    }


    public function classStudents()
    {
        return $this->hasMany(classStudent::class, 'student_id', 'id');
    }


    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }


    //phân quyền
    public function isAdmin()
    {
        return $this->hasAnyRole(['admin']);
    }

    public function isUser()
    {
        return $this->hasAnyRole(['staff']);
    }

    // Quan hệ với bảng course_payment
    public function payments()
    {
        return $this->hasMany(CoursePayment::class, 'student_id');
    }
}
