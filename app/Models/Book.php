<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopePopular(Builder $query,$from = null,$to = null): Builder
    {
        return $query->withCount(['reviews' => function (Builder $q) use ($from , $to ) {$this->dateFilter($q,$from,$to);}])
            ->orderBy('reviews_count', 'desc');
            
    }

    public function scopeHighestRated(Builder $query,$from = null,$to = null): Builder
    {
        return $query->withAvg(['reviews' => fn(Builder $q) => $this->dateFilter($q,$from,$to)],'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeTitle(Builder $query,string $title): Builder
    {
        return $query->where('title', 'LIKE','%' .$title. '%');
    }

    public function scopeMinReviews(Builder $query,int $minReviews): Builder
    {
        return $query->having('reviews_count','>=',$minReviews);
        
    }


    private function dateFilter(Builder $query,$from = null,$to = null)
    {
        if($from && !$to)
        {
            $query->where('created_at', '>=', $from);
        }
        elseif(!$from && $to)
        {
            $query->where('created_at', '<=', $to);
        }
        elseif($from && $to)
        {
            $query->whereBetween('created_at',[$from,$to]);
        }
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now());
    }

    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
        ->highestRated(now()->subMonths(6), now());
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now());
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now());
    }

    

    
}
