<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\File;
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
            'gambar' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        // Handle image upload if exists
        if ($request->has('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension(); // Corrected concatenation
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        // Create a new category directly from the request data
        $category = Category::create($input);

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
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([
            'data' => $category
        ]);
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

        // Make validator
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            //'gambar' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();


        // Handle image upload if exists
        if ($request->has('gambar')) {
            File::delete('uploads/' . $category->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension(); // Corrected concatenation
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        } else {
            unset($input['gambar']);
        }
        // Update the category with the request data
        $category->update($input);

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
        // Retrieve the category by its ID
        $category = Category::find($id);

        // Return a 404 response if category not found
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Delete the image if it exists
        File::delete('uploads/' . $category->gambar);

        // Delete the category
        $category->delete();

        // Return a success response
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
