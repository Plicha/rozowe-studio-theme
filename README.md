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

### Figma Design Tokens & Utility Classes

The theme implements a comprehensive design system based on Figma tokens with utility classes for rapid development.

#### Design Tokens

**Colors (from Figma):**
```scss
$color-black-700: #140d0d;    // Primary text
$color-black-600: #221516;    // Secondary text
$color-burgundy-500: #3a040f; // Primary brand color
$color-burgundy-200: #9a9192; // Secondary brand color
$color-white-200: #e0dbd7;    // Light background
$color-white-100: #f4f5f1;    // Main background
```

**Typography:**
```scss
$font-family-heading: 'EB Garamond', serif;  // Headings
$font-family-body: 'Lato', sans-serif;       // Body text

// Font sizes (from Figma tokens)
$font-size-xs: 12px;   // Small text
$font-size-sm: 14px;   // Small
$font-size-base: 16px; // Body text (P1)
$font-size-lg: 18px;   // Large
$font-size-xl: 20px;   // H3
$font-size-2xl: 24px;  // Medium heading
$font-size-3xl: 32px;  // H2
$font-size-4xl: 40px;  // Large heading
$font-size-5xl: 48px;  // H1
```

**Spacing (from Figma gap tokens):**
```scss
$spacing-xs: 8px;   // Extra small
$spacing-sm: 16px;  // Small
$spacing-md: 32px;  // Medium
$spacing-lg: 40px;  // Large
$spacing-xl: 80px;  // Extra large
```

#### Utility Classes

**Color Utilities:**
```html
<!-- Text colors -->
<h1 class="text-burgundy-500">Primary heading</h1>
<p class="text-black-700">Main text</p>
<span class="text-burgundy-200">Secondary text</span>

<!-- Background colors -->
<div class="bg-white-100">Light background</div>
<section class="bg-burgundy-500">Dark section</section>

<!-- Border colors -->
<div class="border border-burgundy-500">Bordered element</div>
```

**Typography Utilities:**
```html
<!-- Font families -->
<h1 class="font-eb-garamond">Heading with EB Garamond</h1>
<p class="font-lato">Body text with Lato</p>

<!-- Font weights -->
<p class="font-light">Light text</p>
<p class="font-medium">Medium text</p>
<p class="font-bold">Bold text</p>

<!-- Font sizes -->
<h1 class="text-5xl">Large heading (48px)</h1>
<h2 class="text-3xl">Medium heading (32px)</h2>
<p class="text-base">Body text (16px)</p>
<p class="text-sm">Small text (14px)</p>
```

**Spacing Utilities:**
```html
<!-- Margin -->
<div class="m-md">Medium margin all around</div>
<div class="mt-lg mb-sm">Large top, small bottom margin</div>
<div class="mx-auto">Auto horizontal margin (centering)</div>

<!-- Padding -->
<div class="p-lg">Large padding all around</div>
<div class="px-md py-sm">Medium horizontal, small vertical padding</div>
```

**Layout Utilities:**
```html
<!-- Display -->
<div class="flex justify-center items-center">Centered flex container</div>
<div class="grid grid-cols-2 gap-md">2-column grid</div>
<div class="hidden mobile:block">Hidden on desktop, visible on mobile</div>

<!-- Flexbox -->
<div class="flex flex-col mobile:flex-row">
  <div class="flex-1">Flexible item</div>
</div>
```

**Border & Shadow Utilities:**
```html
<!-- Borders -->
<div class="border border-burgundy-500 rounded-lg">Rounded border</div>
<div class="border-2 border-t-0">Custom border</div>

<!-- Shadows -->
<div class="shadow-lg">Large shadow</div>
<div class="shadow-none">No shadow</div>
```

**Responsive Utilities:**
```html
<!-- Mobile first approach -->
<div class="text-center mobile:text-left">Center on desktop, left on mobile</div>
<div class="hidden mobile:flex">Hidden on desktop, flex on mobile</div>
<div class="flex mobile:hidden">Flex on desktop, hidden on mobile</div>

<!-- Tablet and desktop -->
<div class="mobile:hidden tablet:block">Hidden on mobile, block on tablet+</div>
<div class="desktop:flex">Flex only on desktop</div>
```

#### Complete Example

```html
<section class="bg-white-100 p-xl">
  <div class="container mx-auto">
    <h1 class="text-burgundy-500 font-eb-garamond text-5xl text-center mb-lg">
      Welcome to Różowe Studio
    </h1>
    <p class="text-black-700 font-lato text-base text-center mb-md max-w-2xl mx-auto">
      We create beautiful and functional websites with modern design.
    </p>
    <div class="flex justify-center mobile:flex-col gap-md">
      <button class="bg-burgundy-500 text-white-100 px-lg py-sm rounded font-medium hover:bg-black-700 transition-colors">
        Get Started
      </button>
      <button class="border border-burgundy-500 text-burgundy-500 px-lg py-sm rounded font-medium hover:bg-burgundy-500 hover:text-white-100 transition-colors">
        Learn More
      </button>
    </div>
  </div>
</section>
```

#### Benefits

- ✅ **Consistent Design** - All colors, spacing, and typography follow Figma tokens
- ✅ **Rapid Development** - Utility classes for quick styling
- ✅ **Responsive** - Mobile-first approach with responsive utilities
- ✅ **Maintainable** - Centralized design system in SCSS variables
- ✅ **Flexible** - Mix utility classes with custom CSS as needed

