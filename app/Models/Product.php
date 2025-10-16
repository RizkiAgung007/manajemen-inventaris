<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'stock',
    //     'price',
    //     'desc',
    //     'category_id',
    //     'supplier_id',
    //     'image'
    // ];

    protected $guarded = ['id'];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class)->latest();
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_order_product')->withPivot('quantity', 'price')->withTimestamps();
    }
}
