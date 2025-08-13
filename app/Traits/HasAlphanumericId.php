<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasAlphanumericId
{
    /**
     * Boot the trait.
     */
    protected static function bootHasAlphanumericId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = static::generateAlphanumericId();
            }
        });
    }

    /**
     * Generate a unique alphanumeric ID of 24 characters.
     */
    public static function generateAlphanumericId(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $id = '';
        
        for ($i = 0; $i < 24; $i++) {
            $id .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        // Garantir que o ID é único
        while (static::where(static::make()->getKeyName(), $id)->exists()) {
            $id = '';
            for ($i = 0; $i < 24; $i++) {
                $id .= $characters[random_int(0, strlen($characters) - 1)];
            }
        }
        
        return $id;
    }

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the data type of the primary key ID.
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
