<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Service\ContatoService;
use Illuminate\Http\JsonResponse;

class ContactControllerApi extends Controller
{
    private ContatoService $contatoService;

    public function __construct(ContatoService $contatoService)
    {
        $this->contatoService = $contatoService;
    }

    public function index(): JsonResponse
    {
        return $this->contatoService->index();
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        return $this->contatoService->store($request->validated());
    }

    public function show($id): JsonResponse
    {
        return $this->contatoService->show($id);
    }

    public function update(UpdateContactRequest $request, $id): JsonResponse
    {
        return $this->contatoService->update($request->validated(), $id);
    }

    public function destroy($id): JsonResponse
    {
        return $this->contatoService->destroy($id);
    }
}
