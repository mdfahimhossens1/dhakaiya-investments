@extends('layouts.app')

@section('title', 'Achievement Rewards')

@section('content')

@include('user.investment._styles')

<style>
    .ac-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(290px, 1fr)); gap:18px; }
    .ac-card { background:var(--white); border:1px solid var(--border); border-radius:13px; padding:22px; display:flex; flex-direction:column; gap:12px; }
    .ac-card.done { border-color:#86efac; }
    .ac-top { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; }
    .ac-icon { width:46px; height:46px; border-radius:12px; background:#e8f5ef; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:20px; }
    .ac-card h4 { margin:0; font-size:16px; font-weight:700; color:var(--text); }
    .ac-card p { margin:0; font-size:12px; color:var(--muted); line-height:1.7; }
    .bar { height:7px; background:#e2e8f0; border-radius:20px; overflow:hidden; }
    .bar div { height:100%; background:var(--primary); }
    .st-badge.in_progress { background:#dbeafe; color:#1d4ed8; }
    .st-badge.locked { background:#f1f5f9; color:#64748b; }
</style>

@php
    $typeLabels = ['cash' => 'Cash', 'bonus_share' => 'Bonus Share', 'gift' => 'Gift', 'badge' => 'Badge'];
    $stateLabels = ['completed' => 'Completed', 'in_progress' => 'In Progress', 'locked' => 'Locked'];
@endphp

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Achievement Rewards</h2>
            <p>Milestones you can reach by investing more.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-coins"></i> Your investment: {{ number_format($invested, 2) }} BDT</span>
    </div>

    <div class="inv-summary">
        <div class="inv-stat"><div class="icon"><i class="fas fa-trophy"></i></div><div class="label">Total Milestones</div><h3>{{ number_format($summary['total']) }}</h3><small>Published by the platform</small></div>
        <div class="inv-stat"><div class="icon"><i class="fas fa-check-circle"></i></div><div class="label">Completed</div><h3>{{ number_format($summary['completed']) }}</h3><small>Requirement met</small></div>
        <div class="inv-stat"><div class="icon"><i class="fas fa-spinner"></i></div><div class="label">In Progress</div><h3>{{ number_format($summary['in_progress']) }}</h3><small>Getting closer</small></div>
        <div class="inv-stat"><div class="icon"><i class="fas fa-lock"></i></div><div class="label">Locked</div><h3>{{ number_format($summary['locked']) }}</h3><small>Start investing to progress</small></div>
    </div>

    @if($achievements->isEmpty())

        <div class="inv-panel">
            <div class="inv-empty">
                <div class="icon"><i class="fas fa-trophy"></i></div>
                <h4>No Achievements Yet</h4>
                <p>Milestones will appear here once reward categories are published.</p>
            </div>
        </div>

    @else

        <div class="ac-grid">
            @foreach($achievements as $a)
                @php $val = rtrim(rtrim(number_format((float) $a->reward_value, 2), '0'), '.'); @endphp

                <div class="ac-card {{ $a->state === 'completed' ? 'done' : '' }}">
                    <div class="ac-top">
                        <div class="ac-icon"><i class="fas {{ $a->state === 'completed' ? 'fa-trophy' : 'fa-bullseye' }}"></i></div>
                        <span class="st-badge {{ $a->state }}">{{ $stateLabels[$a->state] }}</span>
                    </div>

                    <div>
                        <h4>{{ $a->name }}</h4>
                        @if($a->description)<p>{{ $a->description }}</p>@endif
                    </div>

                    <div style="font-size:12px; color:var(--text);">
                        Reach <strong>{{ number_format((float) $a->min_investment, 2) }} BDT</strong> invested to earn
                        <strong>{{ $a->value_type === 'percent' ? $val . '%' : $val }} {{ $typeLabels[$a->reward_type] ?? ucfirst($a->reward_type) }}</strong>
                    </div>

                    <div>
                        <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--muted); margin-bottom:6px;">
                            <span>{{ $a->progress }}% complete</span>
                            @if($a->state !== 'completed')<span>{{ number_format($a->remaining, 2) }} BDT to go</span>@endif
                        </div>
                        <div class="bar"><div style="width: {{ $a->progress }}%"></div></div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>

@endsection