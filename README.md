# MeiliSearch

A simple Laravel application with MeiliSearch integration for fast, powerful search functionality.

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Start MeiliSearch
```bash
docker-compose up -d meilisearch
```

### 4. Setup Database & Search
```bash
php artisan migrate
php artisan db:seed
php artisan meilisearch:init
```

### 5. Start Laravel Server
```bash
php artisan serve
```

## 🔍 Search API Endpoints

### Basic Search
```bash
# Search for "guitar"
curl "http://localhost:8000/api/advertisements?search=guitar"

# Search with filters
curl "http://localhost:8000/api/advertisements?search=laptop&category=Electronics&min_price=1000"
```

### Advanced Search
```bash
# Advanced search with relevance scoring
curl "http://localhost:8000/api/advertisements/search/advanced?search=vintage"

# Get search suggestions
curl "http://localhost:8000/api/advertisements/search/suggestions?q=mac"
```

### CRUD Operations
```bash
# Get all advertisements
curl "http://localhost:8000/api/advertisements"

# Get specific advertisement
curl "http://localhost:8000/api/advertisements/1"

# Create new advertisement
curl -X POST "http://localhost:8000/api/advertisements" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Item","description":"Description","content":"Content","category":"Electronics","location":"New York","price":299.99}'

# Update advertisement
curl -X PUT "http://localhost:8000/api/advertisements/1" \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title"}'

# Delete advertisement
curl -X DELETE "http://localhost:8000/api/advertisements/1"
```

## 🏗️ Architecture

- **Laravel 12**: PHP framework
- **MeiliSearch**: Fast search engine
- **SQLite**: Database
- **Docker**: MeiliSearch container

## 📁 Key Files

- `app/Models/Advertisement.php` - Advertisement model
- `app/Http/Controllers/AdvertisementController.php` - API controller
- `app/Services/MeiliSearchService.php` - MeiliSearch integration
- `routes/api.php` - API routes
- `docker-compose.yml` - MeiliSearch container

## 🔧 Configuration

MeiliSearch settings in `.env`:
```
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=masterKey
MEILISEARCH_TIMEOUT=10
```

## 📊 Search Features

- **Multi-field search**: Title, content, description, category, location, tags
- **Relevance scoring**: Prioritizes matches in title and content
- **Faceted search**: Filter by category, location, price range
- **Search suggestions**: Auto-complete functionality
- **Fast indexing**: Real-time search updates

## 🐳 Docker (Optional)

For full Docker setup:
```bash
docker-compose up -d
```

This starts:
- **MeiliSearch**: http://localhost:7700
- **Redis**: http://localhost:6379
- **Laravel App**: http://localhost:8000

## 🧪 Testing

```bash
# Health check
curl "http://localhost:8000/api/health"

# Search test
curl "http://localhost:8000/api/advertisements?search=guitar"

# MeiliSearch direct
curl "http://localhost:7700/health"
```

## 📝 Sample Data

The seeder creates 10 sample advertisements with various categories:
- Electronics (MacBook, iPhone, Camera)
- Vehicles (Vintage Car, Motorcycle)
- Furniture (Vintage Chair, Dining Table)
- Musical Instruments (Guitar, Piano)
- Books (Programming Book)

## 🚀 Production Notes

- Change `MEILISEARCH_KEY` in production
- Use proper database (MySQL/PostgreSQL) instead of SQLite
- Configure proper CORS settings
- Add authentication if needed
- Set up proper logging and monitoring
