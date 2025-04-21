<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\View\ConcertViewModel;
use App\Models\ConcertModel;
use File;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

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

        $concert = ConcertModel::find($validated['category_id']);

        $data['concert_end_status']       = empty($data['concert_end']) ? 1 : 0;
        $data['concert_remaining_quota']  = $data['concert_quota'];

        if ($request->filled('concert_banner')) {
            $image = $request->input('concert_banner');
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'banner_' . Carbon::now()->format('Y-m-d_His') . '.jpg';
            File::put(public_path('uploads/concerts/') . $imageName, base64_decode($image));
            $request->concert_banner = $imageName;
            if ($concert->concert_banner && File::exists(public_path('uploads/concerts/') . $concert->concert_banner)) {
                File::delete(public_path('uploads/concerts/') . $concert->concert_banner);
            }
        }

        $concert->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Concert updated Succesfully!',
            'data' => $concert,
        ], 200);
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = ConcertViewModel::query()->orderBy('concert_date', 'desc');
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
}
