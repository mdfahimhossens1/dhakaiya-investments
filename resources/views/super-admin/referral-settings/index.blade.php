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
                    <p>Turn the program on or off.</p>
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
                    <small>
                        Commission is paid <strong>only on the profit</strong> a referred user actually earns.
                        Account creation, share purchase, research and selling do not earn commission.
                        If a profit is reversed, its commission is adjusted automatically.
                    </small>
                </div>
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Commission rate</h3>
                    <p>Percentage of the referred user's profit paid to the direct referrer.</p>
                </div>
            </div>
            <div class="sa-card-body">
                <div class="sa-form-group" style="margin-bottom:0">
                    <label>Commission rate (%) <span>*</span></label>
                    <input type="number" name="level1_percent" min="0" max="100" step="0.01"
                        value="{{ old('level1_percent', $setting->level1_percent) }}" required>
                    <small>Example: at 10%, a ৳1,000 profit gives the referrer ৳100. The referred user keeps the full ৳1,000.</small>
                    @error('level1_percent')
                        <small style="color:#b91c1c">{{ $message }}</small>
                    @enderror
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
                        <small>The referrer must have invested at least this much to earn commission. 0 = just hold any share.</small>
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
                        <label>Minimum commission payout (৳) <span>*</span></label>
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