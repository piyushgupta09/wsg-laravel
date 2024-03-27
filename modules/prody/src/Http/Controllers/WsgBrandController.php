<?php

namespace Fpaipl\Prody\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\Brand;
use Fpaipl\Prody\Models\WsgBrand;
use App\Http\Controllers\Controller;
use Fpaipl\Prody\Http\Requests\BrandRequest;

class WsgBrandController extends Controller
{
    public function index()
    {
        $wsgBrands = WsgBrand::all();
        return view('prody::wsg-brands.index')->with('wsgBrands', $wsgBrands);
    }
   
    public function store(BrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        if (isset($brand)) {

            $brand
                ->addMedia($request->image)
                ->preservingOriginal()
                ->toMediaCollection(Brand::MEDIA_COLLECTION_NAME);

            return redirect()->route('brands.index')->with('toast', [
                'class' => 'success',
                'text' => 'Brand created successfully.'
            ]);

        } else {

            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => 'Brand could not be created.'
            ]);

        }
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        try {
            $brand->update($request->validated());

            if ($request->hasFile('image')) {
                $brand
                    ->addMedia($request->image)
                    ->preservingOriginal()
                    ->toMediaCollection(Brand::MEDIA_COLLECTION_NAME);
            }

            return redirect()->route('brands.edit', $brand)->with('toast', [
                'class' => 'success',
                'text' => 'Brand updated successfully.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => 'Brand could not be updated.'
            ]);
        }
    }

    public function destroy(Request $request, Brand $brand)
    {
        if ($brand->productsWithTrashed->count() > 0) {
            return redirect()->back()->with('toast', [
                'class' => 'danger',
                'text' => 'Brand cannot be deleted as it has products associated with it.'
            ]);
        }

        try {

            $brand->delete();

            return redirect()->route('brands.index')->with('toast', [
                'class' => 'success',
                'text' => 'Brand deleted successfully.'
            ]);

        } catch (\Exception $e) {

            return redirect()->back()->with('toast', [
                'class' => 'danger',
                'text' => 'Brand could not be deleted.'
            ]);

        }
    }
}
