# Tesfaw Amare - Personal Portfolio

A modern, responsive personal portfolio website with PHP backend and MySQL database.

## Features

- Responsive design with smooth animations
- Contact form with email notifications and auto-reply
- Blog system with database storage
- Project showcase from database
- Newsletter subscription system
- Visitor tracking and statistics
- Admin dashboard for content management
- Server-side form validation
- Mobile-friendly navigation

## Tech Stack

### Frontend
- HTML5
- CSS3 (with modern gradients and animations)
- Vanilla JavaScript (ES6+)
- Font Awesome icons

### Backend
- PHP 7.4+
- MySQL 5.7+
- RESTful API architecture

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/MAMP (for local development)

### Setup Instructions

1. **Clone or download this repository**

2. **Database Setup**
   - Create a MySQL database named `portfolio_db`
   - Import the database schema:
   ```bash
   mysql -u root -p portfolio_db < database.sql
   ```

3. **Configure Database Connection**
   - Open `config.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'portfolio_db');
   ```

4. **Configure Email**
   - Update `ADMIN_EMAIL` in `config.php`
   - Ensure your server has mail() function enabled

5. **Set Permissions**
   ```bash
   chmod 755 api/
   chmod 644 config.php
   ```

## Running the Application

### Using XAMPP (Windows/Mac/Linux)
1. Copy project folder to `htdocs/`
2. Start Apache and MySQL from XAMPP Control Panel
3. Visit `http://localhost/portfolio/index.php`

### Using PHP Built-in Server (Development)
```bash
php -S localhost:8000
```
Visit `http://localhost:8000/index.php`

### Using Apache/Nginx (Production)
- Configure virtual host to point to project directory
- Ensure mod_rewrite is enabled for clean URLs

## API Endpoints

- `GET /api/projects.php` - Fetch all active projects
- `GET /api/blog.php?limit=3` - Fetch blog posts
- `POST /api/contact.php` - Submit contact form
- `POST /api/newsletter.php` - Subscribe to newsletter
- `GET /api/visitor-count.php` - Track and get visitor count

## Admin Dashboard

Access the admin panel at `/admin/index.php`

**Default Credentials:**
- Username: `admin`
- Password: `admin123`

**⚠️ IMPORTANT:** Change these credentials in `admin/index.php` before deploying!

### Admin Features
- View contact messages
- Manage projects
- Manage blog posts
- View newsletter subscribers
- Dashboard statistics

## Database Structure

### Tables
- `projects` - Portfolio projects
- `blog_posts` - Blog articles
- `contact_messages` - Contact form submissions
- `newsletter_subscribers` - Email subscribers
- `visitor_stats` - Visitor tracking

## Customization

1. **Profile Picture**
   - Add your photo as `images/profile.jpg`
   - Recommended: 500x500px square image
   - Supports JPG, PNG formats
   - If no image is provided, a placeholder icon will show

2. **Personal Information**
   - Edit `index.php` to update name, bio, contact details
   - Update social media links

3. **Styling**
   - Modify `styles.css` for colors and design
   - Update CSS variables in `:root` for theme colors

4. **Content**
   - Add projects via database or admin panel
   - Create blog posts through database
   - Update skills section in `index.php`

5. **Email Configuration**
   - Configure SMTP if mail() doesn't work
   - Update email templates in API files

## Security Recommendations

### For Production:
1. **Change admin credentials** in `admin/index.php`
2. **Use prepared statements** (already implemented)
3. **Enable HTTPS** with SSL certificate
4. **Implement rate limiting** for API endpoints
5. **Add CSRF protection** for forms
6. **Use environment variables** for sensitive data
7. **Disable error display**:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```
8. **Add .htaccess** for security headers
9. **Regular database backups**
10. **Keep PHP and MySQL updated**

## Deployment

### Shared Hosting (cPanel)
1. Upload files via FTP/File Manager
2. Create MySQL database in cPanel
3. Import `database.sql`
4. Update `config.php` with database credentials
5. Set proper file permissions

### VPS/Cloud (AWS, DigitalOcean, etc.)
1. Install LAMP stack
2. Clone repository to web directory
3. Configure virtual host
4. Import database
5. Update config.php
6. Enable SSL with Let's Encrypt

## Troubleshooting

**Database connection failed:**
- Check credentials in `config.php`
- Ensure MySQL service is running
- Verify database exists

**Emails not sending:**
- Check PHP mail() configuration
- Consider using SMTP library (PHPMailer)
- Verify server allows mail sending

**API returns 404:**
- Check .htaccess configuration
- Verify file paths are correct
- Ensure mod_rewrite is enabled

## License

MIT License - Feel free to use this for your own portfolio!

## Contact

Tesfaw Amare - tesfaw.amare@example.com

## Credits

Built with ❤️ using PHP, MySQL, HTML, CSS, and JavaScript
