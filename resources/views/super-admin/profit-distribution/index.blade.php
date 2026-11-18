@extends('layouts.app')

@section('title', 'Profit Distribution')

@section('content')

@include('user.investment._styles')

<div class="inv-page">
    <div class="inv-heading">
        <div><h2>Daily Profit Distribution</h2><p>Runs automatically every night. You can also run a past date here. Running twice never pays twice.</p></div>
        <span class="inv-badge">{{ number_format($holdings) }} holdings</span>
    </div>

    @if(session('success'))<div class="inv-panel" style="padding:13px 16px;background:#eaf8f0;color:#137547">✓ {{ session('success') }}</div>@endif
    @if($errors->any())<div class="inv-panel" style="padding:13px 16px;background:#fff0ef;color:#b42318">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <div class="inv-panel">
        <form method="POST" action="{{ route('super-admin.profit-distribution.run') }}" class="inv-filter">
            @csrf
            <input type="date" name="date" value="{{ old('date', $yesterday) }}" max="{{ $yesterday }}" style="padding:10px 13px;border:1px solid var(--border);border-radius:8px">
            <label style="display:flex;gap:6px;align-items:center;font-size:13px"><input type="checkbox" name="dry" value="1"> Preview only</label>
            <button class="inv-btn primary">Run</button>
        </form>
        @if($output)<pre style="margin:0;padding:16px 20px;font-size:12px;white-space:pre-wrap;background:#0f172a;color:#e2e8f0;border-radius:0 0 12px 12px">{{ $output }}</pre>@endif
    </div>

    <div class="inv-panel">
        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:520px">
                <thead><tr><th>Profit Date</th><th>Entries</th><th>Users</th><th>Total (BDT)</th></tr></thead>
                <tbody>
                @forelse($days as $d)
                    <tr><td>{{ \Illuminate\Support\Carbon::parse($d->profit_date)->format('d M Y') }}</td><td>{{ $d->entries }}</td><td>{{ $d->users }}</td><td>{{ number_format((float) $d->total_bdt, 2) }}</td></tr>
                @empty
                    <tr><td colspan="4"><div class="inv-empty"><p style="margin:0">No profit has been distributed yet.</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection