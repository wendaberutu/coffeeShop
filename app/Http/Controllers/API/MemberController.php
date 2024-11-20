<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    public function index()
    {
        $members = Member::all();

        return response()->json([
            'data' => $members
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_whatshap' => 'required|string|digits_between:10,15|unique:members,no_whatshap',
            'umur' => 'required|integer|min:1|max:150', // Validasi umur harus diisi
            'email' => 'required|email|max:255|unique:members,email',
            'tanggal_lahir' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = Member::create($request->all());

        return response()->json([
            'message' => 'Member created successfully',
            'data' => $member
        ], 201);
    }

    public function show(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        return response()->json(['data' => $member]);
    }

    public function update(Request $request, string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_member' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string|max:500',
            'no_whatshap' => 'sometimes|string|digits_between:10,15|unique:members,no_whatshap,' . $id,
            'umur' => 'sometimes|integer|min:1|max:150', // Validasi umur harus tetap sesuai
            'email' => 'sometimes|email|max:255|unique:members,email,' . $id,
            'tanggal_lahir' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member->update($request->all());

        return response()->json([
            'message' => 'Member updated successfully',
            'data' => $member
        ]);
    }

    public function destroy(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $member->delete();

        return response()->json(['message' => 'Member deleted successfully']);
    }
}
