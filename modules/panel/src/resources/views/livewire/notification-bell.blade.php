<div>
    <div class="m-4">
        <button wire:click="refreshPage" class="btn btn-primary">Send Notification</button>
    </div>


    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell"></i>
            <span class="badge bg-danger">{{ $count }}</span>
        </a>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach ($notifications as $item)
                <li class="dropdown-item">{{ json_decode(json_encode($item->data))->name }}</li>
            @endforeach
        </ul>
    </div>
</div>