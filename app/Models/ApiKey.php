<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'key',
    ];

    public static function setActiveKey($id)
    {
        // Find the key by ID to get its type
        $key = self::find($id);

        if ($key) {
            // Deactivate all keys of the same type
            self::where('type', $key->type)->update(['is_active' => false]);

            // Activate the selected key
            $key->is_active = true;
            $key->save();
        }
    }
}
