@extends('layouts.app')

@section('title', 'Leaderboard Settings')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Rewards & Referrals</span><i class="fas fa-chevron-right"></i>
                <strong>Leaderboard Settings</strong>
            </div>
            <h1>Leaderboard Settings</h1>
            <p>Choose which leaderboards users see and how investors are ranked.</p>
        </div>
    </div>

    @include('partials.alerts')

    <form action="{{ route('super-admin.leaderboard-settings.update') }}" method="POST" style="max-width:820px">
        @csrf
        @method('PUT')

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Visible leaderboards</h3>
                    <p>Turn off a leaderboard to hide it from the user panel.</p>
                </div>
            </div>
            <div class="sa-card-body">
                @php
                    $toggles = [
                        'resell_leaderboard_enabled'  => ['Resell leaderboard', 'Ranks users by their share resale activity.'],
                        'monthly_leaderboard_enabled' => ['Monthly leaderboard', 'Shows the best referrers of the current month.'],
                        'top_performers_enabled'      => ['Top performers', 'Highlights the best overall investors.'],
                        'show_amount_publicly'        => ['Show amounts publicly', 'Display profit and volume figures next to each name.'],
                    ];
                @endphp

                @foreach($toggles as $field => [$title, $hint])
                    <div class="sa-switch-row">
                        <div>
                            <strong>{{ $title }}</strong>
                            <small>{{ $hint }}</small>
                        </div>
                        <label class="sa-switch">
                            <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $setting->$field) ? 'checked' : '' }}>
                            <i></i>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3>Ranking rules</h3>
                    <p>Decide what counts and when the board starts over.</p>
                </div>
            </div>
            <div class="sa-card-body">
                <div class="sa-form-row-3">
                    <div class="sa-form-group">
                        <label>Ranking basis <span>*</span></label>
                        <select name="ranking_basis" required>
                            @foreach(['resell_profit' => 'Resell profit', 'resell_volume' => 'Resell volume', 'referral_count' => 'Referral count', 'investment_amount' => 'Investment amount'] as $value => $label)
                                <option value="{{ $value }}" {{ old('ranking_basis', $setting->ranking_basis) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sa-form-group">
                        <label>Reset cycle <span>*</span></label>
                        <select name="reset_cycle" required>
                            @foreach(['weekly' => 'Weekly', 'monthly' => 'Monthly', 'never' => 'Never'] as $value => $label)
                                <option value="{{ $value }}" {{ old('reset_cycle', $setting->reset_cycle) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sa-form-group">
                        <label>Show top <span>*</span></label>
                        <input type="number" name="show_top_n" min="1" max="100" value="{{ old('show_top_n', $setting->show_top_n) }}" required>
                        <small>Between 1 and 100 users.</small>
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