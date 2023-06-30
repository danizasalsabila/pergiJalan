<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTransaction extends Model
{
    use HasFactory;
    protected $table = 'admin_transaction';

    protected $fillable = ['id_eticket', 'admin_price'];

    public function eticket()
    {
        return $this->belongsTo(ETicket::class, 'id_eticket');
    }
}
