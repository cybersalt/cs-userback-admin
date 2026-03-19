# Cybersalt Userback Admin

A Joomla 5 System Plugin that injects the [Userback](https://userback.io) feedback widget into your Joomla site for easy bug reporting, screenshots, and feedback collection.

---

## Features

- **Backend Widget**: Display the Userback widget in Joomla Administrator
- **Frontend Widget**: Optionally display on the public frontend
- **Backend Session Detection**: Automatically show widget to users with active admin sessions when browsing the frontend
- **User Group Control**: Restrict widget visibility by Joomla user groups (backend and frontend separately)
- **Guest Access**: Optionally show widget to non-logged-in visitors
- **Token Validation**: Prevents enabling the plugin without a valid Userback access token
- **Safe Failure**: Silently fails without crashing if configuration is invalid

---

## Installation

1. Download the latest release ZIP from the [Releases](https://github.com/cybersalt/cs-userback-admin/releases) page or download this repository as a ZIP
2. In Joomla Administrator, go to **System > Extensions > Install**
3. Upload and install the ZIP file
4. The plugin is automatically enabled with a default access token and Super Users group selected for both backend and frontend

---

## Configuration

### Basic Settings
- **Access Token**: Your Userback project access token (required). Get this from your [Userback dashboard](https://userback.io).

### Backend Settings
- **Enable in Backend**: Show/hide the widget in the administrator area (default: Yes)
- **Backend User Groups**: Restrict which user groups see the widget in the backend. Leave empty to show to all backend users.

### Frontend Settings
- **Enable on Frontend**: Show/hide the widget on the public site (default: No)
- **Frontend User Groups**: Restrict which user groups see the widget on the frontend. Leave empty to show to all logged-in users.
- **Show to Guests**: Allow non-logged-in visitors to see the widget (default: No)

### How Frontend Detection Works

The plugin checks for widget visibility in this order:
1. **Backend session**: If the visitor has an active administrator session cookie, show the widget (respecting user group settings)
2. **Frontend login**: If the visitor is logged in on the frontend, check their user groups
3. **Guest**: If "Show to Guests" is enabled, show to non-logged-in visitors

This means administrators can browse the frontend and still see the feedback widget without needing to log in separately.

---

## Usage

1. Log into your Joomla site
2. The **Userback widget** appears (typically on the right side of the screen)
3. Use the widget to:
   - Report bugs with screenshots
   - Annotate issues visually
   - Provide feedback and suggestions
4. Submissions go directly to your Userback project

---

## Changelog

### Version 1.2.1 (March 2026)
- Added clickable button to open plugin settings after install or update

### Version 1.2.0 (March 2026)
- Added default Userback access token for easier initial setup
- Backend and frontend user group restrictions now default to Super Users
- Frontend widget display is now enabled by default
- Plugin is automatically enabled after installation

### Version 1.1.1 (January 2026)
- Fixed backend session detection for database and filesystem session handlers
- Fixed user group parameter handling for array and comma-separated formats
- Added MD5 and SHA-256 hash fallbacks for session ID matching

### Version 1.1.0 (January 2026)
- Added frontend widget support
- Added backend session detection for frontend display
- Added user group restrictions for backend and frontend
- Added guest access option for frontend
- Improved session detection using database queries

### Version 1.0.0 (September 2025)
- Initial release
- Backend widget injection
- Access token validation

---

## Development

This plugin consists of:
- `userbackadmin.php` - Main PHP plugin logic
- `userbackadmin.xml` - Manifest file for installation and configuration

Clone, fork, or contribute on [GitHub](https://github.com/cybersalt/cs-userback-admin)!

---

## License

GNU General Public License version 3 or later. See [LICENSE](LICENSE) for details.

---

## Credits

**Developed by [Cybersalt Consulting Ltd.](https://cybersalt.com)**

**Contributors:**
- Gurnoor Deol - Original development

---

## Support

- [Report Issues](https://github.com/cybersalt/cs-userback-admin/issues)
- [Userback Documentation](https://help.userback.io/)
