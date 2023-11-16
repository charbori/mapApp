<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class UserGroup extends Model
{
    use HasFactory;

    protected $table = "users_group";
    protected $fillable = ['group_id', 'user_id'];

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
