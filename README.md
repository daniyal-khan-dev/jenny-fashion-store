# Jenny Fashion Store 💄✨

A full-stack e-commerce web application for cosmetics and jewelry, built with PHP 8.2 and MySQL 8.0. Features a complete customer-facing storefront alongside a dedicated admin panel for managing the entire business.

---

**Admin Credentials**
**Email:** admin@gmail.com
**Password:** Admin_10 
 
🔗 Live Demo: https://daniyal-jenny-fashion-store.infinityfreeapp.com/
---

## Features

### Customer Storefront
- **Home** — Hero slider, featured products, promotional banners
- **Shop & Collection** — Browsable product catalog with category filters
- **Product Detail** — Product images, descriptions, add to cart
- **Shopping Cart** — Quantity management, order summary
- **Checkout** — Order placement with delivery details
- **Account** — Register, login, profile editing (avatar initials dropdown)
- **Order History** — Track past orders and their statuses
- **Contact Page** — Send messages directly to the store team

### Admin Panel (`/admin/dashboard`)
- **Dashboard** — Sales overview with charts (Chart.js)
- **Products** — Add, edit, delete products with image upload
- **Categories** — Manage product categories
- **Orders** — View and update order statuses
- **Order History** — Full transaction log
- **Customers** — View registered customer accounts
- **Admins** — Manage admin users
- **Contact Messages** — Read, manage, and respond to customer contact submissions (with unread badge)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 |
| Database | MySQL 8.0 |
| Frontend | Bootstrap 5, jQuery, Vanilla JS |
| Charts | Chart.js |
| Slider | Swiper.js |
| Icons | Font Awesome 6 |
| Server | PHP Built-in Server (via `router.php`) |

---

## Project Structure

```
jenny-fashion-store/
├── admin/                 # Admin panel pages & assets
│   ├── assets/            # Admin-specific CSS/JS
│   ├── controller/        # code.php (get, add, update & delete admin side data)
│   ├── includes/          # Admin header, footer, sidebar
│   ├── other-pages/       # Order & checkout detail views
│   └── dashboard.php      # Admin-Dashboard 
│   └── category.php       # Admin-Category 
│   └── products.php       # Admin-Products 
│   └── orders.php         # Admin-Active-Orders
│   └── order-histroy.php  # Admin-Orders-Histroy
│   └── users.php          # Admin-Customers 
│   └── admins.php         # Admin-Admins 
│   └── contact.php        # Admin-Contact
│
├── assets/                # Customer-side CSS, JS, images
├── config/                # DB connection + auto table creation
├── functions/             # Auth, cart, order, user logic
├── middleware/            # Admin & user auth guards
├── pages/
│   ├── includes/          # Customer header & footer
│   ├── login/             # Login header & footer
│   └── other/             # Cart, checkout, profile, orders
│
├── login.php               # Login / Register
├── index.php               # Homepage
├── collection.php          # Collection listing
├── shop.php                # Shop listing
├── about.php               # About page
├── contact.php             # Contact form page
├── routes.php              # Dynamic route constants
└── .htacess                # Route Settings
```

---

## Getting Started

### Prerequisites
- PHP 8.2+
- MySQL 8.0+

### Run Locally

```bash
# Clone the repository
git clone https://github.com/daniyal-khan-dev/jenny-fashion-store.git
cd jenny-fashion-store

# Start the app (initializes MySQL + PHP server)
bash start.sh
```
---

## Database Tables

| Table | Purpose |
|---|---|
| `users` | Customer accounts |
| `admins` | Admin accounts |
| `categories` | Product categories |
| `products` | Product catalog |
| `cart` | Shopping cart items |
| `orders` | Placed orders |
| `order_items` | Line items per order |
| `contact_messages` | Customer contact form submissions |

---

## 👨‍💻 Author

**M. Daniyal**
- GitHub: https://github.com/daniyal-khan-dev
- LinkedIn: www.linkedin.com/in/m-daniyal-khan
- Email: daniyalkhan0445@gmail.com

## 📞 Support

If you have any questions or need help, please:
- Open an issue on GitHub
- Contact via email
- Connect on LinkedIn

## Video

> see the [LinkedIn post](https://www.linkedin.com/posts/m-daniyal-khan_php-mysql-fullstack-ugcPost-7450900700298887169-X6dW?utm_source=share&utm_medium=member_ios&rcm=ACoAAFWnDjYB4nEsENZkqO85lEs-kZ00jxbTKPk) for a full video walkthrough.

<div align="center">
  <h3>🌟 If you found this project helpful, please give it a star! 🌟</h3>
  
  [![Live Demo](https://img.shields.io/badge/View%20Live%20Demo-Jenny%20Fashion%20Store-orange?style=for-the-badge&logo=netlify)](https://daniyal-jenny-fashion-store.infinityfreeapp.com/)
  
  <img src="assets/img/website-ss/jenny-fashion-store.png" alt="Jenny Fashion Store Website Preview" width="400px" style="border-radius: 10px; margin-top: 20px;">
</div>