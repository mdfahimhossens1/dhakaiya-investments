@extends('layouts.app')

@section('title', 'Referral Statistics')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Statistics</h2>
        <p>Your referral network growth and earnings by level.</p>
    </div>
</div>

@include('partials.referral-styles')

<style>
    .rf-two { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .rf-bar-row { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .rf-bar-row:last-child { margin-bottom: 0; }
    .rf-bar-label { width: 78px; flex-shrink: 0; font-size: 13px; color: var(--text); }
    .rf-bar { flex: 1; height: 10px; background: #f1f5f9; border-radius: 20px; overflow: hidden; }
    .rf-bar span { display: block; height: 100%; background: linear-gradient(90deg, var(--primary), #34d399); border-radius: 20px; min-width: 2px; }
    .rf-bar-count { width: 30px; text-align: right; font-size: 13px; font-weight: 700; color: var(--text); }
    .rf-bar-money { width: 90px; text-align: right; font-size: 12px; color: var(--muted); white-space: nowrap; }

    .rf-list-item { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px solid #f1f5f9; }
    .rf-list-item:last-child { border-bottom: none; }
    .rf-avatar {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        background: #e9f7f1; color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
    }
    .rf-list-item strong { display: block; font-size: 13.5px; color: var(--text); }
    .rf-list-item small { font-size: 12px; color: var(--muted); }

    @media (max-width: 850px) { .rf-two { grid-template-columns: 1fr; } }
    @media (max-width: 550px) {
        .rf-bar-label { width: 62px; font-size: 11.5px; }
        .rf-bar-money { display: none; }
    }
</style>

@php
    use Illuminate\Support\Str;
    $max = max($monthlyStatistics->max('total'), 1);
@endphp

<div class="rf-wrap">

    {{-- Summary --}}
    <div class="rf-grid-3">
        <div class="rf-card">
            <div class="rf-icon"><i class="fa-solid fa-users"></i></div>
            <div>
                <h3>{{ $summary['total_referrals'] }}</h3>
                <p>Direct Referrals</p>
            </div>
        </div>
        <div class="rf-card">
            <div class="rf-icon blue"><i class="fa-solid fa-calendar-days"></i></div>
            <div>
                <h3>{{ $summary['this_month'] }}</h3>
                <p>This Month</p>
            </div>
        </div>
        <div class="rf-card">
            <div class="rf-icon green"><i class="fa-solid fa-coins"></i></div>
            <div>
                <h3>৳{{ number_format($summary['total_commission_bdt'], 2) }}</h3>
                <p>Total Commission</p>
            </div>
        </div>
    </div>

    <div class="rf-two">

        {{-- Monthly --}}
        <div class="rf-panel">
            <div class="rf-panel-head">
                <div>
                    <h3>Monthly Overview</h3>
                    <p>New direct referrals and commission, last six months.</p>
                </div>
            </div>

            @foreach($monthlyStatistics as $stat)
                <div class="rf-bar-row">
                    <div class="rf-bar-label">{{ $stat['month'] }}</div>
                    <div class="rf-bar"><span style="width: {{ ($stat['total'] / $max) * 100 }}%;"></span></div>
                    <div class="rf-bar-count">{{ $stat['total'] }}</div>
                    <div class="rf-bar-money">৳{{ number_format($stat['commission_bdt'], 2) }}</div>
                </div>
            @endforeach
        </div>

        {{-- Latest referrals --}}
        <div class="rf-panel">
            <div class="rf-panel-head">
                <div>
                    <h3>Latest Referrals</h3>
                    <p>People who joined most recently.</p>
                </div>
            </div>

            @forelse($latestReferrals as $ref)
                <div class="rf-list-item">
                    <div class="rf-avatar">{{ strtoupper(mb_substr($ref->name, 0, 1)) }}</div>
                    <div>
                        <strong>{{ Str::mask($ref->name, '*', 2) }}</strong>
                        <small>Joined {{ $ref->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @empty
                <div class="rf-empty" style="padding:30px 10px;">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>No referrals yet. Share your link to grow your network.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Level breakdown --}}
    <div class="rf-panel">
        <div class="rf-panel-head">
            <div>
                <h3>Network &amp; Earnings by Level</h3>
                <p>
                    Team size and commission earned at each level
                    @if($enabled) · Earned on {{ $program['commission_on_label'] }} @endif
                </p>
            </div>
            @if(! $enabled)
                <span class="rf-badge cancelled">Program Off</span>
            @endif
        </div>

        <div class="rf-levels">
            @foreach([1 => 'Direct', 2 => 'Second level', 3 => 'Third level'] as $lvl => $label)
                @php
                    $rate   = (float) ($program['levels'][$lvl] ?? 0);
                    $row    = $byLevel->get($lvl);
                    $earned = $row?->total ?? 0;
                    $count  = (int) ($row?->c ?? 0);
                @endphp

                <div class="rf-level {{ $rate <= 0 ? 'off' : '' }}">
                    <small>Level {{ $lvl }} · {{ $label }}</small>
                    <strong>{{ $team[$lvl] }}</strong>
                    <span>members</span>

                    <span style="margin-top:14px;color:var(--text);font-weight:700;font-size:14px;">
                        ৳{{ number_format($earned, 2) }} earned
                    </span>
                    <span>{{ $count }} {{ Str::plural('commission', $count) }}</span>
                    <span>{{ $rate > 0 ? 'Rate: ' . ($rate + 0) . '%' : 'Level disabled' }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rf-notice">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Note:</strong>
        Earnings exclude cancelled commission. Pending amounts are included and are credited
        to your wallet after the hold period.
    </div>

</div>

@endsection