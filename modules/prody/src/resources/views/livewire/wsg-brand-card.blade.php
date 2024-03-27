<div class="col-md-6 col-xl-3 p-0 border position-relative" 
    style="border-left: solid #e48585 10px !important;">
    <div class="card rounded-0 border-0">
        <div class="card-body d-flex justify-content-between" style="background-color: #e4858520">
            <div class="d-flex flex-column">
                <div class="font-title fs-4 fw-bold">{{ $wsgBrand->name }}</div>
                <div class="font-subtitle">{{ $wsgBrand->info }}</div>
            </div>
            <button class="btn border-0" wire:click="updateCount">
                <i class="bi bi-arrow-clockwise fs-3 lh-1"></i>
            </button>
        </div>
    </div>
    <div class="card rounded-0 border-0 border-top">
        <div class="card-body" style="background-color: #ebc3c320">
            @if ($counts && count($counts) > 0)
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="small ps-3">Item</th>
                            <th class="small text-end">Old</th>
                            <th class="small text-end">New</th>
                            <th class="small text-center">Action</th>
                        </tr>
                    </thead>
                    @foreach ($counts as $item => $count)     
                        <tr>
                            <td class="ps-3 font-subtitle text-capitalize">{{ $item }}</td>
                            <td class="text-end fw-500">{{ $count['old'] }}</td>
                            <td class="text-end fw-500">{{ $count['new'] }}</td>
                            <td class="text-center fw-500">
                                @if ($count['new'] > $count['old'])
                                    <i class="bi bi-cloud-arrow-down text-danger"></i>
                                    {{-- &nbsp;{{ $count['new'] - $count['old'] }} --}}
                                @else
                                    <i class="bi bi-cloud-check-fill text-success"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <button class="btn btn-light w-100 shadow fw-500 ls-1" wire:click="downloadProducts">
                    Download All
                </button>
            @else
                No data available, try to reload.
            @endif
        </div>
    </div>
    @if ($loading)
        <div class="position-absolute top-0 start-0 bottom-0 end-0 bg-dark opacity-50">
            <div class="spinner-border text-light position-absolute top-50 start-50" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif
</div>