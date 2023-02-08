<?php

namespace Tests\Feature;

use App\Console\Commands\GetCat;
use Facades\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{
    /**
     * Test getImage()
     *
     * @return void
     */
    public function test_imageFeature()
    {
        $dir = "test_dir";
        $limit = 1;
        $tags = "cute,fluffy";

        // Http::mock();
        // Storage::mock();
        $cats = ImageService::getImages(GetCat::BASE_URL . "/api/cats",
                                        $limit,
                                        $tags
                                    );

        $this->assertCount($limit, $cats);

        foreach($cats as $cat) {
            // Get Cat
            $cat = ImageService::getImage(GetCat::BASE_URL . "/cat", $cat);

            $this->assertCount($limit, $cat);

            // Store
            ImageService::storeImage($dir, $cat);
            Storage::assertExists($dir . "/" . $cat['filename']);
        }
    }
}

