<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'username',
        'useremail',
        'borrowstatus',
        'rate',
        'review',
        'reason',
        'approvetime',
        'returndate',
        'extenddate',
        'extendreq',
        'reasonextend',
        'rejectextend',
        'notification_count',
        'actualreturndate',
    ];

    // Define relationships with other models
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
