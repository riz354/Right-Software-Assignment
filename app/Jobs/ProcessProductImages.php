<?php

namespace App\Jobs;

use App\Models\ProductImage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessProductImages implements ShouldQueue
{
    use  InteractsWithQueue, Queueable;

    protected $productId;
    protected $imagesPath;

    /**
     * Create a new job instance.
     */
    public function __construct($productId, array $imagesPath)
    {
        $this->productId = $productId;
        $this->imagesPath = $imagesPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $storagePath = storage_path('app/public/products');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $manager = new ImageManager(new Driver());
        foreach ($this->imagesPath as $path) {
            $temp_path = storage_path('app/public/' . $path);
            if (file_exists($temp_path)) {
                $extension = pathinfo($temp_path, PATHINFO_EXTENSION);
                $image = $manager->read($temp_path);
                $image = $image->resize(800, 800);
                $imageName = uniqid() . '.'. $extension;
                $finalPath = 'products/' . $imageName;
                $image->save(storage_path('app/public/' . $finalPath));
                ProductImage::create([
                    'product_id' => $this->productId,
                    'image_path' => $finalPath,
                ]);
                Storage::disk('public')->delete($path);
            }
        }
    }
}
