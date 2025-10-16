<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblRecord extends Model
{
    protected $table = 'tbl_record';
    protected $primaryKey = 'id';
    protected $fillable = [
        'task',
        'filename',
        'path'
    ];
}
