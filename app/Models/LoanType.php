<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    // use HasFactory;
    protected $table = "loan_type"; //cek
    protected $primaryKey = "id"; //cek
    protected $fillable = ['type_name'
    ];
}
