<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'role',
        'content',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
