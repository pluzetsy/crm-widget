## CRM Widget

A lightweight CRM system for receiving and processing support requests from a website via a universal feedback widget.

## Technologies

- PHP 8.4
- Laravel 12
- PostgreSQL
- Docker / docker-compose
- Blade - for widget and admin panel
- Spatie Media Library - file storage
- Spatie Laravel Permission - roles and permissions
- Laravel Breeze (Blade) - authentication
- Vite - CSS/JS build for widget and admin panel

---

## Getting Started

1. Clone the repository
bash
git clone <repository-url> crm-widget
cd crm-widget

2. Configure environment
bash
cp .env.example .env
Then edit .env if necessary:

env
APP_NAME="CRM Widget"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=crm_widget
DB_USERNAME=crm_widget
DB_PASSWORD=secret

3. Start Docker containers
bash
docker-compose up -d --build

4. Install dependencies and run migrations
bash
docker exec -it crm-widget-app bash

composer install
php artisan key:generate
php artisan migrate --seed

5. Build frontend (CSS/JS)
bash
npm install
npm run build

After that, the application will be available at:

Widget: http://localhost:8000/widget

Admin panel: http://localhost:8000/admin/tickets

API: http://localhost:8000/api/...

---

## Test Data
Seeders create a base dataset including:

1 admin account:
email: admin@example.com
password: password

2 manager accounts
email: test@example.org
password: password

Several customers and demo tickets.

---

## Widget Integration (iframe)
html
<iframe
    src="https://your-domain.com/widget"
    title="Feedback widget"
    style="border:0; width:100%; max-width:420px; height:520px;"
    loading="lazy"
></iframe>

---

## API:
POST /api/tickets

Request (form-data):
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+48123123123",
  "subject": "Example subject",
  "text": "This is test ticket text.",
  "attachments[]": [files]
}

Response (json):
{
    "data": {
        "id": 1,
        "subject": "Example subject",
        "text": "This is test ticket text.",
        "status": "new",
        "handled_at": null,
        "created_at": "2025-11-09T19:29:58+00:00",
        "updated_at": "2025-11-09T19:29:58+00:00",
        "customer": {
            "id": 19,
            "name": "John Doe",
            "email": "john2@example.com",
            "phone": "+48123123125",
            "created_at": "2025-11-09T19:29:58+00:00",
            "updated_at": "2025-11-09T19:29:58+00:00"
        },
        "manager": null,
        "attachments": [
            {
                "id": 1,
                "name": "test1.txt",
                "url": "http://localhost/storage/1/test1.txt"
            },
            {
                "id": 2,
                "name": "test2.txt",
                "url": "http://localhost/storage/2/test2.txt"
            }
        ]
    },
    "message": "Ticket created successfully."
}

GET /api/tickets/statistics

Response (json):
{
    "data": {
        "periods": {
            "day": {
                "new": 3,
                "in_progress": 0,
                "done": 0,
                "total": 3
            },
            "week": {
                "new": 28,
                "in_progress": 14,
                "done": 17,
                "total": 59
            },
            "month": {
                "new": 28,
                "in_progress": 14,
                "done": 17,
                "total": 59
            }
        },
        "summary": {
            "total_today": 3,
            "total_week": 59,
            "total_month": 59
        }
    },
    "message": "Ticket statistics loaded successfully"
}


