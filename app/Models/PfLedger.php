<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfLedger extends Model
{
    use HasFactory;

    protected $table = 'pf_ledgers';

    protected $fillable = [
        'employee_id',
        'branch_id',
        'scheme_id',
        'transaction_type',
        'credit',
        'debit',
        'balance',
        'description',
        'reference_type',
        'reference_id',
        'created_by'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function scheme()
    {
        return $this->belongsTo(PfScheme::class, 'scheme_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the parent reference model (contribution, withdrawal, etc.).
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
