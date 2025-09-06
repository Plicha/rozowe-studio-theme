# Rozowe Studio - WordPress Development

Local WordPress development environment with Docker Compose.

## Requirements

- Docker
- Docker Compose

## Getting Started

1. **Clone the repository and navigate to the directory:**
   ```bash
   cd rozowe-studio
   ```

2. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

3. **Open your browser and go to:**
   ```
   http://localhost:8000
   ```

4. **Configure WordPress:**
   - Select language
   - Enter site details
   - Create administrator account
   - Log in to the admin panel

## Project Structure

```
rozowe-studio/
├── docker-compose.yml    # Docker configuration
├── wp-content/          # Directory with themes, plugins and uploads
├── .gitignore          # Files ignored by Git
└── README.md           # This file
```

## Database Access

- **Host:** localhost
- **Port:** 3306
- **Database:** wordpress
- **Username:** wordpress
- **Password:** wordpress

## Container Management

- **Start:** `docker-compose up -d`
- **Stop:** `docker-compose down`
- **Logs:** `docker-compose logs -f`
- **Restart:** `docker-compose restart`

## Development

### Creating Your Own Theme

1. Create a directory in `wp-content/themes/`
2. Add basic theme files (style.css, index.php, functions.php)
3. Activate the theme in WordPress admin panel

### Server Synchronization

The project is configured to work with Git. Changes in the `wp-content/` directory will be tracked by Git and can be synchronized with the production server.

## Notes

- All WordPress files are stored in Docker containers
- The `wp-content/` directory is mapped locally for easy access to themes and plugins
- The database is stored in a Docker volume 



## 🎨 Theme Development with Parcel

The Różowe Studio theme uses Parcel for modern asset building with SCSS and JavaScript.

### Theme Structure

```
wp-content/themes/rozowe-studio/
├── src/                    # Source files
│   ├── scss/              # SCSS with modular architecture
│   │   ├── abstracts/     # Variables, mixins
│   │   ├── base/          # Reset, typography
│   │   ├── components/    # Buttons, forms, Gutenberg blocks
│   │   ├── layout/        # Header, footer, sidebar
│   │   ├── pages/         # Page-specific styles
│   │   └── responsive/    # Mobile styles
│   └── js/                # JavaScript
├── dist/                   # Compiled files (committed to repo)
│   ├── css/main.css       # Main CSS file
│   └── js/main.js         # Main JS file
├── package.json           # Parcel configuration
└── functions.php          # WordPress integration
```

### Development Setup

1. **Navigate to theme directory:**
   ```bash
   cd wp-content/themes/rozowe-studio
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Build production files:**
   ```bash
   npm run build:all
   ```

### Development Commands

#### Development (with HMR)
```bash
# CSS dev server (localhost:1234)
npm run dev

# JS dev server (localhost:1235)
npm run js:dev
```

#### Production
```bash
# Build CSS only
npm run build

# Build JS only
npm run js:build

# Build all files
npm run build:all

# Watch mode - auto build on changes
npm run build:watch

# Clean dist directory
npm run clean
```

### Gutenberg Block Editor

The theme supports the Gutenberg block editor for homepage editing:

1. **Go to:** Pages > Homepage
2. **Click "Edit"** - opens Gutenberg block editor
3. **Add blocks:** text, images, galleries, buttons, columns, etc.
4. **Save changes** - content automatically appears on homepage

### Available Block Styles

- **Headings** (H1-H4) with responsive sizes
- **Paragraphs** with text alignment
- **Buttons** (different styles: filled, outlined)
- **Images** with rounded corners
- **Columns** with responsive layout
- **Galleries** with grid layout
- **Quotes** with colored border
- **Separators** for content division

### Environment Detection

The theme automatically detects environment:

- **Development** (`WP_DEBUG = true`): Uses Parcel dev server
  - CSS: `http://localhost:1234/main.scss`
  - JS: `http://localhost:1235/main.js`

- **Production**: Uses compiled files
  - CSS: `/dist/css/main.css`
  - JS: `/dist/js/main.js`
  - Fallback: original `style.css`

### SCSS Variables

Main variables in `src/scss/abstracts/_variables.scss`:

```scss
$primary-color: #3A040F;
$secondary-color: #E0DBD7;
$background-color: #F4F5F1;
$text-color: #3A040F;
$accent-color: #ff69b4;
```

### Deployment

1. **Build production files:**
   ```bash
   npm run build:all
   ```

2. **Commit and push:**
   ```bash
   git add .
   git commit -m "Build assets"
   git push
   ```

3. **On server:** Compiled files in `/dist/` are automatically used

### Troubleshooting

**Problem:** Homepage doesn't show editor content
**Solution:** Check **Settings > Reading** if "Static page" is selected and "Homepage" is set

**Problem:** Styles don't load in development
**Solution:** Make sure Parcel dev server is running (`npm run dev`)

**Problem:** Build fails
**Solution:** Check if all SCSS files exist in `src/scss/`

### Modular PHP Architecture

The theme uses a modular PHP structure for better organization and maintainability:

```
inc/
├── setup.php          # Theme setup, supports, menus, image sizes
├── assets.php         # Scripts, styles, Parcel integration
├── widgets.php        # Widget areas registration
├── navigation.php     # Menu functions and fallbacks
├── security.php       # Security enhancements
├── homepage.php       # Homepage setup and Gutenberg content
└── helpers.php        # Utility functions (excerpt, body classes, queries)
```

**Benefits:**
- ✅ **Readability** - each file has a single responsibility
- ✅ **Maintainability** - easy to find and edit functions
- ✅ **Modularity** - can enable/disable functionalities
- ✅ **Collaboration** - different people can work on different files
- ✅ **Testing** - easier to test isolated functions

**Main functions.php** (only 22 lines):
```php
<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load theme files
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/homepage.php';
require_once get_template_directory() . '/inc/helpers.php';
```
