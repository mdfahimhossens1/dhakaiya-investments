@extends('layouts.app')

@section('title', 'Product Catalogue Reports')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Reports & Monitoring</span><i class="fas fa-chevron-right"></i>
                <strong>Product Catalogue Reports</strong>
            </div>
            <h1>Product Catalogue Reports</h1>
            <p>How your share products are spread across categories, companies and status.</p>
        </div>
    </div>

    @include('partials.alerts')

@if(!$available)
    <div class="sa-card">
        <div class="sa-empty">
            <div><i class="fas fa-layer-group"></i></div>
            <h4>No product data yet</h4>
            <p>The <code>projects</code> table was not found. Add share products first, then this report fills in.</p>
        </div>
    </div>
@else

    @php
        $maxCat = max(1, (int) $categoryBreakdown->max('total'));
        $maxCo = max(1, (int) $companyBreakdown->max('total'));
        $soldPct = ($stats['shares'] && !is_null($stats['sold'])) ? round($stats['sold'] / $stats['shares'] * 100, 1) : null;
    @endphp

    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue"><i class="fas fa-layer-group"></i></div>
            <div><span>Total products</span><h3>{{ number_format($stats['total']) }}</h3></div>
        </div>
        <div class="sa-stat-card {{ is_null($stats['shares']) ? 'is-missing' : '' }}">
            <div class="sa-stat-icon sa-ic-green"><i class="fas fa-cubes"></i></div>
            <div><span>Total shares</span><h3>{{ is_null($stats['shares']) ? 'Not available' : number_format($stats['shares']) }}</h3></div>
        </div>
        <div class="sa-stat-card {{ is_null($stats['sold']) ? 'is-missing' : '' }}">
            <div class="sa-stat-icon sa-ic-orange"><i class="fas fa-shopping-cart"></i></div>
            <div>
                <span>Shares sold</span>
                <h3>{{ is_null($stats['sold']) ? 'Not available' : number_format($stats['sold']) }}</h3>
                @if(!is_null($soldPct))<small>{{ $soldPct }}% of all shares</small>@endif
            </div>
        </div>
        <div class="sa-stat-card {{ $has['status'] ? '' : 'is-missing' }}">
            <div class="sa-stat-icon sa-ic-purple"><i class="fas fa-flag"></i></div>
            <div>
                <span>Status groups</span>
                <h3>{{ $has['status'] ? $statusBreakdown->count() : 'Not available' }}</h3>
                @if($has['status'])
                    <small>{{ $statusBreakdown->map(fn($t, $s) => ucfirst((string) $s) . ' ' . $t)->implode(' · ') }}</small>
                @endif
            </div>
        </div>
    </div>

    <div class="sa-grid-2">
        <div class="sa-card">
            <div class="sa-card-header"><div><h3>Products by category</h3><p>Where your catalogue is concentrated.</p></div></div>
            <div class="sa-card-body">
                @forelse($categoryBreakdown as $row)
                    <div class="sa-bar-row">
                        <div class="sa-bar-label" title="{{ $row->label }}">{{ $row->label }}</div>
                        <div class="sa-bar-track"><div class="sa-bar-fill" style="width:{{ $row->total / $maxCat * 100 }}%"></div></div>
                        <div class="sa-bar-val">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <div class="sa-muted-note">Products have no category link yet.</div>
                @endforelse
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-header"><div><h3>Products by company</h3><p>Top 8 companies.</p></div></div>
            <div class="sa-card-body">
                @forelse($companyBreakdown as $row)
                    <div class="sa-bar-row">
                        <div class="sa-bar-label" title="{{ $row->label }}">{{ $row->label }}</div>
                        <div class="sa-bar-track"><div class="sa-bar-fill" style="width:{{ $row->total / $maxCo * 100 }}%"></div></div>
                        <div class="sa-bar-val">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <div class="sa-muted-note">Products have no company link yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div><h3>All products</h3><p>Filter to narrow the list. The charts above always show the full catalogue.</p></div>
            <div class="sa-badge">{{ $rows->total() }} Records</div>
        </div>

        <form method="GET" action="{{ route('super-admin.reports.product-catalogue') }}" class="sa-filter-form">
            <div class="sa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search product name..." value="{{ request('search') }}">
            </div>

            @if($has['category'])
                <select name="category" class="sa-filter-select">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            @endif

            @if($has['company'])
                <select name="company" class="sa-filter-select">
                    <option value="">All companies</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ request('company') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            @endif

            @if($has['status'])
                <select name="status" class="sa-filter-select">
                    <option value="">All status</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ (string) request('status') === (string) $s ? 'selected' : '' }}>{{ ucfirst((string) $s) }}</option>
                    @endforeach
                </select>
            @endif

            <button type="submit" class="sa-btn sa-btn-filter"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('super-admin.reports.product-catalogue') }}" class="sa-btn sa-btn-reset"><i class="fas fa-redo"></i> Reset</a>
        </form>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>PRODUCT</th>
                        @if($has['category'])<th>CATEGORY</th>@endif
                        @if($has['company'])<th>COMPANY</th>@endif
                        @if($has['price'])<th>SHARE PRICE</th>@endif
                        @if($has['shares'])<th>TOTAL SHARES</th>@endif
                        @if($has['sold'])<th>SOLD</th>@endif
                        @if($has['status'])<th>STATUS</th>@endif
                        <th>ADDED</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>
                                <div class="sa-name-cell">
                                    <div class="sa-name-icon"><i class="fas fa-layer-group"></i></div>
                                    <strong style="margin:0">{{ $row->name }}</strong>
                                </div>
                            </td>
                            @if($has['category'])<td>@if($row->category_name)<span class="sa-tag">{{ $row->category_name }}</span>@else<span style="color:#a3abad">Uncategorized</span>@endif</td>@endif
                            @if($has['company'])<td>{{ $row->company_name ?? '-' }}</td>@endif
                            @if($has['price'])<td>৳ {{ number_format($row->price, 2) }}</td>@endif
                            @if($has['shares'])<td>{{ number_format($row->total_shares) }}</td>@endif
                            @if($has['sold'])
                                <td>
                                    {{ number_format($row->sold_shares) }}
                                    @if($has['shares'] && $row->total_shares > 0)
                                        <small style="color:#9aa4a8">({{ round($row->sold_shares / $row->total_shares * 100) }}%)</small>
                                    @endif
                                </td>
                            @endif
                            @if($has['status'])<td><span class="sa-tag">{{ ucfirst((string) $row->status) }}</span></td>@endif
                          <td style="white-space:nowrap">
                            @if($row->created_at)
                                {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}
                            @else
                                <span style="color:#a3abad">Not available</span>
                            @endif
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="sa-empty">
                                    <div><i class="fas fa-layer-group"></i></div>
                                    <h4>No products found</h4>
                                    <p>Nothing matches these filters. Reset them to see the full catalogue.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rows->hasPages())
            <div class="sa-pagination">{{ $rows->links() }}</div>
        @endif
    </div>
@endif
</div>

@endsection

@push('styles')
    @include('partials.ui-styles')
    @include('super-admin.partials.report-styles')
@endpush