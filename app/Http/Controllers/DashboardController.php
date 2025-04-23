<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConcertModel;
use App\Models\TicketModel;
use App\Models\View\TicketViewModel;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard.dashboard');
    }

    public function getData(Request $request){
        return response()->json([
            'success' => true,
            'data' => [
                'concert' => $this->concert(),
                'sold' => $this->sold(),
                'book' => $this->book()['count'],
            ],
        ], 200);
    }

    public function getDatatable(Request $request){
        return $this->book()['data'];
    }

    function concert(){
        $count = ConcertModel::count();
        return $count;
    }

    function sold(){
        $count = ConcertModel::where('concert_remaining_quota', 0)->count();
        return $count;
    }

    function book(){
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $startDate = $startOfWeek->format('Y-m-d');
        $endDate = $endOfWeek->format('Y-m-d');

        $table = TicketViewModel::query()->whereBetween('ticket_created_at', [$startDate, $endDate])->orderBy('ticket_created_at', 'desc');

        return [
            'count' => TicketViewModel::whereBetween('created_at', [$startDate, $endDate])->count(),
            'data' => DataTables::of($table)->make(true),
        ]; 
    }
}
