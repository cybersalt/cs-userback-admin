# Changelog

All notable changes to Cybersalt Userback Admin will be documented in this file.

---

## 🚀 Version 1.1.1 (January 2026)

### 🐛 Bug Fixes
- **Session Detection**: Fixed backend session detection to work with both database and filesystem session handlers
- **User Group Parsing**: Fixed handling of user group parameters in both array and comma-separated formats
- **Hash Matching**: Added MD5 and SHA-256 hash fallbacks for session ID matching across different Joomla configurations

---

## 🚀 Version 1.1.0 (January 2026)

### 📦 New Features
- **Frontend Widget Support**: Display Userback widget on the public frontend site
- **Backend Session Detection**: Automatically show widget to administrators browsing the frontend
- **User Group Restrictions (Backend)**: Limit widget visibility by `usergrouplist` selection in admin area
- **User Group Restrictions (Frontend)**: Limit widget visibility by `usergrouplist` selection on public site
- **Guest Access Option**: Optionally show widget to non-logged-in visitors

### 🔧 Improvements
- **Session Detection**: Improved backend session detection using database queries on `#__session` table
- **Documentation**: Updated plugin metadata, credits, and README

---

## 🚀 Version 1.0.0 (September 2025)

### 📦 Initial Release
- **Backend Widget Injection**: Injects Userback widget into Joomla Administrator
- **Access Token Configuration**: Configure your Userback project token via plugin parameters
- **Token Validation**: Prevents enabling plugin without a valid access token
- **Safe Failure**: Silently fails without crashing if configuration is invalid

---

## Credits

**Developed by [Cybersalt Consulting Ltd.](https://cybersalt.com)**

**Contributors:**
- Gurnoor Deol - Original development
