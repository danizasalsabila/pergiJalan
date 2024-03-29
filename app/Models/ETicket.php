<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ETicket extends Model
{
    use HasFactory;
    protected $table = 'eticket';

    protected $fillable = ['id_user', 'id_owner', 'id_destinasi', 'id_ticket', 'name_visitor', 'contact_visitor', 'date_visit', 'admin_price', 'total_price'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket')->select('id', 'price', 'name_ticket');
    }

    public function admintransaksi()
    {
        return $this->hasOne(AdminTransaction::class, 'id_eticket', 'id');
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'id_destinasi')->select('id', 'name_destinasi', 'address', 'contact', 'open_hour', 'closed_hour');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user')->select('id', 'name','email');
    }

    public function owner()
    {
        return $this->belongsTo(OwnerBusiness::class, 'id_owner')->select('id', 'nama_owner');
    }



}
