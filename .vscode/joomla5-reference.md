# Joomla 5 Component Development Reference

## 🚀 Joomla 5 Native Component Structure (PURE J5)

For **pure Joomla 5 native components**, use the modern service provider pattern:

```
com_example/
├── com_example.xml            # Component manifest (REQUIRED)
├── admin/                     # Administrator interface
│   ├── example.php           # Admin entry point (minimal dispatcher)
│   ├── services/             # Service providers (J5 NATIVE)
│   │   └── provider.php      # DI container configuration
│   ├── src/
│   │   ├── Extension/        # Component extension class
│   │   │   └── ExampleComponent.php
│   │   ├── Dispatcher/       # Component dispatcher
│   │   │   └── Dispatcher.php
│   │   ├── Controller/       # Controllers
│   │   ├── Model/           # Models
│   │   └── View/            # Views
│   ├── tmpl/                # Templates
│   ├── language/            # Language files
│   └── sql/                 # Database scripts
├── site/                     # Site (frontend) interface
│   ├── example.php          # Site entry point (minimal dispatcher)
│   ├── src/
│   │   ├── Dispatcher/      # Site dispatcher
│   │   │   └── Dispatcher.php
│   │   ├── Controller/      # Controllers
│   │   ├── Model/          # Models
│   │   └── View/           # Views
│   ├── tmpl/               # Templates
│   └── language/           # Language files
└── media/                   # Static assets (optional)
```

## 🏗️ Essential File Structure

```
com_example/
├── example.xml                 # Component manifest (REQUIRED)
├── example.php                 # Main component entry point (REQUIRED)
├── script.php                  # Installation/upgrade script (optional)
├── admin/                      # Administrator interface
│   ├── example.php            # Admin entry point (REQUIRED)
│   ├── access.xml             # Access control configuration
│   ├── config.xml             # Component configuration
│   ├── sql/                   # Database scripts
│   │   ├── install.mysql.utf8.sql
│   │   └── uninstall.mysql.utf8.sql
│   ├── src/                   # PHP classes (Joomla 5 namespace structure)
│   │   ├── Controller/        # Admin controllers
│   │   ├── Model/            # Admin models
│   │   └── View/             # Admin views
│   ├── tmpl/                 # Admin templates
│   └── language/             # Admin language files
│       └── en-GB/
├── site/                      # Site (frontend) interface
│   ├── example.php           # Site entry point (REQUIRED)
│   ├── src/                  # PHP classes (Joomla 5 namespace structure)
│   │   ├── Controller/       # Site controllers
│   │   ├── Model/           # Site models
│   │   └── View/            # Site views
│   ├── tmpl/                # Site templates
│   └── language/            # Site language files
│       └── en-GB/
└── media/                    # Static assets
    ├── css/
    ├── js/
    └── images/
```

## 📝 Component Manifest (com_example.xml)

