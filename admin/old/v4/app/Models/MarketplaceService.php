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

    protected $appends = ['service_rating'];

    protected $table = 'marketplace_services';
    //protected $primaryKey = "service_id";

    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(MarketplaceServiceReview::class, 'service_id');
    }

    public function getServiceRatingAttribute()
    {
        //return $this->reviews->category_name;

        //return $this->reviews();
        //dd($this->reviews);
        //return $this->id;
        //return $this->reviews[0]->service_id;

        $ratings = MarketplaceServiceReview::where('service_id', $this->id)->where('status', 'active')->get();
        //$user_mode = $rat->name;

        //$ratings = $this->reviews;
        //dd($ratings);
        $averageRating = 0;
        $totalRating = 0;
        foreach ($ratings as $item) {
            $totalRating += $item->rating;
        }
        if ($totalRating) {
            $averageRating = number_format($totalRating / count($ratings), 1);
        }
        return $averageRating;

        //return "Hello";
    }

    // public function averageRating()
    // {
    //     $ratings = $this->rating;
    //     $totalRating = 0;
    //     foreach ($ratings as $rating) {
    //         $totalRating += $rating->rating;
    //     }
    //     $averageRating = $totalRating / count($ratings);
    //     return $averageRating;
    // }


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
