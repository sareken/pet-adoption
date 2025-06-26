```markdown
# Pet Adoption Platform

This project is a PHP and MySQL-based web platform that allows users to view and adopt stray animals. Animal shelters or individual users can post adoption listings. Interested users can apply and get in touch.

## Features

- Display pet listings (name, age, species, gender, description, photo, city)
- User registration and login system
- Add new adoption listings
- Submit and view adoption applications
- Filter listings by species
- Upload images or provide image URLs

## Technologies Used

- Frontend: HTML, CSS, JavaScript (Bootstrap 5)
- Backend: PHP (with session handling)
- Database: MySQL
- Other tools: FontAwesome for icons

## Setup Instructions

To run this project locally:

### 1. Clone the repository

```bash
git clone https://github.com/sareken/pet-adoption.git
2. Enter the project directory
bash
Kopyala
Düzenle
cd pet-adoption
3. Set up the database
Create a MySQL database named pet_adoption

If available, import the SQL schema to create necessary tables: users, pets, adoption_requests

Using phpMyAdmin or command line:

CREATE DATABASE pet_adoption;
Make sure to update database credentials in db_connection.php accordingly.

4. Start the local server
bash
Kopyala
Düzenle
php -S localhost:8000
Then go to: http://localhost:8000

Notes
Make sure assets/images/ folder exists and is writable

File upload support must be enabled in your php.ini

Default images are shown if no image is uploaded

PHP sessions are used for login, logout, and flash messages


