<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Coupon;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\Datatables\CouponDatatable as Datatable;

class CouponController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Coupon', 
            'coupon', 'coupons.index'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|min:5|max:10',
            'type' => 'required|in:fixed,percentage',
            'applicable' => 'required|in:' . implode(',', Coupon::APPLICABLE),
            'value' => 'required|numeric|min:0|max:' . config('settings.max_coupon_value'),
            'max_value' => 'required|numeric|min:0|max:' . config('settings.max_coupon_value'),
            'min_value' => 'required|numeric|min:' . config('settings.min_coupon_value') . '|max:' . config('settings.max_coupon_value'),
            'max_usage' => 'required|numeric|min:1|max:' . config('settings.max_coupon_usage'),
            'max_usage_per_user' => 'required|numeric|min:1|max:' . config('settings.max_coupon_usage_per_user'),
            'valid_from' => 'required|date|after_or_equal:today',
            'valid_to' => 'required|date|after:valid_from',
            'active' => 'required|boolean',
            'detail' => 'nullable|string|max:255',
        ]);

        try {
            Coupon::create($validated);

            return redirect()->route('coupons.index')->with('toast', [
                'class' => 'success',
                'text' => 'Coupon created successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => 'Coupon could not be created'
            ]);
        }
    }
   
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|min:5|max:10',
            'type' => 'required|in:fixed,percentage',
            'applicable' => 'required|in:' . implode(',', Coupon::APPLICABLE),
            'value' => 'required|numeric|min:0|max:' . config('settings.max_coupon_value'),
            'max_value' => 'required|numeric|min:0|max:' . config('settings.max_coupon_value'),
            'min_value' => 'required|numeric|min:' . config('settings.min_coupon_value') . '|max:' . config('settings.max_coupon_value'),
            'max_usage' => 'required|numeric|min:1|max:' . config('settings.max_coupon_usage'),
            'max_usage_per_user' => 'required|numeric|min:1|max:' . config('settings.max_coupon_usage_per_user'),
            'valid_from' => 'required|date|after_or_equal:today',
            'valid_to' => 'required|date|after:valid_from',
            'active' => 'required|boolean',
            'detail' => 'nullable|string|max:255',
        ]);

        try {
            $coupon->update($validated);

            return redirect()->route('coupons.show', $coupon)->with('toast', [
                'class' => 'success',
                'text' => 'Coupon updated successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('toast', [
                'class' => 'danger',
                'text' => 'Coupon could not be updated'
            ]);
        }
    }

   
    public function destroy(Request $request, Coupon $coupon)
    {
        try {
            $coupon->delete();

            return redirect()->route('coupons.index')->with('toast', [
                'class' => 'success',
                'text' => 'Coupon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'class' => 'danger',
                'text' => 'Coupon could not be deleted'
            ]);
        }
    }
    
}
