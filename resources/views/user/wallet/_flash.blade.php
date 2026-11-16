@if(session('success'))<div class="wl-flash ok">✓ {{ session('success') }}</div>@endif
@if(session('error'))<div class="wl-flash err">{{ session('error') }}</div>@endif
@if($errors->any())
    <div class="wl-flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif