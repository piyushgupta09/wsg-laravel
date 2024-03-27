<div class="d-flex flex-column w-100" style="min-height: 60vh; overflow-x: hidden;">

    <!-- Action Bar -->
    <!-- It shows the features like Add, Import, Download Sample, Export, Bulk Delete, Bulk Restore -->
    <div class="btn-group mb-3 w-100" style="max-width: 400px">
        @foreach ($buttonsTop as $button)
            @if ($button['show'][$activePage])
                @include('panel::includes.' . $button['type'], [
                    'title' => $button['label'],
                    'icon' => $button['icon'],
                    'style' => $button['style'],
                    'action' => $button['route'] ? $button['route'] : $button['function'],
                    'data' => Str::afterLast($model, '\\'), 
                ])
            @endif
        @endforeach
    </div>    

    <!-- Search, Alert & Filter Bar -->
    <div class="d-flex align-items-center justify-content-between mb-2">

        <!-- It shows the  search features. -->
        @if ($features['search']['show'][$activePage])
            <div class="me-auto">

                @include('panel::includes.' .$features['search']['type'], [
                    'function' => $features['search']['attributes'],
                    'placeholder' => $features['search']['label'],
                ])

            </div>
        @endif

        <!-- BULK DELETE - It shows the total number of selected records. -->
        @if($features['bulk_actions']['show'][$activePage] && count($selectedRecords))
            <div class="flex-fill d-none d-lg-flex justify-content-center" style="max-width: 50vw">
                <x-panel-selected-records-alert-box
                    type="danger"
                    message="Selected {{ count($selectedRecords) }} rows"
                />
            </div>
        @endif

        <!-- It shows the  Filter Column features. -->
        @if ($features['column_filter']['show'][$activePage])
            @include('panel::includes.' . $features['column_filter']['type'])
        @endif

    </div>

    <!-- BULK DELETE - It shows the total number of selected records. -->
    @if($features['bulk_actions']['show'][$activePage] && count($selectedRecords))
        <div class="flex-fill d-flex d-lg-none justify-content-center w-100">
            <x-panel-selected-records-alert-box
                type="danger"
                message="Selected {{ count($selectedRecords) }} rows"
            />
        </div>
    @endif

    <!-- Data Table -->
    <div class=flex-fill style="overflow-y: auto;">
        <table class="table table-striped" style="overflow-x: scroll !important">
            <thead>
                <tr class="align-items-center">
                    <!-- 
                        BULK DELETE 
                        It gives the features to select "Active Page" and "All Page"
                    -->
                    @if ($features['bulk_actions']['show'][$activePage] && $data->isNotEmpty())
                        <th scope="col">
                            <x-panel-bulk-select  
                                :modelName="$modelName"
                                :labels="$features['bulk_actions']['labels']"
                                :selectPage="$selectPage"
                                :selectAll="$selectAll"
                            />
                        </th>
                    @endif

                    @foreach ($fields as $field)
                        @if ($field['viewable'][$activePage])
                            @include('panel::includes.' .$field['thead']['view'], [
                                'name' => $field['name'],
                                'label'=> $field['labels']['table'],
                                'sortable' => $field['sortable'],
                                'align' => $field['thead']['align']?:'start',
                            ])
                        @endif
                    @endforeach

                    <!-- ROW ACTIONS -->
                    @if ($rowActionsEnabled)
                            @include('panel::includes.' .$field['thead']['view'], [
                                'label' => '',
                                'sortable' => false,
                                'align' =>'center'
                            ])
                    @endif

                </tr>
            </thead>
            <tbody>
                @if ($data->isEmpty())
                    <tr class="w-100">
                        <td colspan="{{ $visiblefields }}">
                            {{ $messages['no_record_found'] }}
                        </td>
                    </tr>
                @else
                    @foreach ($data as $model)
                    
                        <tr>

                            <!-- Bulk Action SelectBox for delete -->
                            @if ($features['bulk_actions']['show'][$activePage])
                                <td>
                                    @include('panel::includes.others.row-checkbox')
                                </td>
                            @endif

                            <!-- Data Row -->
                            @foreach ($fields as $field)
                                @if ($field['viewable'][$activePage])
                                    <td class="">
                                        @include('panel::includes.' .$field['tbody']['view'], [
                                            'iteration'=> $loop->parent->iteration,
                                            'name' => $field['name'],
                                            'value' => $field['tbody']['value'] ?: null,
                                            'align' => $field['tbody']['align']?:'start',
                                        ])
                                    </td>
                                @endif
                            @endforeach

                            <!-- Row Action Buttons: Mobile -->
                            <td class="p-1 d-table-cell d-lg-none text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu p-3 shadow">
                                        <li class="small text-uppercase ls-1 font-quick mb-2 text-center">Take Quick Action</li>
                                        @if ($features['row_actions']['show']['view'][$activePage] ||
                                            $features['row_actions']['show']['edit'] || 
                                            $features['row_actions']['show']['delete'])
                                                @include('panel::includes.' . $features['row_actions']['type']['buttons'])
                                        @endif
                                    </ul>
                                </div>
                            </td>

                            <!-- Row Action Buttons: Desktop -->
                            @if ($features['row_actions']['show']['view'][$activePage] ||
                                $features['row_actions']['show']['edit'] || 
                                $features['row_actions']['show']['delete'])
                                <td class="p-1 d-none d-lg-table-cell text-center">
                                    @include('panel::includes.' . $features['row_actions']['type']['buttons'])
                                </td>
                            @endif

                        </tr>

                        <!-- Expandable View by Row Action -->
                        @if (isset($buttonsTable['view']) && Str::afterLast($buttonsTable['view']['type'], 'action-') == 'toggle')
                            @include('panel::includes.' . $features['row_actions']['type']['collapse'])
                        @endif


                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($features['pagination']['show'][$activePage])
        @include('panel::includes.' . $features['pagination']['type'])
    @endif

</div>
