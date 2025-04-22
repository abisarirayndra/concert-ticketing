<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketModel;
use Illuminate\Support\Facades\Auth;
use App\Models\ConcertModel;
use Carbon\Carbon;
use App\Models\View\ConcertViewModel;

class DashboardUserController extends Controller
{
    public function index(){
        return view('user.dashboard.dashboard');
    }

    public function getData(Request $request){
        $booked = $this->booked();
        $tickets = $this->tickets();
        $others = $this->others();
        $week = $this->week();
        return response()->json([
            'success' => true,
            'data' => [
                'booked' => $booked,
                'tickets' => $tickets,
                'others' => $others,
                'week' => $week['count'],
                'week_data' => $week['data'],
            ],
        ], 200);
    }

    function booked(){
        $count = TicketModel::where('ticket_user_id', Auth::user()->user_id)->count();
        return $count;
    }

    function tickets(){
        $where = [
            'ticket_user_id' => Auth::user()->user_id,
            'ticket_redeem' => 1,
        ];
        $count = TicketModel::where($where)->count();
        return $count;
    }

    function others(){
        $count_concert = ConcertModel::count();
        $count = $this->booked();
        $total = $count_concert - $count;
        return $total;
    }

    function week(){
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $startDate = $startOfWeek->format('Y-m-d');
        $endDate = $endOfWeek->format('Y-m-d');

        return [
            'count' => ConcertModel::whereBetween('concert_date', [$startOfWeek, $endOfWeek])->count(),
            'data' => ConcertViewModel::whereBetween('concert_date', [$startOfWeek, $endOfWeek])->get(),
        ];
    }


}
