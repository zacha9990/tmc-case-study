## Project Laravel API

Ini adalah project Laravel API untuk manajemen kategori dan produk. API ini menyediakan endpoint untuk membuat kategori baru, membuat produk baru, dan mencari produk berdasarkan filter tertentu.

### Instalasi

Berikut adalah langkah-langkah untuk menginstal dan menjalankan project Laravel ini:

1. Clone repositori ini ke direktori lokal Anda:

   ```
   git clone <url-repositori> nama-project
   ```

2. Masuk ke direktori project:

   ```
   cd nama-project
   ```

3. Salin file `.env.example` menjadi `.env`:

   ```
   cp .env.example .env
   ```

4. Konfigurasi file `.env` dengan pengaturan yang sesuai, termasuk pengaturan database.

5. Jalankan perintah berikut untuk menginstal dependensi:

   ```
   composer install
   ```

6. Jalankan migrasi database untuk membuat skema tabel:

   ```
   php artisan migrate
   ```

7. Jalankan server pengembangan Laravel:

   ```
   php artisan serve
   ```

   Server pengembangan akan berjalan di `http://localhost:8000`.

### Penggunaan

Berikut adalah contoh penggunaan API endpoint yang tersedia:

#### Membuat Kategori Baru

- Endpoint: `POST /api/categories`
- Headers: `Authorization: API-KEY`
- Body:

  ```json
  {
    "name": "Nama Kategori"
  }
  ```

- Contoh Response:

  ```json
  {
    "data": {
      "id": "uuid",
      "name": "Nama Kategori",
      "createdAt": 1622505000000
    }
  }
  ```

#### Membuat Produk Baru

- Endpoint: `POST /api/products`
- Headers: `Authorization: API-KEY`
- Body:

  ```json
  {
    "sku": "SKU",
    "name": "Nama Produk",
    "price": 1000000,
    "stock": 100,
    "categoryId": "categoryId"
  }
  ```

- Contoh Response:

  ```json
  {
    "data": {
      "id": "1",
      "sku": "SKU",
      "name": "Nama Produk",
      "price": 1000000,
      "stock": 100,
      "category": {
        "id": "1",
        "name": "Nama Kategori"
      },
      "createdAt": 1622505000000
    }
  }
  ```

#### Mencari Produk

- Endpoint: `GET /api/search`
- Query Parameters:
  - `sku`: Filter berdasarkan SKU, mendukung lebih dari satu parameter
  - `name`: Filter berdasarkan nama (LIKE), mendukung lebih dari satu parameter
  - `price.start`: Filter berdasarkan harga mulai
  - `price.end`: Filter berdasarkan harga akhir
  - `stock.start`: Filter berdasarkan stok mulai
  - `stock.end`: Filter berdasarkan stok akhir
  - `category.id`: Filter berdasarkan ID kategori, mendukung lebih dari satu parameter
  - `category.name`: Filter berdasarkan nama kategori, mendukung lebih dari satu parameter

Contoh Penggunaan:

- Mencari produk dengan SKU dalam (1, 2, 3):
  ```
  GET /api/search?sku[]=1&sku[]=2&sku[]=3
  ```

- Mencari produk dengan

 nama seperti a atau b atau c:
  ```
  GET /api/search?name[]=a&name[]=b&name[]=c
  ```

- Mencari produk dengan harga >= 100 dan <= 1000:
  ```
  GET /api/search?price.start=100&price.end=1000
  ```

- Mencari produk dalam kategori (1, 2, 3):
  ```
  GET /api/search?category.id[]=1&category.id[]=2&category.id[]=3
  ```

- Contoh Response:

  ```json
  {
    "data": [
      {
        "id": "1",
        "sku": "SKU",
        "name": "Nama Produk",
        "price": 1000000,
        "stock": 100,
        "category": {
          "id": "1",
          "name": "Nama Kategori"
        },
        "createdAt": 1622505000000
      }
    ],
    "paging": {
      "size": 10,
      "total": 100,
      "current": 1
    }
  }
  ```

### Unit Test

Unit test telah disediakan untuk memastikan kestabilan dan keandalan project ini. Untuk menjalankan unit test, jalankan perintah berikut:

```
php artisan test
```
