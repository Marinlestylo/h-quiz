<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'gender', 'affiliation', 'unique_id', 'keycloak_id', 'refresh_token'
    ];

    protected $guarded = ['id', 'created_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'keycloak_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function hasRole($roles)
    {
        if (!is_array($roles))
            $roles = [$roles];

        foreach ($roles as $role) {
            if (
                $role == 'student' &&
                str_contains($this->affiliation, 'member') &&
                str_contains($this->affiliation, 'student')
            ) {
                return true;
            }
            if (
                $role == 'teacher' &&
                str_contains($this->affiliation, 'member') &&
                str_contains($this->affiliation, 'staff')
            ) {
                return true;
            }
        }

        return false;
    }

    function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    function isStudent()
    {
        return $this->hasRole('student');
    }

    function student()
    {
        return $this->hasOne(Student::class);
    }

    function activities()
    {
        return $this->hasMany(Activity::class);
    }

    function rosters()
    {
        return $this->hasMany(Roster::class, 'teacher_id');
    }

    function getFullName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        //TODO : si keycloak cassé, rechanger ça
        // return $this->email;
        return $this->id;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        // return 'email';
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        // return $this->email;
        return $this->id;
    }
}
