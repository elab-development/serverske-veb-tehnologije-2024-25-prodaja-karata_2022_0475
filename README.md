# Prodaja karata - Laravel API

## 📌 Opis projekta

Ovaj projekat predstavlja **RESTful API za prodaju karata** za sportske događaje, razvijen u okviru predmeta **Serverske veb tehnologije**. Omogućava korisnicima da se registruju, prijave, pregledaju događaje, rezervišu karte i upravljaju svojim narudžbinama.

## 🛠 Tehnologije

- **Backend:** Laravel 11.x
- **Baza podataka:** MySQL
- **Autentifikacija:** Laravel Sanctum
- **Frontend:** Nema (API-only)
- **PHP verzija:** 8.1 ili novija

## 🚀 Pokretanje projekta lokalno

### 1. Kloniranje repozitorijuma

```bash
git clone https://github.com/elab-development/serverske-veb-tehnologije-2024-25-prodaja-karata_2022_0475.git
```

```bash
cd serverske-veb-tehnologije-2024-25-prodaja-karata_2022_0475
```

### 2. Instalacija zavisnosti

```bash
composer install
npm install
```

### 3. Kopiranje `.env` fajla

```bash
cp .env.example .env
```

### 4. Generisanje aplikacijskog ključa

```bash
php artisan key:generate
```

### 5. Konfiguracija baze podataka

U `.env` fajlu, podesite sledeće vrednosti:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ime_baze
DB_USERNAME=korisnicko_ime
DB_PASSWORD=lozinka
```

### 6. Pokretanje migracija i seed-ova

```bash
php artisan migrate --seed
```

### 7. Pokretanje servera

```bash
php artisan serve
```


## 🧪 Testiranje API-ja

```http
# Registracija korisnika
POST /api/register

# Prijava korisnika
POST /api/login

# Pregled događaja (javna ruta)
GET /api/events

# Kreiranje događaja (admin)
POST /api/events

# Izmena događaja (admin)
PUT /api/events/{id}

# Brisanje događaja (admin)
DELETE /api/events/{id}

# Pregled narudžbina
GET /api/orders

Zaštićene rute zahtevaju Bearer token koji se dobija login-om.

```


## 📄 Struktura projekta

```text
├── app/
│   ├── Models/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── ...
```

## 🧑‍💻 Autor
Luka Arsov - 2022/0475


