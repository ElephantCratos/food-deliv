<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user_1_id', 'user_2_id'];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_2_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getInterlocutor($userId)
    {
        if ($this->user_1_id == $userId) return $this->user2;
        if ($this->user_2_id == $userId) return $this->user1;
        return null;
    }
}
