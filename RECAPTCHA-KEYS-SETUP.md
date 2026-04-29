# Google reCAPTCHA API Keys Setup Guide

## 🚨 Current Issue: "Google reCAPTCHA Loading Issue"

The error message indicates the current API keys are not working properly. You need to get your own API keys from Google.

## 🔑 Why New API Keys Are Needed:

### **Current Keys (Demo/Testing):**
```
Site Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEbQjVyyTy_8fX
Secret Key: 6LeIxAcTAAAAAGG-vFI1TnRWxMZDFuOnJxP1Rz7x
```
**Problems:**
- Limited to specific domains
- Usage restrictions
- May not support image challenges properly
- Not meant for production use

### **Solution: Your Own API Keys**
- ✅ Work with your specific domain
- ✅ Full image-selection challenge support
- ✅ No usage limitations
- ✅ Proper Google support

## 📋 Step-by-Step Setup:

### **Step 1: Create reCAPTCHA Keys**
1. Go to: https://www.google.com/recaptcha/admin/create
2. Sign in with your Google account
3. Fill in the form:
   - **Label**: `Athletiqo Sports Website`
   - **reCAPTCHA type**: `reCAPTCHA v2 ("I'm not a robot" Checkbox)`
   - **Domains**: 
     - `localhost`
     - `127.0.0.1`
     - `yourdomain.com` (add your production domain)

### **Step 2: Copy Your Keys**
After submission, you'll get:
```
Site Key: YOUR_NEW_SITE_KEY_HERE
Secret Key: YOUR_NEW_SECRET_KEY_HERE
```

### **Step 3: Update Configuration Files**

#### **File 1: recaptcha-config.php**
```php
// Replace these lines:
define('RECAPTCHA_SITE_KEY', 'YOUR_NEW_SITE_KEY_HERE');
define('RECAPTCHA_SECRET_KEY', 'YOUR_NEW_SECRET_KEY_HERE');
```

#### **File 2: signup.html**
```javascript
// Find and replace this line:
const RECAPTCHA_SITE_KEY = 'YOUR_NEW_SITE_KEY_HERE';
```

#### **File 3: login.html**
```javascript
// Find and replace this line:
const RECAPTCHA_SITE_KEY = 'YOUR_NEW_SITE_KEY_HERE';
```

## 🎯 Expected Results After Setup:

### **Before (Current):**
- ❌ "Google reCAPTCHA Loading Issue"
- ❌ Falls back to enhanced canvas security
- ❌ No image-selection challenges

### **After (With Your Keys):**
- ✅ "I'm not a robot" checkbox appears
- ✅ Image-selection challenges load properly
- ✅ Traffic lights, buses, bicycles, crosswalks challenges
- ✅ Smooth user experience

## 🔍 Testing Your New Keys:

### **1. Test with Standalone Page:**
Open: `http://localhost:8000/recaptcha-test.html`

### **2. Test with Signup Page:**
Open: `http://localhost:8000/signup.html`

### **3. Expected Flow:**
1. Checkbox appears ✅
2. Click checkbox → Image challenge loads ✅
3. Select images (traffic lights, buses, etc.) ✅
4. Green checkmark appears ✅
5. Account creation works ✅

## 🚨 Important Notes:

### **Domain Configuration:**
- **For Development**: Add `localhost` and `127.0.0.1`
- **For Production**: Add your actual domain
- **Multiple Domains**: Add all domains you'll use

### **Key Security:**
- **Site Key**: Public (used in frontend)
- **Secret Key**: Private (used only in backend PHP)
- **Never share your Secret Key**

### **Troubleshooting:**
- If keys don't work: Double-check domain configuration
- Clear browser cache after updating keys
- Wait 5-10 minutes for Google to register new keys

## 🔄 Quick Fix Summary:

1. **Get keys** from Google reCAPTCHA admin
2. **Update 3 files** with your new keys
3. **Test** with recaptcha-test.html
4. **Enjoy** working image-selection challenges!

## 📞 Need Help?

If you encounter issues:
1. Check domain spelling in Google reCAPTCHA console
2. Ensure keys are copied correctly (no extra spaces)
3. Wait a few minutes after key creation
4. Test with the standalone test page first

---

**⚡ After getting your own API keys, the image-selection challenges (traffic lights, buses, bicycles, crosswalks) will work perfectly!**
