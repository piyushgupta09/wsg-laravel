<?php

namespace Fpaipl\Prody\Http\Controllers;

use Fpaipl\Prody\Models\Tax;
use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Prody\DataTables\TaxDatatable as Datatable;

class TaxController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Prody\Models\Tax' , 
            'tax', 'taxes.index'
        );
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'hsncode' => 'required|string|max:255',
            'gstrate' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);
        
        Tax::create([
            'name' => $request->input('hsncode') . ' - GST ' . $request->input('gstrate') . '%',
            'hsncode' => $request->input('hsncode'),
            'gstrate' => $request->input('gstrate'),
            'description' => $request->input('description'),
        ]);
        
        return redirect()->route('taxes.index')->with('toast', [
            'class' => 'success',
            'text' => 'Tax created successfully.'
        ]);
    }

    public function update(Request $request, Tax $tax)
    {
        $request->validate([
            'hsncode' => 'required|string|max:255',
            'gstrate' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $tax->name = $request->input('hsncode') . ' - GST ' . $request->input('gstrate') . '%';
        $tax->hsncode = $request->input('hsncode');
        $tax->gstrate = $request->input('gstrate');
        $tax->description = $request->input('description');
        $tax->save();

        return redirect()->route('taxes.index')->with('toast', [
            'class' => 'success',
            'text' => 'Tax updated successfully.'
        ]);
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();

        return redirect()->route('taxes.index')->with('toast', [
            'class' => 'success',
            'text' => 'Tax deleted successfully.'
        ]);
    }
}