### Joomla 5 Native Manifest (PURE J5) - COMPLETE VERSION
```xml
<?xml version="1.0" encoding="utf-8"?>
<extension type="component" version="5.0" method="upgrade">
	<name>COM_EXAMPLE</name>
	<element>com_example</element>
	<creationDate>November 2025</creationDate>
	<author>Your Name</author>
	<authorEmail>email@domain.com</authorEmail>
	<authorUrl>https://domain.com</authorUrl>
	<copyright>(C) 2025 Your Name</copyright>
	<license>GPL v2.0 or later</license>
	<version>1.0.0</version>
	<description>COM_EXAMPLE_XML_DESCRIPTION</description>
	
	<!-- Namespace for autoloading (REQUIRED for J5) -->
	<namespace path="src">YourName\Component\Example</namespace>
	
	<!-- Database Installation (CRITICAL: paths relative to admin folder) -->
	<install>
		<sql>
			<file driver="mysql" charset="utf8">sql/install.mysql.utf8.sql</file>
		</sql>
	</install>
	
	<uninstall>
		<sql>
			<file driver="mysql" charset="utf8">sql/uninstall.mysql.utf8.sql</file>
		</sql>
	</uninstall>
	
	<!-- Update schemas for version upgrades -->
	<update>
		<schemas>
			<schemapath type="mysql">sql/updates/mysql</schemapath>
		</schemas>
	</update>
	
	<!-- Administration -->
	<administration>
		<menu img="class:grid">COM_EXAMPLE</menu>
		<submenu>
			<menu link="option=com_example&amp;view=dashboard" view="dashboard" img="class:dashboard" alt="COM_EXAMPLE_MENU_DASHBOARD">COM_EXAMPLE_MENU_DASHBOARD</menu>
			<menu link="option=com_example&amp;view=items" view="items" img="class:list" alt="COM_EXAMPLE_MENU_ITEMS">COM_EXAMPLE_MENU_ITEMS</menu>
		</submenu>
		<files folder="admin">
			<filename>example.php</filename>
			<filename>access.xml</filename>
			<filename>config.xml</filename>
			<folder>services</folder>
			<folder>src</folder>
			<folder>tmpl</folder>
			<folder>language</folder>
			<folder>sql</folder>
		</files>
		<languages folder="admin/language">
			<language tag="en-GB">en-GB/com_example.ini</language>
			<language tag="en-GB">en-GB/com_example.sys.ini</language>
		</languages>
	</administration>
	
	<!-- Site Files -->
	<files folder="site">
		<filename>example.php</filename>
		<folder>src</folder>
		<folder>tmpl</folder>
		<folder>language</folder>
	</files>
	
	<languages folder="site/language">
		<language tag="en-GB">en-GB/com_example.ini</language>
		<language tag="en-GB">en-GB/com_example.sys.ini</language>
	</languages>
	
	<!-- Optional: Update server configuration -->
	<changelogurl>https://yourdomain.com/changelog/com_example.xml</changelogurl>
	<updateservers>
		<server type="extension" priority="1" name="Example Updates">https://yourdomain.com/updates/com_example.xml</server>
	</updateservers>
</extension>
```

### ⚠️ CRITICAL: SQL File Paths in Manifest

**IMPORTANT**: SQL file paths in `<install>`, `<uninstall>`, and `<update>` sections are **relative to the admin folder**, NOT prefixed with `admin/`:

✅ **CORRECT:**
```xml
<install>
	<sql>
		<file driver="mysql" charset="utf8">sql/install.mysql.utf8.sql</file>
	</sql>
</install>
```

❌ **WRONG:**
```xml
<install>
	<sql>
		<!-- This will fail - Joomla looks for admin/admin/sql/install.mysql.utf8.sql -->
		<file driver="mysql" charset="utf8">admin/sql/install.mysql.utf8.sql</file>
	</sql>
</install>
```

The actual file location is `admin/sql/install.mysql.utf8.sql`, but in the manifest you specify just `sql/install.mysql.utf8.sql` because Joomla already knows it's in the admin folder.

### Joomla 5 Native Manifest (PURE J5) - MINIMAL VERSION
```xml
<?xml version='1.0' encoding='utf-8'?>
<extension type="component" version="5.0" method="upgrade">
	<name>COM_EXAMPLE</name>
	<element>com_example</element>
	<creationDate>November 2025</creationDate>
	<author>Your Name</author>
	<authorEmail>email@domain.com</authorEmail>
	<authorUrl>https://domain.com</authorUrl>
	<copyright>(C) 2025 Your Name</copyright>
	<license>GPL v2.0 or later</license>
	<version>1.0.0</version>
	<description>Component Description</description>
	
	<!-- Namespace for autoloading (REQUIRED for J5) -->
	<namespace path="src">YourName\Component\Example</namespace>
	
	<!-- Administration -->
	<administration>
		<menu>COM_EXAMPLE</menu>
		<files folder="admin">
			<filename>example.php</filename>
			<folder>services</folder>
			<folder>src</folder>
			<folder>tmpl</folder>
			<folder>language</folder>
			<folder>sql</folder>
		</files>
		<languages folder="admin/language">
			<language tag="en-GB">en-GB/com_example.ini</language>
			<language tag="en-GB">en-GB/com_example.sys.ini</language>
		</languages>
	</administration>
	
	<!-- Site Files -->
	<files folder="site">
		<filename>example.php</filename>
		<folder>src</folder>
		<folder>tmpl</folder>
		<folder>language</folder>
	</files>
	
	<languages folder="site/language">
		<language tag="en-GB">en-GB/com_example.ini</language>
	</languages>
</extension>
```

