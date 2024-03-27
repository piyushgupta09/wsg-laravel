<?php

namespace Fpaipl\Authy\Http\Coordinators;

use App\Helpers\Responder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fpaipl\Authy\Models\Account;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Resources\AccountResource;

class AccountCoordinator extends Coordinator
{
    /**
     * Retrieve and return the user's account details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAccount(Request $request)
    {
        $user = request()->user();
        $userAccount = $user->accountAll;
        return Responder::ok(null, AccountResource::make($userAccount));
    }

    /**
     * Handles various steps of KYC verification for the user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountVerification(Request $request)
    {
        $request->validate([
            'kycstep' => 'required|string|in:' . implode(',', config('settings.kycsteps')),
        ], [
            'kycstep.required' => 'Invalid request form',
            'kycstep.in' => 'Invalid request form',
        ]);

        $user = $request->user();
        $account = $user->accountAll;

        switch ($request->kycstep) {

            case 'business':

                $request->validate(
                    Account::businessKycValidaionRules(),
                    Account::businessKycValidationMessages()
                );
                
                $account->updateFields($request, [
                    'name', 'type', 'lifespan', 'turnover'
                ]);
               
                break;

            case 'address':
                
                $request->validate(
                    Account::addressValidationRules(),
                    Account::addressValidationMessages()
                );

                $account->updateFields($request, [
                    'address', 'city', 'state', 'pincode', 'contact'
                ]);

                break;

            case 'documents':

                if (!$this->applicationIsInEditMode($account)) {
                    if ($this->validateDocumentSets($request) !== true) {
                        return false;
                    }
                }
               
                foreach (Account::documentTypes() as $type => $fileConstant) {
                    if ($request->has($type) && $request->hasFile("{$type}_file")) {
                        $request->validate(
                            call_user_func([
                                "Fpaipl\Authy\Models\Account", "{$type}ValidationRules"
                            ]),
                            call_user_func([
                                "Fpaipl\Authy\Models\Account", "{$type}ValidationMessages"
                            ])
                        );
                        $account->uploadDocument($type, $request, $fileConstant);
                    }
                }

                break;

            case 'review':
                $request->validate(
                    Account::reviewValidationRules(),
                    Account::reviewValidationMessages()
                );
                break;

            default:
                break;
        }

        if ($request->kycstep == 'review') {
            $account->status = 'submitted';
        } else {
            $account->kycstep = $this->nextKycStep($request->kycstep);
        }

        // arrange tags
        $accountTags = [];
        array_push($accountTags, $account->application_id);
        array_push($accountTags, $account->reason);
        array_push($accountTags, $account->approver_name);
        array_push($accountTags, $account->terms);
        array_push($accountTags, $account->kycstep);
        array_push($accountTags, $account->name);
        array_push($accountTags, $account->type);
        array_push($accountTags, $account->lifespan);
        array_push($accountTags, $account->turnover);
        array_push($accountTags, $account->address);
        array_push($accountTags, $account->city);
        array_push($accountTags, $account->pincode);
        array_push($accountTags, $account->state);
        array_push($accountTags, $account->contact);
        array_push($accountTags, $account->other);
        array_push($accountTags, $account->gstin);
        array_push($accountTags, $account->aadhar);
        array_push($accountTags, $account->pan);
        array_push($accountTags, $account->bank);
        array_push($accountTags, $account->user->name);
        array_push($accountTags, $account->user->email);
        $account->tags = implode(',', $accountTags);

        $account->save();

        return Responder::ok('Account verification step completed', AccountResource::make($account));
    }

    /**
     * Handles the request to edit the KYC form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editKycForm(Request $request)
    {
        // Validate the incoming request, specifically the 'kycstep' field
        $request->validate([
            'kycstep' => 'required|string|in:' . implode(',', config('settings.kycsteps')),
        ], [
            'kycstep.required' => 'Invalid request form',
            'kycstep.in' => 'Invalid request form',
        ]);

        // Retrieve the currently authenticated user
        $user = $request->user();

        // Retrieve the account associated with the user (explicitly load the account relationship without scope)
        $account = $user->accountAll;

        // Update the 'kycstep' field in the account to the previous step
        $account->kycstep = $this->previousKycStep($request->kycstep);
        $account->save(); // Save the changes to the database

        // Return a JSON response indicating success and supplying the updated account data
        return response()->json([
            'status' => 'ok',
            'data' => AccountResource::make($account),
        ]);
    }

    public function skipAccountVerification(Request $request)
    {
        $user = $request->user();
        $account = $user->accountAll;

        $account->kycstep = 'review';
        $account->status = 'submitted';
        $account->save();

        return Responder::ok('Account verification step skipped', AccountResource::make($account));
    }

    /**
     * Returns the static data required for the KYC form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function staticData()
    {
        return Responder::ok(null, [
            'kycsteps' => config('settings.kycsteps'),
            'types' => config('settings.types'),
            'lifespan' => config('settings.lifespan'),
            'turnover' => config('settings.turnover'),
            'support' => config('settings.support'),
            'states' => config('settings.states'),
            'countries' => config('settings.countries'),
        ]);
    }

    //----------------------------- Private methods ---------------------------------

    /**
     * Validates the document sets.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function validateDocumentSets($request)
    {
        $validSets = 0;

        foreach (Account::documentSets() as $set) {
            $isValidSet = true;

            foreach ($set as $field) {
                if (Str::endsWith($field, '_file')) {
                    if (!$request->hasFile($field)) {
                        $isValidSet = false;
                        break;
                    }
                } else {
                    if ($request->input($field) === null || $request->input($field) === "null") {
                        $isValidSet = false;
                        break;
                    }
                }
            }

            if ($isValidSet) {
                $validSets++;
            }
        }

        if ($validSets < 2) {
            return Responder::error([
                'message' => 'At least two sets of documents are required, either GSTIN, Aadhar, Bank Cheque, PAN or other approved documents along with their respective files'
            ], 422);
        }

        return true;
    }

    /**
     * Checks if the user account is in edit mode.
     * 
     * @param  \Fpaipl\Authy\Models\Account  $account
     * @return bool
     */
    private function applicationIsInEditMode($account)
    {
        return $account->gstin || $account->aadhar || $account->pan || $account->bank || $account->other;
    }

