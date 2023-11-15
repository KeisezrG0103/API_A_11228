<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'subscriptions';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'category',
        'price',
        'transaction_date',
    ];

}
