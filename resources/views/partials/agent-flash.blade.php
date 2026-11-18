@if(session('success'))
    <div class="ag-notice ok"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="ag-notice err"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="ag-notice err">
        <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif