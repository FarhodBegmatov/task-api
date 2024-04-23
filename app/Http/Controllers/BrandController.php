<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreRequest;
use App\Http\Requests\Brand\UpdateRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::query()->paginate();
        return success_out(BrandResource::collection($brand), true);
    }

    public function store(StoreRequest $request)
    {
        $request->validated();

        if ($request->file()) {
            $fileName = time().'-'.$request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('brands', $fileName, 'public');
        }

        $brand = Brand::query()->create([
            'name' => $request->get('name'),
            'photo_url' => $fileName ?? null
        ]);
        return success_out(BrandResource::make($brand));
    }

    public function show(Brand $brand)
    {
        return success_out(BrandResource::make($brand));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'nullable|string|unique:brands,name,'.$brand->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->file()) {
            if ($brand->photo_url)
                Storage::delete('brands/' . $brand->photo_url);

            $fileName = $request->photo->getClientOriginalName();
            $request->file('photo')->storeAs('brands', $fileName, 'public');
            $data['photo_url'] = $fileName;
        }

        $brand->update($data);
        return success_out(BrandResource::make($brand));
    }

    public function destroy(Brand $brand)
    {
        if ($brand->delete() && $brand->photo_url)
            Storage::delete('brands/' . $brand->photo_url);

        return success_out(BrandResource::make($brand),false,'Brand deleted successfully');
    }
}
