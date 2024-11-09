<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceService extends Model
{
    use HasFactory;

    protected $table = 'marketplace_service';

    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }
}
