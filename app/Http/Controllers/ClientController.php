<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;

class ClientController extends Controller
{
    // Validation commune
    protected function validateClient(Request $request, $clientId = null): array
    {
        return $request->validate([
            'raison_sociale' => ['required', 'string', 'max:120'],
            'contact' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'adresse' => ['nullable', 'string'],
            'type_client' => ['required', 'in:industriel,commercial,particulier'],
        ]);
    }

    // Liste des clients
    public function index(Request $request)
    {
        $query = Client::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('raison_sociale')->paginate(3)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    // Formulaire de création
    public function create()
    {
        return view('clients.form');
    }

    // Enregistrement
    public function store(Request $request)
    {
        $validated = $this->validateClient($request);

        try {
            Client::create($validated);
            return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout : ' . $e->getMessage()]);
        }
    }
public function ajaxStore(Request $request)
{
    $validated = $this->validateClient($request);

    $client = Client::create($validated);

    return response()->json([
        'success' => true,
        'client' => $client
    ]);
}

    // Formulaire d’édition
    public function edit(Client $client)
    {
        return view('clients.form', compact('client'));
    }

    // Mise à jour
    public function update(Request $request, Client $client)
    {
        $validated = $this->validateClient($request, $client->id);

        try {
            $client->update($validated);
            return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    // Suppression
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
