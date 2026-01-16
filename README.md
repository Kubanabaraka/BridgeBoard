# BridgeBoard

A skill-sharing and service marketplace platform built with PHP where users can post services, browse offerings, and connect with service providers in their community.

## 🌟 Features

- **User Authentication**:  Secure registration and login system with session management
- **Skill Posts**: Create, browse, edit, and delete service offerings
- **Categories**: Organized skill posts by categories for easy navigation
- **Search Functionality**: Find services and providers quickly
- **User Profiles**:  Manage personal information, bio, location, and avatar
- **Messaging System**: Contact service providers directly through the platform
- **Location-Based**:  Filter and search services by location
- **Price Range**: Set price ranges for services
- **Image Upload**: Support for multiple images per skill post
- **Role-Based Access**: Member and admin user roles

## 🛠️ Technology Stack

- **Backend**: PHP 8+ (with strict types)
- **Database**: MySQL 8.0+
- **Architecture**: MVC (Model-View-Controller)
- **Router**: Custom routing system
- **Session Management**: PHP native sessions
- **File Uploads**: Image upload support for avatars and skill posts

## 📋 Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Web server (Apache/Nginx)
- Composer (optional, for dependency management)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Kubanabaraka/BridgeBoard.git
   cd BridgeBoard
   ```

2. **Set up the database**
   ```bash
   mysql -u root -p < sql/bridgeboard.sql
   ```

3. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` with your database credentials and application settings: 
   ```env
   APP_NAME=BridgeBoard
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost/BridgeBoard/public

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bridgeboard
   DB_USERNAME=root
   DB_PASSWORD=your_password

   SESSION_NAME=bridgeboard_session
   UPLOAD_MAX_SIZE=5242880
   ALLOWED_IMAGE_TYPES=jpg,jpeg,png,webp
   ```

4. **Set up file permissions**
   ```bash
   chmod -R 755 storage
   ```

5. **Configure your web server**
   
   Point your web server's document root to the `public` directory. 
   
   **Apache example** (`.htaccess` should handle rewriting):
   ```apache
   DocumentRoot /path/to/BridgeBoard/public
   ```

6. **Access the application**
   
   Navigate to `http://localhost/BridgeBoard/public` in your browser

## 📁 Project Structure

```
BridgeBoard/
├── config/              # Configuration files
│   ├── app.php         # Application config
│   └── database.php    # Database config
├── public/             # Public web directory
│   └── index.php       # Application entry point
├── sql/                # Database schemas and migrations
│   └── bridgeboard.sql # Database schema
├── src/                # Application source code
│   ├── Controllers/    # Request handlers
│   ├── Core/          # Core framework components
│   ├── Models/        # Data models
│   └── Services/      # Business logic services
├── storage/           # File uploads and cache
├── tools/             # Development tools
├── bootstrap.php      # Application bootstrap
├── . env.example       # Environment variables template
└── . gitignore        # Git ignore rules
```

## 🎯 Available Routes

### Public Routes
- `GET /` - Home page
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /login` - Login form
- `POST /login` - Process login
- `POST /logout` - Logout user

### Protected Routes
- `GET /dashboard` - User dashboard
- `GET /posts` - Browse skill posts
- `GET /posts/create` - Create new post form
- `POST /posts` - Store new post
- `GET /posts/{id}` - View post details
- `GET /posts/{id}/edit` - Edit post form
- `POST /posts/{id}/update` - Update post
- `POST /posts/{id}/delete` - Delete post
- `GET /search` - Search posts
- `GET /profile` - Current user profile
- `GET /profile/{id}` - View user profile
- `POST /profile` - Update profile
- `GET /contact` - Messages inbox
- `POST /contact` - Send message

## 🔧 Configuration

### Upload Settings
- **Max file size**:  5MB (5242880 bytes)
- **Allowed image types**: jpg, jpeg, png, webp

### Session Settings
- **Session name**: bridgeboard_session (configurable)

### Database
- **Charset**: utf8mb4
- **Collation**: utf8mb4_unicode_ci

## 🗄️ Database Schema

The application uses four main tables:

- **users**: User accounts with authentication and profile information
- **categories**: Service categories for organizing posts
- **skill_posts**: Service listings with details, pricing, and images
- **messages**:  Direct messaging between users

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 👤 Author

**Kubanabaraka**

- GitHub: [@Kubanabaraka](https://github.com/Kubanabaraka)

## 🐛 Bug Reports

If you discover any bugs, please create an issue on GitHub with detailed information about the problem.

## ✨ Acknowledgments

- Built with PHP and MySQL
- Uses custom MVC architecture
- Designed for community skill-sharing

---

Made with ❤️ by Kubanabaraka
