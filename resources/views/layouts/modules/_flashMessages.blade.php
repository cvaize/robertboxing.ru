@if(session('flashMessages') && is_array(session('flashMessages')))
    @foreach(session('flashMessages') as $message)
        <div class="container pt-2 mt-2">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-{{ $message['type'] ?? 'success' }} text-center mb-0">
                        {!! $message['text'] ?? null !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

