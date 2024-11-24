<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $table = 'cashs';

    protected $fillable = ['date', 'description', 'category_id', 'notes', 'amount'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
