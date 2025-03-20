<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['chat_id', 'message', 'sender', 'image_text'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
