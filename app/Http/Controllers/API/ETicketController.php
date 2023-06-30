<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdminTransaction;
use App\Models\ETicket;
use App\Models\Ticket;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ETicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eticket = ETicket::with('ticket', 'user', 'destinasi', 'owner')->get();
        if ($eticket->count() > 0) {
            return response([
                'status' => 'success',
                'message' => 'E-Ticket Berhasil ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'E-Ticket gagal ditampilkan'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function showByIdOwner($id)
    {
        //
        $eticket = ETicket::with('destinasi', 'user', 'ticket')->where('id_owner', $id)->orderBy('id', 'desc')->get();
        ;
        if ($eticket->count() > 0) {
            return response([
                'status' => 'E-Ticket berdasarkan id owner berhasil ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'eticket tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function showByIdUser($id)
    {
        //
        $eticket = ETicket::with('destinasi')->where('id_user', $id)->orderBy('id', 'desc')->get();
        
        if ($eticket->count() > 0) {
            return response([
                'status' => 'E-Ticket berdasarkan id user berhasil ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'eticket tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function showByIdTicket($id)
    {
        //
        $eticket = ETicket::with('destinasi')->where('id_ticket', $id)->orderBy('id', 'desc')->get();
        ;
        if ($eticket->count() > 0) {
            return response([
                'status' => 'E-Ticket berdasarkan id ticket berhasil ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'eticket tidak ditemukan'
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {
        $dt = new DateTime();
        $tahun = $dt->format('y');
        $bulan = $dt->format('m');


        $request->validate([
            'id_user' => 'required|exists:user,id',
            'id_owner' => 'required|exists:owner_business,id',
            'id_ticket' => 'required|exists:ticket,id',
            'id_destinasi' => 'required|exists:destinasi,id',
            'name_visitor' => 'required|string',
            'contact_visitor' => 'required|string',
            'date_visit' => 'required|string',
        ]);

        $eticket = new ETicket;
        $eticket->id_user = $request->input('id_user');
        $eticket->id_owner = $request->input('id_owner');
        $eticket->id_ticket = $request->input('id_ticket');
        $eticket->id_destinasi = $request->input('id_destinasi');
        $eticket->name_visitor = $request->input('name_visitor');
        $eticket->contact_visitor = $request->input('contact_visitor');
        $eticket->date_visit = $request->input('date_visit');
        $eticket->date_book = $dt;

        // Generate random ID to make payment virtual account
        $length = 12; // Jumlah karakter yang diinginkan
        $randomId = str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        $eticket->virtual_account = $randomId;
        $eticket->save();

        // Mendapatkan harga tiket dari tabel 'ticket' berdasarkan 'id_ticket'
        $ticket = Ticket::findOrFail($request->input('id_ticket'));
        $eticket->price = $ticket->price;

        // Menambahkan nilai admin_price dan menghitung total_price
        $adminPrice = 1500;
        $eticket->admin_price = $adminPrice;
        $eticket->total_price = $ticket->price + $adminPrice;

        $eticket->save();

        //to reduce the amount of stock on ticket table with id_ticket request
        $ticket = Ticket::findOrFail($request->input('id_ticket'));
        $ticket->decrement('stock');

        //to increase the amount of ticket_sold on ticket table with id_ticket request
        $ticket = Ticket::findOrFail($request->input('id_ticket'));
        $ticketSold = $ticket->eTickets()->count();
        $ticket->ticket_sold = $ticketSold;
        $ticket->save();

        // Membuat entri baru pada tabel Transaksi
        $admintransaksi = new AdminTransaction;
        $admintransaksi->id_eticket = $eticket->id;
        $admintransaksi->admin_price = $eticket->admin_price;
        $admintransaksi->save();

        if ($eticket != null && $admintransaksi != null) {
            $eticket = ETicket::with('admintransaksi')->findOrFail($eticket->id);
            return response([
                'status' => 'success',
                'message' => 'E-Ticket Berhasil Ditambahkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'E-Ticket gagal Ditambahkan'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getETicketByYearOwner(Request $request, $id_owner)
    {
        // $id_owner = $request->id_owner;
        $year = $request->input('year');

        $eticket = ETicket::with('destinasi')->where('id_owner', $id_owner)->whereYear('date_book', $year)->get();

        if ($eticket->count() > 0) {
            return response([
                'status' => 'success',
                'message' => 'E-Ticket berdasarkan tahun Berhasil Ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'E-Ticket gagal Ditampilkan'
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getETicketByMonthOwner(Request $request, $id_owner)
    {
        // $id_owner = $request->id_owner;
        $year = $request->input('year');
        $month = $request->input('month');

        $eticket = ETicket::with('destinasi')->where('id_owner', $id_owner)->whereYear('date_book', $year)->whereMonth('date_book', $month)->get();

        if ($eticket->count() > 0) {
            return response([
                'status' => 'success',
                'message' => 'E-Ticket berdasarkan bulan ke ' . $month . ' di tahun ' . $year . ' Berhasil Ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'E-Ticket gagal Ditampilkan'
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function getETicketByWeekOwner(Request $request, $id_owner)
    {
        $date = Carbon::parse($request->input('date'))->startOfDay()->toDateString();
        $startDate = Carbon::parse($date)->subDays(7)->startOfDay()->toDateString();
    
        
        $eticket = ETicket::with('destinasi')
        ->where('id_owner', $id_owner)
        ->whereDate('date_book', '>=', $startDate)
        ->whereDate('date_book', '<=', $date)
        ->get();


        if ($eticket->count() > 0) {
            return response([
                'status' => 'success',
                'message' => 'E-Ticket mulai dari tanggal 7 hari sebelum ' . $date . 'Berhasil Ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'E-Ticket berdasarkan minggu gagal Ditampilkan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        // $ticket = Ticket::where('id_destinasi', $id)->get();
        $eticket = ETicket::with('destinasi', 'ticket')->where('id', $id)->get();
        if ($eticket ->count() > 0) {
            return response([
                'status' => 'ETicket berhasil ditampilkan',
                'data' => $eticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'ETicket tidak ditemukan'
            ], 404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}