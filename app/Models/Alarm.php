<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Attachment;
use App\Models\Comment;

class Alarm extends Model
{
    use HasFactory;

    protected $table = "alarm";
    protected $guarded = [];
}
