<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Drive extends Model
{
    use SoftDeletes, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['user_ulid', 'file_name', 'file_path', 'file_size', 'file_type'];

    /**
     * Get the user that owns the drive file.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ulid', 'ulid')->withTrashed();
    }
}
