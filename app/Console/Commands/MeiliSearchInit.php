<?php

namespace App\Console\Commands;

use App\Services\MeiliSearchService;
use Illuminate\Console\Command;

class MeiliSearchInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize MeiliSearch index and populate with data';

    /**
     * Execute the console command.
     */
    public function handle(MeiliSearchService $meiliSearch)
    {
        $this->info('🚀 Initializing MeiliSearch...');

        try {
            // Initialize the index
            $this->info('📊 Creating index and configuring settings...');
            $meiliSearch->initializeIndex();

            // Index all advertisements
            $this->info('📝 Indexing advertisements...');
            $meiliSearch->indexAllAdvertisements();

            // Get stats
            $stats = $meiliSearch->getStats();
            
            $this->info('✅ MeiliSearch initialized successfully!');
            $this->info("📈 Indexed {$stats['numberOfDocuments']} documents");
            $this->info('🔍 MeiliSearch is ready at: http://localhost:7700');
            $this->info('🔑 Master key: masterKey');

        } catch (\Exception $e) {
            $this->error('❌ Failed to initialize MeiliSearch: ' . $e->getMessage());
            $this->error('Make sure MeiliSearch is running: docker-compose up -d meilisearch');
            return 1;
        }

        return 0;
    }
}
