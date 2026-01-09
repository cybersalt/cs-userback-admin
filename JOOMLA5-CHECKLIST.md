# Joomla 5 Package Development Checklist

## Before Starting Development
- [ ] Use `version="5.0"` in all manifests (not 3.0)
- [ ] Include `<element>com_yourname</element>` in component manifests
- [ ] Plan your folder structure early
- [ ] **USE JOOMLA NATIVE LIBRARIES ONLY** - Essential for true J5 native status

## Joomla 5 Native Principles ⭐
- [ ] **Archive Handling**: Use `Joomla\Archive\Archive` instead of PCLZip/ZipArchive
- [ ] **File Operations**: Use `Joomla\CMS\Filesystem\File` and `Joomla\CMS\Filesystem\Folder`
- [ ] **Database**: Use `Joomla\Database\DatabaseInterface` and `Joomla\CMS\Factory::getDbo()`
- [ ] **HTTP Requests**: Use `Joomla\CMS\Http\HttpFactory` instead of cURL/file_get_contents
- [ ] **Caching**: Use `Joomla\CMS\Cache\CacheControllerFactory`
- [ ] **Configuration**: Use `Joomla\CMS\Component\ComponentHelper::getParams()`
- [ ] **Events**: Use `Joomla\CMS\Plugin\CMSPlugin` and `SubscriberInterface`
- [ ] **Language**: Use `Joomla\CMS\Language\Text` instead of custom solutions
- [ ] **Session**: Use `Joomla\CMS\Session\Session` instead of PHP sessions
- [ ] **Input**: Use `Joomla\CMS\Factory::getApplication()->getInput()`

**RULE**: Always prefer Joomla's built-in libraries over third-party solutions!

## Manifest Requirements
- [ ] Package manifest: `<targetplatform name="joomla" version="5.*" />`
- [ ] Component manifest: Both `<files>` (site) and `<administration><files folder="admin">` (admin) sections
- [ ] Media section: `<media folder="admin/media">` if media is in admin folder
- [ ] Component entry point: `com_componentname.php` in site files

## File Structure
- [ ] Site files at ZIP root: `com_component.php`, `index.html`
- [ ] Admin files in `admin/` folder
- [ ] Media files path matches manifest declaration
- [ ] All PHP files start with `<?php` (no BOM, no whitespace before)

## ZIP Creation
- [ ] Use forward slashes `/` in ZIP paths (not backslashes `\`)
- [ ] Component manifest named to match element: `com_component.xml`
- [ ] No intermediate build artifacts in final package

## Common Joomla 5 Issues
- [ ] Modern namespace usage (`use Joomla\CMS\Factory` instead of `JFactory`)
- [ ] Event subscription interface for plugins
- [ ] Updated form field types and attributes
- [ ] Proper access control with `core.manage` permissions
- [ ] CSS/JS loading through proper Joomla 5 methods
- [ ] **CRITICAL**: Replace all third-party libraries with Joomla native equivalents
- [ ] Remove deprecated PHP functions (ereg, magic_quotes, create_function, etc.)

## Third-Party Library Replacements (MANDATORY for J5 Native)
- [ ] **PCLZip** → `Joomla\Archive\Archive`
- [ ] **PHPMailer** → `Joomla\CMS\Mail\MailerFactory`
- [ ] **cURL** → `Joomla\CMS\Http\HttpFactory`
- [ ] **Custom DB classes** → `Joomla\Database\DatabaseInterface`
- [ ] **Custom file handlers** → `Joomla\CMS\Filesystem\*`
- [ ] **jQuery/Bootstrap** → Use Joomla's included versions via `HTMLHelper`
- [ ] **Custom session handling** → `Joomla\CMS\Session\Session`

## Native Library Benefits
- **Security**: No third-party vulnerabilities or outdated dependencies
- **Performance**: Optimized specifically for Joomla's architecture
- **Compatibility**: Guaranteed to work with current and future Joomla versions
- **Updates**: Automatic security updates with Joomla core updates
- **Support**: Full community and official support for native solutions
## Testing
- [ ] Test installation on clean Joomla 5 site
- [ ] Verify no warnings during installation
- [ ] Check that all files are copied to correct locations
- [ ] Test uninstallation (leaves no orphaned files)
- [ ] Test upgrade installation over existing version
- [ ] Verify CSS loads properly and no duplicate stylesheets exist
- [ ] Test in both light and dark mode themes
- [ ] Check browser cache doesn't interfere with new styles
- [ ] **Test all native library implementations work correctly**

## CSS and Assets (Joomla 5 Best Practices)
- [ ] Use `component/resources/css/` for component stylesheets (not `media/com_component/css/`)
- [ ] Load CSS via controller: `$document->addStyleSheet('components/com_component/resources/css/stylesheet.css')`
- [ ] Remove legacy Joomla 2.5/3 CSS code and comments
- [ ] Implement proper dark/light mode CSS using Bootstrap 5 variables
- [ ] Clean up duplicate CSS files from old `media/` folder structure

## Installation Script Enhancements
- [ ] Add cleanup code in `postflight()` to remove old conflicting files
- [ ] Handle upgrade scenarios by cleaning old `media/com_component/` folders
- [ ] Implement proper update server configuration
- [ ] Test installation over existing versions (upgrade path)

## Common Issues to Avoid
- ❌ Wrong schema version (3.0 instead of 5.0)
- ❌ Missing <element> tag
- ❌ Media folder path mismatch
- ❌ Windows backslashes in ZIP paths
- ❌ Missing site component entry point
- ❌ BOM in PHP files
- ❌ "_new" suffixes in filenames
- ❌ Legacy CSS files conflicting with new Joomla 5 structure
- ❌ Missing cleanup in installation script for upgrade scenarios
- ❌ Using old `media/com_component/css/` instead of `component/resources/css/`

## StageIt v5.2.0 Lessons Learned
### Package Structure Evolution
- **Problem**: Old `media/com_stageit/css/` conflicted with new `component/resources/css/` structure
- **Solution**: Automated cleanup in installation script (`script.php`) to remove old paths
- **Lesson**: Always include cleanup code for upgrade scenarios

### CSS Architecture 
- **Problem**: Legacy Joomla 2.5/3 CSS caused confusion and bloat
- **Solution**: Complete removal of outdated code, proper J5 Bootstrap 5 integration
- **Lesson**: Clean up legacy code completely, don't just comment it out

### Button Styling Specifics
- **Problem**: Generic CSS variables made buttons unreadable in light mode
- **Solution**: Fixed colors (#0b5ed7 light, #59a645 hover) with proper specificity
- **Lesson**: Sometimes you need specific colors rather than generic theme variables

### Installation Script Robustness
- **Enhancement**: Added `cleanupOldFiles()` method to handle conflicting legacy files
- **Benefit**: Seamless upgrades without manual intervention
- **Lesson**: Think about the upgrade path, not just fresh installs