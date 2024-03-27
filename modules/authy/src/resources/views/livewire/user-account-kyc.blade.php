<div class="position-relative">

    <table class="table table-sm table-striped">
        <tbody class="font-heading">
            <tr>
                <td class="fw-500 w-25">Name</td>
                <td colspan="2" class="fw-500">{{ $account->name }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Type</td>
                <td colspan="2" class="fw-500">{{ $account->type }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Lifespan</td>
                <td colspan="2" class="fw-500">{{ $account->lifespan }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Turnover</td>
                <td colspan="2" class="fw-500">{{ $account->turnover }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Address</td>
                <td colspan="2" class="fw-500">{{ $account->address }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">City</td>
                <td colspan="2" class="fw-500">{{ $account->city }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">State</td>
                <td colspan="2" class="fw-500">{{ $account->state }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Pincode</td>
                <td colspan="2" class="fw-500">{{ $account->pincode }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Mobile</td>
                <td colspan="2" class="fw-500">{{ $account->contact }}</td>
            </tr>
            <tr>
                <td class="fw-500 w-25">Manager</td>
                <td colspan="2" class="fw-500">{{ $account->manager_name }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-center fw-bold text-decoration-underline">Documents</td>
            </tr>
        </tbody>
    </table>

    <div class="row border border-top-0 px-3 pb-3 mx-2">
        <!-- For GSTIN -->
        @if (isset($docs['gstin']) && isset($docs['gstin_file']))
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="text-bg-dark rounded-pill px-3 py-2 fw-500">Gst Registration Certificate</div>
                    <div class="fw-bold my-3">{{ $docs['gstin'] }}</div>
                    <img src="{{ $docs['gstin_file'] }}" class="wh-150 of-contain" />
                    <a href="{{ $docs['gstin_file'] }}" target="_blank" class="btn btn-link fw-500">Click to Download</a>
                </div>
            </div>
        @endif

        <!-- For PAN -->
        @if (isset($docs['pan']) && isset($docs['pan_file']))
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="text-bg-dark rounded-pill px-3 py-2 fw-500">PAN</div>
                    <div class="fw-bold my-3">{{ $docs['pan'] }}</div>
                    <img src="{{ $docs['pan_file'] }}" class="wh-150 of-contain" />
                    <a href="{{ $docs['pan_file'] }}" target="_blank" class="btn btn-link fw-500">Click to Download</a>
                </div>
            </div>
        @endif

        <!-- For Aadhar -->
        @if (isset($docs['aadhar']) && isset($docs['aadhar_file']))
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="text-bg-dark rounded-pill px-3 py-2 fw-500">Aadhar</div>
                    <div class="fw-bold my-3">{{ $docs['aadhar'] }}</div>
                    <img src="{{ $docs['aadhar_file'] }}" class="wh-150 of-contain" />
                    <a href="{{ $docs['aadhar_file'] }}" target="_blank" class="btn btn-link fw-500">Click to Download</a>
                </div>
            </div>
        @endif

        <!-- For Bank -->
        @if (isset($docs['bank']) && isset($docs['bank_file']))
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="text-bg-dark rounded-pill px-3 py-2 fw-500">Bank</div>
                    <div class="fw-bold my-3">{{ $docs['bank'] }}</div>
                    <img src="{{ $docs['bank_file'] }}" class="wh-150 of-contain" />
                    <a href="{{ $docs['bank_file'] }}" target="_blank" class="btn btn-link fw-500">Click to Download</a>
                </div>
            </div>
        @endif

        <!-- For Other -->
        @if (isset($docs['other']) && isset($docs['other_file']))
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="text-bg-dark rounded-pill px-3 py-2 fw-500">Other Document</div>
                    <div class="fw-bold my-3">{{ $docs['other'] }}</div>
                    <img src="{{ $docs['other_file'] }}" class="wh-150 of-contain" />
                    <a href="{{ $docs['other_file'] }}" target="_blank" class="btn btn-link fw-500">Click to Download</a>
                </div>
            </div>
        @endif

    </div>

    @if ($account->status == 'pending')
        <div class="m-3 d-flex border p-3 rounded align-items-center">
            <i class="bi bi-hourglass-split text-primary fs-4"></i>
            <div class="d-flex flex-column ps-3">
                <span class="fw-500">User has not completed his kyc.</span>
            </div>
        </div>
    @endif

    @if ($account->status == 'submitted')
        @if ($account->kycstep == 'review')
            <div class="px-3 mt-3 text-center">
                <div class="lable mb-1">Enter Reason <small>(Mandatory in case of rejection)</small></div>
                <input type="text" class="form-control" wire:model='reason'>
                @if (session('message'))
                    <div class="text-danger">{{ session('message') }}</div>
                @endif
            </div>
            <div class="p-3 d-flex justify-content-center">
                <button class="btn btn-outline-danger py-1 px-3 me-2" wire:click='rejectAccount'>
                    Reject Account
                </button>
                <button class="btn btn-success py-1 px-3 ms-2" wire:click='approveAccount'>
                    Approve Account
                </button>
            </div>
        @else
            <div class="m-3 d-flex border p-3 rounded" style="background-color: lightgray">
                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                <div class="d-flex flex-column ps-3">
                    <span class="fw-500">User has not completed his kyc.</span>
                </div>
            </div>
        @endif
    @endif

    @if ($account->status == 'rejected')
        <div class="m-3 d-flex border p-3 rounded" style="background-color: lightgray">
            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
            <div class="d-flex flex-column ps-3">
                <p class="mb-1 fw-500">User has completed his kyc and his account is rejected.</p>
                <p class="mb-1 font-title">Reason: <strong>{{ $account->reason }}</strong></p> 
                <p class="mb-1 font-title">Rejected By: <strong>{{ $account->approver_name }}</strong></p>
                <p class="mb-1 font-title">Date: <strong>{{ $account->updated_at }}</strong></p>
                <p class="font-title fw-bold mt-3 td-underline">Available Action:</p>
                <p class="font-title"><strong>Archive:</strong> User not able to re-apply for kyc, also hide from accounts list.
                    <button class="btn btn-danger py-0" wire:click='archiveAccount'>Archive Now</button>
                </p>
                <p class="mb-1 font-title"><strong>Delete:</strong> Allow user to re-apply for kyc.
                    <button class="btn btn-success py-0" wire:click='deleteAccount'>Delete Now</button>
                </p>
            </div>
        </div>
    @endif

    @if ($account->status == 'approved')
        <div class="m-3 d-flex border p-3 rounded" style="background-color: lightgray">
            <i class="bi bi-check-circle-fill text-success fs-4"></i>
            <div class="d-flex flex-column ps-3">
                <span class="fw-500">User has completed his kyc and his account is approved.</span>
                <span class="">Approved By: <strong>{{ $account->approver_name }}</strong></span>
                <span class="">Date: <strong>{{ $account->updated_at }}</strong></span>
                <p class="font-title fw-bold mt-3 td-underline">Available Action:</p>
                <p class="font-title mb-2"><strong>Block:</strong> User not able to enter website, also hide from accounts list.</p>
                <div class=""><button class="btn btn-danger py-0" wire:click='blockAccount'>Block Now</button></div>
            </div>
        </div>
    @endif

    @if ($blocked)
        <div class="position-absolute top-0 start-0 end-0 bottom-0" style="background-color: rgba(79, 0, 0, 0.75)">
            <div class="d-flex flex-column justify-content-center align-items-center h-100 p-3">
                <p class="fs-2 fw-bold text-white text-center">This account has been blocked.</p>
                <button class="btn btn-success" wire:click='unBlockAccount'>UnBlock Account</button>
            </div>
        </div>
    @endif


    <button class="btn btn-success py-1 px-3 ms-2" wire:click='approveAccount'>
        Approve Account
    </button>

</div>