# 🔑 Cara Mendapatkan Google OAuth Credentials

## 📋 Langkah-langkah Setup Google Console

### 1. Buka Google Cloud Console
- Kunjungi: https://console.cloud.google.com/
- Login dengan akun Google Anda

### 2. Buat Project Baru
- Klik "Select a project" → "New Project"
- Nama project: `Toko Sepatu Online`
- Klik "Create"

### 3. Enable Google+ API
- Di sidebar kiri: "APIs & Services" → "Library"
- Cari "Google+ API" → Klik → "Enable"
- Atau cari "Google Identity" → Enable

### 4. Buat OAuth Consent Screen
- "APIs & Services" → "OAuth consent screen"
- Pilih "External" → "Create"
- Isi form:
  - App name: `Toko Sepatu Online`
  - User support email: email Anda
  - Developer contact: email Anda
- Klik "Save and Continue"
- Skip "Scopes" → "Save and Continue"
- Skip "Test users" → "Save and Continue"

### 5. Buat OAuth Credentials
- "APIs & Services" → "Credentials"
- Klik "+ Create Credentials" → "OAuth client ID"
- Application type: "Web application"
- Name: `Toko Sepatu Web Client`
- Authorized redirect URIs:
  ```
  http://localhost:8000/auth/google/callback
  http://127.0.0.1:8000/auth/google/callback
  ```
- Klik "Create"

### 6. Copy Credentials
Setelah dibuat, akan muncul popup dengan:
- **Client ID**: `123456789-abcdefg.apps.googleusercontent.com`
- **Client Secret**: `GOCSPX-abcdefghijklmnop`

## 🔧 Setup di Laravel

### 1. Tambah ke .env
```env
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnop
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 2. Install Socialite
```bash
composer require laravel/socialite
```

### 3. Run Migration
```bash
php artisan migrate
```

## 🌐 Production Setup

Untuk production, tambah domain production ke Authorized redirect URIs:
```
https://yourdomain.com/auth/google/callback
```

Dan update .env production:
```env
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

## ✅ Testing

1. Buka halaman login: `http://localhost:8000/login`
2. Klik "Masuk dengan Google"
3. Login dengan akun Google
4. User akan tersimpan di database dan login otomatis

## 🔒 Security Notes

- Jangan commit credentials ke Git
- Gunakan environment variables
- Untuk production, gunakan domain HTTPS
- Regularly rotate client secrets