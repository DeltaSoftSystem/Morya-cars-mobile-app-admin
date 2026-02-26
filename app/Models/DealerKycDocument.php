<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerKycDocument extends Model
{
    use HasFactory;
    protected $table = 'dealer_kyc_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_path',
        'status',
        'admin_remark'
    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id');
    }
}
