# Changelog

All notable changes to Cybersalt Userback Admin will be documented in this file.

---

## 🚀 Version 1.4.0 (2026-04-20)

### 📦 New Features
- **User Identification**: Feedback now arrives with the submitter's name, email, user groups, and Joomla version attached. When a frontend visitor has an active backend session, the admin user is credited instead of the guest/frontend identity.
- **Page & Context Metadata**: Every submission includes the page URL, component (`option`), view, layout, item ID, active template, hostname, and environment (dev/production) — visible in the Userback dashboard sidebar.
- **Native Screenshot Toggle**: New "Native Screenshot" setting (Auto / Yes / No). Auto enables the browser's native screenshot API on localhost, `.local`, `.test`, and hostnames containing `staging` or `dev` — fixing the "feedback arrived without a screenshot" issue on private/staging sites.
- **Backend / Frontend Categories**: Separate category text fields pre-tag submissions as "Admin Backend" or "Public Site" by default so feedback is instantly routable in the Userback dashboard.

### 🔧 Improvements
- **Embed Mode Description**: Clarified in-admin that both embed modes still receive dynamic user identification, context, category, and screenshot settings from the plugin.

---

## 🚀 Version 1.3.0 (2026-04-20)

### 🔒 Security
- **XSS Fix (HIGH)**: Fixed stored XSS vulnerability in token injection. The access token is now strictly validated against an allowlist pattern and JSON-encoded before injection into the script block, preventing a user with plugin-edit ACL from breaking out of the JS string to execute arbitrary code in other admins' sessions.

---

## 🚀 Version 1.2.2 (March 2026)

### 📦 New Features
- **Embed Mode Selection**: Choose between entering just the access token or pasting the full Userback embed script
- **Custom Script Support**: Textarea for pasting the complete Userback embed script from the dashboard
- **Smart Script Handling**: Automatically strips `<script>` tags from pasted scripts

### 🔧 Improvements
- **Removed Default Token**: Access token field now defaults to blank for security

---

## 🚀 Version 1.2.1 (March 2026)

### 🔧 Improvements
- **Post-Install Settings Link**: Added clickable button to open plugin settings after install or update

---

## 🚀 Version 1.2.0 (March 2026)

### 🔧 Improvements
- **Default Access Token**: Added default Userback access token for easier initial setup
- **Default Super Users Group**: Backend and frontend user group restrictions now default to Super Users
- **Frontend Enabled by Default**: Frontend widget display is now enabled by default
- **Auto-Enable on Install**: Plugin is automatically enabled after installation

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
