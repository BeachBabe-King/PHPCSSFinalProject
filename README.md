# The Infinite Library

An e-commerce bookstore website built with PHP, MySQL, and custom CSS. This project is an online shopping center with public pages for customers and a secure admin dashboard for inventory management.

## Project Overview

The Infinite Library is a dynamic site featuring:
- **Frontend**: Browse books, view details, search and filter products
- **Admin Backend**: Secure dashboard for managing inventory with full CRUD
- **Responsive Design**: Fully responsive using custom CSS


## Technologies
PHP 8, MySQL, HTML5, CSS3, JavaScript (ES6)

## Features

### Public Pages
- **Homepage** - Hero section, featured books carousel, new releases carousel
- **Shop** - Product grid with filters (genre, price, page count) and search
- **Product Details** - Full product information with stock status
- **About** - Company story and mission
- **Help** - Contact information and support topics (contact page)
- **Login/Register** - User authentication

### Admin Dashboard
- **Add Products** - Form with image upload and validation
- **Manage Products** - View all products with search and pagination
- **Edit Products** - Update product information and images
- **Delete Products** - Remove products with confirmation

### Security
- Password hashing using password_hash() with PASSWORD_DEFAULT
- Prepared statements for all database queries
- Session-based authentication
- Admin-only page with redirects
- Input validation and sanitization
- XSS prevention with htmlspecialchars()


## Setup Instructions

1. **Input your own credentials into the Config.php file**
```php
define("DB_HOST", "localhost");
define("DB_NAME", "name");
define("DB_USER", "user");
define("DB_PASS", "password here");
```

2. **Create the tables**
    - Run the sql schemas provided in repository to create admin table and products table

### Creating an Admin User

1. Create an account through register page
2. Update the user to be an admin in the table:
```sql
   UPDATE FPAdmin SET isAdmin = 1 WHERE email = 'your@email.com';
```

## Design System

### Color Palette
| Color | Hex | Usage               |
|-------|-----|---------------------|
| Cream Background | #F7F3E9 | Main background     |
| Dark Brown | #3D2817 | Header/Footer       |
| Primary Accent | #4B2E05 | Text, borders       |
| Gold Accent | #FCBA03 | Buttons, highlights |
| Burgundy | #800020 | CTA buttons         |

### Typography
- **Headings**: Playfair Display (serif)
- **Body Text**: Lora (serif)

### Responsive Breakpoints
- Desktop: 992px+
- Tablet: 768px - 991px
- Mobile: 576px - 767px
- Small Mobile: < 576px

## Author

**Jessie Davis**  
Student ID: 200433256  
Georgian College
