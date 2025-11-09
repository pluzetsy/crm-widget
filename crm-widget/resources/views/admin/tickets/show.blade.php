@extends('layouts.admin')

@section('title', 'Ticket #'.$ticket->id)

@section('content')
    <p><a href="{{ route('admin.tickets.index') }}">← Back to tickets</a></p>

    <h1>Ticket #{{ $ticket->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <dl>
        <dt>Status</dt>
        <dd>
            <span class="badge badge-{{ $ticket->status->value }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status->value)) }}
            </span>
        </dd>

        <dt>Created at</dt>
        <dd>{{ $ticket->created_at->format('Y-m-d H:i') }}</dd>

        <dt>Handled at</dt>
        <dd>{{ $ticket->handled_at?->format('Y-m-d H:i') ?? '—' }}</dd>

        <dt>Customer</dt>
        <dd>{{ $ticket->customer->name ?? '—' }}</dd>

        <dt>Email</dt>
        <dd>{{ $ticket->customer->email ?? '—' }}</dd>

        <dt>Phone</dt>
        <dd>{{ $ticket->customer->phone ?? '—' }}</dd>

        <dt>Subject</dt>
        <dd>{{ $ticket->subject }}</dd>
    </dl>

    <h3>Message</h3>
    <textarea readonly>{{ $ticket->text }}</textarea>

    <div class="attachments">
        <h3>Attachments</h3>
        @if($ticket->media->isEmpty())
            <p>No attachments.</p>
        @else
            <ul>
                @foreach($ticket->media as $file)
                    <li>
                        <a href="{{ $file->getUrl() }}" target="_blank" rel="noopener">
                            {{ $file->file_name }} ({{ number_format($file->size / 1024, 1) }} KB)
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <h3>Change status</h3>
    <form method="post" action="{{ route('admin.tickets.update-status', $ticket) }}">
        @csrf
        @method('PATCH')

        <select name="status">
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($ticket->status->value === $status)>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>

        <button type="submit">Save</button>
    </form>
@endsection
