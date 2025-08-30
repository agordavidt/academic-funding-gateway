Academic Funding Gateway Network
A comprehensive Laravel 10 application for managing academic grants through streamlined registration and payment processing.
🚀 Features
Admin Dashboard

Data Import: Bulk import student data via CSV
User Management: View and manage all registered students
Application Review: Review and update application statuses
Analytics: Dashboard with key metrics and statistics
Communication: Automated email notifications

Student Registration Portal

Phone Verification: Secure phone-based identity verification
Profile Completion: Streamlined multi-step registration form
Payment Integration: Secure payment processing (placeholder implementation)
Status Tracking: Real-time registration status updates
Email Notifications: Automated confirmations and status updates

🏗️ Technology Stack

Backend: PHP 8.1+, Laravel 10
Frontend: HTML5, CSS3, JavaScript, Bootstrap 5
Database: MySQL 8.0
Email: SMTP (SendGrid, Mailgun compatible)
Payment: Flutterwave (placeholder implementation included)

📋 Implementation Phases
✅ Phase 1: Admin Backend (Weeks 1-3)

 Database schema and migrations
 Eloquent models with relationships
 Admin dashboard with analytics
 User management system
 CSV data import functionality

✅ Phase 2: Student Frontend (Weeks 4-6)

 Multi-step registration funnel
 Phone number verification
 Profile completion forms
 Payment gateway integration (placeholder)
 Responsive Bootstrap 5 UI

✅ Phase 3: Communication & Finalization (Weeks 7-8)

 Email notification system
 Automated status update emails
 Payment confirmation emails
 Application status notifications
 Production-ready configuration

🚦 Getting Started
Prerequisites
bashPHP 8.1+
Composer
MySQL 8.0+
Node.js 16+
Quick Installation
bash# Clone the project
git clone <repository-url>
cd academic-funding-gateway

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
DB_DATABASE=academic_funding_gateway
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations and seed data
php artisan migrate --seed

# Start the application
php artisan serve
Visit http://localhost:8000 to access the application.
📊 Sample CSV Format
Student Data Import Template
Create a CSV file with the following headers:
csvphone_number,first_name,last_name,email,school,matriculation_number,address
08123456789,John,Doe,john.doe@email.com,University of Lagos,UNILAG/2023/001,"123 Main Street, Lagos"
08134567890,Jane,Smith,jane.smith@email.com,University of Abuja,UNIABUJA/2023/002,"456 Oak Avenue, Abuja"
08145678901,Mike,Johnson,mike.johnson@email.com,University of Port Harcourt,UNIPORT/2023/003,"789 Elm Street, Port Harcourt"
Import Instructions

Go to /admin/import
Upload your CSV file
Review import results
Students can now register using their phone numbers

🔧 Configuration
Email Setup (Production)
env# Using SendGrid
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
Payment Gateway (Production)
env# Flutterwave Configuration
FLUTTERWAVE_PUBLIC_KEY=your_public_key
FLUTTERWAVE_SECRET_KEY=your_secret_key
FLUTTERWAVE_SECRET_HASH=your_webhook_hash
FLUTTERWAVE_ENVIRONMENT=live
🎯 Usage Examples
Admin Workflow

Import Student Data

Navigate to /admin/import
Upload CSV file with student information
Review import results


Manage Applications

View all students at /admin/users
Filter by payment/application status
Update application statuses
Automated emails sent on status changes


Monitor Progress

Check dashboard metrics at /admin/dashboard
Track registration completion rates
Monitor payment collections



Student Workflow

Registration

Visit /student/register
Enter pre-registered phone number
Complete profile information
Pay ₦3,000 registration fee


Application Process

Receive payment confirmation email
Wait for application review
Get notified of acceptance/rejection
Access training program (if accepted)



🏢 Business Logic
Grant Structure

Value: Up to ₦500,000 per recipient
Type: Full scholarship to training programs
Fee: ₦3,000 non-refundable registration fee
Distribution: Through partner training institutions

Application Statuses

Pending: Initial application state
Reviewing: Under assessment by admin team
Accepted: Approved for grant award
Rejected: Not selected for current round

Payment Processing

Amount: ₦3,000 (fixed registration fee)
Gateway: Flutterwave integration
Status: Success/Failed tracking
Notifications: Automated confirmations

🔒 Security Features

CSRF protection on all forms
SQL injection prevention via Eloquent ORM
Input validation and sanitization
Secure password hashing
Environment-based configuration
Database transaction safety

📈 Analytics & Reporting
The admin dashboard provides:

Total registered students
Completed profiles count
Payment status breakdown
Application status distribution
Revenue tracking
Registration timeline

🚀 Deployment
Production Checklist
bash# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set production environment
APP_ENV=production
APP_DEBUG=false

# Configure HTTPS
# Set up database backups
# Configure monitoring
Server Requirements

PHP 8.1+ with required extensions
MySQL 8.0+ or equivalent
Web server (Apache/Nginx)
SSL certificate for HTTPS
Email service (SendGrid/Mailgun)

🤝 Contributing

Fork the repository
Create a feature branch
Make your changes
Test thoroughly
Submit a pull request

📞 Support
For technical issues or questions:

Check the installation guide
Review Laravel documentation
Examine application logs in storage/logs/

📄 License
This project is licensed under the MIT License.

Academic Funding Gateway Network - Empowering Education Through Technology