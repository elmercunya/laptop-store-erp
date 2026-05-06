<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Validation\Rule;


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
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required',
            'document_number' => 'required|unique:clients,document_number',
            'name' => 'required',
        ]);

        if($request->document_type == 'DNI'){
            $request->validate(['document_number' => 'digits:8']);
        }elseif($request->document_type == 'RUC'){
            $request->validate(['document_number' => 'digits:11|starts_with:10,20']);
        }elseif($request->document_type == 'CE'){
            $request->validate(['document_number' => 'digits:9']);
        }

        Client::create($request->all());

        return redirect()->route('clients.index')->with('message','Cliente creado exitosamente');;
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
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'document_type' => 'required',
            'document_number' => [
                'required',
                Rule::unique('clients')->ignore($id),
            ],
            'name' => 'required',
        ]);

        if($request->document_type == 'DNI'){
            $request->validate(['document_number' => 'digits:8']);
        }elseif($request->document_type == 'RUC'){
            $request->validate(['document_number' => 'digits:11|starts_with:10,20']);
        }elseif($request->document_type == 'CE'){
            $request->validate(['document_number' => 'digits:9']);
        }

        $client = Client::find($id);

        $client->update($request->all());

        return redirect()->route('clients.index');
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
