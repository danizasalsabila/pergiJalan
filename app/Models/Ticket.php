<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'ticket';

    protected $fillable = ['id_destinasi', 'price', 'stock', 'ticket_sold', 'created_at'];

    




    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'id_destinasi')->select('id', 'id_owner', 'name_destinasi', 'address','city', 'contact', 'url_map', 'open_hour', 'closed_hour');
    }

    public function etickets()
    {
        return $this->hasMany(ETicket::class, 'id_ticket');
    }
    


}
