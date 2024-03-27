<div class="mb-4">

    <div class="d-flex justify-content-between align-items-center w-100 mb-3">

        <div class="">
            <div class="smaller text-capitalize">{{ \Carbon\Carbon::now()->diffForHumans($order->created_at) }}</div>
            <div class="fw-bold fs-5 ls-1">Order Catelogs</div>
        </div>        

        <div class="">
            <div class="smaller text-capitalize mb-1">Update Order Status</div>
            <div class="dropdown border">
                <button class="btn border-0 btn-sm w-100 text-capitalize dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ $order->status }}
                </button>
                <ul class="dropdown-menu">
                    @foreach ($orderStatuses as $orderStatus)    
                        <li>
                            <button type="button" wire:click.prevent="update('{{ $orderStatus }}')" class="dropdown-item">
                                {{ ucwords($orderStatus) }}
                            </button>                        
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="accordion font-title" id="orderProductsAccordion{{ $orderId }}">
        @foreach ($orderProducts as $orderProduct)
            <div id="orderItem{{ $orderProduct->id }}" class="accordion-item mb-3 border rounded overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" 
                        data-bs-toggle="collapse" aria-expanded="true" 
                        data-bs-target="#orderProductsAccordionItem{{ $orderProduct->id }}" 
                        aria-controls="orderProductsAccordionItem{{ $orderProduct->id }}">
                        <div class="d-flex w-100">
                            <img src="{{ $orderProduct->product->getImage() }}" alt="Product Image" class="of-cover" width="80" height="100">
                            <div class="d-flex flex-column justify-content-evenly ps-3">
                                <span class="fw-500">{{ $orderProduct->suborder_id }}</span>
                                <span class="fw-bold fs-5">#{{ $orderProduct->product->code }}</span>
                                <span class="fw-light">{{ $orderProduct->product->category->name }} | {{ $orderProduct->product->brand->name }}</span>
                            </div>
                        </div>
                    </button>
                </h2>
                <div 
                    class="accordion-collapse collapse show" 
                    id="orderProductsAccordionItem{{ $orderProduct->id }}" 
                    data-bs-parent="#orderProductsAccordion{{ $orderId }}">

                    <div class="accordion-body p-0 text-capitalize">
                        <table class="table table-borderless w-100 mb-0 small">
                            <thead class="table-secondary fw-bold">
                                <tr class="text-center">
                                    <td class="table-danger fw-normal" onclick="printContent('orderItem{{ $orderProduct->id }}')">
                                        <div class="d-flex align-items-center w-100 justify-content-center">
                                            <i class="bi bi-printer fs-5 lh-1 me-2"></i>
                                            <span>Order</span>
                                        </div>
                                    </td>
                                    @foreach($orderProduct->product->productRanges as $range)
                                        <td>{{ $range->name }}</td>
                                    @endforeach
                                    <td class="border-start fw-normal">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderProduct->product->productOptions as $option)
                                    <tr class="text-center">
                                        <td class="table-secondary fw-bold ls-1">{{ $option->name }}</td>
                                        @foreach($orderProduct->product->productRanges as $range)
                                            <td class="fw-500">{{ $quantities[$orderProduct->id][$option->id][$range->id] }}</td>
                                        @endforeach
                                        <td class="border-start">{{ $this->getProductOptionsTotal($orderProduct, $option) }}</td> <!-- Product Options Total -->
                                    </tr>
                                @endforeach
                                <tr class="text-center border-top">
                                    <td class="table-secondary">Total</td>
                                    @foreach($orderProduct->product->productRanges as $range)
                                        <td>{{ $this->getProductRangesTotal($orderProduct, $range) }}</td> <!-- Product Ranges Total -->
                                    @endforeach
                                    <td class="border-start fw-bold">{{ $orderProduct->quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script>
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>