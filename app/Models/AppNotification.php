<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image_path',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            try {
                $messaging = app('firebase.messaging');

                $notification = \Kreait\Firebase\Messaging\Notification::create($model->title, $model->body);
                if ($model->image_path) {
                    $notification = $notification->withImageUrl(url('storage/' . $model->image_path));
                }

                $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('topic', 'all_users')
                    ->withNotification($notification);

                $messaging->send($message);
            } catch (\Exception $e) {
                // Log the error but don't fail the creation
                \Illuminate\Support\Facades\Log::error('FCM Error: ' . $e->getMessage());
            }
        });
    }
}
