<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(){
        return view('admin.categories.index');
    }

    public function save(Request $request){
        if(isset($request->category_id)) $this->update($request);
        else $this->store($request);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = CategoryModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category Stored Succesfully!',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:category,category_id',
            'category_name' => 'required|string|max:255',
        ]);

        $category = CategoryModel::find($validated['category_id']);
        $category->update([
            'category_name' => $validated['category_name'],
        ]);

        return response()->json([
            'message' => 'Category Updated Succesfully!',
            'data' => $category,
        ]);
    }

    public function destroy(Request $request)
    {
        $category = CategoryModel::findOrFail($request['category_id']);
        $category->delete();

        return response()->json([
            'message' => 'Category Deleted Succesfully',
        ]);
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = CategoryModel::query(); 
            return DataTables::of($data)->make(true);
        }
    }

    public function read(Request $request){
        $data = CategoryModel::find($request->category_id);

        if ($data) {
            return response()->json([
                'data' => $data
            ]);
        }

        return response()->json([
            'error' => 'Data not found'
        ], 404);
    }
}
