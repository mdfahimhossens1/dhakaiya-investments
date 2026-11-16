@extends('layouts.app')

@section('title', 'Withdrawal Methods')

@section('content')

@include('user.wallet._styles')

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Withdrawal Methods</h2>
            <p>Methods available for your country and the accounts you have saved.</p>
        </div>
        <a href="{{ route('user.payment-information.index') }}" class="wl-pill">+ Add / Manage Accounts</a>
    </div>

    @include('user.wallet._flash')

    <div class="wl-hero">
        <div>
            <h2>Withdrawal Method Overview</h2>
            <p>{{ collect($withdrawalMethods)->where('status', 'Activated')->count() }} of {{ count($withdrawalMethods) }} methods are set up with an account.</p>
        </div>
        <div class="wl-hero-actions"><a href="{{ route('user.withdraw') }}">Request Withdrawal</a></div>
    </div>

    <div class="wl-mgrid">
        @forelse($withdrawalMethods as $m)
            <div class="wl-mcard">
                <div class="wl-mtop">
                    <div class="wl-mic">
                        @if(!empty($m['emoji']))<span>{{ $m['emoji'] }}</span>@else<i class="{{ $m['icon'] }}"></i>@endif
                    </div>
                    <span class="wl-badge {{ $m['status'] === 'Activated' ? 'approved' : 'pending' }}">{{ $m['status'] }}</span>
                </div>

                <h4>{{ $m['name'] }}</h4>
                <p>{{ $m['description'] }}</p>

                @forelse($m['accounts'] as $a)
                    <div class="wl-acc">
                        <span><strong>{{ $a->currency }}</strong> · {{ $a->maskedDetails() }}</span>
                        <span>{{ $a->is_default ? 'Default' : '' }}</span>
                    </div>
                @empty
                    <div class="wl-acc"><span>Account details</span><strong>Not added</strong></div>
                @endforelse

                <a href="{{ route('user.payment-information.index') }}" class="wl-btn ghost" style="width:100%;margin-top:14px">
                    {{ $m['status'] === 'Activated' ? 'Manage Accounts' : 'Add Account' }}
                </a>
            </div>
        @empty
            <div class="wl-mcard">
                <h4>No Methods Available</h4>
                <p>No withdrawal method is available for your country yet. Please check back later.</p>
            </div>
        @endforelse
    </div>

    <div class="wl-notice" style="margin-top:25px">
        <i class="fa-solid fa-circle-info"></i>
        <div>
            <h4>Important Information</h4>
            <p>Payment methods are managed by the platform. Only methods that support your country and wallet currency are shown. Add your account details once, then use them for every withdrawal. Your full account number is never displayed.</p>
        </div>
    </div>

</div>

@endsection