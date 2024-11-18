<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Apply middleware to protect routes except 'index'.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
           
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        // Create the Order
        $order = Order::create($input);

        // Create Order Details using a for loop
        for ($i = 0; $i < count($input['id_produk']); $i++) {
            OrderDetail::create([
                'id_order' => $order['id'],
                'id_produk' => $input['id_produk'][$i],
                'jumlah' => $input['jumlah'][$i],
                'total' => $input['total'][$i],
            ]);
        }

        // Return a success response with the created Order data
        return response()->json([
            'data' => $order
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the Order by ID
        $order = Order::find($id);

        // Check if the Order exists
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
            
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        // Update the Order
        $order->update($input);

        // Delete existing Order Details
        OrderDetail::where('id_order', $order['id'])->delete();

        // Recreate Order Details using a for loop
        for ($i = 0; $i < count($input['id_produk']); $i++) {
            OrderDetail::create([
                'id_order' => $order['id'],
                'id_produk' => $input['id_produk'][$i],
                'jumlah' => $input['jumlah'][$i],
                'total' => $input['total'][$i],
            ]);
        }

        // Return success response with updated Order data
        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Retrieve the Order by its ID
        $order = Order::find($id);

        // Return a 404 response if Order not found
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Delete related Order Details
        OrderDetail::where('id_order', $order['id'])->delete();

        // Delete the Order
        $order->delete();

        // Return a success response
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