### Legacy Compatible Manifest (J3/J4/J5)
```xml
<?xml version="1.0" encoding="utf-8"?>
<extension type="component" method="upgrade">
    <name>Example Component</name>
    <author>Your Name</author>
    <creationDate>November 2025</creationDate>
    <copyright>Copyright Text</copyright>
    <license>GPL v2.0 or later</license>
    <authorEmail>email@domain.com</authorEmail>
    <authorUrl>https://domain.com</authorUrl>
    <version>1.0.0</version>
    <description>COM_EXAMPLE_XML_DESCRIPTION</description>
    
    <!-- Joomla 5 Namespace (IMPORTANT) -->
    <namespace path="src">YourName\Component\Example</namespace>
    
    <!-- Installation script -->
    <scriptfile>script.php</scriptfile>
    
    <!-- Database -->
    <install>
        <sql>
            <file driver="mysql" charset="utf8">admin/sql/install.mysql.utf8.sql</file>
        </sql>
    </install>
    
    <uninstall>
        <sql>
            <file driver="mysql" charset="utf8">admin/sql/uninstall.mysql.utf8.sql</file>
        </sql>
    </uninstall>
    
    <!-- Media files -->
    <media folder="media" destination="com_example">
        <folder>css</folder>
        <folder>js</folder>
        <folder>images</folder>
    </media>
    
    <!-- Administration -->
    <administration>
        <menu img="class:grid">COM_EXAMPLE_MENU</menu>
        <files folder="admin">
            <folder>language</folder>
            <folder>sql</folder>
            <folder>src</folder>
            <folder>tmpl</folder>
            <filename>access.xml</filename>
            <filename>config.xml</filename>
            <filename>example.php</filename>
        </files>
        <languages folder="admin/language">
            <language tag="en-GB">en-GB/com_example.ini</language>
            <language tag="en-GB">en-GB/com_example.sys.ini</language>
        </languages>
    </administration>
    
    <!-- Site -->
    <files folder="site">
        <folder>language</folder>
        <folder>src</folder>
        <folder>tmpl</folder>
        <filename>example.php</filename>
    </files>
    
    <languages folder="site/language">
        <language tag="en-GB">en-GB/com_example.ini</language>
    </languages>
    
    <!-- Main component entry point -->
    <filename>example.php</filename>
</extension>
```

## 🎯 Entry Point Files

### Joomla 5 Native Entry Point (admin/example.php)
```php
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Joomla 5 native: Boot the component and dispatch
$app = Factory::getApplication();
$app->bootComponent('com_example')->getDispatcher($app)->dispatch();
```

### Joomla 5 Native Entry Point (site/example.php)
```php
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Joomla 5 native: Boot the component and dispatch
$app = Factory::getApplication();
$app->bootComponent('com_example')->getDispatcher($app)->dispatch();
```

### Service Provider (admin/services/provider.php)
```php
<?php
namespace YourName\Component\Example\Administrator;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use YourName\Component\Example\Administrator\Extension\ExampleComponent;

return new class () implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\YourName\\Component\\Example'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\YourName\\Component\\Example'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new ExampleComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                return $component;
            }
        );
    }
};
```

### Component Extension Class (admin/src/Extension/ExampleComponent.php)
```php
<?php
namespace YourName\Component\Example\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Psr\Container\ContainerInterface;

class ExampleComponent extends MVCComponent implements BootableExtensionInterface
{
    use HTMLRegistryAwareTrait;

    public function boot(ContainerInterface $container)
    {
        // Perform any boot tasks if needed
    }
}
```

### Component Dispatcher (admin/src/Dispatcher/Dispatcher.php)
```php
<?php
namespace YourName\Component\Example\Administrator\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcher;

class Dispatcher extends ComponentDispatcher
{
    protected $namespace = 'YourName\\Component\\Example';

    protected function checkAccess()
    {
        parent::checkAccess();

        if (!$this->app->getIdentity()->authorise('core.manage', 'com_example')) {
            throw new \Exception($this->app->getLanguage()->_('JERROR_ALERTNOAUTHOR'), 403);
        }
    }
}
```

