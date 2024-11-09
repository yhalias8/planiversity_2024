<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceWishList extends Model
{
    use HasFactory;

    protected $table = 'marketplace_wishlist';
    protected $primaryKey = "id";


    public function services()
    {
        return $this->belongsTo(MarketplaceService::class, 'service_id');
        //return MarketplaceWishList::find($this->id);
    }
}
