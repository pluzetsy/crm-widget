<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $service)
    {
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->service->create($request->validated());
        return TicketResource::make($ticket)
            ->additional([
                'message' => 'Ticket created successfully.',
            ])
            ->response()
            ->setStatusCode(201);
    }
}
