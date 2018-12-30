<?php
/**
 * Created by PhpStorm.
 * User: Infinity
 * Date: 12/29/2018
 * Time: 8:43 AM
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function save(array $image, string $disk = 'items'):string
    {
        $name = $this->generateName($image['image']['name']);
        $data = $this->getImageData($image['image']['dataUrl']);
        $this->store($name, $data, $disk);

        return $name;
    }

    private function store(string $name, string $data, string $disk): void
    {
        Storage::disk($disk)->put($name, $data);
    }

    private function generateName(string $name):string
    {
        $explExtension = explode('.', $name);
        $extension = end($explExtension);
        return $explExtension[0] . '_' . time() . '.' . $extension;
    }

    private function getImageData(string $data):string
    {
        $imageExpl = explode(',', $data);
        return base64_decode(end($imageExpl));
    }
}