<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone',
        'address',
        'date_of_birth',
        'employee_id',
        'student_id',
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
            'date_of_birth' => 'date',
        ];
    }

    // Subjects taught by this teacher
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    // Grades where this user is class teacher
    public function gradesAsTeacher()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    // Enrollments for students
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    // Marks for students
    public function marks()
    {
        return $this->hasManyThrough(Mark::class, Enrollment::class, 'student_id', 'enrollment_id');
    }

    // Scopes for filtering by type
    public function scopeTeachers($query)
    {
        return $query->where('type', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('type', 'student');
    }

    public function scopeAdmins($query)
    {
        return $query->where('type', 'admin');
    }


    public function canAccessPanel(Panel $panel): bool
    {
        // Super admin bypass all checks
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // Admin can access
        if ($this->hasRole('admin')) {
            return true;
        }

        // Teachers can access
        if ($this->hasRole('teacher')) {
            return true;
        }

        // students cannot access admin panel
        // (they will have their own dashboard later)
        return false;
    }
}
