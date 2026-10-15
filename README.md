# Forum d'Orientation Ben M'sik — QR Code Check-in System

A robust, self-hosted, and production-ready Laravel application designed for managing participant registration, automated PDF convocation generation with embedded QR codes, and real-time mobile check-ins over a local network (LAN) during live events.

Developed for the 3rd edition of the Ben M'sik Student Orientation Forum.

---

## Key Features

- Automated CSV/Google Form Import: Seamlessly imports participant data and registers them into the database.
- Dynamic QR Code Generation: Generates high-performance PNG QR codes using bacon/bacon-qr-code and GD backend, avoiding external dependencies like Imagick.
- Instant PDF Convocations: Generates professional PDF tickets (via DomPDF) containing unique QR codes for each participant.
- LAN Mobile Scanning: Fully operational on local wireless networks (0.0.0.0), allowing organizers to use their smartphone cameras to scan attendee tickets without requiring an active internet connection.
- Real-Time Dashboard: Live counters tracking total pre-registrations, checked-in participants, and pending attendees with instant status toggling.

---

## Tech Stack

- Framework: Laravel 12 (PHP 8.2+)
- Database: MySQL / SQLite
- PDF Generation: barryvdh/laravel-dompdf
- QR Code Engine: bacon/bacon-qr-code
- Frontend: Bootstrap 5, Blade Templates

---

## Installation & Local Setup

1. Clone the repository:
   git clone https://github.com/YOUR_USERNAME/forum-benmsik-checkin.git
   cd forum-benmsik-checkin

2. Install dependencies:
   composer install
   npm install && npm run build

3. Configure environment variables:
   Copy the example environment file and configure your database:
   cp .env.example .env
   php artisan key:generate
   Update your database credentials and set your APP_URL in .env:
   APP_URL=http://YOUR_LOCAL_IP:8000

4. Run database migrations:
   php artisan migrate:fresh

5. Run the local network server (for mobile scanning):
   php artisan serve --host=0.0.0.0 --port=8000

---

## Event Day Workflow

1. Connect your laptop and scanning smartphones to the same local Wi-Fi network or mobile hotspot.
2. Run the server binding to all network interfaces (--host=0.0.0.0).
3. Organizers open their smartphone camera app, scan the participant's QR code on their convocation PDF, and instantly validate their check-in on the live dashboard.

---

## Author

- Ezzoukhry Mouhssine
