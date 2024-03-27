<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Fpaipl\Prody\Models\Tax;
use Illuminate\Http\Request;
use Fpaipl\Prody\Models\Category;
use Fpaipl\Prody\Models\WsgBrand;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Log;
use Fpaipl\Panel\Http\Coordinators\Coordinator;

class SyncCoordinator extends Coordinator
{
    public function taxes(Request $request, $wsgbrand)
    {
        return $this->sync($request, $wsgbrand, 'taxes');
    }

    public function brands(Request $request, $wsgbrand)
    {
        return $this->sync($request, $wsgbrand, 'brands');
    }

    public function categories(Request $request, $wsgbrand)
    {
        return $this->sync($request, $wsgbrand, 'categories');
    }

    public function collections(Request $request, $wsgbrand)
    {
        return $this->sync($request, $wsgbrand, 'collections');
    }

    private function sync(Request $request, $wsgbrand, $type)
    {
        Log::info('Syncing started ' . $type . ' for ' . $wsgbrand);

        // Validates the incoming request for a 'token'.
        $request->validate([
            'token' => 'required|string|max:255',
        ]);

        // Checks if the provided token matches the expected value.
        if ($request->input('token') != config('wsgbrand.token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token',
            ]);
        }

        // Fetches the brand from the database.
        $wsgBrand = WsgBrand::where('uuid', $wsgbrand)->first();
        if (!$wsgBrand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid brand',
            ]);
        }

        // Fetches the data based on the type.
        switch ($type) {
            case 'brands':
                $data = $wsgBrand->brands;
                break;

            case 'categories':
                $data = Category::active()->get();
                break;

            case 'collections':
                $data = Collection::nonRanged()->active()->get();
                break;

            case 'taxes':
                $data = Tax::active()->get();
                break;
            
            default: break;
        }

        // Adds the images to the models.
        $conversions = ['s100', 's300', 's500', 's800'];
        foreach ($data as $model) {

            // Checks if the model has an 'getImage' method.
            if (!method_exists($model, 'getImage')) {
                continue;
            }

            // Fetches the images for the model.
            $images = [];
            foreach ($conversions as $conversion) {
                $images[$conversion] = $model->getImage($conversion);
            }

            // Adds the images to the model.
            $model->images = $images;
        }

        // Prepares the response.
        $response = [
            'data' => $data,
            'status' => 'success',
            'message' => 'Synced successfully',
        ];

        Log::info('Synced ' . $type . ' for ' . $wsgBrand->name . ' (' . $wsgBrand->uuid . ')' . ' with ' . count($data) . ' records');

        // Returns the sales orders as a collection of resources.
        return response()->json($response);
    }
}
