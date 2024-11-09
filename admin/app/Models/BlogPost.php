<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $appends = ['category_type'];

    public function author()
    {
        return $this->belongsTo(BlogAuthor::class, 'author_id');
    }

    //published_at
    public function getPublishedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }

    public function getCategoryTypeAttribute()
    {
        $cats = explode(",", $this->categories);
        foreach ($cats as $perm) {
            $singledata = $this::categoryType($perm);
            $output[] = $singledata;
        }
        return $output;
    }

    public static function categoryType($cat_id)
    {
        $result = DB::table('blog_categories')->select('category_name', 'slug')->where('id', $cat_id)->first();
        return $result;
    }
}
