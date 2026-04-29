# Athletiqo Co. - Premium Sports Wear & Equipment

A modern, premium e-commerce website for sports wear and equipment, built with cutting-edge web technologies and designed to deliver an exceptional user experience.

## 🌟 Features

### 🎨 **Premium Design**
- Modern sports-brand aesthetics inspired by Nike, Adidas, Gymshark
- Glassmorphism and neon accent effects
- Smooth animations and micro-interactions
- Professional typography and spacing
- Dark/Light theme toggle with localStorage persistence

### 🛍️ **E-Commerce Functionality**
- Advanced product search with filters and sorting
- Dynamic shopping cart with localStorage
- Product recommendations and "You Might Also Like"
- Category-based navigation
- Price range filtering
- Real-time search suggestions

### 📱 **Responsive Design**
- Mobile-first approach with hamburger menu
- Tablet and desktop optimized layouts
- Touch-friendly interface
- Adaptive typography and spacing

### 🔐 **Secure Authentication**
- User registration and login system
- Password hashing with bcrypt
- Session management
- Input validation and sanitization
- CSRF protection

### 🎯 **User Experience**
- Toast notifications for user feedback
- Loading states and smooth transitions
- Hover effects and interactive elements
- Accessibility features (ARIA labels, keyboard navigation)
- SEO-optimized structure

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+ or 8.0+
- MySQL 5.7+ or 8.0+
- Web server (Apache/Nginx)
- Modern web browser

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Athletiqo-Co
   ```

2. **Database Setup**
   
   Import the database schema:
   ```bash
   mysql -u username -p database_name < database_setup.sql
   ```
   
   Or run the SQL file directly in your MySQL client.

3. **Configure Database Connection**
   
   Edit `config.php` and update your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'athletiqo_db');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

4. **Web Server Configuration**
   
   **Apache (.htaccess):**
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ $1 [L]
   ```
   
   **Nginx:**
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

5. **File Permissions**
   ```bash
   chmod 755 -R .
   chmod 644 *.php *.html *.css *.js
   ```

6. **Access the Application**
   
   Open your browser and navigate to:
   ```
   http://localhost/Athletiqo-Co/
   ```

## 📁 Project Structure

```
Athletiqo-Co/
├── index.html              # Homepage
├── wear.html               # Sports wear products
├── equipment.html          # Sports equipment products
├── cart.html               # Shopping cart
├── search.html             # Advanced search
├── login.php               # User login
├── signup.php              # User registration
├── logout.php              # User logout
├── config.php              # Database configuration
├── database_setup.sql      # Database schema
├── style.css               # Main stylesheet
├── script.js               # JavaScript functionality
└── README.md               # This file
```

## 🛠️ Technologies Used

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with CSS variables
- **JavaScript (ES6+)** - Dynamic functionality
- **Inter Font** - Professional typography

### Backend
- **PHP 8.0+** - Server-side logic
- **MySQL** - Database management
- **PDO** - Database interactions
- **bcrypt** - Password hashing

### Design Features
- **CSS Grid & Flexbox** - Responsive layouts
- **CSS Variables** - Theme system
- **CSS Animations** - Smooth transitions
- **Glassmorphism** - Modern UI effects
- **Gradient Accents** - Visual hierarchy

## 🎯 Key Features Explained

### Theme System
- Persistent dark/light mode toggle
- Smooth theme transitions
- Optimized color schemes for both modes
- Accessibility-compliant contrast ratios

### Search System
- Real-time search suggestions
- Multi-criteria filtering (category, price, subcategory)
- Sort options (price, name, relevance)
- Quick filter tags

### Shopping Cart
- Add/remove items with quantity controls
- Persistent cart using localStorage
- Dynamic price calculations
- Cart summary with checkout flow

### Authentication
- Secure password hashing
- Session management
- Input validation
- Error handling

## 📱 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile Safari 14+
- Chrome Mobile 90+

## 🔧 Customization

### Adding New Products
Edit the `products` array in `script.js`:
```javascript
{
    id: 9,
    name: "Product Name",
    category: "wear|equipment",
    subcategory: "subcategory",
    price: 999,
    image: "image-url",
    buyLink: "affiliate-link",
    keywords: ["keyword1", "keyword2"]
}
```

### Theme Customization
Modify CSS variables in `style.css`:
```css
:root {
    --accent-primary: #your-color;
    --accent-secondary: #your-color;
    /* Add more custom variables */
}
```

### Adding New Pages
1. Create new HTML file following the existing structure
2. Include the header, navigation, and footer
3. Add page-specific content
4. Update navigation links

## 🚀 Performance Optimizations

- **Lazy Loading** - Images load on demand
- **Minified Assets** - Optimized CSS and JS
- **Caching** - Browser caching headers
- **CDN Ready** - External font and image loading
- **SEO Friendly** - Semantic HTML and meta tags

## 🔒 Security Features

- **Input Sanitization** - Prevents XSS attacks
- **SQL Injection Protection** - Prepared statements
- **CSRF Protection** - Token-based validation
- **Session Security** - Secure cookie settings
- **Password Hashing** - bcrypt encryption

## 📊 Analytics Integration

Google Analytics is integrated for tracking:
- Page views and user behavior
- E-commerce events
- Conversion tracking
- User demographics

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📝 License

This project is for educational and demonstration purposes.

## 🆘 Support

For issues and questions:
1. Check the documentation
2. Review the code comments
3. Test in different browsers
4. Verify database connections

## 🎯 Future Enhancements

- **Payment Gateway Integration** - Stripe/PayPal
- **Product Reviews** - User rating system
- **Wishlist Functionality** - Save favorite items
- **Order History** - User purchase tracking
- **Admin Panel** - Product management
- **API Integration** - Headless commerce
- **PWA Support** - Progressive Web App
- **Multi-language Support** - Internationalization

---

**Built with ❤️ for the athletic community**