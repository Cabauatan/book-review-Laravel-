<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory,SoftDeletes;
    //   protected $fillable = ['review', 'rating'];
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    protected static function booted()
    {
        self::updated(function (Review $review) {cache()->forget('book:' . $review->book_id);});
        self::deleted(function (Review $review) {cache()->forget('book:' . $review->book_id);});
    }
}
