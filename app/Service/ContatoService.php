<?php

namespace App\Service;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContatoService
{
    public function index(): JsonResponse
    {
        $contacts = Contact::all();
        return response()->json($contacts, 200);
    }

    public function store(array $validatedData): JsonResponse
    {
        $contact = Contact::create($validatedData);
        return response()->json($contact, 201);
    }

    public function show(int $id): JsonResponse
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json($contact, 200);
    }

    public function update(array $validatedData, int $id): JsonResponse
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->update($validatedData);
        return response()->json($contact, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->delete();
        return response()->json(['message' => 'Contact deleted successfully'], 200);
    }
}
