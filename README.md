# Gestore Collezioni

Applicazione Laravel 11 per gestire elenchi di oggetti distribuiti in luoghi fisici, con etichettatura e ricerca.

## Funzionalità

- **Autenticazione** — registrazione, login, gestione profilo (Laravel Breeze)
- **Collezioni** — raggruppa gli oggetti per categoria (es. Libri, Utensili, Giochi)
- **Oggetti** — ogni oggetto appartiene a una collezione, può avere un luogo e una quantità
- **Luoghi** — indica dove si trovano fisicamente gli oggetti (es. Garage, Scaffale soggiorno)
- **Etichette** — label colorate per categorizzare ulteriormente gli oggetti
- **Ricerca** — trova oggetti per nome, descrizione, luogo o etichetta, con filtri combinabili

## Stack

- PHP 8.4 / Laravel 11
- SQLite (database locale)
- Tailwind CSS + Alpine.js (Laravel Breeze Blade)
- Vite

## Installazione

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install && npm run build
php artisan serve
```

## Struttura dati

```
User
 ├── Collections (1:N)
 │    └── Items (1:N)
 │         ├── Location (N:1, nullable)
 │         └── Tags (N:M)
 ├── Locations (1:N)
 └── Tags (1:N)
```

Ogni risorsa è isolata per utente: ogni utente vede solo le proprie collezioni, oggetti, luoghi e etichette.
