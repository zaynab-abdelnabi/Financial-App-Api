<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurring extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function Transaction(){

        return $this->hasMany(Transaction::class);
    }
}
