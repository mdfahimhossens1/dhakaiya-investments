@extends('layouts.app')

@section('title', 'Referral Commission')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Commission</h2>
        <p>Track your referral earnings and commission history.</p>
    </div>
</div>

@include('partials.referral-styles')

@php
    use Illuminate\Support\Str;

    $typeLabels = ['share_purchase' => 'Share Purchase', 'profit' => 'Profit'];
    $e = $eligibility;
    $days = (int) $e['hold_days'];

    $required = (float) $e['required_bdt'];
    $mine     = (float) $e['my_investment_bdt'];
    $progress = $required > 0 ? min(100, round(($mine / $required) * 100)) : 100;

    $levelRates = collect($e['levels'])->filter(fn ($p) => (float) $p > 0);
@endphp

<div class="rf-wrap">

    {{-- Summary --}}
    <div class="rf-grid-3">
        <div class="rf-card">
            <div class="rf-icon"><i class="fa-solid fa-coins"></i></div>
            <div>
                <h3>৳{{ number_format($summary['total_commission'], 2) }}</h3>
                <p>Total Commission</p>
            </div>
        </div>

        <div class="rf-card">
            <div class="rf-icon amber"><i class="fa-solid fa-clock"></i></div>
            <div>
                <h3>৳{{ number_format($summary['pending_commission'], 2) }}</h3>
                <p>Pending Commission</p>
            </div>
        </div>

        <div class="rf-card">
            <div class="rf-icon green"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <h3>৳{{ number_format($summary['paid_commission'], 2) }}</h3>
                <p>Paid to Wallet</p>
                @foreach($summary['paid_by_currency'] as $cur => $amt)
                    @if($cur)
                        <small>{{ number_format($amt, 2) }} {{ $cur }}</small>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Eligibility --}}
    @if(! $e['enabled'])
        <div class="rf-notice warn">
            <i class="fa-solid fa-circle-pause"></i>
            <strong>Program is off.</strong>
            No new commission is being created right now. Your existing pending commission is not affected.
        </div>
    @elseif($e['qualified'])
        <div class="rf-notice ok">
            <i class="fa-solid fa-circle-check"></i>
            <strong>You are eligible.</strong>
            You will receive commission on your referrals' {{ strtolower($e['commission_on_label']) }}.
        </div>
    @else
        <div class="rf-panel" style="border-color:#fde68a;background:#fffbeb;">
            <div class="rf-panel-head" style="margin-bottom:6px;">
                <div>
                    <h3 style="color:#92400e;"><i class="fa-solid fa-lock"></i> Unlock your commission</h3>
                    <p style="color:#92400e;">
                        Invest at least ৳{{ number_format($required, 2) }} to start earning referral commission.
                    </p>
                </div>
            </div>

            <div class="rf-progress"><span style="width: {{ $progress }}%;"></span></div>

            <p style="font-size:12.5px;margin:0;color:#92400e;">
                You have invested ৳{{ number_format($mine, 2) }} ·
                <strong>৳{{ number_format($e['shortfall_bdt'], 2) }} more</strong> to qualify ({{ $progress }}%)
            </p>
        </div>
    @endif

    {{-- Program rules (dynamic) --}}
    @if($e['enabled'])
        <div class="rf-panel">
            <div class="rf-panel-head">
                <div>
                    <h3>Commission Rates</h3>
                    <p>Earned on: {{ $e['commission_on_label'] }}</p>
                </div>
                <span class="rf-badge paid">Program Active</span>
            </div>

            <div class="rf-levels">
                @foreach([1 => 'Direct referral', 2 => 'Second level', 3 => 'Third level'] as $lvl => $label)
                    @php $p = (float) ($e['levels'][$lvl] ?? 0); @endphp
                    <div class="rf-level {{ $p <= 0 ? 'off' : '' }}">
                        <small>Level {{ $lvl }}</small>
                        <strong>{{ $p > 0 ? ($p + 0) . '%' : '—' }}</strong>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            <div class="rf-chips">
                <span class="rf-chip">
                    <i class="fa-solid fa-hourglass-half"></i>
                    Hold period: {{ $days }} {{ Str::plural('day', $days) }}
                </span>
                <span class="rf-chip">
                    <i class="fa-solid fa-wallet"></i>
                    Min withdrawal: ৳{{ number_format($e['min_payout_bdt'], 2) }}
                </span>
                @if($required > 0)
                    <span class="rf-chip">
                        <i class="fa-solid fa-user-check"></i>
                        Min investment: ৳{{ number_format($required, 2) }}
                    </span>
                @endif
                @if($e['max_per_referral_bdt'] !== null)
                    <span class="rf-chip">
                        <i class="fa-solid fa-ranking-star"></i>
                        Cap per referral: ৳{{ number_format($e['max_per_referral_bdt'], 2) }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- History --}}
    <div class="rf-panel">
        <div class="rf-panel-head">
            <div>
                <h3>Commission History</h3>
                <p>Every commission you have earned and its status.</p>
            </div>

            <div class="rf-tabs">
                @foreach(['all' => 'All', 'pending' => 'Pending', 'paid' => 'Paid'] as $key => $label)
                    <a class="rf-tab {{ $status === $key ? 'active' : '' }}"
                       href="{{ request()->url() }}?status={{ $key }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        @if($commissions->count())
            <div class="rf-table-wrap">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>From</th>
                            <th>Level</th>
                            <th>Type</th>
                            <th class="num">Base</th>
                            <th class="num">Rate</th>
                            <th class="num">Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commissions as $c)
                            <tr>
                                <td>
                                    {{ $c->created_at->format('d M Y') }}
                                    <small>{{ $c->created_at->format('h:i A') }}</small>
                                </td>
                                <td>{{ Str::mask($c->sourceUser?->name ?? 'User', '*', 2) }}</td>
                                <td><span class="rf-badge lvl">L{{ $c->level }}</span></td>
                                <td><span class="rf-badge type">{{ $typeLabels[$c->type] ?? ucfirst($c->type) }}</span></td>
                                <td class="num">৳{{ number_format($c->base_bdt, 2) }}</td>
                                <td class="num">{{ (float) $c->percent }}%</td>
                                <td class="num">
                                    <span class="rf-money">৳{{ number_format($c->commission_bdt, 2) }}</span>
                                    @if($c->status === 'paid' && $c->wallet_currency)
                                        <small>{{ number_format($c->wallet_amount, 2) }} {{ $c->wallet_currency }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="rf-badge {{ $c->status }}">{{ ucfirst($c->status) }}</span>
                                    @if($c->status === 'pending' && $c->release_at)
                                        <small>Releases {{ $c->release_at->diffForHumans() }}</small>
                                    @elseif($c->status === 'paid' && $c->paid_at)
                                        <small>{{ $c->paid_at->format('d M Y') }}</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="rf-pagination">{{ $commissions->links() }}</div>
        @else
            <div class="rf-empty">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <h4>No commission {{ $status !== 'all' ? "({$status})" : '' }} yet</h4>
                <p>
                    When people you refer buy shares or earn profit, your commission will appear here.
                </p>
            </div>
        @endif
    </div>

    <div class="rf-notice">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Note:</strong>
        New commission stays <em>pending</em> for the hold period, then is credited to your wallet.
        Rules may be updated by the platform and apply to future commission only.
    </div>

</div>

@endsection