@extends('layouts.app')

@section('title', 'Position / Rank Settings')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Rewards & Referrals</span><i class="fas fa-chevron-right"></i>
                <strong>Position / Rank Settings</strong>
            </div>
            <h1>Position / Rank Settings</h1>
            <p>Set the investment-based ranks and what users need to reach each one.</p>
        </div>

        <button type="button" class="sa-btn sa-btn-primary" onclick="openCreateRank()">
            <i class="fas fa-plus"></i> Add Rank
        </button>
    </div>

    @include('partials.alerts')

    <div class="sa-card">
        <div class="sa-card-header">
            <div>
                <h3>Rank Levels</h3>
                <p>A user holds the highest rank whose requirements they meet.</p>
            </div>
            <div class="sa-badge">{{ $ranks->total() }} Records</div>
        </div>

        <div class="sa-table-wrapper">
            <table class="sa-table" style="min-width:1000px">
                <thead>
                    <tr>
                        <th>RANK</th>
                        <th>LEVEL</th>
                        <th>MIN. INVESTMENT</th>
                        <th>MIN. REFERRALS</th>
                        <th>MIN. RESELL VOLUME</th>
                        <th>BONUS</th>
                        <th>PROFIT BOOST</th>
                        <th>LOCK</th>
                        <th>STATUS</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranks as $rank)
                        <tr>
                            <td>
                                <div class="sa-name-cell" style="min-width:150px">
                                    <div class="sa-name-icon" style="background:{{ $rank->badge_color }}22;color:{{ $rank->badge_color }}">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <strong style="margin:0">{{ $rank->name }}</strong>
                                </div>
                            </td>
                            <td><span class="sa-order">{{ $rank->level }}</span></td>
                            <td>৳ {{ number_format($rank->min_total_investment, 2) }}</td>
                            <td>{{ number_format($rank->min_active_referrals) }}</td>
                            <td>৳ {{ number_format($rank->min_resell_volume, 2) }}</td>
                            <td>৳ {{ number_format($rank->bonus_amount, 2) }}</td>
                            <td>{{ rtrim(rtrim(number_format($rank->profit_boost_percent, 2), '0'), '.') }}%</td>
                            <td>{{ $rank->lock_period_days }} days</td>
                            <td>
                                @if($rank->status)
                                    <span class="sa-status sa-status-on"><span></span> Active</span>
                                @else
                                    <span class="sa-status sa-status-off"><span></span> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="sa-actions">
                                    <button type="button" class="sa-action-btn sa-action-edit" title="Edit"
                                        data-item="{{ json_encode($rank->only(['id','name','level','min_total_investment','min_active_referrals','min_resell_volume','bonus_amount','profit_boost_percent','lock_period_days','badge_color','status'])) }}"
                                        onclick="editRank(this)">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="sa-action-btn sa-action-delete" title="Delete"
                                        onclick="deleteRank({{ $rank->id }}, @js($rank->name))">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="sa-empty">
                                    <div><i class="fas fa-crown"></i></div>
                                    <h4>No ranks yet</h4>
                                    <p>Add your first rank, for example Bronze at level 1.</p>
                                    <button type="button" class="sa-btn sa-btn-primary" onclick="openCreateRank()">
                                        <i class="fas fa-plus"></i> Add Rank
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ranks->hasPages())
            <div class="sa-pagination">{{ $ranks->links() }}</div>
        @endif
    </div>
</div>


