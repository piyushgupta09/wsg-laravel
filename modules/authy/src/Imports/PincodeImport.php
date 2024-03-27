<?php

namespace Fpaipl\Authy\Imports;
use Fpaipl\Authy\Models\State;
use Fpaipl\Authy\Models\Country;
use Fpaipl\Authy\Models\Pincode;
use Fpaipl\Authy\Models\District;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PincodeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public $country;

    public function __construct()
    {
        // Check for existing records to avoide duplicacy
        $this->country = Country::where('name', 'india')->first();
        if(empty($this->country)){
            $this->country = Country::create([
                'name' => 'india',
            ]);    
        } 
    }

    public function collection(Collection $rows)
    {
       $counter = 0;
        foreach ($rows as $row) {

            // Initial Values
            $state= null;
            $district= null;
           
            // Check for existing records to avoide duplicacy
            $state = State::where('name', $row['statename'])->first();
            if(empty($state)){
                $state= State::create([
                    'country_id' => $this->country->id,
                    'name' => $row['statename'],
                ]);
            } 

            // Check for existing records to avoide duplicacy
            $district = District::where('name', $row['district'])->where('state_id', $state->id)->first();
            if(empty($district)){
                $district= District::create([
                    'name' => $row['district'],
                    'state_id' => $state->id,
                ]);
            }
            
            // Check for existing records to avoide duplicacy
            $pincode = Pincode::where('pincode', $row['pincode'])->first();
            if(empty($pincode)){
                Pincode::create([
                    'pincode' => $row['pincode'],
                    'district_id' => $district->id,
                ]);
            }
            
            if($counter++ == 19300) exit;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
