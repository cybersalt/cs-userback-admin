# Cybersalt Userback Admin

A Joomla 5 and 6 System Plugin that injects the [Userback](https://userback.io) feedback widget into your Joomla site for easy bug reporting, screenshots, and feedback collection.

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

## Userback account, billing and data sent

Userback is a separate, paid third-party service. This plugin only connects your Joomla site to it.

- **Billing**: You need your own Userback account at [userback.io](https://userback.io). Plan fees, usage limits and invoices are between you and Userback. Cybersalt does not bill for Userback, and the plugin adds no charge of its own.
- **Access token**: Open your project in your Userback dashboard and copy the access token from the widget installation snippet. Paste it into the plugin settings, or paste the whole snippet in Full Script mode.
- **Data sent to Userback**: Whenever the widget loads, the visitor's browser sends Userback the Joomla user ID, name, email address and user group IDs, the Joomla version, the full URL of the page (including any query string), the component, view, layout and item ID, the template name, the host name, and anything you add under Custom Data Extras. Whatever the visitor types, captures or records in the feedback form is sent as well. Guests are sent as "Guest" with no email address.
- **Who is affected**: Only people who are shown the widget under the Backend and Frontend settings. If you show the widget to public visitors, mention Userback in your own privacy policy.

The same note appears at the top of the plugin settings.

---

## Installation

1. Download the latest release ZIP from the [Releases](https://github.com/cybersalt/cs-userback-admin/releases) page or download this repository as a ZIP
2. In Joomla Administrator, go to **System > Extensions > Install**
3. Upload and install the ZIP file
4. The plugin is automatically enabled with a default access token and Super Users group selected for both backend and frontend

---

## Configuration

### Basic Settings
- **Embed Mode**: *Access Token* (recommended) builds the loader from just your token. *Full Script* uses the snippet you paste from your Userback dashboard.
- **Access Token**: Your Userback project access token (required in Access Token mode). Get this from your [Userback dashboard](https://userback.io).
- **Userback Embed Script**: The full snippet (Full Script mode). The sample `user_data` that Userback puts in the snippet (for example `someone@example.com`) is ignored: the plugin always identifies the real logged-in Joomla user.
- **Native Screenshot**, **Custom Data Extras (JSON)**: See the field descriptions in the plugin settings.

### Backend Settings
- **Enable in Backend**: Show/hide the widget in the administrator area (default: Yes)
- **Backend User Groups**: Restrict which user groups see the widget in the backend. Leave empty to show to all backend users.

### Frontend Settings
- **Enable on Frontend**: Show/hide the widget on the public site (default: No)
- **Frontend User Groups**: Restrict which user groups see the widget on the frontend. Leave empty to show to all logged-in users.
- **Show to Guests**: Allow non-logged-in visitors to see the widget (default: No)

### Support Settings
- **Support Email**, **Support URL**, **Support Label**: Where to get help (defaults point to Cybersalt support). They are used in the plugin's admin messages, for example when you try to enable the plugin without an access token.

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

### Version 1.5.1 (2026-11-19)
- Disclosure of Userback billing, token location and data sent (settings note and this README)
- Support settings (email, URL, label) used in the plugin's admin messages
- Backend and Frontend User Groups use the chip-style multi-select
- Update stream now covers Joomla 5 and 6
- Fixed: in Full Script mode the sample `user_data` in the pasted snippet replaced the real Joomla user (issue #1)

### Version 1.5.0 (2026-04-20)
- Custom Data Extras JSON field for appending arbitrary key/value pairs to every feedback submission

### Version 1.4.0 (2026-04-20)
- User identification (name, email, user groups, Joomla version) attached to every submission
- Page context metadata (URL, component, view, layout, item ID, template, hostname, environment)
- Native screenshot toggle with auto-detection of localhost/staging/dev hostnames
- Separate backend and frontend categories for dashboard routing

### Version 1.3.0 (2026-04-20)
- Security fix: patched stored XSS via unsanitized access token (token now validated and JSON-encoded)

### Version 1.2.2 (March 2026)
- Added embed mode selection: access token or full script paste
- Removed default access token for security

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
- `src/cs_userbackadmin.php` - Main PHP plugin logic
- `src/cs_userbackadmin.xml` - Manifest file for installation and configuration
- `src/language/en-GB/` - English language strings

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
