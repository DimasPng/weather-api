```markdown
# 🌦️ Weather Alert System

---

## 🛠️ Getting Started

### 📥 Clone the Repository

git clone https://github.com/DimasPng/weather-api
cd weather-api
```

---

### ⚙️ Environment Configuration

1. Copy the example environment file:

```bash
cp .env.example .env
```

2. Set your OpenWeather API key in the `.env` file:

```
WEATHER_API_KEY=your_openweather_api_key
```

---

## 🐳 Docker & Make Setup

This project uses Docker and requires GNU Make for simplified commands.

To initialize everything, just run:

```bash
make init
```

This command will:

- Build Docker containers
- Install PHP dependencies
- Run database migrations and seeders

---

## 🌍 Access the Application

Once initialized, access the app via:

- Web Interface: [http://localhost](http://localhost)
- Mail Viewer (MailHog): [http://localhost:8082](http://localhost:8082)

---

## 📂 Project Structure

- `routes/api.php` – API routes
- `routes/web.php` – Web interface for subscriptions
- `routes/console.php` – Scheduled tasks and artisan commands
- `app/Http/Controllers` – Handles incoming HTTP requests
- `app/Mail` – Mailable classes for sending emails
- `app/Jobs` – Queued jobs for email dispatch
- `app/Services` – Core business logic (weather fetching, formatting)
- `app/Repositories` – Data management for subscriptions
- `resources/js/components` -  Vue components for the subscription form
- `resources/views/emails` – Email templates using Blade

---
```
