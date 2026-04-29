# Google reCAPTCHA v2 Setup Guide for Athletiqo

This guide will help you set up Google reCAPTCHA v2 with image-based bot protection for the Athletiqo sports e-commerce platform.

## 📋 Overview

The Athletiqo platform now includes Google reCAPTCHA v2 ("I'm not a robot") protection for:
- **Login Page** (`login.html`)
- **Signup Page** (`signup.html`)
- **Contact Forms** (if implemented in future)

The system uses image-selection challenges where users may need to select:
- Traffic lights
- Buses
- Bicycles
- Crosswalks
- And other objects

## 🔧 Prerequisites

1. **PHP Server** (for backend validation)
2. **Google Account** (for reCAPTCHA keys)
3. **Domain Name** (for production setup)

## 🚀 Step-by-Step Setup

### 1. Get Google reCAPTCHA Keys

1. Visit [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin/create)
2. Sign in with your Google account
3. Fill in the form:
   - **Label**: `Athletiqo Sports E-commerce`
   - **reCAPTCHA type**: `reCAPTCHA v2 ("I'm not a robot" Checkbox)`
   - **Domains**: 
     - `localhost` (for development)
     - `yourdomain.com` (for production)
     - `www.yourdomain.com` (for production)
4. Accept the terms of service
5. Click **Submit**
6. Copy your **Site Key** and **Secret Key**

### 2. Update Configuration

Open `recaptcha-config.php` and replace the demo keys with your actual keys:

```php
// Replace these with your actual keys from Google reCAPTCHA console
define('RECAPTCHA_SITE_KEY', 'YOUR_ACTUAL_SITE_KEY_HERE');
define('RECAPTCHA_SECRET_KEY', 'YOUR_ACTUAL_SECRET_KEY_HERE');
```

**Important**: The demo keys (`6LeIxAcTAAAAAJcZVRqyHh71UMIEbQjVyyTy_8fX`) are for testing only and should be replaced in production.

### 3. Update Frontend Keys

Update the site key in both HTML files:

#### In `login.html`:
```javascript
const RECAPTCHA_SITE_KEY = 'YOUR_ACTUAL_SITE_KEY_HERE';
```

#### In `signup.html`:
```javascript
const RECAPTCHA_SITE_KEY = 'YOUR_ACTUAL_SITE_KEY_HERE';
```

### 4. Server Requirements

Ensure your PHP server has:
- **PHP 7.0+** (recommended 8.0+)
- **cURL extension** enabled
- **JSON extension** enabled
- **OpenSSL extension** enabled

### 5. File Permissions

Set appropriate permissions for PHP files:
```bash
chmod 644 recaptcha-config.php
chmod 644 login-process.php
chmod 644 signup-process.php
```

## 🔒 Security Features Implemented

### Backend Validation
- **Token Verification**: Secure validation with Google's API
- **IP Address Tracking**: Logs user IP for security monitoring
- **Hostname Validation**: Ensures requests come from your domain
- **Action Validation**: Verifies the intended action (login/signup)

### Rate Limiting
- **Login Attempts**: Max 5 failed attempts per 15 minutes
- **Signup Attempts**: Max 5 signup attempts per hour
- **IP-based Tracking**: Prevents brute force attacks

### Password Security
- **Bcrypt Hashing**: Uses PASSWORD_BCRYPT with cost factor 12
- **Password Requirements**: 
  - Minimum 8 characters
  - At least one uppercase letter
  - At least one lowercase letter
  - At least one number

### Input Sanitization
- **HTML Special Chars**: Prevents XSS attacks
- **Email Validation**: RFC-compliant email checking
- **File Upload Validation**: Secure image processing for profile photos

## 🎨 UI/UX Features

### Responsive Design
- **Mobile Optimized**: Scales properly on all devices
- **Dark Mode Support**: Automatically adapts to theme changes
- **Loading States**: Visual feedback during verification

### Error Handling
- **Clear Messages**: User-friendly error descriptions
- **Automatic Reset**: reCAPTCHA resets on failed attempts
- **Network Handling**: Graceful handling of connection issues

## 📱 Testing the Implementation

### Development Testing
1. Use the demo keys for initial testing
2. Test with different browsers and devices
3. Verify dark/light theme switching
4. Test form validation scenarios

### Production Testing
1. Replace demo keys with production keys
2. Test with your actual domain
3. Monitor security logs
4. Test rate limiting functionality

## 🔧 Configuration Options

### reCAPTCHA Settings
In `recaptcha-config.php`, you can customize:

