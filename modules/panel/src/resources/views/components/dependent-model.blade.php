<div class="card">
    <div class="card-header bg-secondary">
        <button class="btn text-light w-100 p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#modelRelationaldependancyModal" aria-expanded="false" aria-controls="modelRelationaldependancyModal">
            <div class="d-flex justify-content-between w-100">
                <strong>Model Relational Dependencies</strong>
                <i class="bi bi-chevron-down"></i>
            </div>
        </button>
    </div>
    @if($dependentRecordCount)
        <div class="card-body p-0">
            <div class="collapse" id="modelRelationaldependancyModal">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Model</th>
                            <th scope="col">Records</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dependentRecords as $key => $value)
                            <tr>
                                <td>{{ Str::title($key)  }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

