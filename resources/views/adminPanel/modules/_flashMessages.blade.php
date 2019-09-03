@if(session('flash_message') && isset(session('flash_message')['type']) && isset(session('flash_message')['text']))
    <div class="row justify-content-center px-3">
        <div class="text-center col-12 mt-3 alert alert-{{ session('flash_message')['type'] }}">
            {!!  session('flash_message')['text']!!}
        </div>
    </div>
@endif