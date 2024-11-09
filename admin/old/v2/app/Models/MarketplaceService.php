<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceService extends Model
{
    use HasFactory;

    // protected $appends = [
    //     'target',
    // ];

    protected $table = 'marketplace_service';
    //protected $primaryKey = "service_id";

    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    public function wishlist()
    {
        //->where('uuid', '4ed0992d-e255-4566-83b1-3391db01828a');
        return $this->hasMany(MarketplaceWishList::class, 'service_id');
        //return MarketplaceWishList::find($this->id);
    }

    //protected $appends = ["image"];

    // public function getTargetAttribute()
    // {

    //     //return (string) MarketplaceWishList::find($this->id);

    //     return MarketplaceWishList::select('id')->where('service_id', $this->id)->get();
    //     //return MarketplaceWishList::find($this->id);
    //     //return $hold['id'];
    //     //return $hold->id;
    // }
}
