# 🍽️ BiteCraft - Restaurant Management & Online Ordering System

<p align="center">
  <b>Delicious Food. Crafted With Love. ❤️</b>
</p>

<p align="center">
  A modern restaurant website and online food ordering system built using PHP, MySQL and Bootstrap.
</p>

---

## ✨ About The Project

**BiteCraft** is a modern and user-friendly restaurant management and online ordering web application.

The system allows customers to explore the restaurant menu, add food items to their cart, place orders, make table reservations, and contact the restaurant.

Administrators can manage restaurant operations through the admin panel, including menu items, categories, customer orders, and reservations.

---

## 🚀 Features

### 👤 Customer Features

* 🔐 User Registration and Login
* 🍔 Browse Available Food Menu
* 🛒 Add Items to Cart
* 📦 Manage Food Orders
* 🧾 View My Orders
* 📅 Book a Table
* 📧 Contact the Restaurant
* 👤 User Session Management
* 📱 Fully Responsive Design

### 🛠️ Admin Features

* 🔑 Admin Dashboard
* 🍽️ Manage Menu Items
* 🏷️ Manage Food Categories
* 📦 Manage Customer Orders
* 📅 Manage Table Reservations
* 👥 Manage Users
* 📊 Monitor Restaurant Activities

---

## 🖥️ Pages

| Page                | Description                                                 |
| ------------------- | ----------------------------------------------------------- |
| 🏠 Home             | Welcome page with popular dishes and restaurant information |
| 🍽️ Menu            | Browse all available food items                             |
| ℹ️ About            | Learn more about BiteCraft                                  |
| 📞 Contact          | Send messages and contact the restaurant                    |
| 🔐 Login            | User authentication                                         |
| 📝 Register         | Create a new customer account                               |
| 🛒 Cart             | View and manage selected food items                         |
| 📦 My Orders        | View customer orders                                        |
| 📅 Reservation      | Book a table at the restaurant                              |
| 🛠️ Admin Dashboard | Manage restaurant operations                                |

---

## 🛠️ Technologies Used

### Frontend

* HTML5
* CSS3
* Bootstrap 5
* Bootstrap Icons
* JavaScript

### Backend

* PHP
* MySQL

### Tools

* XAMPP
* phpMyAdmin
* Git & GitHub
* VS Code

---

## 📂 Project Structure

```text
BiteCraft/
│
├── admin/
│   ├── index.php
│   ├── menu-items.php
│   ├── categories.php
│   ├── orders.php
│   └── reservations.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   └── images/
│
├── config/
│   └── database.php
│
├── index.php
├── menu.php
├── about.php
├── contact.php
│
├── login.php
├── register.php
├── logout.php
│
├── cart.php
├── add-to-cart.php
│
├── my-orders.php
│
├── reservation.php
│
└── README.md
```

---

## ⚙️ Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/your-username/bitecraft.git
```

### 2️⃣ Move the Project

Move the project folder to your XAMPP `htdocs` directory.

```text
C:\xampp\htdocs\BiteCraft
```

### 3️⃣ Start XAMPP

Start the following services:

```text
Apache
MySQL
```

### 4️⃣ Create the Database

Open **phpMyAdmin** and create a database.

```sql
bite_craft
```

### 5️⃣ Configure Database Connection

Open:

```text
config/database.php
```

Update your database credentials.

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "bite_craft";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);
```

### 6️⃣ Run the Project

Open your browser and visit:

```text
http://localhost/BiteCraft/
```

---

## 🎨 User Interface

BiteCraft provides a clean and modern restaurant experience with:

* ✨ Modern Bootstrap UI
* 📱 Mobile Responsive Layout
* 🍔 Attractive Food Cards
* 🟡 BiteCraft Yellow Theme
* 🛒 Easy Food Ordering
* 📅 Simple Table Reservation
* 🔐 Secure User Authentication

---

## 📸 Screenshots
```text

```
<img width="1920" height="4026" alt="Screenshot 2026-09-02 at 06-22-46 BiteCraft Restaurant" src="https://github.com/user-attachments/assets/4c6f8a52-a45d-4a65-8ea6-7869762e680e" />


---

## 🔮 Future Improvements

* 💳 Online Payment Integration
* ⭐ Food Ratings and Reviews
* 🔔 Order Notifications
* 📧 Email Notifications
* 📱 Progressive Web App (PWA)
* 📊 Advanced Sales Reports
* 🤖 AI Food Recommendations
* 🚚 Food Delivery Tracking

---



<p align="center">
  Made with ❤️ and 🍕 by <b>Sewwandi Kaushalya</b>
</p>

<p align="center">
  ⭐ If you like this project, don't forget to give it a star!
</p>
