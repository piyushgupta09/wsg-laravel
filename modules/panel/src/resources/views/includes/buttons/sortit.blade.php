{{-- 
 $modelName : It is model name used for generating unique id of input field.   
 $name :  It is field name of table of database
 $label :  It ia used as label
 $sortable : it control that sortable section(Asc or Desc) of field will be created or not .    
--}}
<th>
    <div class="d-flex align-items-center font-quick fw-bold w-100 text-{{ $align }}" style="white-space: nowrap;">
        {{ $label }}
        @if (!empty($sortable))
            @if (Str::beforeLast($sortSelect, '#') == $name)
                @if (Str::afterLast($sortSelect, '#') == 'asc')
                    <button 
                        type="button" 
                        class="ms-2 btn py-0 px-1 rounded-0 border-0 text-bg-secondary" 
                        wire:click="toggleSort('{{ $name . '#desc' }}')">
                        <i class="bi bi-sort-down"></i>
                    </button>
                @endif
                @if (Str::afterLast($sortSelect, '#') == 'desc')
                    <button 
                        type="button"
                        class="ms-2 btn py-0 px-1 rounded-0 border-0 text-bg-secondary" 
                        wire:click="toggleSort('{{ $name . '#asc' }}')">
                        <i class="bi bi-sort-up"></i>
                    </button>
                @endif
            @else
                <button 
                    type="button" 
                    class="ms-2 btn py-0 px-1 rounded-0 border-0 text-muted" 
                    wire:click="toggleSort('{{ $name . '#asc' }}')">
                    <i class="bi bi-justify"></i>
                </button>
            @endif
        @endif
    </div>
</th>
