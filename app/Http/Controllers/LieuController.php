<?php

namespace App\Http\Controllers;

use App\Models\Lieu;
use App\Http\Requests\StoreLieuRequest;
use App\Http\Requests\UpdateLieuRequest;

class LieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lieux = Lieu::all();
        return view('lieus.index', compact('lieux'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lieus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLieuRequest $request)
    {
        $lieu = new Lieu;
        $lieu->ville = $request->ville;
        $lieu->adresse = $request->adresse;
        $lieu->save();

        return redirect()->route('lieus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lieu $lieu)
    {
        // return view('lieus.show', compact('lieu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lieu $lieu)
    {
        return view('lieus.edit', compact('lieu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLieuRequest $request, Lieu $lieu)
    {
        $lieu = Lieu::find($lieu->id);
        $lieu->ville = $request->ville;
        $lieu->adresse = $request->adresse;
        $lieu->save();
        
        return redirect()->route('lieus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lieu $lieu)
    {
        $lieu->delete();

        return redirect()->route('lieus.index');
    }
}
