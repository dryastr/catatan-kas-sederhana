<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    use HasFactory;

    protected $table = 'cash_outs';

    protected $fillable = ['date', 'description', 'category_id', 'notes', 'amount'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
