<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\View\ConcertViewModel;
use App\Models\View\TicketViewModel;
use App\Models\TicketModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ConcertModel;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketUserController extends Controller
{
    public function index(){
        return view('user.tickets.index');
    }

    public function getData(Request $request){
        $data = ConcertViewModel::query()
                ->when($request->category_id, function ($query, $category_id) {
                    return $query->where('concert_category_id', $category_id);
                })
                ->when($request->start, function ($query, $start) {
                    return $query->whereDate('concert_date', '>=', $start);
                })
                ->when($request->end, function ($query, $end) {
                    return $query->whereDate('concert_date', '<=', $end);
                })
                ->orderBy('concert_date', 'desc')
                ->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function checkTicket(Request $request){
        $where = [
            'ticket_concert_id' => $request->concert_id,
            'ticket_user_id' => $request->user_id,
        ];

        $data = TicketViewModel::where($where)->first();

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => ConcertModel::find($request->concert_id),
            ]);
        }
    }

    public function process(Request $request){
        if($request->command == "book") $ops = $this->book($request);
        else if($request->command == "redeem") $ops = $this->redeem($request);
        return response()->json($ops);
    }

    function book(Request $request){
        
        $concert = ConcertModel::find($request->concert_id);
        if($concert->concert_remaining_quota == 0){
            return [
                'success' => false,
                'message' => 'Ticket Full Booked!',
            ];
        }else{
            $count_ticket = TicketViewModel::where('ticket_user_id', Auth::user()->user_id)->count();
            if($count_ticket == 5){
                return [
                    'success' => false,
                    'message' => 'you have reached the maximum number of ticket orders',
                ];
            }

            $data_concert['concert_remaining_quota'] = $concert->concert_remaining_quota - 1;
            $concert->update($data_concert);
            
            $data['ticket_concert_id'] = $request->concert_id;
            $data['ticket_user_id'] = Auth::user()->user_id;
            $data['ticket_code'] = Str::upper(Str::random(5));
            $data['ticket_redeem'] = 0;
    
            $ticket = TicketModel::create($data);
    
            return [
                'success' => true,
                'message' => 'Ticket Booked!',
                'data' => $ticket,
            ];
        }
        
    }

    public function redeem(Request $request)
    {
        $ticket = TicketViewModel::where([
            'ticket_concert_id' => $request->concert_id,
            'ticket_user_id'    => $request->user_id,
        ])->firstOrFail();

        $data = [
            'ticket' => $ticket,
        ];

        $pdf = Pdf::loadView('user.tickets.pdf.ticket', $data)
                ->setPaper('A5');

        $filename = 'ticket_' . now()->format('Ymd_His') . '_' . Str::random(5) . '.pdf';

        $saveDir = public_path('uploads/tickets');
        if (!File::exists($saveDir)) {
            File::makeDirectory($saveDir, 0755, true);
        }
        $pdf->save("{$saveDir}/{$filename}");

        $ticket_table = TicketModel::find($ticket->ticket_id);

        $ticket_table->update([
            'ticket_redeem' => 1,
            'ticket_file'   => $filename,
        ]);

        return [
            'success' => true,
            'message' => 'Ticket Redeemed!',
            'data'    => $ticket,
        ];
    }

    public function history(){
        return view('user.tickets.history');
    }

    public function getDataHistory(Request $request){
        $data = TicketViewModel::query()
                ->where('ticket_user_id', Auth::user()->user_id)
                ->when($request->category_id, function ($query, $category_id) {
                    return $query->where('concert_category_id', $category_id);
                })
                ->when($request->start, function ($query, $start) {
                    return $query->whereDate('concert_date', '>=', $start);
                })
                ->when($request->end, function ($query, $end) {
                    return $query->whereDate('concert_date', '<=', $end);
                })
                ->orderBy('ticket_created_at', 'desc')
                ->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function download(Request $request)
    {
        $ticket = TicketModel::where('ticket_concert_id', $request->concert_id)
                        ->where('ticket_user_id', $request->user_id)
                        ->firstOrFail();

        $path = public_path('uploads/tickets/' . $ticket->ticket_file);
        if (File::exists($path)) {
            return response()->download($path);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File not found.'
            ], 404);
        }
    }
}