### Site Dispatcher (site/src/Dispatcher/Dispatcher.php)
```php
<?php
namespace YourName\Component\Example\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcher;

class Dispatcher extends ComponentDispatcher
{
    protected $namespace = 'YourName\\Component\\Example';
}
```

### Legacy Entry Point Files (for backward compatibility)

### Main Component Entry (example.php)
```php
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('Example');
$input = Factory::getApplication()->getInput();
$controller->execute($input->getCmd('task'));
$controller->redirect();
```

### Admin Entry (admin/example.php)
```php
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('Example', ['base_path' => JPATH_COMPONENT_ADMINISTRATOR]);
$controller->execute(Factory::getApplication()->getInput()->get('task'));
$controller->redirect();
```

### Site Entry (site/example.php)
```php
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('Example');
$controller->execute(Factory::getApplication()->getInput()->get('task'));
$controller->redirect();
```

## 🔧 Joomla 5 MVC Classes

### Controller Template
```php
<?php
namespace YourName\Component\Example\Administrator\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class DisplayController extends BaseController
{
    protected $default_view = 'dashboard';
    
    public function display($cachable = false, $urlparams = [])
    {
        return parent::display($cachable, $urlparams);
    }
}
```

### Model Template
```php
<?php
namespace YourName\Component\Example\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class ItemsModel extends ListModel
{
    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);
        // Build your query here
        return $query;
    }
}
```

### View Template
```php
<?php
namespace YourName\Component\Example\Administrator\View\Dashboard;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    public function display($tpl = null)
    {
        $this->addToolbar();
        parent::display($tpl);
    }
    
    protected function addToolbar()
    {
        ToolbarHelper::title('Example Component');
    }
}
```

## 🔑 Critical Joomla 5 Requirements

### ✅ Must Have:
1. **Component manifest** with proper namespace
2. **Main entry point** (component.php in root)
3. **Admin entry point** (admin/component.php)
4. **Site entry point** (site/component.php) 
5. **Proper namespace structure** in src/ folders
6. **UTF-8 encoding** (not UTF-8 BOM)

### ⚠️ Common Mistakes:
- Missing main entry point file
- Wrong namespace in manifest
- Incorrect file paths in manifest
- Missing admin/site entry points
- Wrong encoding in XML

## 📦 Installation Package

### ⚠️ CRITICAL: ZIP Structure Requirements

**DO NOT use `Compress-Archive` for Joomla packages!** PowerShell's `Compress-Archive` creates files with backslash characters in their names (e.g., `admin\access.xml`) instead of actual folder structures. This causes Joomla to fail with "Unable to detect manifest file" even though the manifest exists.

### ✅ CORRECT Build Method (Proper Folder Structure):
```powershell
# This creates a ZIP with proper forward-slash folder structure
$packageName = "com_example_$(Get-Date -Format 'yyyyMMdd').zip"
Remove-Item $packageName -Force -ErrorAction SilentlyContinue

Add-Type -AssemblyName System.IO.Compression.FileSystem
$zip = [System.IO.Compression.ZipFile]::Open($packageName, [System.IO.Compression.ZipArchiveMode]::Create)

function Add-ZipEntry($zip, $file, $entryName) {
    [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file, $entryName, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null
}

# Add root files
Add-ZipEntry $zip "com_example.xml" "com_example.xml"
Add-ZipEntry $zip "com_example" "com_example"  # Entry point (no extension)
Add-ZipEntry $zip "example.php" "example.php"

# Add admin folder with proper paths
Get-ChildItem "admin" -Recurse -File | ForEach-Object {
    $relativePath = $_.FullName.Substring((Resolve-Path "admin").Path.Length + 1)
    Add-ZipEntry $zip $_.FullName "admin/$($relativePath.Replace('\', '/'))"
}

# Add site folder if it exists
if (Test-Path "site") {
    Get-ChildItem "site" -Recurse -File | ForEach-Object {
        $relativePath = $_.FullName.Substring((Resolve-Path "site").Path.Length + 1)
        Add-ZipEntry $zip $_.FullName "site/$($relativePath.Replace('\', '/'))"
    }
}

$zip.Dispose()
Write-Host "✓ Package created: $packageName" -ForegroundColor Green
```

