<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advertisements = [
            [
                'title' => 'Vintage 1960s Gibson Les Paul Guitar',
                'description' => 'Beautiful vintage Gibson Les Paul in excellent condition. Perfect for collectors and musicians.',
                'content' => 'This stunning 1960s Gibson Les Paul features the classic sunburst finish and original PAF pickups. The guitar has been professionally maintained and comes with original case and documentation. Perfect for serious collectors or professional musicians looking for that authentic vintage tone. The neck is straight, frets are in excellent condition, and all electronics are working perfectly.',
                'category' => 'Musical Instruments',
                'location' => 'New York, NY',
                'price' => 8500.00,
                'contact_email' => 'guitar.collector@email.com',
                'contact_phone' => '+1-555-0123',
                'tags' => ['guitar', 'vintage', 'gibson', 'les paul', 'collectible', 'music'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(30),
            ],
            [
                'title' => 'MacBook Pro 16-inch M3 Max - Like New',
                'description' => 'Latest MacBook Pro with M3 Max chip, barely used, perfect condition.',
                'content' => 'Selling my MacBook Pro 16-inch with M3 Max chip, 32GB RAM, and 1TB SSD. Purchased 3 months ago and used very lightly. Still under Apple warranty. Comes with original box, charger, and all accessories. Perfect for video editing, software development, or any demanding tasks. No scratches or dents, looks brand new.',
                'category' => 'Electronics',
                'location' => 'San Francisco, CA',
                'price' => 3200.00,
                'contact_email' => 'tech.seller@email.com',
                'contact_phone' => '+1-555-0456',
                'tags' => ['macbook', 'apple', 'laptop', 'm3 max', 'computer', 'tech'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(21),
            ],
            [
                'title' => 'Antique Oak Dining Table Set',
                'description' => 'Beautiful solid oak dining table with 6 matching chairs, perfect for family gatherings.',
                'content' => 'This gorgeous antique oak dining table set includes a large table (seats 8) and 6 matching chairs. The table features intricate carved legs and a beautiful natural oak finish. All pieces are in excellent condition with only minor wear consistent with age. Perfect for a traditional dining room or farmhouse-style home. The chairs are comfortable and sturdy, and the table has been well-maintained over the years.',
                'category' => 'Furniture',
                'location' => 'Chicago, IL',
                'price' => 1200.00,
                'contact_email' => 'furniture.seller@email.com',
                'contact_phone' => '+1-555-0789',
                'tags' => ['furniture', 'dining table', 'oak', 'antique', 'chairs', 'home'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(45),
            ],
            [
                'title' => 'Professional Camera Equipment Package',
                'description' => 'Complete professional photography setup including camera, lenses, and accessories.',
                'content' => 'Selling my complete professional photography kit. Includes Canon EOS R5 camera body, 24-70mm f/2.8L lens, 70-200mm f/2.8L lens, 50mm f/1.4 lens, professional tripod, camera bag, memory cards, and various filters. Everything is in excellent condition and well-maintained. Perfect for wedding photography, portraits, or any professional photography work. All items come with original boxes and documentation.',
                'category' => 'Photography',
                'location' => 'Los Angeles, CA',
                'price' => 5500.00,
                'contact_email' => 'photographer@email.com',
                'contact_phone' => '+1-555-0321',
                'tags' => ['camera', 'canon', 'photography', 'lenses', 'professional', 'equipment'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(60),
            ],
            [
                'title' => 'Vintage Rolex Submariner Watch',
                'description' => 'Classic Rolex Submariner from 1980s, excellent condition, comes with papers.',
                'content' => 'This beautiful vintage Rolex Submariner dates from the early 1980s and is in excellent condition. The watch features the classic black dial and bezel, and the movement is running perfectly. Comes with original box, papers, and service records. The bracelet is in good condition with minimal stretch. This is a true investment piece that will only appreciate in value. Perfect for collectors or anyone who appreciates fine timepieces.',
                'category' => 'Jewelry & Watches',
                'location' => 'Miami, FL',
                'price' => 12500.00,
                'contact_email' => 'watch.collector@email.com',
                'contact_phone' => '+1-555-0654',
                'tags' => ['rolex', 'submariner', 'vintage', 'watch', 'luxury', 'collectible'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(90),
            ],
            [
                'title' => 'Electric Bike - Commuter Special',
                'description' => 'High-quality electric bike perfect for city commuting, low mileage.',
                'content' => 'Selling my electric bike that I used for commuting to work. It has a 500W motor, 48V battery, and can go up to 25 mph. The battery lasts about 40-50 miles on a single charge. The bike is in excellent condition with only 200 miles on it. Comes with charger, lock, and helmet. Perfect for someone looking to reduce their carbon footprint while getting around the city efficiently.',
                'category' => 'Transportation',
                'location' => 'Portland, OR',
                'price' => 1800.00,
                'contact_email' => 'bike.seller@email.com',
                'contact_phone' => '+1-555-0987',
                'tags' => ['electric bike', 'commuter', 'eco-friendly', 'transportation', 'bicycle'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(15),
            ],
            [
                'title' => 'Artisan Handmade Ceramic Dinnerware Set',
                'description' => 'Beautiful handcrafted ceramic dinnerware set for 8 people, unique design.',
                'content' => 'This stunning handmade ceramic dinnerware set includes 8 dinner plates, 8 salad plates, 8 bowls, and 8 mugs. Each piece is handcrafted by a local artisan and features a unique glazed finish. The set has a modern, minimalist design that would complement any contemporary kitchen. All pieces are food-safe and dishwasher safe. This is a one-of-a-kind set that you won\'t find anywhere else.',
                'category' => 'Home & Garden',
                'location' => 'Seattle, WA',
                'price' => 450.00,
                'contact_email' => 'ceramic.artist@email.com',
                'contact_phone' => '+1-555-0147',
                'tags' => ['ceramic', 'handmade', 'dinnerware', 'artisan', 'unique', 'kitchen'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(30),
            ],
            [
                'title' => 'Vintage Vinyl Record Collection',
                'description' => 'Large collection of classic rock and jazz vinyl records from 1960s-1980s.',
                'content' => 'Selling my extensive vinyl record collection featuring classic rock, jazz, and blues from the 1960s through 1980s. Includes original pressings of albums by The Beatles, Led Zeppelin, Miles Davis, John Coltrane, and many more. All records are in excellent condition and have been properly stored. This collection represents decades of careful curation and would be perfect for a serious music collector or someone starting their vinyl journey.',
                'category' => 'Music & Media',
                'location' => 'Austin, TX',
                'price' => 2200.00,
                'contact_email' => 'vinyl.collector@email.com',
                'contact_phone' => '+1-555-0258',
                'tags' => ['vinyl', 'records', 'music', 'vintage', 'collection', 'classic rock'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(45),
            ],
            [
                'title' => 'Professional Kitchen Knife Set',
                'description' => 'High-quality Japanese steel knife set with wooden block, perfect for chefs.',
                'content' => 'This professional-grade knife set includes 8 knives made from high-carbon Japanese steel. The set comes with a beautiful wooden storage block and includes chef\'s knife, bread knife, paring knife, utility knife, and more. Each knife is razor-sharp and perfectly balanced. The handles are ergonomically designed for comfort during long cooking sessions. This set would be perfect for a professional chef or serious home cook.',
                'category' => 'Kitchen & Dining',
                'location' => 'Boston, MA',
                'price' => 350.00,
                'contact_email' => 'chef.tools@email.com',
                'contact_phone' => '+1-555-0369',
                'tags' => ['knives', 'kitchen', 'japanese steel', 'professional', 'cooking', 'chef'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(25),
            ],
            [
                'title' => 'Outdoor Camping Gear Package',
                'description' => 'Complete camping setup including tent, sleeping bags, and cooking equipment.',
                'content' => 'Selling my complete camping gear package that I used for weekend trips. Includes a 4-person tent, 2 sleeping bags, camping stove, lantern, cooler, and various other camping accessories. Everything is in good condition and has been well-maintained. Perfect for someone looking to get into camping or upgrade their existing gear. All items come with original packaging and instructions.',
                'category' => 'Sports & Outdoors',
                'location' => 'Denver, CO',
                'price' => 650.00,
                'contact_email' => 'outdoor.gear@email.com',
                'contact_phone' => '+1-555-0741',
                'tags' => ['camping', 'outdoor', 'gear', 'tent', 'hiking', 'adventure'],
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(35),
            ],
        ];

        foreach ($advertisements as $advertisement) {
            Advertisement::create($advertisement);
        }
    }
}
