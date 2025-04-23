<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\View\ConcertViewModel;
use App\Models\ConcertModel;
use File;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\View\TicketViewModel;

class ConcertController extends Controller
{
    public function index(){
        return view('admin.concerts.index');
    }

    public function save(Request $request){
        if(isset($request->concert_id)) $this->update($request);
        else $this->store($request);
    }

    public function store(Request $request){
        $data = $request->validate([
            'concert_band'            => 'required|string|max:255',
            'concert_date'            => 'required|date',
            'concert_start'           => 'required',
            'concert_end'             => 'nullable',
            'concert_location'        => 'required|string|max:255',
            'concert_price'           => 'required|numeric|min:0',
            'concert_quota'           => 'required|integer|min:1',
            'concert_category_id'     => 'required|exists:category,category_id',
            'concert_banner'          => 'nullable', // ini base64 string
        ]);

        $data['concert_end_status']       = empty($data['concert_end']) ? 1 : 0;
        $data['concert_remaining_quota']  = $data['concert_quota'];

        if (!empty($data['concert_banner'])) {
            $base64 = $data['concert_banner'];
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
            $binary = base64_decode($base64);
            $imageName = 'banner_' . Carbon::now()->format('YmdHis') . '.jpg';
            $saveDir = public_path('uploads/concerts');
            if (!File::exists($saveDir)) {
                File::makeDirectory($saveDir, 0755, true);
            }
            File::put(public_path("uploads/concerts/{$imageName}"), $binary);
            $data['concert_banner'] = $imageName;
        }

        $concert = ConcertModel::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Concert Stored Succesfully!',
            'data' => $concert,
        ], 201);
    }

    public function update(Request $request){
        $data = $request->validate([
            'concert_id'              => 'required|exists:concert,concert_id',
            'concert_band'            => 'required|string|max:255',
            'concert_date'            => 'required|date',
            'concert_start'           => 'required',
            'concert_end'             => 'nullable',
            'concert_location'        => 'required|string|max:255',
            'concert_price'           => 'required|numeric|min:0',
            'concert_quota'           => 'required|integer|min:1',
            'concert_category_id'     => 'required|exists:category,category_id',
            'concert_banner'          => 'nullable', // ini base64 string
        ]);

        $concert = ConcertModel::find($data['concert_id']);

        $data['concert_remaining_quota']  = $data['concert_quota'];

        if (!isset($data['concert_end']) || !$data['concert_end']) {
            $data['concert_end'] = null;
            $data['concert_end_status'] = 1;
        } else {
            $data['concert_end_status'] = 0;
        }

        if (!empty($data['concert_banner'])) {
            $image = $data['concert_banner'];
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'banner_' . Carbon::now()->format('Y-m-d_His') . '.jpg';
            File::put(public_path('uploads/concerts/') . $imageName, base64_decode($image));
            $data['concert_banner'] = $imageName;
            if ($concert->concert_banner && File::exists(public_path('uploads/concerts/') . $concert->concert_banner)) {
                File::delete(public_path('uploads/concerts/') . $concert->concert_banner);
            }
        }else{
            unset($data['concert_banner']);
        }

        $concert->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Concert updated Succesfully!',
            'data' => $concert,
        ], 200);
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = ConcertViewModel::query()->orderBy('concert_date', 'desc');
            if (!empty($request->category_id)) {
                $data->where('concert_category_id', $request->category_id);
            }
        
            if (!empty($request->start_date)) {
                $data->whereDate('concert_date', '>=', $request->start_date);
            }
        
            if (!empty($request->end_date)) {
                $data->whereDate('concert_date', '<=', $request->end_date);
            }
            return DataTables::of($data)->make(true);
        }
    }

    public function read(Request $request){
        $data = ConcertViewModel::find($request->concert_id);

        if ($data) {
            return response()->json([
                'data' => $data
            ]);
        }

        return response()->json([
            'error' => 'Data not found'
        ], 404);
    }

    public function destroy(Request $request)
    {
        $concert = ConcertModel::findOrFail($request['concert_id']);
        if ($concert->concert_banner && File::exists(public_path('uploads/concerts/') . $concert->concert_banner)) {
            File::delete(public_path('uploads/concerts/') . $concert->concert_banner);
        }
        $concert->delete();

        return response()->json([
            'message' => 'Concert Deleted Succesfully',
        ]);
    }

    public function getDataTicket(Request $request){
        $data = TicketViewModel::query()->where('ticket_concert_id', $request->concert_id)->orderBy('ticket_created_at', 'desc');
        return DataTables::of($data)->make(true);
    }
}
