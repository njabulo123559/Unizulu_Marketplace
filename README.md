# Unizulu e-Commerce Website

## Project Overview
The Unizulu e-commerce project is an online platform designed for Unizulu students and staff to order food from the restaurant, DocShed. The website offers features such as secure payments, order tracking, and account management, making the process convenient for users and providing a platform for students to enhance their entrepreneurial skills.

## Features
- User registration and login system
- Admin dashboard for managing products, users, and orders
- Secure online payments
- Real-time order tracking
- Responsive design for mobile and desktop

## Technologies Used
- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL
- **Other Tools:** Git, Apache (XAMPP), Stripe(Payment Gateway)

## Installation Instructions

### Prerequisites
1. [Git](https://git-scm.com/) must be installed.
2. You will need [XAMPP](https://www.apachefriends.org/index.html) (or WAMP/LAMP for Linux/Mac users) to set up Apache and MySQL.

### Steps to Run the Project Locally

#### 1. Clone the Repository
First, clone the GitHub repository to your local machine:
```bash
git clone https://github.com/njabulo123559/Unizulu_Marketplace.git
cd your-repository-name

## **Set Up XAMPP**
 - Start XAMPP and ensure both Apache and MySQL services are running.
 - Place the cloned project in the htdocs directory inside XAMPP (usually C:\xampp\htdocs).

## **Update the Database Connection**
In the config/db.php file, update the database credentials:

  - define('DB_SERVER', 'localhost');
  - define('DB_USERNAME', 'root'); // Default username in XAMPP
  - define('DB_PASSWORD', ''); // No password in XAMPP by default
  - define('DB_NAME', 'your-database-name');

## composer install