### ❌ WRONG Method (Creates Broken Structure):
```powershell
# DON'T DO THIS - Creates backslash filenames, not folders!
Compress-Archive -Path "*.xml", "*.php", "admin", "site" -DestinationPath $name -Force
```

### Required Files in Package:
- `com_example.xml` (manifest at root)
- `com_example` (entry point file without extension)
- `example.php` (main component file)
- `admin/` folder (with proper forward-slash paths)
- `site/` folder (with proper forward-slash paths)  
- `media/` folder (if used)

## � Language Files

### Language File Structure

Components require two types of language files:

1. **`.ini`** - Runtime strings (used in views, controllers, models)
2. **`.sys.ini`** - System strings (used by Joomla system for menus, installation, descriptions)

### Admin Language Files

**Location**: `admin/language/en-GB/`

**com_example.ini** - Runtime translations:
```ini
; Component runtime language strings
COM_EXAMPLE="Example Component"
COM_EXAMPLE_TITLE="Title"
COM_EXAMPLE_DESCRIPTION="Description"
COM_EXAMPLE_SAVE_SUCCESS="Item saved successfully"
```

**com_example.sys.ini** - System translations (CRITICAL for menus):
```ini
; Component system language strings
COM_EXAMPLE="Example Component"
COM_EXAMPLE_XML_DESCRIPTION="Component description shown during installation"

; Menu items (REQUIRED for submenu display)
COM_EXAMPLE_MENU_DASHBOARD="Dashboard"
COM_EXAMPLE_MENU_ITEMS="Items"
COM_EXAMPLE_MENU_CATEGORIES="Categories"
```

### Site Language Files

**Location**: `site/language/en-GB/`

**com_example.ini** - Site runtime translations
**com_example.sys.ini** - Site system translations (menu items, module positions)

### ⚠️ CRITICAL: Submenu Language Strings

Submenu items defined in the manifest **MUST** have corresponding language strings in the **`.sys.ini`** file:

**Manifest:**
```xml
<submenu>
    <menu link="option=com_example&amp;view=dashboard">COM_EXAMPLE_MENU_DASHBOARD</menu>
    <menu link="option=com_example&amp;view=items">COM_EXAMPLE_MENU_ITEMS</menu>
</submenu>
```

**admin/language/en-GB/com_example.sys.ini:**
```ini
COM_EXAMPLE_MENU_DASHBOARD="Dashboard"
COM_EXAMPLE_MENU_ITEMS="Items"
```

If these strings are missing, the menu will display the language constant codes instead of translated text.

## �🎨 VS Code Tasks Template

```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "Build Joomla Component (CORRECT METHOD)",
            "type": "shell",
            "command": "powershell",
            "args": [
                "-Command",
                "$packageName = 'com_example_' + (Get-Date -Format 'yyyyMMdd') + '.zip'; Remove-Item $packageName -Force -ErrorAction SilentlyContinue; Add-Type -AssemblyName System.IO.Compression.FileSystem; $zip = [System.IO.Compression.ZipFile]::Open($packageName, [System.IO.Compression.ZipArchiveMode]::Create); function Add-ZipEntry($zip, $file, $entryName) { [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file, $entryName, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null }; Add-ZipEntry $zip 'com_example.xml' 'com_example.xml'; Add-ZipEntry $zip 'com_example' 'com_example'; Add-ZipEntry $zip 'example.php' 'example.php'; Get-ChildItem 'admin' -Recurse -File | ForEach-Object { $relativePath = $_.FullName.Substring((Resolve-Path 'admin').Path.Length + 1); Add-ZipEntry $zip $_.FullName \"admin/$($relativePath.Replace('\\', '/'))\"; }; if (Test-Path 'site') { Get-ChildItem 'site' -Recurse -File | ForEach-Object { $relativePath = $_.FullName.Substring((Resolve-Path 'site').Path.Length + 1); Add-ZipEntry $zip $_.FullName \"site/$($relativePath.Replace('\\', '/'))\"; }; }; $zip.Dispose(); Write-Host \"✓ Package: $packageName\" -ForegroundColor Green"
            ],
            "group": "build",
            "problemMatcher": []
        }
    ]
}
```

---

**Remember: Always test installation in a Joomla 5 development environment first!**