# 🎉 QR Code Generator

A web application for generating various types of QR codes including:

- **Website URLs**
- **PDF documents**
- **vCards (digital business cards)**
- **Image galleries**
- **Social media profiles**

Built with **Laravel**, **Bootstrap**, and the **SimpleSoftwareIO QR Code package**.

---

## ✨ User Experience

- Clean, responsive interface
- Real-time QR code preview
- Download QR codes as **PNG** or **SVG**
- File uploads with validation
- Mobile-friendly design

---

## 🚀 Installation

### 📋 Requirements

- **PHP** 8.1+  
- **Composer**  
- **MySQL** 5.7+  
- **Node.js** 16+  
- **Laravel** 10+  

---

### ⚙️ Setup Steps

1️⃣ **Clone the repository:**

```bash
git clone https://github.com/Hidaya01/QrCodeGenerator.git
cd qr-generator
2️⃣ Install dependencies:

bash
Copier
Modifier
composer install
npm install
3️⃣ Configure environment:

bash
Copier
Modifier
cp .env.example .env
php artisan key:generate
4️⃣ Update .env with your database credentials:

ini
Copier
Modifier
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
5️⃣ Run migrations:

bash
Copier
Modifier
php artisan migrate
6️⃣ Build assets:

bash
Copier
Modifier
npm run build
7️⃣ Start the development server:

bash
Copier
Modifier
php artisan serve

