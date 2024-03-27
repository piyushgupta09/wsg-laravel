<?php

namespace Fpaipl\Authy\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Fpaipl\Authy\Models\Account;
use Fpaipl\Authy\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // if no documents are uploaded, then the account is skipped
        $isSkipped = $this->gstin || $this->pan || $this->aadhar || $this->bank || $this->other ? false : true;

        return [
            'user_id' => UserResource::make($this->user),
            'application_id' => $this->application_id,
            'status' => $this->status,
            'kycstep' => $this->kycstep,
            'reason' => $this->reason,
            'approver_name' => $this->approver_name,

            'name' => $this->name,
            'type' => $this->type,
            'lifespan' => $this->lifespan,
            'turnover' => $this->turnover,

            'address' => $this->address,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'state' => $this->state,
            'contact' => $this->contact,

            'gstin' => $this->gstin,
            'gstin_file' => $this->getFirstMediaUrl(Account::GST_FILE),
            'pan' => $this->pan,
            'pan_file' => $this->getFirstMediaUrl(Account::PAN_FILE),
            'aadhar' => $this->aadhar,
            'aadhar_file' => $this->getFirstMediaUrl(Account::AADHAR_FILE),
            'bank' => $this->bank,
            'bank_file' => $this->getFirstMediaUrl(Account::BANK_FILE),
            'other' => $this->other,
            'other_file' => $this->getFirstMediaUrl(Account::OTHER_FILE),

            'skipped' => $isSkipped,

            'tags' => $this->tags,
            
            'manager_name' => config('settings.manager.name'),
            'manager_phone' => config('settings.manager.phone'),

            'created_at' => date_format($this->created_at, 'Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
