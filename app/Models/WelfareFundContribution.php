<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareFundContribution extends Model
{
    use HasFactory;

    protected $table = 'welfare_fund_contributions';

    protected $fillable = [
        'user_id',
        'payroll_id',
        'amount',
        'month',
        'year',
        'contribution_date',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
