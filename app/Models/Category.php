<?php

namespace App\Models;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'income',
        'expense'
    ];

    /**
     * Get the keywords for the category.
     */
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }
}
