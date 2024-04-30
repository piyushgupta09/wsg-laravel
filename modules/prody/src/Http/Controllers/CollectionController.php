<?php

namespace Fpaipl\Prody\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Validation\Rules\File;
use Fpaipl\Prody\Http\Requests\CollectionRequest;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Prody\Http\Requests\CollectionEditRequest;
use Fpaipl\Prody\DataTables\CollectionDatatable as Datatable;

class CollectionController extends PanelController
{

    public function __construct()
    {
        parent::__construct(new Datatable(), 'Fpaipl\Prody\Models\Collection' , 'collection', 'collections.index');
    }
   
    public function store(CollectionRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'recommended'; // 'recommended', 'new', 'trending', 'best_seller', 'sale', 'clearance', 'custom
        $collection = Collection::create($data);

        if (isset($collection)) {

            $collection->addMediaFromRequest('image')
                ->toMediaCollection(Collection::MEDIA_COLLECTION_NAME);

            return redirect()->route('collections.index')->with('toast', [
                'class' => 'success',
                'text' => $this->messages['create_success']
            ]);
        } else {
            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => $this->messages['create_error']
            ]);
        }
    }

    public function update(Request $request, Collection $collection)
    {
        try {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:collections,name,' . $collection->name . ',name'],
                'order' => ['nullable', 'numeric'],
                'shade' => ['nullable', 'string'], // '#f7d5d8
                'info' => ['nullable', 'string'],
                'active' => ['nullable', 'boolean'],
                'images.*' => ['nullable', File::types(['jpg', 'webp', 'png', 'jpeg'])],    
            ]);

            $collection->update($validated);

            $collection->addMediaFromRequest('image')
                ->toMediaCollection(Collection::MEDIA_COLLECTION_NAME);

            return redirect()->route('collections.edit', $collection)->with('toast', [
                'class' => 'success',
                'text' => $this->messages['edit_success']
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => $this->messages['edit_error']
            ]);
        }
    }

    public function destroy(Request $request, Collection $collection)
    {
        $response = Collection::safeDeleteModels(
            array($collection->id), 
            'App\Models\Collection'
        );

        switch ($response) {
            case 'dependent':
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['has_dependency']
                ]);
                break;
            case 'success':
                session()->flash('toast', [
                    'class' => 'success',
                    'text' => $this->messages['delete_success']
                ]);
                break;    
            default: // failure
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['delete_error']
                ]);
                break;
        }

        return redirect()->route('collections.index');
    }
}
