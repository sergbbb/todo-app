<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The limit of undone Todos
     *
     * @var int
     */
    protected int $todosLimit = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function checkTodosLimitAndMarkAsDone()
    {
        $undoneCount = $this->todos()->where('is_done', 0)->count();
        if ($undoneCount > $this->todosLimit) {
            $ids = $this->todos()
                ->where('is_done', 0)
                ->offset($this->todosLimit)
                ->limit(100)
                ->orderBy('id', 'DESC')
                ->pluck('id');

            Todo::whereIn('id', $ids)->update(['is_done' => 1]);
        }
    }
}
