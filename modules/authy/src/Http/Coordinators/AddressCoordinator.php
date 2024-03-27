<?php

namespace Fpaipl\Authy\Http\Coordinators;

use App\Helpers\Responder;
use Illuminate\Http\Request;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Resources\AddressResource;
use Fpaipl\Authy\Http\Requests\CreateAddressRequest;
use Fpaipl\Authy\Http\Requests\UpdateAddressRequest;


class AddressCoordinator extends Coordinator
{
    public $country = 'india';

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if(!$user) {
            return Responder::error('Unauthorized', 401);
        }
        
        $addresses = $user->addresses()->get();
        return Responder::ok(null, AddressResource::collection($addresses));
    }

    public function store(CreateAddressRequest $request)
    {
        try {

            /** @var \App\Models\User $user */
            $user = auth()->user();

            if(!$user) {
                return Responder::error('Unauthorized', 401);
            }
    
            $addressData = $request->validated();
            $newAddress = Address::create([
                'name' => $addressData['name'],
                'contacts' => $addressData['contacts'],
                'line1' => $addressData['line1'],
                'line2' => $addressData['line2'],
                'district' => isset($addressData['district']) ? $addressData['district'] : 'district',
                'state' => isset($addressData['state']) ? $addressData['state'] : 'state',
                'country' => isset($addressData['country']) ? $addressData['country'] : $this->country,
                'pincode' => $addressData['pincode'],
                'gstin' => isset($addressData['gstin']) ? $addressData['gstin'] : null,
                'pan' => isset($addressData['pan']) ? $addressData['pan'] : null,
                'addressable_id' => $user->id,
                'addressable_type' => get_class($user),
            ]);
            $newAddress->print = $this->generatePrint($newAddress);
            $newAddress->save();

            $addresses = $user->addresses()->get();
            return Responder::ok(null, AddressResource::collection($addresses));

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }    

    public function show(Address $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $address->addressable_id != $user->id) {
            return Responder::error('Unauthorized', 401);
        }

        return new AddressResource($address);
    }

    public function update(Request $request, Address $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $address->addressable_id != $user->id) {
            return Responder::error('Unauthorized', 401);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'contacts' => ['required','string', 'max:255'],
            'line1' => ['required', 'string', 'min:5', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'gstin' => ['nullable', 'string', 'min:15', 'max:15', 'unique:addresses,gstin,'.$address->id],
            'pan' => ['nullable', 'string', 'min:10', 'max:10', 'unique:addresses,pan,'.$address->id],
        ]);

        try {
            $addressData = $request->all();
            $address->name = $addressData['name'];
            $address->contacts = $addressData['contacts'];
            $address->line1 = $addressData['line1'];
            $address->line2 = $addressData['line2'];
            $address->gstin = isset($addressData['gstin']) ? $addressData['gstin'] : null;
            $address->pan = isset($addressData['pan']) ? $addressData['pan'] : null;
            $address->save();
            $updatedAddress = $address->fresh();
            $updatedAddress->print = $this->generatePrint($updatedAddress);
            $updatedAddress->save();
            
            $addresses = $user->addresses()->get();
            return Responder::ok(null, AddressResource::collection($addresses));
    
        } catch (\Exception $e) {
            return Responder::error($e->getMessage(), 200);
        }
    }

    public function destroy(Address $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user || $address->addressable_id != $user->id) {
            return ApiResponse::success(null, 'Unauthorized Access', 403); // Consider using an appropriate HTTP status code
        }

        // Check if address is used in any orders
        $billingAddress = $user->orders()->where('billing_address_id', $address->id)->first();
        $shippingAddress = $user->orders()->where('shipping_address_id', $address->id)->first();
        if ($billingAddress || $shippingAddress) {
            return ApiResponse::success(null, 'Address is used in an order');
        }

        // Normalize and compare addresses
        $normalizedUserAddress = strtolower(str_replace(' ', '', $user->account->address ?? ''));
        $normalizedAddressLine1 = strtolower(str_replace(' ', '', $address->line1 ?? ''));
        
        if ($normalizedUserAddress === $normalizedAddressLine1) {
            return ApiResponse::success(null, 'Address is used in account');
        }

        try {
            $address->delete();
            $addresses = $user->addresses()->get();
            return ApiResponse::success(AddressResource::collection($addresses), 'Address deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500); // Use a more appropriate status code for errors
        }
    }

    public function generatePrint(Address $address){

        $seperator = '-';
        $print =  $address->name.$seperator;
        $print .=  $address->lname.$seperator;
        $print .=  $address->contacts.$seperator;
        $print .=  $address->line1.$seperator;
        $print .= $address->line2.$seperator;
        $print .= $address->district.$seperator;
        $print .= $address->state.$seperator;
        $print .= $address->country.$seperator;
        $print .= $address->pincode.$seperator;
        return $print;
    }
}
