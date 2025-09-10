#!/bin/bash

# Melli Search Docker Setup Script

echo "🚀 Setting up Melli Search with Docker..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generating application key..."
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php -r "echo 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . PHP_EOL;" >> .env

# Build and start containers
echo "🏗️  Building Docker containers..."
docker-compose build

echo "🚀 Starting containers..."
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 10

# Run migrations and seeders
echo "🗄️  Running database migrations..."
docker-compose exec app php artisan migrate --force

echo "🌱 Seeding database with sample data..."
docker-compose exec app php artisan db:seed --force

echo "✅ Setup complete!"
echo ""
echo "🌐 Your Melli Search application is now running at:"
echo "   http://localhost:8000"
echo ""
echo "📚 API Endpoints:"
echo "   GET  /api/advertisements - List all advertisements"
echo "   POST /api/advertisements - Create new advertisement"
echo "   GET  /api/advertisements/{id} - Get specific advertisement"
echo "   PUT  /api/advertisements/{id} - Update advertisement"
echo "   DELETE /api/advertisements/{id} - Delete advertisement"
echo "   GET  /api/advertisements/search/advanced - Advanced search"
echo "   GET  /api/advertisements/search/suggestions - Search suggestions"
echo "   GET  /api/health - Health check"
echo ""
echo "🔍 Example search queries:"
echo "   http://localhost:8000/api/advertisements?search=guitar"
echo "   http://localhost:8000/api/advertisements?category=Electronics"
echo "   http://localhost:8000/api/advertisements?location=New York"
echo "   http://localhost:8000/api/advertisements?min_price=1000&max_price=5000"
echo ""
echo "🛠️  Useful commands:"
echo "   docker-compose logs -f app    # View application logs"
echo "   docker-compose exec app bash  # Access container shell"
echo "   docker-compose down           # Stop containers"
echo "   docker-compose up -d          # Start containers"
