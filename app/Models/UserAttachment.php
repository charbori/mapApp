<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttachment extends Model
{
    use HasFactory;

    protected $table = "user_attachment";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "id",
        "path"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
