<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
	const CAT_BASE_URL = "https://cataas.com/api";

	/**
	 * getImage - get images form api
	 *
	 * @return void
	 */
	public function getImages(string $url, int $limit, string $tags = null): array
	{
		Log::debug('Getting Images JSON');

		$resp = Http::get($url,
		[
			'limit' => $limit,
			'tags' => $tags,
		]);

		if (!$resp->json()) {
			throw new Exception("No Images found");
		}

		$images = array_column($resp->json(), '_id');

		return $images;
	}

	/**
	 * getImage - get image form api
	 *
	 * @return void
	 */
	public function getImage(string $url, string $id): array
	{
		Log::debug('Getting Image', [
			"image" => $id,
		]);

		ini_set('memory_limit', '1G'); // Hack

		$resp = Http::get($url . "/" . $id);

		if (!$resp) {
			throw new Exception("Image Not Found");
		}

		$content_type = $resp->header('Content-Type');

		$ext = match($content_type) {
			'image/jpeg' => '.jpg',
			'image/png' => '.png',
			'image/gif' => '.gif',
			default => dd($content_type),
		};

		$image = [
			'encoded' => $resp->body(), //base64 image
			'filename' => $id . $ext,
			'mime' => $resp->header('Content-Type'),
		];

		return $image;
	}

	/**
	 * storeImage - store image to dir
	 *
	 * @param string $dir - directory
	 * @param array $image - image obj
	 * @return void
	 */
	public function storeImage(string $dir, array $image): void
	{
		Log::debug("Storing image", [
			"dir" => $dir,
			"filename" => $image['filename'],
		]);

		$this->verifyStorage($dir);

		Storage::put($dir . "/" . $image['filename'], $image['encoded']);

		Log::debug("Image stored", [
			"dir" => $dir,
			"filename" => $image['filename'],
		]);

		return;
	}

	/**
	 * verifyStorage - check storage location
	 *
	 * @param string $dir - directory
	 * @return void
	 */
	public function verifyStorage(string $dir): void
	{
		Log::debug("Checking storage", [
			"dir" => $dir,
		]);

		$path = public_path() . "/" . $dir;

		if(!File::exists($path)) {
			File::makeDirectory($path, $mode = 0777, true, true);
			return;
		}

		if(!empty(File::files($path))) {
			throw new Exception("folder contains files");
		}
	}

	/**
	 * getTags - get tags from api
	 *
	 * @return array
	 */
	public function getTags(string $url): array
	{
		Log::debug("Getting all tags", [
			"url" => "url",
		]);

		$tags = Http::get($url);

		if(!$tags) {
			throw new Exception("Error resolving tags");
		}

		return $tags->json();
	}
}
