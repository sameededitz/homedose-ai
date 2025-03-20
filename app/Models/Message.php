<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['chat_id', 'message', 'sender'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    protected $appends = ['image_url']; // Automatically append the image URL

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk('media')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/bmp']);
    }

    public function getImageUrlAttribute()
    {
        $media = $this->getFirstMedia('image');
        return $media ? $media->getUrl() : null;
    }
}
