
# 🍴 The Gallery Café Web Application

An interactive, database-driven web application designed for **The Gallery Café, Colombo**. This system improves customer experience by streamlining **reservations, pre-orders, menu browsing, and user management**, while providing separate dashboards for **Admins, Staff, and Customers**.

---

## 🚀 Features

### 👨‍💻 User Roles

* **Admin Dashboard**

  * Manage food menus (add, update, delete items)
  * Manage users (add, update, delete accounts)

* **Staff Dashboard**

  * View and confirm/cancel reservations
  * View and confirm/cancel orders

* **Customer Portal**

  * Browse menus by category (Sri Lankan, Chinese, Italian)
  * Place food pre-orders
  * Make table reservations
  * View special promotions & events

### 🎨 UI/UX

* Simple login/registration forms
* Responsive navigation (Home, About Us, Events, Promotions, Reservations, Contact)
* Organized menu with search and filter options
* User-friendly dashboards for Admins and Staff

---

## 🛠️ Technologies

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP (via WAMPServer)
* **Database:** MySQL

---

## 📂 Project Structure

```
/The Gallery Café
│── /frontend         # UI & static files
│── /backend          # PHP application logic
│   ├── config.php    # Database connection file
│── /database         
│   ├── schema.sql    # Database schema & tables
│── /screenshots      # Screenshots of UI & dashboards
│── README.md         # Project documentation
```

---

## 📸 Screenshots

Place images in the `/screenshots` folder and reference them like this:

### 🌐 Home Page

<img width="1903" height="946" alt="image" src="https://github.com/user-attachments/assets/da77012d-029d-47de-81f1-5b13078ee3c8" />

<img width="1905" height="750" alt="image" src="https://github.com/user-attachments/assets/82b8ee0e-bb65-459b-886a-8369fde78785" />

<img width="1904" height="803" alt="image" src="https://github.com/user-attachments/assets/cc69a8c2-4979-4b9b-a00d-249661483337" />


<img width="1902" height="813" alt="image" src="https://github.com/user-attachments/assets/db6f93b8-c50d-4e02-86fd-e904d42ae2d8" />

<img width="1908" height="822" alt="image" src="https://github.com/user-attachments/assets/76099718-c498-4e30-aeb4-16b8539d9589" />


<img width="1903" height="815" alt="image" src="https://github.com/user-attachments/assets/6dbcb88f-e41a-4eca-8a3d-255431b4b15b" />

<img width="1898" height="224" alt="image" src="https://github.com/user-attachments/assets/ca49b91a-8eb1-4c6a-807b-4f5f343a587e" />








### 🍽️ Menu Page

![Menu Page](screenshots/menu.png)

### 👨‍💻 Admin Dashboard

![Admin Dashboard](screenshots/admin-dashboard.png)

### 🧑‍🍳 Staff Dashboard

![Staff Dashboard](screenshots/staff-dashboard.png)

### 📅 Reservation Form

![Reservation Form](screenshots/reservation.png)

---

## ⚡ Getting Started

### 1. Install Requirements

* Download and install [WAMPServer](https://www.wampserver.com/).
* Ensure **Apache** and **MySQL** services are running.

### 2. Set Up the Project

1. Copy the project folder (`The Gallery Café`) into:

   ```
   C:/wamp64/www/
   ```

2. Import the SQL database:

   * Open **phpMyAdmin** → `http://localhost/phpmyadmin`
   * Create a new database:

     ```sql
     CREATE DATABASE restaurant;
     ```
   * Import `schema.sql` from the `/database` folder.

3. Configure the database connection in `config.php`:

   ```php
   <?php
   // Database configuration
   $servername = "localhost";
   $username   = "root";      // Default WAMP username
   $password   = "";          // Default WAMP password (empty)
   $dbname     = "restaurant";

   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);

   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```

### 3. Run the Application

* Start WAMPServer.
* Open your browser and go to:

  ```
  http://localhost/The%20Gallery%20Caf%C3%A9
  ```

  *(Tip: Rename folder to `TheGalleryCafe` for easier access.)*

---

## 🔑 Default Login Credentials

*(You can update these in the database once imported)*

* **Admin** → username: `admin` | password: `123`
* **Staff** → username: `staff` | password: `123`
* **Customer** → register a new account via the signup form

---

## 📜 License

This project is for academic purposes. You may reuse parts with proper credit.

---
