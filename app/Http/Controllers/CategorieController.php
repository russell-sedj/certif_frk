<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Categorie::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request)
    {
        $categorie= new Categorie;
        $categorie->nom=$request->nom;
        $categorie->description=$request->description;
        $categorie->save();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        //return view('categories.show',compact('categorie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $category)
    {

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, Categorie $category)
    {
        
        $category=Categorie::find($category->id);
        $category->nom=$request->nom;
        $category->description=$request->description;
        $category->save();
      return redirect()->route('categories.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $category)
    {          
        if($category->formations->count()>0){
        return redirect()->back()->with('LockdeleteError', 'Vous ne pouvez effectuer cette action car cette catégorie est déjà liée à des formations.');
            }
        $category->delete();
        return redirect()->route('categories.index');

    }
}
