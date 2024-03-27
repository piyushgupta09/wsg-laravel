@extends('panel::datatable')

@section('dashboard-content')

    @php
        $splitNameModels = [
            [
                'id' => 'inwardreturn',
                'name' => 'InwardReturn',
                'route' => 'inward-return'
            ],
            [
                'id' => 'outwardreturn',
                'name' => 'OutwardReturn',
                'route' => 'outward-return'
            ],
        ];

        $modelNameRoute = $modelName;
        foreach($splitNameModels as $splitNameModel) {
            if($modelName == $splitNameModel['id']) {
                $modelName = $splitNameModel['name'];
                $modelNameRoute = $splitNameModel['route'];
            }
        }

        // dump('model: '.$modelName);
        // dd('route: '.$modelNameRoute);

    @endphp

    @if ($errors->any())
        <div class="alert alert-danger pb-0">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($formType == 'advance_delete')
        <div class="list-group">
            @php
                $modelClass = get_class($model);
                $datatable = 'Fpaipl\\Brandy\\Datatables\\' . Str::title($modelName) . 'Datatable';
                $returnURL = route(Str::plural($modelNameRoute) . '.index', ['page'=>request()->query('page'),'from' =>'crud' ]);
            @endphp
            <livewire:delete 
                :model="$model" :modelClass="$modelClass" :datatableClass="$datatable" :formType="$formType"
                :returnURL="$returnURL" :key="Str::plural($modelName, 2) . '-delete-' . now()" 
            />
        </div>
    @else

        @include('panel::includes.dashboard.page-breadcrumb')

        <!-------------- Start the form ------------->
        @if ($formType != 'show')

            <form method="post" enctype="multipart/form-data"
                action="{{ route(Str::plural($modelNameRoute) . (($formType == 'create' || $formType == 'duplicate') ? '.store' : '.update'), $model) }}">
                @csrf

                @if ($formType == 'edit')
                    @method('PUT')
                    <input type="hidden" name="model" value="{{ $model->id }}">
                    <input type="hidden" name="current_page_no" value="{{ $current_page_no }}" />
                @endif
        @endif

            <!--------- Form Fields ---------->
            <div class="row">
                @foreach($fields as $field)

                    {{-- Artificial Fields are not included in the form --}}
                    @if (!$field['artificial'])

                        {{--
                            $field['fillable']['component'] : Specifies the component name used to create the input field.
                            $field['fillable']['type'] : Represents the type of the input field (e.g., 'text', 'file').
                            $field['name'] : Represents the field name in the database table.
                            $field['labels']['table'] : Serves as the label for the input field.
                            $field['fillable']['style'] : Represents the class name for the input field.
                            $field['fillable']['placeholder'] : Contains the placeholder value for the input field.
                            $field['fillable']['attributes'] : An array containing attribute values for the input fields.
                            $field['fillable']['rows'] : Specifies the number of rows in a textarea input field.
                        --}}
                        @if ($formType == 'show' && isset($field['showable']) && !$field['showable'])
                            @continue
                        @else
                            @include('panel::includes.' .$field['fillable']['component'], [
                                'type' => $field['fillable']['type'],
                                'options' => isset($field['fillable']['options']) ? $field['fillable']['options'] : null,
                                'name' => $field['name'],
                                'model' => $model,
                                'note' => empty($field['fillable']['note']) ? '' : $field['fillable']['note'],
                                'show' => $formType == 'show' ? true : false,
                                'label' => $field['labels']['table'],
                                'style' => $field['fillable']['style'],
                                'p_style' => array_key_exists("p_style", $field['fillable']) ? $field['fillable']['p_style'] : '',
                                'placeholder' => $field['fillable']['placeholder'],
                                'attribute' => $field['fillable']['attributes'],
                                'rows'=> $field['fillable']['rows'],
                                'default' => isset($field['fillable']['default']) ? $field['fillable']['default'] : null,
                            ])
                        @endif

                    @endif

                @endforeach
            </div>

            <!--------- Duplicate Checkbox ---------->
            @if ($formType == 'create' && $model && request()->query('duplicate'))
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="withrelations" id="withRelationCheckbox" checked value="{{ request()->query('duplicate') }}">
                    <label class="form-check-label" for="withRelationCheckbox">
                        Also duplicate the related records
                        {{-- Also duplicate the related records {{ count($model->modelRelations) ? '(i.e.' . implode(', ', $model->modelRelations) . ')' : '.' }} --}}
                    </label>
                </div>
            @endif

        <!-------------- End the form ------------->
        @if ($formType != 'show')
        
                <!-- Create or Edit -->
                <div class="d-flex justify-content-between">
                    <div class="">
                        <button type="reset" class="btn px-3 btn-secondary">Reset</button>
                        @if ($formType == 'edit')
                            <a class="btn px-3 btn-secondary text-capitalize" href="{{ route(Str::plural($modelNameRoute) . '.show', $model) }}">View {{ $modelName }}</a>
                        @endif
                    </div>
                    <button type="submit" class="btn px-3 btn-primary">
                        {{ ($formType == 'create' || $formType == 'duplicate') ? 'Save Details' : 'Update Details' }}
                    </button>
                </div>

            </form>
            
        @else

        @if (!in_array(get_class($model), config('panel.view-only-models', [])))
            @php
                $datatable = 'Fpaipl\\Brandy\\Datatables\\' . Str::title($modelName) . 'Datatable';
                $constName = $datatable . '::DUPLICATE';
            @endphp
            <div class="d-flex justify-content-between mb-3">
                <div class="">
                    {{-- @use Illuminate\Support\Facades\Route; --}}
                    @if (Route::has(Str::plural($modelNameRoute) . '.create'))
                        <a class="btn btn-sm px-3 btn-secondary" href="{{ route(Str::plural($modelNameRoute) . '.create') }}">Create New</a>
                    @endif
                    @if (defined($constName) && constant($constName) && Route::has(Str::plural($modelNameRoute) . '.create'))
                        <a class="btn btn-sm px-3 btn-secondary" href="{{ route(Str::plural($modelNameRoute) . '.create', ['duplicate' => $model]) }}">Duplicate</a>
                    @endif
                </div>
                @if ($formType == 'show' && Route::has(Str::plural($modelNameRoute) . '.edit'))
                    <a class="btn btn-sm px-3 btn-secondary text-capitalize" href="{{ route(Str::plural($modelNameRoute) . '.edit', $model) }}">Edit {{ $modelName }}</a>
                @endif
            </div>
        @endif

            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

            @if(session()->has('message'))
                <div class="px-3 pt-3">
                    <div class="alert alert-success py-2">
                        <div class="d-flex">
                            {{ session('message') }}
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
               
            @switch(true)

                @case($model instanceof App\Models\User)
                    <livewire:user-assign-roles wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceof Fpaipl\Brandy\Models\Party)
                    <livewire:party-assign-roles wire:key="now()" :modelId="$model->id" />
                    <livewire:add-address wire:key="now()" :modelId="$model->id" modelClass="Fpaipl\Brandy\Models\Party" />
                    @break

                @case($model instanceof Fpaipl\Brandy\Models\Employee)
                    <livewire:employee-assign-roles wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceof Fpaipl\Panel\Models\Failedjob)
                    <livewire:failed-jobs wire:key="now()" :modelId="$model->id"/>
                    @break


                @case($model instanceof Fpaipl\Prody\Models\Product)
                    <livewire:product-details wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceof Fpaipl\Prody\Models\Collection)
                    <livewire:collection-products wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceof Fpaipl\Prody\Models\Material)
                    <livewire:material-options wire:key="now()" :modelId="$model->id" />
                    <livewire:material-ranges wire:key="now()" :modelId="$model->id" />
                    @break


                @case($model instanceof Fpaipl\Shopy\Models\Order)
                    <livewire:order-products wire:key="now()" :modelId="$model->id" />
                    <livewire:order-payments wire:key="now()" :modelId="$model->id" />
                    <livewire:order-deliveries wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceof Fpaipl\Shopy\Models\Delivery)
                    <livewire:order-delivery wire:key="now()" :modelId="$model->id" />
                    @break

                @case($model instanceOf Fpaipl\Shopy\Models\Payment)
                    <livewire:order-payment wire:key="now()" :modelId="$model->id" />
                    @break


                @case($model instanceof Fpaipl\Authy\Models\Account)
                    <livewire:user-account-kyc wire:key="now()" :modelId="$model->id" />
                    @break
                
                @case($model instanceof Fpaipl\Shopy\Models\Coupon)
                    <livewire:discount-coupon wire:key="now()" :modelId="$model->id" />
                    @break

                @default
                    @break

            @endswitch
        
        @endif

    @endif

@endsection