<div class="sa-modal" id="rankModal">
    <div class="sa-modal-box">
        <div class="sa-modal-header">
            <div>
                <h3><i class="fas fa-crown"></i> <span id="rankModalTitle">Add Rank</span></h3>
                <p>Requirements and benefits for this rank.</p>
            </div>
            <button type="button" class="sa-modal-close" onclick="closeModal('rankModal')"><i class="fas fa-times"></i></button>
        </div>

        <form id="rankForm" method="POST" action="{{ route('super-admin.position-settings.store') }}">
            @csrf
            <input type="hidden" name="_method" id="rankMethod" value="POST" disabled>

            <div class="sa-modal-body">
                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Rank name <span>*</span></label>
                        <input type="text" name="name" id="k_name" maxlength="80" placeholder="e.g. Gold" required>
                    </div>
                    <div class="sa-form-group">
                        <label>Level <span>*</span></label>
                        <input type="number" name="level" id="k_level" min="1" placeholder="1" required>
                        <small>Must be unique. Higher level = higher rank.</small>
                    </div>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Min. total investment (৳) <span>*</span></label>
                        <input type="number" name="min_total_investment" id="k_min_total_investment" min="0" step="0.01" value="0" required>
                    </div>
                    <div class="sa-form-group">
                        <label>Min. active referrals <span>*</span></label>
                        <input type="number" name="min_active_referrals" id="k_min_active_referrals" min="0" value="0" required>
                    </div>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Min. resell volume (৳) <span>*</span></label>
                        <input type="number" name="min_resell_volume" id="k_min_resell_volume" min="0" step="0.01" value="0" required>
                    </div>
                    <div class="sa-form-group">
                        <label>Rank bonus (৳) <span>*</span></label>
                        <input type="number" name="bonus_amount" id="k_bonus_amount" min="0" step="0.01" value="0" required>
                        <small>Paid once when the user reaches this rank.</small>
                    </div>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Profit boost (%) <span>*</span></label>
                        <input type="number" name="profit_boost_percent" id="k_profit_boost_percent" min="0" max="100" step="0.01" value="0" required>
                    </div>
                    <div class="sa-form-group">
                        <label>Share lock period (days) <span>*</span></label>
                        <input type="number" name="lock_period_days" id="k_lock_period_days" min="0" value="0" required>
                    </div>
                </div>

                <div class="sa-form-group">
                    <label>Badge color <span>*</span></label>
                    <input type="color" name="badge_color" id="k_badge_color" value="#087f5b" required>
                </div>

                <div class="sa-switch-row" style="margin-bottom:0">
                    <div>
                        <strong>Active</strong>
                        <small>Inactive ranks are skipped when users are ranked.</small>
                    </div>
                    <label class="sa-switch">
                        <input type="checkbox" name="status" id="k_status" value="1" checked>
                        <i></i>
                    </label>
                </div>
            </div>

            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('rankModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> <span id="rankSubmitText">Save rank</span></button>
            </div>
        </form>
    </div>
</div>


<div class="sa-modal" id="deleteRankModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete rank?</h3>
        <p>Are you sure you want to delete <strong id="deleteRankName"></strong>? This action cannot be undone.</p>
        <form id="deleteRankForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('deleteRankModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-danger"><i class="fas fa-trash"></i> Yes, delete</button>
            </div>
        </form>
    </div>
</div>

@endsection


@push('styles')
    @include('partials.ui-styles')
@endpush

@push('scripts')
    @include('partials.ui-scripts')
    <script>
        const rankBase = @js(url('super-admin/position-settings'));
        const rankFields = ['name','level','min_total_investment','min_active_referrals','min_resell_volume','bonus_amount','profit_boost_percent','lock_period_days','badge_color'];

        function openCreateRank() {
            const form = document.getElementById('rankForm');
            form.reset();
            form.action = rankBase;
            document.getElementById('rankMethod').disabled = true;
            document.getElementById('rankModalTitle').textContent = 'Add Rank';
            document.getElementById('rankSubmitText').textContent = 'Save rank';
            openModal('rankModal');
        }

        function editRank(btn) {
            const d = JSON.parse(btn.dataset.item);
            const form = document.getElementById('rankForm');
            form.action = rankBase + '/' + d.id;

            const method = document.getElementById('rankMethod');
            method.value = 'PUT';
            method.disabled = false;

            rankFields.forEach(function (f) { setValue('k_' + f, d[f]); });
            document.getElementById('k_status').checked = !!d.status;

            document.getElementById('rankModalTitle').textContent = 'Edit Rank';
            document.getElementById('rankSubmitText').textContent = 'Update rank';
            openModal('rankModal');
        }

        function deleteRank(id, name) {
            document.getElementById('deleteRankForm').action = rankBase + '/' + id;
            document.getElementById('deleteRankName').textContent = name;
            openModal('deleteRankModal');
        }
    </script>
@endpush