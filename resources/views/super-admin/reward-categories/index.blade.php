@extends('layouts.app')

@section('title', 'Reward Categories')

@section('content')

@php
    $typeLabels = ['cash' => 'Cash Bonus', 'bonus_share' => 'Bonus Share', 'gift' => 'Gift', 'badge' => 'Badge'];
@endphp

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Rewards & Referrals</span><i class="fas fa-chevron-right"></i>
                <strong>Reward Categories</strong>
            </div>
            <h1>Reward Categories</h1>
            <p>Define the rewards users can earn based on their investment.</p>
        </div>

        <button type="button" class="sa-btn sa-btn-primary" onclick="openCreateReward()">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>

    @include('partials.alerts')

    <div class="sa-card">
        <div class="sa-card-header">
            <div>
                <h3>All Reward Categories</h3>
                <p>Users see these on their Rewards and Reward Categories pages.</p>
            </div>
            <div class="sa-badge">{{ $categories->total() }} Records</div>
        </div>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>CATEGORY</th>
                        <th>TYPE</th>
                        <th>REWARD VALUE</th>
                        <th>MIN. INVESTMENT</th>
                        <th>ORDER</th>
                        <th>STATUS</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="sa-name-cell">
                                    <div class="sa-name-icon"><i class="fas fa-gift"></i></div>
                                    <div>
                                        <strong>{{ $category->name }}</strong>
                                        <small>{{ \Illuminate\Support\Str::limit($category->description, 60) ?: 'No description' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="sa-tag">{{ $typeLabels[$category->reward_type] ?? $category->reward_type }}</span></td>
                            <td>
                                @if($category->value_type === 'percent')
                                    {{ rtrim(rtrim(number_format($category->reward_value, 2), '0'), '.') }}%
                                @else
                                    ৳ {{ number_format($category->reward_value, 2) }}
                                @endif
                            </td>
                            <td>৳ {{ number_format($category->min_investment, 2) }}</td>
                            <td><span class="sa-order">{{ $category->sort_order }}</span></td>
                            <td>
                                @if($category->status)
                                    <span class="sa-status sa-status-on"><span></span> Active</span>
                                @else
                                    <span class="sa-status sa-status-off"><span></span> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="sa-actions">
                                    <button type="button" class="sa-action-btn sa-action-edit" title="Edit"
                                        data-item="{{ json_encode($category->only(['id','name','description','reward_type','value_type','reward_value','min_investment','sort_order','status'])) }}"
                                        onclick="editReward(this)">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="sa-action-btn sa-action-delete" title="Delete"
                                        onclick="deleteReward({{ $category->id }}, @js($category->name))">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sa-empty">
                                    <div><i class="fas fa-gift"></i></div>
                                    <h4>No reward categories yet</h4>
                                    <p>Create your first category to start rewarding investors.</p>
                                    <button type="button" class="sa-btn sa-btn-primary" onclick="openCreateReward()">
                                        <i class="fas fa-plus"></i> Add Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="sa-pagination">{{ $categories->links() }}</div>
        @endif
    </div>
</div>


{{-- Create / Edit modal (one modal, two modes) --}}
<div class="sa-modal" id="rewardModal">
    <div class="sa-modal-box">
        <div class="sa-modal-header">
            <div>
                <h3><i class="fas fa-gift"></i> <span id="rewardModalTitle">Add Category</span></h3>
                <p>Set the reward type, value and who qualifies.</p>
            </div>
            <button type="button" class="sa-modal-close" onclick="closeModal('rewardModal')"><i class="fas fa-times"></i></button>
        </div>

        <form id="rewardForm" method="POST" action="{{ route('super-admin.reward-categories.store') }}">
            @csrf
            <input type="hidden" name="_method" id="rewardMethod" value="POST" disabled>

            <div class="sa-modal-body">
                <div class="sa-form-group">
                    <label>Name <span>*</span></label>
                    <input type="text" name="name" id="r_name" maxlength="120" placeholder="e.g. Gold Investor Bonus" required>
                </div>

                <div class="sa-form-group">
                    <label>Description</label>
                    <textarea name="description" id="r_description" rows="3" maxlength="500" placeholder="Short description shown to users"></textarea>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Reward type <span>*</span></label>
                        <select name="reward_type" id="r_reward_type" required>
                            @foreach($typeLabels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sa-form-group">
                        <label>Value type <span>*</span></label>
                        <select name="value_type" id="r_value_type" required>
                            <option value="fixed">Fixed amount (৳)</option>
                            <option value="percent">Percentage (%)</option>
                        </select>
                    </div>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Reward value <span>*</span></label>
                        <input type="number" name="reward_value" id="r_reward_value" min="0" step="0.01" value="0" required>
                    </div>
                    <div class="sa-form-group">
                        <label>Minimum investment (৳) <span>*</span></label>
                        <input type="number" name="min_investment" id="r_min_investment" min="0" step="0.01" value="0" required>
                    </div>
                </div>

                <div class="sa-form-group">
                    <label>Sort order</label>
                    <input type="number" name="sort_order" id="r_sort_order" min="0" value="0">
                    <small>Lower numbers appear first.</small>
                </div>

                <div class="sa-switch-row" style="margin-bottom:0">
                    <div>
                        <strong>Active</strong>
                        <small>Inactive categories are hidden from users.</small>
                    </div>
                    <label class="sa-switch">
                        <input type="checkbox" name="status" id="r_status" value="1" checked>
                        <i></i>
                    </label>
                </div>
            </div>

            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('rewardModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> <span id="rewardSubmitText">Save category</span></button>
            </div>
        </form>
    </div>
</div>


{{-- Delete modal --}}
<div class="sa-modal" id="deleteRewardModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete category?</h3>
        <p>Are you sure you want to delete <strong id="deleteRewardName"></strong>? This action cannot be undone.</p>
        <form id="deleteRewardForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('deleteRewardModal')">Cancel</button>
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
        const rewardBase = @js(url('super-admin/reward-categories'));

        function openCreateReward() {
            const form = document.getElementById('rewardForm');
            form.reset();
            form.action = rewardBase;
            document.getElementById('rewardMethod').disabled = true;
            document.getElementById('rewardModalTitle').textContent = 'Add Category';
            document.getElementById('rewardSubmitText').textContent = 'Save category';
            openModal('rewardModal');
        }

        function editReward(btn) {
            const d = JSON.parse(btn.dataset.item);
            const form = document.getElementById('rewardForm');
            form.action = rewardBase + '/' + d.id;

            const method = document.getElementById('rewardMethod');
            method.value = 'PUT';
            method.disabled = false;

            setValue('r_name', d.name);
            setValue('r_description', d.description);
            setValue('r_reward_type', d.reward_type, 'cash');
            setValue('r_value_type', d.value_type, 'fixed');
            setValue('r_reward_value', d.reward_value, 0);
            setValue('r_min_investment', d.min_investment, 0);
            setValue('r_sort_order', d.sort_order, 0);
            document.getElementById('r_status').checked = !!d.status;

            document.getElementById('rewardModalTitle').textContent = 'Edit Category';
            document.getElementById('rewardSubmitText').textContent = 'Update category';
            openModal('rewardModal');
        }

        function deleteReward(id, name) {
            document.getElementById('deleteRewardForm').action = rewardBase + '/' + id;
            document.getElementById('deleteRewardName').textContent = name;
            openModal('deleteRewardModal');
        }
    </script>
@endpush