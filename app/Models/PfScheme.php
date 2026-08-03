<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfScheme extends Model
{
    use HasFactory;

    protected $table = 'pf_schemes';

    protected $fillable = [
        'name',
        'description',
        'employee_contribution_percentage',
        'employer_contribution_percentage',
        'is_active',
    ];

    public function contributions()
    {
        return $this->hasMany(ProvidentFundContribution::class, 'scheme_id');
    }

    public function loans()
    {
        return $this->hasMany(ProvidentFundLoan::class, 'scheme_id');
    }

    public function ledgers()
    {
        return $this->hasMany(PfLedger::class, 'scheme_id');
    }
}
