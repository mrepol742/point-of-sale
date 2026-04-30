<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class Model extends EloquentModel
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['id', 'updated_at', 'deleted_at'];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'ulid') && empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    /**
     * Get the hidden attributes for serialization.
     *
     * @return array
     */
    public function getHidden(): array
    {
        return array_unique(array_merge(parent::getHidden(), $this->hidden));
    }
}
