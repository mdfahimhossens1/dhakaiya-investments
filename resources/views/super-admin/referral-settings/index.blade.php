@extends('layouts.app')

@section('title', 'Referral Settings')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Rewards & Referrals</span><i class="fas fa-chevron-right"></i>
                <strong>Referral Settings</strong>
            </div>
            <h1>Referral Settings</h1>
            <p>Control how referral commission is earned, held and withdrawn.</p>
        </div>
    </div>

    @include('partials.alerts')

    <form action="{{ route('super-admin.referral-settings.update') }}" method="POST" style="max-width:820px">
        @csrf
        @method('PUT')

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Referral program</h3>
                    <p>Turn the program on or off and choose what earns commission.</p>
                </div>
            </div>
            <div class="sa-card-body">
                <div class="sa-switch-row">
                    <div>
                        <strong>Referral system enabled</strong>
                        <small>When off, no new commission is created.</small>
                    </div>
                    <label class="sa-switch">
                        <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', $setting->is_enabled) ? 'checked' : '' }}>
                        <i></i>
                    </label>
                </div>

                <div class="sa-form-group" style="margin-bottom:0">
                    <label>Commission is paid on <span>*</span></label>
                    <select name="commission_on" required>
                        @foreach(['share_purchase' => 'Referred user\'s share purchase', 'profit' => 'Referred user\'s profit', 'both' => 'Both purchase and profit'] as $value => $label)
                            <option value="{{ $value }}" {{ old('commission_on', $setting->commission_on) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Commission by level</h3>
                    <p>Level 1 is the direct referrer. Use 0 to switch a level off.</p>
                </div>
                <span class="sa-total" id="levelTotal">Total 0%</span>
            </div>
            <div class="sa-card-body">
                <div class="sa-form-row-3">
                    @foreach([1,2,3] as $n)
                        <div class="sa-form-group">
                            <label>Level {{ $n }} (%) <span>*</span></label>
                            <input type="number" class="level-input" name="level{{ $n }}_percent" min="0" max="100" step="0.01"
                                value="{{ old('level'.$n.'_percent', $setting->{'level'.$n.'_percent'}) }}" required>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Limits and payout</h3>
                    <p>Rules that protect the platform from abuse.</p>
                </div>
            </div>
            <div class="sa-card-body">
                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Minimum investment to qualify (৳) <span>*</span></label>
                        <input type="number" name="min_investment_to_qualify" min="0" step="0.01"
                            value="{{ old('min_investment_to_qualify', $setting->min_investment_to_qualify) }}" required>
                        <small>A referred user must invest at least this much.</small>
                    </div>
                    <div class="sa-form-group">
                        <label>Max commission per referral (৳)</label>
                        <input type="number" name="max_commission_per_referral" min="0" step="0.01"
                            value="{{ old('max_commission_per_referral', $setting->max_commission_per_referral) }}" placeholder="No limit">
                        <small>Leave empty for no limit.</small>
                    </div>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Hold period (days) <span>*</span></label>
                        <input type="number" name="hold_days" min="0" value="{{ old('hold_days', $setting->hold_days) }}" required>
                        <small>Commission stays pending for this many days.</small>
                    </div>
                    <div class="sa-form-group">
                        <label>Minimum commission withdrawal (৳) <span>*</span></label>
                        <input type="number" name="min_commission_withdraw" min="0" step="0.01"
                            value="{{ old('min_commission_withdraw', $setting->min_commission_withdraw) }}" required>
                    </div>
                </div>
            </div>

            <div class="sa-save-bar">
                <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> Save settings</button>
            </div>
        </div>
    </form>
</div>

@endsection


@push('styles')
    @include('partials.ui-styles')
@endpush

@push('scripts')
    <script>
        (function () {
            const inputs = document.querySelectorAll('.level-input');
            const out = document.getElementById('levelTotal');

            function update() {
                let sum = 0;
                inputs.forEach(function (i) { sum += parseFloat(i.value) || 0; });
                out.textContent = 'Total ' + (Math.round(sum * 100) / 100) + '%';
            }

            inputs.forEach(function (i) { i.addEventListener('input', update); });
            update();
        })();
    </script>
@endpush