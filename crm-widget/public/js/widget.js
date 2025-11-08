const form = document.getElementById('ticket-form');
const submitBtn = document.getElementById('submit-btn');
const statusMessage = document.getElementById('status-message');

function setStatus(message, type = 'success') {
    statusMessage.textContent = message;
    statusMessage.className = 'status-message ' +
        (type === 'success' ? 'status-success' : 'status-error');
}

function clearStatus() {
    statusMessage.textContent = '';
    statusMessage.className = 'status-message';
}

function clearErrors() {
    document.querySelectorAll('[data-error-for]').forEach(el => {
        el.textContent = '';
    });
}

function showValidationErrors(errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const el = document.querySelector('[data-error-for="' + field + '"]');
        if (el) {
            el.textContent = messages.join(' ');
        }
    });
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    clearStatus();
    clearErrors();

    const formData = new FormData(form);

    submitBtn.disabled = true;

    try {
        const response = await fetch('/api/tickets', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json().catch(() => ({}));

        if (response.status === 422 && data.errors) {
            showValidationErrors(data.errors);
            setStatus('Please fix the errors and try again.', 'error');
        } else if (!response.ok) {
            setStatus(data.message || 'Unexpected error occurred.', 'error');
        } else {
            setStatus(data.message || 'Ticket created successfully.');
            form.reset();
        }
    } catch (e) {
        setStatus('Network error. Please try again later.', 'error');
    } finally {
        submitBtn.disabled = false;
    }
});
