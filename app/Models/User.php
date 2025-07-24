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
        'address',
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
        return $this->hasMany(classStudent::class, 'student_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(courses::class, 'courses_id');
    }



    // // quan hệ với nhiều classes
    public function classes()
    {
        return $this->belongsToMany(
            classes::class,   // Model lớp
            'class_student',              // Bảng trung gian
            'student_id',                 // FK ở bảng trung gian trỏ về users
            'class_id'                    // FK ở bảng trung gian trỏ về classes
        );
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
    // app/Models/User.php
public function isStaff()
{
    return $this->role === 'staff';
}

}