```php
// Theme: 'light' or 'dark'
define('RECAPTCHA_THEME', 'light');

// Size: 'normal', 'compact', or 'invisible'
define('RECAPTCHA_SIZE', 'normal');

// Type: 'image' or 'audio'
define('RECAPTCHA_TYPE', 'image');
```

### Security Settings
```php
// Maximum login attempts
define('MAX_LOGIN_ATTEMPTS', 5);

// Lockout duration in seconds (15 minutes)
define('LOGIN_ATTEMPT_TIMEOUT', 900);

// reCAPTCHA timeout in seconds (2 minutes)
define('RECAPTCHA_TIMEOUT', 120);
```

## 📊 Monitoring and Logging

### Security Events
The system logs important security events:
- Failed login attempts
- Successful logins
- reCAPTCHA failures
- Brute force attempts
- Account creation

### Log Location
Security events are logged to PHP error logs. In production, consider implementing:
- Database logging
- Email notifications for suspicious activity
- Real-time monitoring dashboard

## 🚨 Troubleshooting

### Common Issues

#### 1. "reCAPTCHA response is missing"
**Cause**: User didn't complete the reCAPTCHA challenge
**Solution**: Ensure user completes the "I'm not a robot" checkbox

#### 2. "Invalid reCAPTCHA response format"
**Cause**: Network issue or Google API problem
**Solution**: Check internet connection and try again

#### 3. "reCAPTCHA hostname mismatch"
**Cause**: Domain not configured in Google reCAPTCHA console
**Solution**: Add your domain to the reCAPTCHA settings

#### 4. "Security verification failed"
**Cause**: Invalid or expired reCAPTCHA token
**Solution**: User should retry the verification

### Debug Mode
To enable debug logging, add this to `recaptcha-config.php`:
```php
define('RECAPTCHA_DEBUG', true);
```

## 🔄 Maintenance

### Regular Tasks
1. **Monitor Security Logs**: Review for suspicious activity
2. **Update Keys**: Rotate keys periodically for security
3. **Check Rate Limits**: Adjust based on traffic patterns
4. **Update Dependencies**: Keep PHP and extensions updated

### Performance Optimization
1. **Caching**: Implement APCu for rate limiting (already included)
2. **CDN**: Use CDN for reCAPTCHA script loading
3. **Database Optimization**: Index user email column for faster lookups

## 📞 Support

### Google reCAPTCHA Documentation
- [Official Documentation](https://developers.google.com/recaptcha)
- [Troubleshooting Guide](https://developers.google.com/recaptcha/docs/troubleshooting)

### Common Issues and Solutions
- **CORS Errors**: Ensure proper headers are set
- **Timeout Issues**: Increase PHP execution time if needed
- **Memory Issues**: Optimize image processing for profile photos

## 🎯 Best Practices

### Security
1. **Never expose Secret Key** in frontend code
2. **Use HTTPS** in production
3. **Implement IP whitelisting** for admin access
4. **Regular security audits** of authentication system

### User Experience
1. **Clear instructions** for reCAPTCHA completion
2. **Alternative verification** for accessibility (audio option)
3. **Progressive enhancement** - form works without JavaScript
4. **Mobile-friendly** interface design

### Performance
1. **Lazy loading** of reCAPTCHA when needed
2. **Minimize API calls** to Google services
3. **Optimize images** and profile photo uploads
4. **Use caching** for frequently accessed data

## 🔮 Future Enhancements

### Planned Features
1. **reCAPTCHA v3** for seamless verification
2. **Two-factor authentication** (2FA)
3. **Social login integration** (Google, Facebook)
4. **Advanced fraud detection**
5. **Biometric authentication** support

### Scalability
1. **Load balancing** for high traffic
2. **Database sharding** for user data
3. **Redis integration** for session management
4. **Microservices architecture** for authentication

---

## 📝 Quick Setup Checklist

- [ ] Get Google reCAPTCHA keys
- [ ] Update `recaptcha-config.php` with actual keys
- [ ] Update site keys in `login.html` and `signup.html`
- [ ] Test with demo keys first
- [ ] Replace with production keys for live deployment
- [ ] Monitor security logs
- [ ] Test on mobile devices
- [ ] Verify dark mode compatibility
- [ ] Check rate limiting functionality
- [ ] Document your custom configurations

---

**🎉 Your Athletiqo platform is now protected by Google reCAPTCHA v2 with comprehensive security features!**

For additional support or questions, refer to the [Google reCAPTCHA Documentation](https://developers.google.com/recaptcha) or contact your development team.
