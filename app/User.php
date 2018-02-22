<?php

namespace App;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $fname
 * @property string|null $lname
 * @property string $pseudo
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $activated
 * @property string|null $signup_ip
 * @property string|null $signup_confirmation_ip
 * @property string|null $deleted_at
 * @property int|null $role_id
 * @property int|null $total_answers
 * @property string|null $custom_grade
 * @property int|null $is_ban
 * @property string $email
 * @property string|null $grade
 * @property string|null $picture
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCustomGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePseudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSignupConfirmationIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSignupIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTotalAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePicture($value)
 * @property string|null $about
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAbout($value)
 */
class User extends Authenticatable implements CanResetPassword
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pseudo', 'email', 'password', 'fname', 'lname', 'signup_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'signup_ip', 'activated', 'signup_confirmation_ip'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'pseudo';
    }

    public function isMember() {
        return $this->activated == 1;
    }

    public function isVIP() {
        return $this->role_id == 3;
    }

    public function isAtLeastVIP() {
        return $this->role_id == 1 or $this->role_id == 3;
    }

    public function isAdmin() {
        return $this->role_id == 1;
    }
}
