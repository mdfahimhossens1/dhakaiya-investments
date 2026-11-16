@if(session('success'))
    <div class="sa-alert sa-alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
@endif

@if($errors->any())
    <div class="sa-alert sa-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
@endif