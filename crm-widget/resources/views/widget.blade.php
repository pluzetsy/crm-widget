<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Widget</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/widget.css') }}">
</head>
<body>
<div class="widget-container">
    <div class="card">
        <h1 class="card-title">Feedback form</h1>
        <p class="card-subtitle">
            Leave your request and our manager will contact you.
        </p>

        <form id="ticket-form" enctype="multipart/form-data" novalidate>
            <div class="field">
                <label class="label" for="name">Name *</label>
                <input class="input" type="text" id="name" name="name" required>
                <div class="error-text" data-error-for="name"></div>
            </div>

            <div class="field">
                <label class="label" for="phone">Phone (E.164)</label>
                <input class="input" type="tel" id="phone" name="phone" placeholder="+48123123123">
                <div class="error-text" data-error-for="phone"></div>
                <div class="help-text">Format: +[country code][number], e.g. +48123123123</div>
            </div>

            <div class="field">
                <label class="label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email">
                <div class="error-text" data-error-for="email"></div>
            </div>

            <div class="field">
                <label class="label" for="subject">Subject *</label>
                <input class="input" type="text" id="subject" name="subject" required>
                <div class="error-text" data-error-for="subject"></div>
            </div>

            <div class="field">
                <label class="label" for="text">Message *</label>
                <textarea class="textarea" id="text" name="text" required></textarea>
                <div class="error-text" data-error-for="text"></div>
            </div>

            <div class="field">
                <span class="label">Attachments</span>
                <input class="files-input" type="file" name="attachments[]" multiple>
                <div class="error-text" data-error-for="attachments"></div>
                <div class="help-text">Up to 10 MB per file.</div>
            </div>

            <button type="submit" class="button" id="submit-btn">Send request</button>

            <div id="status-message" class="status-message"></div>
        </form>
    </div>
</div>

<script>
    window.widgetConfig = {
        apiUrl: "{{ url('/api/tickets') }}",
        csrfToken: "{{ csrf_token() }}",
    };
</script>
<script src="{{ asset('js/widget.js') }}"></script>
</body>
</html>
