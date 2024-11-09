<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCategory extends Model
{
    use HasFactory;

    protected $table = 'marketplace_categories';

    public function services()
    {
        return $this->hasMany(MarketplaceService::class, 'category_id');
    }

    public static function totalServices()
    {

        $total_count = MarketplaceService::where('status', 'active')->count();

        // $averageRating = 0;
        // $totalRating = 0;
        // foreach ($ratings as $item) {
        //     $totalRating += $item->rating;
        // }
        // if ($totalRating) {
        //     $averageRating = number_format($totalRating / count($ratings), 1);
        // }
        return $total_count;
    }
}
