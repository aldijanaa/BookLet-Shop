# Introduction to Web programming Project

## Responsive bookshop website called "BookLet"

This is the repository for the project for the International Burch University course Introduction to Web Programming.
During this project full-stack online book-shop application was built called "BookLet". 

## Technologies Used
Frontend: 
- HTML/CSS/JS
- Ajax/JS
- SPApp

Backend:
- PHP/Flight PHP

Database:
- MySQL

Authorization
- JWT
- Middleware

Frontend and Backend connected through: Ajax

## Key Requirements
1. __Functional__: Functional and responsive frontend with the functional backend 
2. __Dynamic__: Dynamically retrieving data from the MySQL database and displaying the content dynamically.  
3. __Intuitive__: Easy to navigate through the website
4. __Secure__: Implemention of middleware to restrict unauthorized users to access routes if they are not valid.


## How to run project locally
 Clone the project
```bash
git clone https://github.com/aldijanaa/BookLet-Shop.git
```

Navigate to the backend directory:
```bash
cd backend
```

Install neccessary dependencies in order for the project to be able to run with FlightPHP:
```bash
composer install
composer require mikecao/flight
```

Configure MySQL Database:
- Make sure MySQL is running and create your own instance of the database named booklet_database and adjust properties in config.php to match your configuration (config.php for security reasons wasn't pushed to GitHub).

# Conclusion
First time using technologies such as FlightPHP and the usage of SPApp. Overall quiet interesting but complex project. Learned how to navigate through the routes and how to test them through Swagger. 