<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Apply middleware to protect routes except 'index' and 'show'.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'kategori' => 'required|string|max:255', // Tidak lagi memeriksa tabel categories
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $input = $request->all();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $input['gambar'] = $request->file('gambar')->store('uploads', 'public'); // Store file in public/uploads
        }

        // Create a new product
        $product = Product::create($input);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'kategori' => 'nullable|string|max:255', // Tidak lagi memeriksa tabel categories
            'nama_produk' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $input = $request->all();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete the old image
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }

            $input['gambar'] = $request->file('gambar')->store('uploads', 'public');
        }

        // Update the product
        $product->update($input);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // Delete the image if it exists
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        // Delete the product
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ]);
    }
}
