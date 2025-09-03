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

