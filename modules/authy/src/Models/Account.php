<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Traits\Authx;
use Spatie\MediaLibrary\HasMedia;
use Fpaipl\Panel\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;

class Account extends Model implements HasMedia
{
    use 
        Authx,
        InteractsWithMedia,
        BelongsToUser;

    protected $fillable = [
        'application_id',
        'user_id',
        'status',
        'reason',
        'approver_name',
        'terms',
        'kycstep',
        'name',
        'type',
        'lifespan',
        'turnover',
        'address',
        'city',
        'pincode',
        'state',
        'contact',
        'other',
        'gstin',
        'aadhar',
        'pan',
        'bank',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    const STATUS = ['pending', 'submitted', 'approved', 'rejected'];

    // Property to define the starting application ID format
    public static $startingApplicationId = 'WSG240000';

    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude 'pending' status
        static::addGlobalScope('excludePending', function (Builder $builder) {
            $builder->where('status', '<>', 'pending');
        });

        // Event to auto-set the 'application_id' on model creation
        static::creating(function ($account) {
            // Get the last application_id and increment it
            $lastAccount = static::withoutGlobalScope('excludePending')->orderBy('application_id', 'desc')->first();
        
            if ($lastAccount) {
                $lastApplicationId = $lastAccount->application_id;
        
                preg_match('/(\D+)(\d+)/', $lastApplicationId, $matches);
                
                $prefix = $matches[1] ?? ''; // Extract non-numeric prefix
                $number = $matches[2] ?? ''; // Extract numeric part
        
                $newNumber = str_pad((int)$number + 1, strlen($number), '0', STR_PAD_LEFT);
                $account->application_id = $prefix . $newNumber;
            } else {
                $account->application_id = static::$startingApplicationId;
            }
        });
        
    }

    const GST_FILE = 'gst_file';
    const PAN_FILE = 'pan_file';
    const BANK_FILE = 'bank_file';
    const OTHER_FILE = 'other_file';
    const AADHAR_FILE = 'aadhar_file';

