# Melli Search

A powerful Laravel-based advertisement search system with advanced search capabilities, built with Docker support.

## Features

- 🔍 **Advanced Search**: Multi-field search with relevance scoring
- 📊 **Filtering**: Category, location, price range, and date filters
- 🏷️ **Tagging System**: Flexible tagging for better categorization
- 📱 **RESTful API**: Complete CRUD operations with JSON responses
- 🐳 **Docker Support**: Easy deployment with Docker and Docker Compose
- 💾 **SQLite Database**: Lightweight database with sample data
- 🚀 **Performance Optimized**: Indexed database queries and efficient search algorithms

## Quick Start with Docker

### Prerequisites

- Docker
- Docker Compose

### Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd melli-search
```

2. Run the setup script:
```bash
./docker-setup.sh
```

3. Access the application:
- Web: http://localhost:8000
- API: http://localhost:8000/api

## API Endpoints

### Advertisement Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/advertisements` | List all advertisements with search/filter options |
| POST | `/api/advertisements` | Create a new advertisement |
| GET | `/api/advertisements/{id}` | Get specific advertisement |
| PUT | `/api/advertisements/{id}` | Update advertisement |
| DELETE | `/api/advertisements/{id}` | Delete advertisement |

### Search & Discovery

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/advertisements/search/advanced` | Advanced search with relevance scoring |
| GET | `/api/advertisements/search/suggestions` | Get search suggestions |
| GET | `/api/health` | Health check endpoint |

## Search Parameters

### Basic Search
```
GET /api/advertisements?search=guitar
```

### Advanced Filtering
```
GET /api/advertisements?search=macbook&category=Electronics&location=San Francisco&min_price=2000&max_price=4000
```

### Sorting
```
GET /api/advertisements?sort_by=price&sort_order=asc
```

### Pagination
```
GET /api/advertisements?page=2&per_page=10
```

## Advertisement Schema

```json
{
  "id": 1,
  "title": "Vintage 1960s Gibson Les Paul Guitar",
  "description": "Beautiful vintage Gibson Les Paul in excellent condition...",
  "content": "This stunning 1960s Gibson Les Paul features...",
  "category": "Musical Instruments",
  "location": "New York, NY",
  "price": 8500.00,
  "contact_email": "guitar.collector@email.com",
  "contact_phone": "+1-555-0123",
  "tags": ["guitar", "vintage", "gibson", "les paul", "collectible", "music"],
  "is_active": true,
  "expires_at": "2024-10-10T00:00:00.000000Z",
  "created_at": "2024-09-10T08:10:23.000000Z",
  "updated_at": "2024-09-10T08:10:23.000000Z"
}
```

## Search Algorithm

The Melli search system uses a sophisticated multi-field search algorithm:

1. **Field Priority Scoring**:
   - Title matches: 10 points
   - Content matches: 5 points
   - Description matches: 3 points
   - Category matches: 3 points
   - Location matches: 3 points
   - Tag matches: 1 point

2. **Search Features**:
   - Multi-word search support
   - Case-insensitive matching
   - Partial word matching
   - JSON tag searching
   - Relevance-based sorting

## Development Setup

### Without Docker

1. Install dependencies:
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

4. Start development server:
```bash
php artisan serve
npm run dev
```

### With Docker (Development)

1. Build and start containers:
```bash
docker-compose up -d
```

2. Run migrations and seeders:
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

## Sample Data

The application comes with 10 sample advertisements across various categories:

- Musical Instruments (Vintage Gibson Les Paul)
- Electronics (MacBook Pro M3 Max)
- Furniture (Antique Oak Dining Set)
- Photography (Professional Camera Kit)
- Jewelry & Watches (Vintage Rolex Submariner)
- Transportation (Electric Bike)
- Home & Garden (Ceramic Dinnerware)
- Music & Media (Vinyl Collection)
- Kitchen & Dining (Professional Knife Set)
- Sports & Outdoors (Camping Gear)

## Docker Commands

```bash
# Start containers
docker-compose up -d

# View logs
docker-compose logs -f app

# Access container shell
docker-compose exec app bash

# Stop containers
docker-compose down

# Rebuild containers
docker-compose build --no-cache
```

## Environment Variables

Key environment variables for configuration:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:YourAppKeyHere
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

## Performance Considerations

- Database indexes on frequently searched fields
- Pagination to limit result sets
- Efficient query building with Eloquent scopes
- Redis caching support (configured but not required)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please open an issue in the repository.