    /**
     * Returns the next KYC step.
     * 
     * @param  string  $currentStep
     * @return string
     */
    private function nextKycStep($currentStep)
    {
        $steps = config('settings.kycsteps');

        // Make sure steps are actually configured and non-empty
        if (empty($steps)) {
            return null;  // or however you want to handle this edge case
        }

        $currentStepIndex = array_search($currentStep, $steps);

        // If current step is not found, return it as is
        if ($currentStepIndex === false) {
            return $currentStep;
        }

        // Calculate the next step index
        $nextStepIndex = $currentStepIndex + 1;

        // Check if next step index exists in steps array
        if (isset($steps[$nextStepIndex])) {
            return $steps[$nextStepIndex];
        } else {
            return $currentStep;  // Return the current step if there's no next step
        }
    }

    /**
     * Returns the previous KYC step.
     * 
     * @param  string  $currentStep
     * @return string
     */
    private function previousKycStep($currentStep)
    {
        $steps = config('settings.kycsteps');

        // Make sure steps are actually configured and non-empty
        if (empty($steps)) {
            return null;  // or however you want to handle this edge case
        }

        $currentStepIndex = array_search($currentStep, $steps);

        // If current step is not found, return it as is
        if ($currentStepIndex === false) {
            return $currentStep;
        }

        // Calculate the next step index
        $previousStepIndex = $currentStepIndex - 1;

        // Check if next step index exists in steps array
        if (isset($steps[$previousStepIndex])) {
            return $steps[$previousStepIndex];
        } else {
            return $currentStep;  // Return the current step if there's no next step
        }
    }
    
}
