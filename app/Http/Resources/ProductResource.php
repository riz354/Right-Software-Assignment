<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $images = $this->images;
        $imagesPath = [];
        if (isset($images)) {
            foreach ($images as $image) {
                $path = asset('storage/' . $image->image_path);
                $imagesPath[] = $path;
            }
        }
        return [
            'id' => $this->id ?? '-',
            'name' => $this->name ?? '-',
            'price' => $this->price ?? '-',
            'description' => $this->description ?? '-',
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'created_at' => $this->category->created_at ? $this->category->created_at->format('M d, Y') : null,
                'updated_at' => $this->category->updated_at ? $this->category->updated_at->format('M d, Y') : null,
            ] : [],
            'images' => $imagesPath,
            'created_at' => $this->created_at->format('M d, Y'),
            'updated_at' => $this->updated_at->format('M d, Y'),
        ];
    }
}
