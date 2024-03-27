<div class="font-heading mb-4">
    <div class="row">

        {{-- New Payments --}}
        <div class="col-md-6">
            <div class="font-heading">
                <div class="card mb-3">
                    <div class="card-body"
                        data-bs-toggle="collapse" data-bs-target="#orderPendingPaymentsCollapse" aria-expanded="false" aria-controls="orderPendingPaymentsCollapse">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <button class="btn me-3">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h5 class="fw-bold ls-1">New Payments</h5>
                                    <small class="text-muted">
                                        {{ $order->orderPayments->where('status', '==', 'pending')->count() }} Payment(s) Found
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-end">
                                <span class="fw-bold fs-5">
                                    <i class="bi bi-currency-rupee"></i>
                                    {{ number_format($order->payableAmount() > 0 ? $order->payableAmount() : $order->total) }}
                                </span>
                                <small class="text-muted">
                                    {{ $order->payableAmount() > 0 ? 'Payment Due' : ($order->approvedPayments() == $order->total ? 'Paid & Approved' : 'Pending Approval') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="orderPendingPaymentsCollapse">
                    <div class="row g-2">
                        @foreach ($order->orderPayments as $payment)
                            @if ($payment->status == 'pending')    
                                <div class="col-lg-6 mb-3">
                                    
                                    @if ($payment->getImage())
                                        <a href="{{ $payment->getImage() }}" target="_blank" class="btn btn-sm w-100">
                                            <div class="card card-body w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="d-flex">
                                                            <h6 class="mb-1">{{ ucfirst($payment->status) }}</h6>
                                                            <span class="smaller">
                                                                <i class="bi bi-download mx-2"></i>
                                                                {{-- {{ Str::afterLast($payment->getImage(), '.') == 'pdf' ? 'PDF' : 'Image' }} --}}
                                                            </span>
                                                        </div>
                                                        <small class="text-muted">Ref: {{ $payment->reference_id }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-currency-rupee small"></i>
                                                            {{ number_format($payment->amount) }}
                                                        </h6>
                                                        <small class="text-muted">{{ $payment->date }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="card card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="d-flex">
                                                        <h6 class="mb-1">{{ ucfirst($payment->status) }}</h6>
                                                    </div>
                                                    <small class="text-muted">Ref: {{ $payment->reference_id }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <h6 class="mb-1">
                                                        <i class="bi bi-currency-rupee small"></i>
                                                        {{ number_format($payment->amount) }}
                                                    </h6>
                                                    <small class="text-muted">{{ $payment->date }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="px-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">Checked By</div>
                                            @if ($payment->checkedBy)
                                                <div>{{ $payment->checkedBy?->name }}</div>
                                            @else
                                                <div class="btn-group" role="group"
                                                    aria-label="Basic example">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click.prevent="markPaymentRejected('{{ $payment->id }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="markPaymentRejected('{{ $payment->id }}')">
                                                        Mark Rejected
                                                        <span wire:loading
                                                            wire:target="markPaymentRejected('{{ $payment->id }}')"
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        wire:click.prevent="markPaymentChecked('{{ $payment->id }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="markPaymentChecked('{{ $payment->id }}')">
                                                        Mark Checked
                                                        <span wire:loading
                                                            wire:target="markPaymentChecked('{{ $payment->id }}')"
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-bold">Approved By</div>
                                            @if ($payment->approvedBy)
                                                <div>{{ $payment->approvedBy?->name }}</d>
                                            @else
                                                @if ($payment->checkedBy)
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic example">
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            wire:click.prevent="markPaymentUnApproved('{{ $payment->id }}')"
                                                            wire:loading.attr="disabled"
                                                            wire:target="markPaymentUnApproved('{{ $payment->id }}')">
                                                            Unapprove
                                                            <span wire:loading
                                                                wire:target="markPaymentUnApproved('{{ $payment->id }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            wire:click.prevent="markPaymentApproved('{{ $payment->id }}')"
                                                            wire:loading.attr="disabled"
                                                            wire:target="markPaymentApproved('{{ $payment->id }}')">
                                                            Approve
                                                            <span wire:loading
                                                                wire:target="markPaymentApproved('{{ $payment->id }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="ps-2">Pending to be checked</div>
                                                @endif
                                            @endif
        
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>  
            </div>
        </div>

        {{--Payment History --}}
        <div class="col-md-6">
            <div class="font-heading">
                <div class="card mb-3">
                    <div class="card-body"
                        data-bs-toggle="collapse" data-bs-target="#orderPaymentsCollapse" aria-expanded="false" aria-controls="orderPaymentsCollapse">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <button class="btn me-3">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h5 class="fw-bold ls-1">Payment History</h5>
                                    <small class="text-muted">
                                        {{ $order->orderPayments->where('status', '!=', 'pending')->count() }} Payment(s) Found
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-end">
                                <span class="fw-bold fs-5">
                                    <i class="bi bi-currency-rupee"></i>
                                    {{ number_format($order->payableAmount() > 0 ? $order->payableAmount() : $order->total) }}
                                </span>
                                <small class="text-muted">
                                    {{ $order->payableAmount() > 0 ? 'Payment Due' : ($order->approvedPayments() == $order->total ? 'Paid & Approved' : 'Pending Approval') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="orderPaymentsCollapse">
                    <div class="row g-2">
                        @foreach ($order->orderPayments as $payment)
                            @if ($payment->status !== 'pending')    
                                <div class="col-lg-6">
                                    @if ($payment->getImage())
                                        <a href="{{ $payment->getImage() }}" target="_blank" class="btn btn-sm w-100">
                                            <div class="card card-body w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="d-flex">
                                                            <h6 class="mb-1">{{ ucfirst($payment->status) }}</h6>
                                                            <span class="smaller">
                                                                <i class="bi bi-download mx-2"></i>
                                                                {{-- {{ Str::afterLast($payment->getImage(), '.') == 'pdf' ? 'PDF' : 'Image' }} --}}
                                                            </span>
                                                        </div>
                                                        <small class="text-muted">Ref: {{ $payment->reference_id }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-currency-rupee small"></i>
                                                            {{ number_format($payment->amount) }}
                                                        </h6>
                                                        <small class="text-muted">{{ $payment->date }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="card card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="d-flex">
                                                        <h6 class="mb-1">{{ ucfirst($payment->status) }}</h6>
                                                    </div>
                                                    <small class="text-muted">Ref: {{ $payment->reference_id }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <h6 class="mb-1">
                                                        <i class="bi bi-currency-rupee small"></i>
                                                        {{ number_format($payment->amount) }}
                                                    </h6>
                                                    <small class="text-muted">{{ $payment->date }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>  
            </div>
        </div>

    </div>
</div>
