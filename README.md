# 🛒 Modern E-Commerce Platform

A comprehensive, production-ready e-commerce platform built with modern technologies. Features a complete shopping experience with user authentication, product management, payment processing, and admin dashboard.

![E-Commerce Platform](https://img.shields.io/badge/React-18-blue) ![Node.js](https://img.shields.io/badge/Node.js-18-green) ![MongoDB](https://img.shields.io/badge/MongoDB-7.0-green) ![Docker](https://img.shields.io/badge/Docker-Ready-blue)

## ✨ Features

### 🔐 Authentication & Security
- JWT-based authentication with refresh tokens
- Role-based access control (Customer/Admin)
- Password hashing with bcrypt
- Rate limiting and security headers
- Input validation and sanitization

### 🛍️ Shopping Experience
- Product catalog with categories and search
- Advanced filtering (price, brand, rating)
- Shopping cart with persistent storage
- Wishlist functionality
- Product reviews and ratings
- Coupon system with discounts

### 💳 Payment & Orders
- Stripe payment integration
- Secure checkout process
- Order tracking and history
- Email notifications (ready for implementation)
- Inventory management

### 📊 Admin Dashboard
- Sales analytics and reporting
- Product and category management
- Order fulfillment system
- User management
- Revenue tracking and insights

### 🎨 Modern UI/UX
- Responsive design (mobile-first)
- TailwindCSS with custom design system
- Loading states and error handling
- Optimistic updates for better UX
- Accessibility features

## 🚀 Tech Stack

### Frontend
- **React 18** - Modern React with hooks
- **TailwindCSS** - Utility-first CSS framework
- **Zustand** - Lightweight state management
- **React Router v6** - Client-side routing
- **Axios** - HTTP client
- **React Query** - Data fetching and caching
- **Headless UI** - Accessible UI components

### Backend
- **Node.js** - JavaScript runtime
- **Express.js** - Web framework
- **MongoDB** - NoSQL database
- **Mongoose** - MongoDB object modeling
- **JWT** - Authentication tokens
- **Stripe** - Payment processing
- **Bcrypt** - Password hashing

### DevOps & Deployment
- **Docker** - Containerization
- **Docker Compose** - Multi-container orchestration
- **Nginx** - Reverse proxy (production)
- **MongoDB** - Database with indexes

## 📁 Project Structure

```
ecommerce-platform/
├── backend/                 # Node.js/Express API
│   ├── middleware/          # Authentication, error handling
│   ├── models/             # MongoDB schemas
│   │   ├── User.js         # User model with addresses, wishlist
│   │   ├── Product.js      # Product with variants, reviews
│   │   ├── Category.js     # Hierarchical categories
│   │   ├── Order.js        # Order management
│   │   └── Cart.js         # Shopping cart logic
│   ├── routes/             # API endpoints
│   │   ├── auth.js         # Authentication routes
│   │   ├── products.js     # Product CRUD operations
│   │   ├── cart.js         # Cart management
│   │   ├── orders.js       # Order processing
│   │   ├── payment.js      # Stripe integration
│   │   └── admin.js        # Admin dashboard APIs
│   ├── server.js           # Express server setup
│   └── Dockerfile          # Backend container
├── frontend/               # React application
│   ├── src/
│   │   ├── components/     # Reusable UI components
│   │   │   ├── Auth/       # Authentication components
│   │   │   ├── Layout/     # Header, Footer, Navigation
│   │   │   ├── Product/    # Product-related components
│   │   │   └── UI/         # Generic UI components
│   │   ├── pages/          # Page components
│   │   │   ├── Auth/       # Login, Register, etc.
│   │   │   ├── Admin/      # Admin dashboard pages
│   │   │   └── ...         # Other pages
│   │   ├── store/          # Zustand state management
│   │   │   ├── authStore.js    # Authentication state
│   │   │   └── cartStore.js    # Shopping cart state
│   │   ├── services/       # API service layer
│   │   └── App.js          # Main application component
│   └── Dockerfile          # Frontend container
├── docker-compose.yml      # Multi-service orchestration
├── mongo-init.js          # Database initialization
└── README.md              # This file
```

## 🚀 Quick Start

### Prerequisites
- Node.js 18+ 
- Docker & Docker Compose
- Git

### 1. Clone the Repository
```bash
git clone <repository-url>
cd ecommerce-platform
```

### 2. Install Dependencies
```bash
npm run install-deps
```

### 3. Environment Setup
```bash
# Copy environment template
cp backend/env.example backend/.env

# Edit backend/.env with your configuration
```

### 4. Start with Docker (Recommended)
```bash
# Start all services
docker-compose up --build

# Or run in background
docker-compose up -d --build
```

### 5. Access the Application
- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:5000
- **MongoDB**: localhost:27017

### 6. Default Admin Account
- **Email**: admin@ecommerce.com
- **Password**: admin123

## 🔧 Development Setup

### Local Development (without Docker)

#### Backend
```bash
cd backend
npm install
npm run dev
```

#### Frontend
```bash
cd frontend
npm install
npm start
```

### Environment Variables

#### Backend (.env)
```env
# Server Configuration
NODE_ENV=development
PORT=5000

# Database
MONGODB_URI=mongodb://localhost:27017/ecommerce

# Authentication
JWT_SECRET=your_super_secret_jwt_key_here_make_it_long_and_random
JWT_EXPIRE=30d

# Stripe Payment
STRIPE_SECRET_KEY=sk_test_your_stripe_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_stripe_webhook_secret_here

# Cloudinary (for image uploads)
CLOUDINARY_CLOUD_NAME=your_cloudinary_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

# Email Configuration
EMAIL_FROM=noreply@yourstore.com
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
```

#### Frontend (.env)
```env
REACT_APP_API_URL=http://localhost:5000/api
REACT_APP_STRIPE_PUBLISHABLE_KEY=pk_test_your_stripe_publishable_key_here
```

## 📚 API Documentation

### Authentication Endpoints
```http
POST /api/auth/register     # User registration
POST /api/auth/login        # User login
POST /api/auth/logout       # User logout
GET  /api/auth/me          # Get current user
PUT  /api/auth/profile     # Update profile
PUT  /api/auth/password    # Change password
```

### Product Endpoints
```http
GET    /api/products              # Get products (with filtering)
GET    /api/products/:id          # Get single product
GET    /api/products/featured     # Get featured products
POST   /api/products/:id/reviews  # Add product review
```

### Cart Endpoints
```http
GET    /api/cart              # Get user cart
POST   /api/cart/items        # Add item to cart
PUT    /api/cart/items/:id    # Update item quantity
DELETE /api/cart/items/:id    # Remove item from cart
POST   /api/cart/coupon       # Apply coupon
```

### Order Endpoints
```http
GET  /api/orders           # Get user orders
POST /api/orders           # Create new order
GET  /api/orders/:id       # Get order details
PUT  /api/orders/:id/cancel # Cancel order
```

### Admin Endpoints
```http
GET /api/admin/dashboard/stats    # Dashboard statistics
GET /api/admin/analytics/sales    # Sales analytics
GET /api/admin/products           # Manage products
GET /api/admin/orders             # Manage orders
GET /api/admin/users              # Manage users
```

## 🐳 Docker Deployment

### Production Deployment
```bash
# Build and start all services
docker-compose -f docker-compose.yml up --build -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down
```

### Service Management
```bash
# Restart specific service
docker-compose restart backend

# View service status
docker-compose ps

# Scale services
docker-compose up --scale backend=3
```

## 🧪 Testing

### Running Tests
```bash
# Backend tests
cd backend
npm test

# Frontend tests
cd frontend
npm test
```

### Test Coverage
```bash
# Backend coverage
cd backend
npm run test:coverage

# Frontend coverage
cd frontend
npm run test:coverage
```

## 📊 Performance & Monitoring

### Database Optimization
- Indexed fields for fast queries
- Connection pooling
- Query optimization

### Frontend Optimization
- Code splitting with React.lazy()
- Image optimization
- Bundle size optimization
- Caching strategies

### Monitoring (Recommended)
- Application performance monitoring
- Error tracking (Sentry)
- Log aggregation
- Health checks

## 🔒 Security Features

- **Authentication**: JWT with refresh tokens
- **Authorization**: Role-based access control
- **Input Validation**: Comprehensive validation
- **Rate Limiting**: API protection
- **Security Headers**: Helmet.js integration
- **Password Security**: Bcrypt hashing
- **CORS**: Proper cross-origin configuration

## 🚀 Deployment Options

### 1. Docker (Recommended)
```bash
docker-compose up --build
```

### 2. Cloud Deployment
- **Frontend**: Vercel, Netlify, AWS S3
- **Backend**: Railway, Render, AWS EC2
- **Database**: MongoDB Atlas, AWS DocumentDB

### 3. Traditional VPS
- Ubuntu/CentOS server
- Nginx reverse proxy
- PM2 process manager
- SSL certificates

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow ESLint configuration
- Write meaningful commit messages
- Add tests for new features
- Update documentation

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: Check this README and code comments
- **Issues**: Open an issue on GitHub
- **Discussions**: Use GitHub Discussions for questions

## 🎯 Roadmap

### Planned Features
- [ ] Email notifications
- [ ] Advanced analytics
- [ ] Multi-language support
- [ ] Mobile app
- [ ] Recommendation engine
- [ ] Advanced coupon system
- [ ] Real-time notifications
- [ ] Inventory alerts

### Performance Improvements
- [ ] Redis caching
- [ ] CDN integration
- [ ] Database optimization
- [ ] Image optimization
- [ ] Bundle optimization

---

**Built with ❤️ using modern web technologies**


