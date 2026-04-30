<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use SoftDeletes, HasFactory, Notifiable;

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'barcode',
        'unit_measurement',
        'is_active',
        'quantity',
        'category_ulid',
        'age_restriction',
        'description',
        'taxes',
        'cost_price',
        'markup',
        'sale_price',
        'color',
        'image',
    ];

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_ulid', 'ulid')->withTrashed();
    }
}
