<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketIndexRequest;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use App\TicketStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $service)
    {
    }

    public function index(TicketIndexRequest $request): View
    {
        $filters = $request->filters();
        $tickets = $this->service->paginateWithFilters($filters);
        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'filters' => $filters,
            'statuses' => TicketStatus::values(),
        ]);
    }

    public function show(Ticket $ticket): View
    {
        return view('admin.tickets.show', [
            'ticket' => $this->service->getWithRelations($ticket),
            'statuses' => TicketStatus::values(),
        ]);
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->service->updateStatus($ticket, $request->input('status'));
        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully.');
    }
}
