<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $query = Client::query();

        if($busqueda) {
            $query->where('document_number', 'LIKE', '%'.$busqueda.'%');
        }

        $clients = $query->paginate(15);


        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = ['DNI', 'RUC', 'CE'];

        return view('clients.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        Client::create($data);

        return redirect()->route('clients.index')->with('message','Cliente creado exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::find($id);

        $types = ['DNI', 'RUC', 'CE'];

        return view('clients.edit', compact('client', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        
        $data = $request->validated();

        $client->update($data);

        return redirect()->route('clients.index')->with('message', 'Cliente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);

        $client->delete();

        return redirect()->route('clients.index')->with('message', 'Cliente eliminado exitosamente');
    }
}
