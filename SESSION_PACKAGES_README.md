# Spa Session Packages Feature - Implementation Guide

This document explains the complete implementation of the dynamic spa session packages feature.

## Overview

The feature adds four dynamic package cards (Session Green, Yellow, Red, and Rainbow) to the Therapist Detail Page with a complete booking system managed through the admin panel.

## What Was Created

### 1. Database Tables

Two new tables were created:

#### `spa_sessions` Table
- `id` - Primary key
- `name` - Session name (e.g., "Session Green")
- `image` - Session image path
- `therapy_time` - Duration (e.g., "60 minutes")
- `price` - Session price
- `description` - Detailed description
- `status` - active/inactive
- `created_at` - Timestamp
- `updated_at` - Timestamp

#### `session_bookings` Table
- `id` - Primary key
- `session_id` - Foreign key to spa_sessions
- `name` - Customer name
- `email` - Customer email
- `phone` - Customer phone
- `spa_address` - Selected spa location
- `message` - Special requests
- `status` - pending/confirmed/cancelled/completed
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 2. Admin Panel Features

#### New Admin Pages
- **`admin/sessions.php`** - Manage all spa sessions
  - Add new sessions
  - Edit existing sessions
  - Delete sessions
  - Upload session images
  - Set pricing and duration
  - Control status (active/inactive)

- **`admin/get_session_data.php`** - AJAX endpoint for editing sessions

#### Navigation
- Added "Spa Sessions" link in the admin sidebar with calendar-heart icon

### 3. Frontend Features

#### Session Cards Section
- Added to `therapist-details.php` above the footer
- Displays 4 session cards in a responsive grid
- Each card shows:
  - Session image (or default image)
  - Session name
  - Duration with clock icon
  - Price
  - Brief description
  - "Book Now" button

#### Single Session Detail Page
- **`session-details.php`** - Dedicated page for each session
  - Large session image
  - Full session details (name, duration, price, description)
  - Contact information (phone, WhatsApp, email)
  - Booking form with fields:
    - Full Name
    - Email
    - Phone
    - Spa Address (dropdown with 3 locations)
    - Message/Special Request
  - AJAX form submission with success/error messages

### 4. Backend Processing

#### Booking Form Handler
- **`process_session_booking.php`** - Processes session bookings
  - Validates all form inputs
  - Saves booking to database
  - Sends confirmation email to customer
  - Sends notification email to admin
  - Returns JSON response for AJAX

### 5. Helper Functions

Added to `includes/functions.php`:
- `getAllSessions($status)` - Get all sessions (active/inactive/all)
- `getSessionById($id)` - Get single session details
- `createSessionBooking($data)` - Create new session booking
- `getAllSessionBookings()` - Get all session bookings

### 6. Styling

Added comprehensive CSS in `assets/css/style.css`:
- Session card styling with hover effects
- Session detail page layout
- Booking form container
- Responsive design for mobile/tablet
- Modern transitions and animations

## Installation Steps

### Step 1: Run Database Migration

Visit this URL in your browser:
```
https://boyztown.in/run_session_migration.php
```

This will:
- Create the two database tables
- Insert 4 default session packages
- Show success confirmation

### Step 2: Verify Admin Access

1. Login to admin panel: `https://boyztown.in/admin/`
2. Click on "Spa Sessions" in the sidebar
3. You should see 4 default sessions

### Step 3: Customize Sessions

In the admin panel, you can:
1. Click "Add New Session" to create more sessions
2. Click edit icon to modify existing sessions
3. Upload custom images for each session
4. Set pricing and durations
5. Activate/deactivate sessions

### Step 4: Test Frontend

1. Visit any therapist detail page (e.g., `therapist-details.php?id=1`)
2. Scroll down to see the "Spa Session Packages" section
3. Click on any session card to view details
4. Submit a test booking through the form

## Spa Address Options

The booking form includes three spa locations:
1. Shop No. 5, Ground Floor, Eros Hotel, Nehru Place, New Delhi, Delhi 110019
2. Shop No. 92, First Floor, Global Mall, Rajouri Garden, New Delhi, Delhi 110027
3. 2nd Floor, Ambience Mall, Vasant Kunj, New Delhi, Delhi 110070

You can modify these in `session-details.php` by editing the dropdown options.

## Default Sessions Included

1. **Session Green** - 60 minutes - ₹2,500
   - Refreshing wellness session with natural therapies

2. **Session Yellow** - 90 minutes - ₹3,500
   - Energizing spa experience with premium treatments

3. **Session Red** - 120 minutes - ₹4,500
   - Intensive full-body treatment with deep tissue massage

4. **Session Rainbow** - 150 minutes - ₹6,000
   - Ultimate luxury spa package with comprehensive treatments

## Email Notifications

When a customer books a session:
- Customer receives confirmation email with booking details
- Admin receives notification email with customer information

Make sure SMTP is configured in `includes/config.php` for emails to work.

## File Structure

```
project/
├── admin/
│   ├── sessions.php (Session management page)
│   └── get_session_data.php (AJAX endpoint)
├── includes/
│   └── functions.php (Updated with session functions)
├── assets/
│   └── css/
│       └── style.css (Updated with session styling)
├── session-details.php (Single session page)
├── process_session_booking.php (Booking handler)
├── therapist-details.php (Updated with session cards)
├── run_session_migration.php (Database setup script)
└── migrations/
    └── create_spa_sessions.sql (SQL migration file)
```

## Responsive Design

The feature is fully responsive:
- Desktop: 4 cards in a row
- Tablet: 2 cards in a row
- Mobile: 1 card per row (stacked)

## Security Features

- SQL injection protection via prepared statements
- XSS prevention via htmlspecialchars()
- Email validation
- Phone number validation (10 digits)
- CSRF protection ready

## Customization Tips

### Change Session Card Colors
Edit in `assets/css/style.css`:
```css
.session-card-title {
    color: var(--text-dark); /* Change title color */
}
.session-price {
    color: var(--primary-color); /* Change price color */
}
```

### Add More Spa Locations
Edit `session-details.php` line ~70:
```html
<option value="Your New Address">Your New Address</option>
```

### Modify Email Templates
Edit `process_session_booking.php` lines 40-80

## Troubleshooting

### Sessions Not Showing
1. Check if sessions are set to "active" in admin panel
2. Run the migration script again
3. Check database connection

### Booking Form Not Working
1. Check browser console for JavaScript errors
2. Verify `process_session_booking.php` exists
3. Check email configuration in `includes/config.php`

### Images Not Displaying
1. Verify image upload permissions on `uploads/sessions/` folder
2. Check image file formats (JPG, PNG, WebP supported)
3. Ensure correct image path in database

## Support

For issues or questions, check:
- Database tables exist: Run migration script
- File permissions: Uploads folder should be writable
- Error logs: Check server error logs for PHP errors

## Future Enhancements

Possible additions:
- Session booking calendar
- Online payment integration
- Customer reviews/ratings
- Session availability management
- Booking history for customers
- Session analytics in admin dashboard
