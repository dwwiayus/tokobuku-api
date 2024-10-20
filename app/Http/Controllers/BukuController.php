<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Buku :: all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);
    
        // Menyimpan data ke dalam database
        $buku = Buku::create($validatedData);
    
        // Mengembalikan respons dengan data buku yang baru dibuat
        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Buku::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'sometimes|required|string',
            'penulis' => 'sometimes|required|string',
            'harga' => 'sometimes|required|numeric',
            'stok' => 'sometimes|required|integer',
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Buku::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }

    /**
 * Display a listing of books based on the kategori_id.
 */
public function getByKategori($kategori_id)
{
    $bukus = Buku::where('kategori_id', $kategori_id)->get();

    if ($bukus->isEmpty()) {
        return response()->json([
            'message' => 'No books found for the given category'
        ], 404);
    }

    return response()->json($bukus, 200);
}
}


