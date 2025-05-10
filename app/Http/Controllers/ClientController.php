<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->clients()->latest()->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client = $request->user()->clients()->create($data);

        return response()->json($client, 201);
    }

    public function show(Client $client)
    {
        $this->authorizeClient($client);
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($data);

        return response()->json($client);
    }

    public function destroy(Client $client)
    {
        $this->authorizeClient($client);
        $client->delete();

        return response()->json(['message' => 'Client deleted']);
    }

    private function authorizeClient(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
