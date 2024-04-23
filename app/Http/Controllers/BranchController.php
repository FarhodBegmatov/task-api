<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreRequest;
use App\Http\Requests\Branch\UpdateRequest;
use App\Http\Resources\Branch\BranchResource;
use App\Models\Branch;
use App\Models\BranchImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function index()
    {
        $branch = Branch::query()
            ->with(['brand', 'files'])
            ->paginate();
        return success_out(BranchResource::collection($branch),true);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $branch = Branch::query()->create($data);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $image) {
                $fileName = time().'-'.$image->getClientOriginalName();
                $image->storeAs('branches', $fileName, 'public');

                BranchImage::query()->create([
                    'path' => $fileName,
                    'branch_id' => $branch->id
                ]);
            }
        }
        return success_out(BranchResource::make($branch));
    }

    public function show(Branch $branch)
    {
        return success_out(BranchResource::make($branch));
    }

    public function update(UpdateRequest $request, Branch $branch)
    {
        $data = $request->validated();

        if ($request->hasFile('files')) {
            foreach ($branch->files as $image) {
                Storage::delete('branches/' . $image->path);
            }
            foreach ($request->file('files') as $image) {
                $fileName = time().'-'.$image->getClientOriginalName();
                $image->storeAs('branches', $fileName, 'public');

                BranchImage::query()->update([
                    'path' => $fileName,
                    'branch_id' => $branch->id
                ]);
            }
        }
        $branch->update($data);

        return success_out(BranchResource::make($branch));
    }

    public function destroy(Branch $branch)
    {
        if ($branch->delete() && $branch->files)
            foreach ($branch->files as $image) {
                Storage::delete('branches/' . $image->path);
            }

        return success_out(BranchResource::make($branch),false,'Branch deleted successfully');
    }

    public function branchCountByRegion()
    {
        $branchCounts = Branch::query()->select('region', 'brand_id', DB::raw('count(*) as branch_count'))
            ->groupBy('region', 'brand_id')
            ->get();

        return response()->json(['branch_counts' => $branchCounts], 200);
    }

    public function branchCountByDistrict()
    {
        $branchCounts = Branch::query()->select('district', 'brand_id', DB::raw('count(*) as branch_count'))
            ->groupBy('district', 'brand_id')
            ->get();

        return response()->json(['branch_counts' => $branchCounts], 200);
    }
}
