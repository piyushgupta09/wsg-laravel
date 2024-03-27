<div class="font-heading">
    <div class="row">
        <div class="col-md-12">
            <p class="fw-bold fs-5 ls-1">Payment Details for Payment ID: {{ $paymentId }}</p>

            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="ps-2 table-secondary">Payment Date/Time</td>
                        <th class="ps-2">{{ $payment->date }}</th>
                    </tr>
                    <tr>
                        <td class="ps-2 table-secondary">File Attachment</td>
                        <td class="ps-2">
                            @if (!empty($payment->getImage()))
                                <a href="{{ $payment->getImage() }}" target="_blank" class="td-none text-dark">
                                    <div class="d-flex justify-content-start align-items-center">
                                        @if (Str::afterLast($payment->getImage(), '.') == 'pdf')
                                            Open Pdf
                                        @else
                                            <i class="bi bi-download me-2 small"></i> Download File
                                        @endif
                                    </div>
                                </a>
                            @else
                                No Attachment
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-2 table-secondary">Checked By</td>
                        <th class="ps-2">{{ $payment->checkedBy?->name }}</th>
                    </tr>
                    <tr>
                        <td class="ps-2 table-secondary">Approved By</td>
                        <th class="ps-2">{{ $payment->approvedBy?->name }}</th>
                    </tr>
                </tbody>
            </table>
      
            <div class="d-flex justify-content-between">
                @if ($payment->checkedBy && !$payment->approvedBy)    
                    <button type="button" class="btn btn-sm btn-success"
                        wire:click.prevent="markPaymentApproved('{{ $payment->id }}')">
                        Approve
                    </button>
                    <button type="button" class="btn btn-sm btn-danger"
                        wire:click.prevent="markPaymentUnApproved('{{ $payment->id }}')">
                        Unapprove
                    </button>
                @elseif (!$payment->checkedBy)    
                    <button type="button" class="btn btn-sm btn-danger"
                        wire:click.prevent="markPaymentRejected('{{ $payment->id }}')">
                        Mark Rejected
                    </button>
                    <button type="button" class="btn btn-sm btn-success"
                        wire:click.prevent="markPaymentChecked('{{ $payment->id }}')">
                        Mark Checked
                    </button>
                @endif
            </div>
              
        </div>
    </div>
</div>
