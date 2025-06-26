Pet Adoption Platform
This is a web-based pet adoption platform developed using PHP and MySQL. It allows animal shelters or individual users to post adoption listings, and enables others to view, filter, and apply to adopt animals.

Features
View pet listings (name, age, species, gender, description, photo, city)

User registration and login system

Add new adoption listings

Submit and view adoption applications

Filter listings by species

Upload pet images or provide image URLs

Technologies Used
Frontend: HTML, CSS, JavaScript (Bootstrap 5)

Backend: PHP (with session management)

Database: MySQL

Other Tools: FontAwesome (for icons)

Setup Instructions
1. Clone the Repository
bash
Kopyala
Düzenle
git clone https://github.com/sareken/pet-adoption.git
2. Enter the Project Directory
bash
Kopyala
Düzenle
cd pet-adoption
3. Set Up the Database
Create a MySQL database named pet_adoption.

Import the SQL schema to create necessary tables (users, pets, adoption_requests) if available.

Example using MySQL:
CREATE DATABASE pet_adoption;
Make sure to update your database credentials in db_connection.php.

4. Start the Local Server
bash
Kopyala
Düzenle
php -S localhost:8000
Then open your browser and visit:

arduino
Kopyala
Düzenle
http://localhost:8000
Notes
Ensure the assets/images/ folder exists and has write permissions.

Enable file upload support in your php.ini file.

Default images are shown if no image is uploaded.

PHP sessions are used for login, logout, and flash messages.

