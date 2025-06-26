# Pet Adoption Platform

This is a web-based **Pet Adoption Platform** developed using PHP and MySQL. Animal shelters or individual users can add adoption listings, and other users can view listings and apply for adoption.

---

## Features

- Display pet listings (name, age, species, gender, description, photo, city)
- User registration and login system
- Add new adoption listings
- Submit and view adoption applications
- Filter listings by species (e.g., cats, dogs)
- Upload images or add via image URLs

---

## Technologies Used

| Layer       | Technology                |
|-------------|----------------------------|
| Frontend    | HTML, CSS, Bootstrap 5     |
| Backend     | PHP (with session handling)|
| Database    | MySQL                      |
| Utilities   | FontAwesome (for icons)    |

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/sareken/pet-adoption.git

2. Navigate into the Project Directory
cd pet-adoption

3. Set Up the Database
Create a MySQL database named pet_adoption.

Import the SQL schema (if provided) to create the required tables: users, pets, adoption_requests.
CREATE DATABASE pet_adoption;
Update your database credentials in the db_connection.php file.
4. Start the Local PHP Server
php -S localhost:8000

Then open the following URL in your browser:
http://localhost:8000

Notes
Ensure the folder assets/images/ exists and is writable.

File upload must be enabled in your php.ini file.

If no image is uploaded, a default placeholder image will be displayed.

PHP sessions are used for login, logout, and flash messages.

