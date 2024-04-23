<?php

namespace App\Http\Resources\Branch;

use App\Http\Resources\Brand\BrandResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            //'brand_id'=> $this->brand_id,
            'brand'=> BrandResource::make($this->brand),
            'name' => $this->name,
            'region' => $this->region,
            'district' => $this->district,
            'images' => BranchImageResource::collection($this->files)
        ];
    }
}
