{{-- 
   $queryName : It is the "page"(hard code) key for pagination 
   $queryValue : It is current page value in pagination
   $action : route name
   $data : It is a model
   $style : Class name
   $confirm : Control the bootstrape modal will show or not
   $title : It is a label
--}}

<form class="btn btn-outline-dark border-secondary py-0 overflow-hidden" action="{{ route($action, [$data]) }}" method="post">
    @csrf
    @method('DELETE')

    <button 
        type="submit"
        style="color: inherit"
        class="btn p-0 {{ $style }}">
        @if (isset($icon))
            <i class="{{ $icon }}"></i>
        @else
            {{ $title }}
        @endif
    </button>
</form>

{{-- <div class="">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-outline-dark border-secondary py-0 overflow-hidden br-s"
        data-bs-toggle="modal" data-bs-target="#deleteModal">
        @if (isset($icon))
        <i class="{{ $icon }}"></i>
        @else
        {{ $title }}
        @endif
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Confirm Delete</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form class="form-inline" action="{{ route($action, [$data]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}