# 🍴 Restaurant Analytics Dashboard

This project is a full-stack application built with:
- **Frontend**: Next.js (React)  
- **Backend**: Laravel (PHP)  
- **Database**: MySQL (or your preferred DB)  

The dashboard allows users to:
- View restaurants with search & filter  
- Select a restaurant and see daily order metrics (count, revenue, average order value, peak hour)  
- Filter by date range and order amount  
- View Top 3 Restaurants by revenue  

---

## Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/sha855/lara-react-next

### run "npm i" and  "npm run dev" in express-frontend directory for frontend run

--it will run on http://localhost:3000/

### run "composer install" and "php artisan migrate" and "php artisan db:seed" and "php artisan serve" 

--it will run on 127.0.0.1:8000

🔗 API Endpoints GET /restaurants → List restaurants GET /restaurants/top?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD → Top 3 restaurants by revenue GET /orders/metrics?restaurant_id=X&start_date=YYYY-MM-DD&end_date=YYYY-MM-DD&min_amount=&max_amount= → Metrics for a restaurant 🛠 Tech Stack Frontend: Next.js, React, Axios Backend: Laravel 12, PHP 8+ Database: MySQL / MariaDB
