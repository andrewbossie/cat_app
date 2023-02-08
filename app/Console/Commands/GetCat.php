<?php

namespace App\Console\Commands;

use App\Services\ImageService as ServicesImageService;
use Facades\ImageService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use ImageServiceFacade;

class GetCat extends Command
{
    const BASE_URL = "https://cataas.com";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:get
                            {dir : directory}
                            {lim : return limit}
                            {tags? : OPTIONAL filtering tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get cats from API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::debug("Handling Command", [
            "command" => static::class,
        ]);

        $dir = str_replace("/", "", $this->argument("dir"));
        $limit = intval($this->argument("lim"));
        $tags = $this->argument("tags");
        $exploded_tags = explode(",", $tags);
        $diff_tags = [];

        if ($exploded_tags) {
            $ext_tags = ImageService::getTags(GetCat::BASE_URL . "/api/tags");
            $diff_tags = array_diff($exploded_tags, $ext_tags);
        }

        if(count($diff_tags)) {
            throw new Exception("Found tags not present remotely");
        }

        $cats = ImageService::getImages(GetCat::BASE_URL . "/api/cats",
                                        $limit,
                                        $tags
                                    );

        foreach($cats as $cat) {
            // Get Cat
            $cat = ImageService::getImage(GetCat::BASE_URL . "/cat", $cat);

            // Store
            ImageService::storeImage($dir, $cat);
        }
    }
}
