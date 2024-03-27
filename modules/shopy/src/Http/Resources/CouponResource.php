<?php

namespace Fpaipl\Shopy\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Formatting dates and possibly restructuring the data
        return [
            'id' => $this->id,
            'code' => $this->code,
            'offer' => $this->detail, // Assuming 'detail' contains the offer description
            'applicable' => $this->applicable,
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXpVV_b-0m8nOpQZxOTM938r0wxVkYDJPEpl1hUEpa3jdHZxTznhB45CgTI-ZUN5a8Ik4&usqp=CAU', // You may need to adjust this
            'title' => $this->type, // Example, adjust based on actual usage
            'start' => Carbon::parse($this->valid_from)->format('Y-m-d'),
            'end' => Carbon::parse($this->valid_to)->format('Y-m-d'),
            'conditions' => $this->getConditions(), // You might need to create this method or adjust according to your needs
            'status' => $this->active ? 'active' : 'inactive', // Example, adjust as needed
        ];
    }

    /**
     * Example method to format conditions, adjust according to your actual data structure.
     */
    protected function getConditions()
    {
        // This is an example, you need to replace it with your actual logic
        // For instance, if conditions are stored as JSON or as a serialized array, decode them here
        return [
            'Condition 1',
            'Condition 2',
            // etc...
        ];
    }
}
