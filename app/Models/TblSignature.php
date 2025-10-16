<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblSignature extends Model
{
    protected $table = 'tbl_signature';
    protected $primaryKey = 'signature_id';
    protected $fillable = [
        'signature_name',
        'signature_file',
        'signature_path'
    ];
}
