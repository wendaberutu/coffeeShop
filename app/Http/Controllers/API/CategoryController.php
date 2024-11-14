<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Make validator
    $validator = Validator::make($request->all(), [
        'nama_kategori' => 'required',
        'deskripsi' => 'required',
        'gambar' => 'required'
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(
            $validator->errors(),
            422
        );
    }

    // Create a new category directly from the request data
    $category = Category::create($request->all());

    // Return a success response with the created category data
    return response()->json([
        'data' => $category
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        // Check if the category exists
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Update the category with the request data
        $category->update($request->all());

        // Return success response with updated category data
        return response()->json([
            'message' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to delete the category by its ID
        $deleted = Category::destroy($id);

        // Check if the deletion was successful
        if ($deleted) {
            return response()->json([
                'message' => 'Category deleted successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
    }
}
