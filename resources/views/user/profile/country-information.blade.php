@extends('layouts.app')

@section('title', 'Country Information')

@section('content')

<div class="country-page">

    <div class="page-heading">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>My Profile</span>
                <span>/</span>
                <strong>Country Information</strong>
            </div>

            <h1>Country Information</h1>
            <p>View your registered country and regional information.</p>
        </div>
    </div>

    @if($country)

        <div class="country-overview">

            <div class="country-icon">🌍</div>

            <div class="country-overview-info">
                <span class="overview-label">REGISTERED COUNTRY</span>
                <h2>{{ $country['name'] }}</h2>
                <p>Country Code: {{ $country['code'] }}</p>
            </div>

            <div class="country-status">
                <span class="status-dot"></span>
                Registered Country
            </div>

        </div>

        <div class="information-card">

            <div class="card-heading">
                <div>
                    <h3>Regional Information</h3>
                    <p>Your country-related account information.</p>
                </div>

                <span class="read-only-badge">Read Only</span>
            </div>

            <div class="information-grid">

                <div class="info-item">
                    <span class="info-icon">🏳️</span>
                    <div>
                        <label>Country Name</label>
                        <h4>{{ $country['name'] }}</h4>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">🔤</span>
                    <div>
                        <label>Country Code</label>
                        <h4>{{ $country['code'] }}</h4>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">📞</span>
                    <div>
                        <label>International Calling Code</label>
                        <h4>{{ $country['calling_code'] }}</h4>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">💰</span>
                    <div>
                        <label>Currency</label>
                        <h4>{{ $country['currency'] }}</h4>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">💱</span>
                    <div>
                        <label>Currency Code</label>
                        <h4>{{ $country['currency_code'] }}</h4>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">🔣</span>
                    <div>
                        <label>Currency Symbol</label>
                        <h4>{{ $country['currency_symbol'] }}</h4>
                    </div>
                </div>

                <div class="info-item full-width">
                    <span class="info-icon">🕒</span>
                    <div>
                        <label>Time Zone</label>
                        <h4>{{ $country['timezone_label'] }}</h4>
                        <small>{{ $country['timezone'] }}</small>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <div>
                        <label>Date Format</label>
                        <h4>{{ $country['date_format'] }}</h4>
                    </div>
                </div>

            </div>

        </div>

        <div class="country-note">
            <span>ℹ️</span>
            <div>
                <strong>Country Information Notice</strong>
                <p>
                    Your registered country information is displayed here.
                    To change your country, visit the Personal Information page.
                </p>

                <a href="{{ route('user.personal-information.index') }}">
                    Update Personal Information →
                </a>
            </div>
        </div>

    @else

        <div class="empty-country">
            <div>🌍</div>
            <h3>Country Information Unavailable</h3>
            <p>Your registered country information could not be found.</p>

            <a href="{{ route('user.personal-information.index') }}">
                Update Personal Information
            </a>
        </div>

    @endif

</div>

@endsection

@push('styles')
<style>
.country-page {
    max-width: 1200px;
    margin: 0 auto;
}

.page-heading {
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    align-items: center;
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 12px;
}

.breadcrumb a {
    color: #087f5b;
    text-decoration: none;
}

.breadcrumb strong {
    color: #334155;
    font-weight: 600;
}

.page-heading h1 {
    font-size: 25px;
    color: #1e293b;
    margin: 0 0 7px;
}

.page-heading p {
    color: #84919f;
    font-size: 13px;
    margin: 0;
}

.country-overview {
    background: linear-gradient(120deg, #087f5b, #065f46);
    border-radius: 14px;
    padding: 27px;
    display: flex;
    align-items: center;
    gap: 20px;
    color: white;
    margin-bottom: 22px;
}

.country-icon {
    width: 65px;
    height: 65px;
    background: rgba(255,255,255,.15);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.country-overview-info {
    flex: 1;
}

.overview-label {
    font-size: 10px;
    letter-spacing: 1.3px;
    opacity: .75;
    font-weight: 700;
}

.country-overview-info h2 {
    font-size: 23px;
    margin: 5px 0;
    color: white;
}

.country-overview-info p {
    margin: 0;
    font-size: 12px;
    opacity: .85;
}

.country-status {
    font-size: 11px;
    background: rgba(255,255,255,.15);
    padding: 9px 13px;
    border-radius: 30px;
    white-space: nowrap;
}

.status-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #86efac;
    border-radius: 50%;
    margin-right: 5px;
}

.information-card {
    background: #fff;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 25px;
}

.card-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 20px;
    border-bottom: 1px solid #edf0f2;
    margin-bottom: 20px;
}

.card-heading h3 {
    margin: 0 0 5px;
    color: #1e293b;
    font-size: 16px;
}

.card-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 12px;
}

.read-only-badge {
    background: #f1f5f9;
    color: #64748b;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.information-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 14px;
    border: 1px solid #edf0f2;
    border-radius: 10px;
    min-width: 0;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    background: #effaf5;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.info-item label {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 6px;
}

.info-item h4 {
    color: #263445;
    font-size: 13px;
    font-weight: 600;
    margin: 0;
    overflow-wrap: anywhere;
}

.info-item small {
    display: block;
    color: #94a3b8;
    margin-top: 5px;
    font-size: 11px;
}

.country-note {
    display: flex;
    gap: 12px;
    background: #f0fdf4;
    border: 1px solid #dcfce7;
    padding: 17px;
    border-radius: 10px;
    margin-top: 20px;
}

.country-note > span {
    font-size: 18px;
}

.country-note strong {
    color: #166534;
    font-size: 12px;
}

.country-note p {
    color: #64748b;
    font-size: 12px;
    margin: 5px 0 8px;
    line-height: 1.6;
}

.country-note a {
    color: #087f5b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.empty-country {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 55px 20px;
    text-align: center;
}

.empty-country > div {
    font-size: 38px;
}

.empty-country h3 {
    color: #1e293b;
    margin: 15px 0 8px;
}

.empty-country p {
    color: #94a3b8;
    font-size: 13px;
}

.empty-country a {
    display: inline-block;
    margin-top: 12px;
    background: #087f5b;
    color: white;
    text-decoration: none;
    padding: 10px 17px;
    border-radius: 7px;
    font-size: 12px;
}

@media(max-width: 650px) {
    .country-overview {
        flex-wrap: wrap;
        padding: 20px;
    }

    .country-status {
        margin-left: 85px;
    }

    .information-card {
        padding: 17px;
    }

    .information-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .info-item.full-width {
        grid-column: auto;
    }

    .card-heading {
        align-items: flex-start;
    }
}
</style>
@endpush