    public function scopeNewAccounts($query)
    {
        return $query->where('status', 'pending')->orWhere('status', 'submitted');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::GST_FILE)->singleFile();
        $this->addMediaCollection(self::PAN_FILE)->singleFile();
        $this->addMediaCollection(self::BANK_FILE)->singleFile();
        $this->addMediaCollection(self::AADHAR_FILE)->singleFile();
        $this->addMediaCollection(self::OTHER_FILE)->singleFile();
    }

    public function updateFields($request, $fields)
    {
        foreach ($fields as $field) {
            $this->updateField($request, $field);
        }
    }

    public function updateField($request, $field)
    {
        $this->$field = $request->$field;
    }

    /**
     * Update the document field and upload the file
     *
     * @param [type] $field
     * @param [type] $request
     * @param [type] $collection
     * @return void
     */
    public function uploadDocument($field, $request, $collection)
    {
        $this->$field = $request->$field;
        $this->addMedia($request->file($field . '_file'))->toMediaCollection($collection);
    }

    // ------------------------  VALIDATION ------------------------

    // 1. Business KYC validation rules

    public static function businessKycValidaionRules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', config('settings.types')),
            'lifespan' => 'required|string|in:' . implode(',', config('settings.lifespan')),
            'turnover' => 'required|string|in:' . implode(',', config('settings.turnover')),
        ];
    }

    public static function businessKycValidationMessages()
    {
        return [
            'name.required' => 'Business name is required',
            'name.string' => 'Business name must be a string',
            'name.max' => 'Business name must not exceed 255 characters',
            'type.required' => 'Business type is required',
            'type.string' => 'Business type must be a string',
            'type.in' => 'Invalid business type',
            'lifespan.required' => 'Business lifespan is required',
            'lifespan.string' => 'Business lifespan must be a string',
            'lifespan.in' => 'Invalid business lifespan',
            'turnover.required' => 'Business turnover is required',
            'turnover.string' => 'Business turnover must be a string',
            'turnover.in' => 'Invalid business turnover',
        ];
    }

    // 2. Address validation rules

    public static function addressValidationRules()
    {
        return [
            'contact' => 'required|numeric|digits:10',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|numeric|digits:6',
            'state' => 'required|string|in:' . implode(',', config('settings.states')),
        ];
    }

    public static function addressValidationMessages()
    {
        return [
            'contact.required' => 'Contact number is required',
            'contact.numeric' => 'Contact number must be numeric, no alphabets and spaces allowed',
            'contact.digits' => 'Contact number must be 10 digits',
            'address.required' => 'Address is required',
            'address.string' => 'Address must be a string',
            'address.max' => 'Address must not exceed 255 characters',
            'city.required' => 'City is required',
            'city.string' => 'City must be a string',
            'city.max' => 'City must not exceed 255 characters',
            'pincode.required' => 'Pincode is required',
            'pincode.numeric' => 'Pincode must be numeric, no alphabets and spaces allowed',
            'pincode.digits' => 'Pincode must be 6 digits',
            'state.required' => 'State is required',
            'state.string' => 'State must be a string',
            'state.in' => 'Invalid state',
        ];
    }

    // 3. Document validation rules

    public static function documentSets()
    {
        return [
            'gstin' => ['gstin', 'gstin_file'],
            'aadhar' => ['aadhar', 'aadhar_file'],
            'bank' => ['bank', 'bank_file'],
            'pan' => ['pan', 'pan_file'],
            'other' => ['other', 'other_file'],
        ];
    }

    public static function documentTypes()
    {
        return [
            'gstin' => self::GST_FILE,
            'aadhar' => self::AADHAR_FILE,
            'pan' => self::PAN_FILE,
            'bank' => self::BANK_FILE,
            'other' => self::OTHER_FILE,
        ];
    }

    public static function gstinValidationRules()
    {
        return [
            'gstin' => 'required|string|size:15|regex:/\d{2}[a-z]{5}\d{4}[a-z]{1}[a-z\d]{1}[z]{1}[a-z\d]{1}/i',
            'gstin_file' => 'required|file',
        ];
    }

    public static function gstinValidationMessages()
    {
        return [
            'gstin.required' => 'GSTIN number is required',
            'gstin.string' => 'GSTIN number must be a string',
            'gstin.size' => 'GSTIN number must be 15 characters',
            'gstin.regex' => 'Invalid GSTIN number',
            'gstin_file.required' => 'GSTIN file is required',
            'gstin_file.file' => 'GSTIN file must be a file',
        ];
    }

    public static function aadharValidationRules()
    {
        return [
            'aadhar' => 'required|numeric|digits:12',
            'aadhar_file' => 'required|file',
        ];
    }

    public static function aadharValidationMessages()
    {
        return [
            'aadhar.required' => 'Aadhar number is required',
            'aadhar.numeric' => 'Aadhar number must be numeric, no alphabets and spaces allowed',
            'aadhar.digits' => 'Aadhar number must be 12 digits',
            'aadhar_file.required' => 'Aadhar file is required',
            'aadhar_file.file' => 'Aadhar file must be a file',
        ];
    }

    public static function panValidationRules()
    {
        return [
            'pan' => 'required|string|size:10|regex:/[a-z]{5}\d{4}[a-z]{1}/i',
            'pan_file' => 'required|file',
        ];
    }

    public static function panValidationMessages()
    {
        return [
            'pan.required' => 'PAN number is required',
            'pan.string' => 'PAN number must be a string',
            'pan.size' => 'PAN number must be 10 characters',
            'pan.regex' => 'Invalid PAN number',
            'pan_file.required' => 'PAN file is required',
            'pan_file.file' => 'PAN file must be a file',
        ];
    }

    public static function bankValidationRules()
    {
        return [
            'bank' => 'required|numeric',
            'bank_file' => 'required|file',
        ];
    }

    public static function bankValidationMessages()
    {
        return [
            'bank.required' => 'Bank account number is required',
            'bank.numeric' => 'Bank account number must be numeric, no alphabets and spaces allowed',
            'bank_file.required' => 'Bank file is required',
            'bank_file.file' => 'Bank file must be a file',
        ];
    }

    public static function otherValidationRules()
    {
        return [
            'other' => 'required|string|min:1|max:255',
            'other_file' => 'required|file',
        ];
    }

    public static function otherValidationMessages()
    {
        return [
            'other.required' => 'Other document name is required',
            'other.string' => 'Other document name must be a string',
            'other.min' => 'Other document name must be at least 1 character',
            'other.max' => 'Other document name must not exceed 255 characters',
            'other_file.required' => 'Other file is required',
            'other_file.file' => 'Other file must be a file',
        ];
    }

    // 4. Review validation rules

    public static function reviewValidationRules()
    {
        return [
            'terms' => 'required|accepted',
        ];
    }

    public static function reviewValidationMessages()
    {
        return [
            'terms.required' => 'Terms and conditions are required',
            'terms.accepted' => 'Terms and conditions must be accepted',
        ];
    }

    // ------------------------  END VALIDATION ------------------------    
}
