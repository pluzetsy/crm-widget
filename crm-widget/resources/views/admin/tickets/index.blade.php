@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Tickets – Admin')

@section('content')
    <h1>Tickets</h1>

    <form method="get" class="filters">
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">Any</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Email</label>
            <input type="text" name="email" value="{{ $filters['email'] ?? '' }}">
        </div>
        <div>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $filters['phone'] ?? '' }}">
        </div>
        <div>
            <label>Date from</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
        </div>
        <div>
            <label>Date to</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
        </div>
        <div style="grid-column: span 5; text-align: right;">
            <button type="submit">Apply filters</button>
        </div>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Created at</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $ticket->customer->name ?? '—' }}</td>
                <td>{{ $ticket->customer->email ?? '—' }}</td>
                <td>{{ $ticket->customer->phone ?? '—' }}</td>
                <td>{{ Str::limit($ticket->subject, 40) }}</td>
                <td>
                    <span class="badge badge-{{ $ticket->status->value }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status->value)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.tickets.show', $ticket) }}">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No tickets found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @if($tickets->hasPages())
        <div class="pagination-wrapper">
            {{ $tickets->links() }}
        </div>
    @endif
@endsection
