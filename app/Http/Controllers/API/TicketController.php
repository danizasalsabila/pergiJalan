<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ETicket;
use App\Models\Ticket;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $ticket = Ticket::with('ticket')->get();
        $ticket = Ticket::with('destinasi')->get();

        // return response()->json([
        //     'ticket' => $ticket
        // ]);

        if ($ticket != null) {
            return response([
                'status' => 'success',
                'message' => 'Tiket Berhasil ditampilkan',
                'data' => $ticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Tiket gagal ditampilkan'
            ], 404);
        }
    }

    public function getTicketSoldByDestination(Request $request)
    {
        // Validasi inputan jika diperlukan
        $request->validate([
            'id_destinasi' => 'required|exists:destinasi,id'
        ]);

        $destinationId = $request->input('id_destinasi');

        // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        $eticketExists = ETicket::where('id_destinasi', $destinationId)->exists();

        if (!$eticketExists) {
            return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        }

        // Menghitung jumlah tiket terjual berdasarkan ID destinasi
        $ticketSold = ETicket::whereHas('ticket', function ($query) use ($destinationId) {
            $query->where('id_destinasi', $destinationId);
        })->count();

        return response()->json(['ticket_sold' => $ticketSold], 200);
    }

    public function getTicketSoldByDestinationInMonth(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        // Validasi inputan jika diperlukan

        $request->validate([
            'id_destinasi' => 'required|exists:destinasi,id'
        ]);

        $destinationId = $request->input('id_destinasi');

        // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        $eticketExists = ETicket::where('id_destinasi', $destinationId)->exists();
        

        if (!$eticketExists) {
            return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        }
        $ticketSold = ETicket::where('id_destinasi', $destinationId)
        ->whereYear('date_book', $year)
        ->whereMonth('date_book', $month)
        ->count();

        return response()->json(['ticket_sold' => $ticketSold], 200);
    }

    public function getTicketSoldByDestinationInYear(Request $request)
    {
        $year = $request->input('year');


        $request->validate([
            'id_destinasi' => 'required|exists:destinasi,id'
        ]);

        $destinationId = $request->input('id_destinasi');

        // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        $eticketExists = ETicket::where('id_destinasi', $destinationId)->exists();
        

        if (!$eticketExists) {
            return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        }
        $ticketSold = ETicket::where('id_destinasi', $destinationId)
        ->whereYear('date_book', $year)
        ->count();

        return response()->json(['ticket_sold' => $ticketSold], 200);
    }

    public function getTicketSoldByDestinationInWeek(Request $request)
    {
        // $date = Carbon::parse($request->input('date'))->startOfDay();
        // $startDate = $date->copy()->subDays(7)->startOfDay();


        // $request->validate([
        //     'id_destinasi' => 'required|exists:destinasi,id'
        // ]);

        // $destinationId = $request->input('id_destinasi');

        // // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        // $eticketExists = ETicket::where('id_destinasi', $destinationId)->exists();
        

        // if (!$eticketExists) {
        //     return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        // }
        // $ticketSold = ETicket::where('id_destinasi', $destinationId)
        // ->whereBetween('date_book', [$startDate, $date])
        // ->count();

        // return response()->json(['ticket_sold' => $ticketSold], 200);
        $date = Carbon::parse($request->input('date'))->startOfDay()->toDateString();
        $startDate = Carbon::parse($date)->subDays(7)->startOfDay()->toDateString();
    
        $request->validate([
            'id_destinasi' => 'required|exists:destinasi,id'
        ]);
    
        $destinationId = $request->input('id_destinasi');
    
        // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        $eticketExists = ETicket::where('id_destinasi', $destinationId)->exists();
    
        if (!$eticketExists) {
            return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        }
    
        $ticketSold = ETicket::where('id_destinasi', $destinationId)
            ->whereDate('date_book', '>=', $startDate)
            ->whereDate('date_book', '<=', $date)
            ->count();
    
        return response()->json(['ticket_sold' => $ticketSold], 200);
    }

    public function getTicketSoldByIdOwner(Request $request)
    {
        // Validasi inputan jika diperlukan
        $request->validate([
            'id_owner' => 'required|exists:owner_business,id'
        ]);

        $ownerId = $request->input('id_owner');

        // Mengecek apakah terdapat E-Ticket dengan id_destinasi yang diberikan
        $eticketExists = ETicket::where('id_owner', $ownerId)->exists();

        if (!$eticketExists) {
            return response()->json(['message' => 'Tidak terdapat pembelian tiket'], 404);
        }

        // Menghitung jumlah tiket terjual berdasarkan ID destinasi
        $ticketSold = ETicket::whereHas('ticket', function ($query) use ($ownerId) {
            $query->where('id_owner', $ownerId);
        })->count();

        return response()->json(['ticket_sold' => $ticketSold], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dt = new DateTime();
        $tahun = $dt->format('y');
        $bulan = $dt->format('m');

        $request->validate([
            'id_destinasi' => 'required|exists:destinasi,id',
            'name_ticket' => 'required|string',
            'price' => 'nullable|integer|min:1',
            'stock' => 'nullable|integer|min:0',
            // 'ticket_sold' => 'nullable|integer|min:0',
            // 'visit_date' => 'nullable|string',
        ]);

        $ticket = new Ticket;
        $ticket->id_destinasi = $request->input('id_destinasi');
        $ticket->name_ticket = $request->input('name_ticket');
        $ticket->price = $request->input('price');
        $ticket->stock = $request->input('stock');
        $ticket->ticket_sold = 0; 
        // $ticket->ticket_sold = $request->input('ticket_sold');
        // $ticket->visit_date = $request->input('visit_date');
        $ticket->created_at = $dt;
        $ticket->save();

        if ($ticket != null) {
            return response([
                'status' => 'success',
                'message' => 'Ticket Berhasil Ditambahkan',
                'data' => $ticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Ticket gagal Ditambahkan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *  @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        // $ticket = Ticket::where('id_destinasi', $id)->get();
        $ticket = Ticket::where('id_destinasi', $id)->get();
        if ($ticket->count() > 0) {
            return response([
                'status' => 'Ticket berhasil ditampilkan',
                'data' => $ticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Ticket tidak ditemukan'
            ], 404);
        }
    }

    
    /**
     * Display the specified resource.
     *  @return \Illuminate\Http\Response
     */
    public function showByMostSoldTicket(Request $request)
    {
        //
        $request->validate([
            'id_owner' => 'required|exists:owner_business,id'
        ]);

        $ownerId = $request->input('id_owner');

        $ticket = Ticket::with('destinasi')->join('destinasi', 'ticket.id_destinasi', '=', 'destinasi.id')
            ->select('ticket.*')
            ->where('destinasi.id_owner', $ownerId)
            ->where('ticket.ticket_sold', '>', 0)
            ->orderByDesc('ticket.ticket_sold',)
            ->get();

        if ($ticket->count() > 0) {
            return response([
                'status' => 'Ticket berdasarkan penjualan terbanyak berhasil ditampilkan',
                'data' => $ticket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Ticket berdasarkan penjualan terbanyak tidak ditemukan'
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $getTicket = Ticket::where('id', $id)->first();
        if (!$getTicket) {
            return response([
                'status' => 'failed',
                'message' => 'ID Ticket tidak ditemukan'
            ], 404);
        }

        $dt = new DateTime();
        $tahun = $dt->format('y');
        $bulan = $dt->format('m');

        $requestTicket = [
            'name_ticket' => $request->name_ticket,
            'price' => $request->price,
            'stock' => $request->stock,
            'updated_at' => $dt
        ];

        // $idDestinasi = $getTicket->idDestinasi;
        $updateTicket = DB::table('ticket')->where('id', $id)->update($requestTicket);


        if ($updateTicket != null) {
            return response([
                'status' => 'success',
                'message' => 'Ticket Berhasil Diedit',
                'data' => $requestTicket
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Ticket gagal Diedit',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *  @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $getTicket = Ticket::where('id', $id)->first();
        $ticket = Ticket::where('id', $id)->delete();

        if ($ticket) {
            return response([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 200);
        } else {
            return response([
                'status' => 'failed',
                'message' => 'Data gagal dihapus'
            ], 404);
        }

    }
}