@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

@php
    // Controller theke pathale ei variable gulo use hobe, na pathale sample data dekhabe
    $growthLabels = $growthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $growthInvested = $growthInvested ?? [5000, 8000, 12000, 15000, 20000, 24000, 30000, 34000, 40000, 46000, 52000, 60000];
    $growthProfit = $growthProfit ?? [200, 600, 1100, 1700, 2500, 3400, 4600, 5900, 7400, 9100, 11000, 13200];

    $portfolioLabels = $portfolioLabels ?? ['Real Estate', 'Agriculture', 'Trade', 'Others'];
    $portfolioValues = $portfolioValues ?? [45, 25, 20, 10];
@endphp

<div class="welcome-banner">
    <div>
        <span class="welcome-label">MEMBER DASHBOARD</span>
        <h1>Hello, {{ auth()->user()->name }}!</h1>
        <p>Welcome to your Dhakaiya Investment account.</p>
    </div>

    <div class="welcome-icon">⌂</div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-top">
            <span>Account Status</span>
            <div class="stat-icon green">✓</div>
        </div>
        <h2>Active</h2>
        <p>Your account is registered</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>My Referrals</span>
            <div class="stat-icon blue">♙</div>
        </div>
        <h2>{{ auth()->user()->referrals()->count() }}</h2>
        <p>People registered through your code</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Membership</span>
            <div class="stat-icon purple">♧</div>
        </div>
        <h2>Member</h2>
        <p>Platform account</p>
    </div>

</div>

{{-- ===== Charts ===== --}}
<div class="chart-grid">

    <div class="dashboard-card chart-card">
        <div class="chart-head">
            <div>
                <h3>Investment Growth</h3>
                <p>Monthly invested amount vs profit</p>
            </div>
            <span class="chart-badge">This Year</span>
        </div>

        <div class="chart-box">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <div class="dashboard-card chart-card">
        <div class="chart-head">
            <div>
                <h3>Portfolio Distribution</h3>
                <p>Where your money is invested</p>
            </div>
        </div>

        <div class="chart-box chart-box-doughnut">
            <canvas id="portfolioChart"></canvas>
        </div>
    </div>

</div>

<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>My Account</h3>
        <p>Your personal account information</p>

        <div class="account-details user-details">
            <div>
                <span>Full Name</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <div>
                <span>Phone Number</span>
                <strong>{{ auth()->user()->phone }}</strong>
            </div>

            <div>
                <span>Email Address</span>
                <strong>{{ auth()->user()->email ?? 'Not provided' }}</strong>
            </div>

            <div>
                <span>Country</span>
                <strong>{{ auth()->user()->country }}</strong>
            </div>

            <div>
                <span>Registration Date</span>
                <strong>{{ auth()->user()->created_at->format('d M Y') }}</strong>
            </div>
        </div>
    </div>

    <div class="dashboard-card referral-card">
        <div class="referral-icon">♧</div>

        <h3>My Referral Code</h3>

        <p>Share your referral code with others.</p>

        <div class="referral-code" id="referralCode">
            {{ auth()->user()->referral_code }}
        </div>

        <button type="button" class="copy-btn" onclick="copyReferral()">
            Copy Referral Code
        </button>

        <span id="copyMessage"></span>
    </div>

</div>

<style>
    .chart-grid {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: 22px;
        margin-bottom: 22px;
    }

    .chart-card {
        padding: 22px;
    }

    .chart-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .chart-head h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #172b25;
    }

    .chart-head p {
        margin: 5px 0 0;
        font-size: 12px;
        color: #87948e;
    }

    .chart-badge {
        background: #e8f7ef;
        color: #087f5b;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .chart-box {
        position: relative;
        height: 300px;
    }

    .chart-box-doughnut {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 991px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }

        .chart-box,
        .chart-box-doughnut {
            height: 260px;
        }
    }
</style>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    function copyReferral() {
        const code = document.getElementById('referralCode').innerText.trim();

        navigator.clipboard.writeText(code).then(() => {
            document.getElementById('copyMessage').innerText = 'Copied successfully!';
        }).catch(() => {
            document.getElementById('copyMessage').innerText = 'Unable to copy. Please copy manually.';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
        Chart.defaults.color = '#87948e';

        /* ===== Chart 1: Investment Growth (Line) ===== */
        const growthCtx = document.getElementById('growthChart').getContext('2d');

        const greenGradient = growthCtx.createLinearGradient(0, 0, 0, 300);
        greenGradient.addColorStop(0, 'rgba(8, 127, 91, 0.25)');
        greenGradient.addColorStop(1, 'rgba(8, 127, 91, 0)');

        const blueGradient = growthCtx.createLinearGradient(0, 0, 0, 300);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json($growthLabels),
                datasets: [
                    {
                        label: 'Invested',
                        data: @json($growthInvested),
                        borderColor: '#087f5b',
                        backgroundColor: greenGradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#087f5b',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2
                    },
                    {
                        label: 'Profit',
                        data: @json($growthProfit),
                        borderColor: '#3b82f6',
                        backgroundColor: blueGradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#3b82f6',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 18 }
                    },
                    tooltip: {
                        backgroundColor: '#172b25',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.dataset.label + ': ৳ ' + ctx.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#eef2f0' },
                        ticks: {
                            callback: function (v) {
                                return v >= 1000 ? (v / 1000) + 'k' : v;
                            }
                        }
                    }
                }
            }
        });

        /* ===== Chart 2: Portfolio Distribution (Doughnut) ===== */
        new Chart(document.getElementById('portfolioChart'), {
            type: 'doughnut',
            data: {
                labels: @json($portfolioLabels),
                datasets: [{
                    data: @json($portfolioValues),
                    backgroundColor: ['#087f5b', '#3b82f6', '#8b5cf6', '#f59e0b'],
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 16 }
                    },
                    tooltip: {
                        backgroundColor: '#172b25',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@include('partials.dashboard-styles')