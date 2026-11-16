@extends('layouts.app')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')

@section('content')

<style>
.pm{width:100%}
.pm-head{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;flex-wrap:wrap}
.pm-head h2{margin:0 0 6px;font-size:23px;font-weight:700;color:#172033}
.pm-head p{margin:0;color:#8490a3;font-size:13px}
.pm-btn{padding:11px 18px;background:#172d55;color:#fff;border:0;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer}
.pm-btn:hover{background:#244477}
.pm-btn.light{background:#edf0f5;color:#475467}
.pm-alert{padding:13px 16px;border-radius:9px;margin-bottom:16px;font-size:13px}
.pm-alert.ok{color:#137547;background:#eaf8f0;border:1px solid #c8ecd7}
.pm-alert.err{color:#b42318;background:#fff0ef;border:1px solid #ffd5d1}
.pm-alert ul{margin:6px 0 0;padding-left:18px}
.pm-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:20px}
.pm-stat{background:#fff;border:1px solid #edf0f5;border-radius:12px;padding:16px}
.pm-stat span{display:block;color:#8490a3;font-size:11px;margin-bottom:6px}
.pm-stat h3{margin:0;font-size:22px;color:#172033}
.pm-card{background:#fff;border:1px solid #edf0f5;border-radius:12px;overflow:hidden}
.pm-filter{display:flex;gap:8px;flex-wrap:wrap;padding:16px 20px;border-bottom:1px solid #edf0f5}
.pm-filter input,.pm-filter select{padding:9px 11px;border:1px solid #dce2ea;border-radius:8px;font-size:12px;background:#fff}
.pm-scroll{overflow-x:auto}
.pm-table{width:100%;min-width:1000px;border-collapse:collapse}
.pm-table th{padding:12px 16px;background:#f8f9fc;color:#8490a3;font-size:10px;font-weight:700;letter-spacing:.5px;text-align:left;text-transform:uppercase}
.pm-table td{padding:13px 16px;border-top:1px solid #f0f2f6;font-size:12px;color:#344054;vertical-align:middle}
.pm-name{display:flex;align-items:center;gap:11px}
.pm-ico{width:38px;height:38px;border-radius:9px;background:#edf2fa;display:flex;align-items:center;justify-content:center;font-size:18px}
.pm-name small{display:block;color:#929bad;font-size:11px;margin-top:3px}
.pm-tag{display:inline-block;background:#f0f4fb;color:#345b96;padding:3px 8px;border-radius:12px;font-size:10px;font-weight:600;margin:1px}
.pm-on{color:#16814f;font-weight:600}.pm-off{color:#a0a8b6}
.pm-status{border:0;padding:6px 11px;border-radius:20px;font-size:11px;font-weight:600;cursor:pointer}
.pm-status.active{background:#e9f8f0;color:#16814f}.pm-status.inactive{background:#fff1ed;color:#c65b3c}
.pm-act{display:flex;gap:6px}.pm-act form{margin:0}
.pm-ib{width:32px;height:32px;border:0;border-radius:7px;cursor:pointer;font-size:13px}
.pm-ib.edit{background:#edf3ff;color:#3568c8}.pm-ib.del{background:#fff0ef;color:#d34c43}
.pm-pg{padding:15px 20px;border-top:1px solid #edf0f5}
.pm-empty{text-align:center;padding:40px;color:#8994a7}
/* modal */
.pm-ov{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:99999;display:none;align-items:center;justify-content:center;padding:18px;overflow-y:auto}
.pm-ov.show{display:flex}
.pm-modal{width:100%;max-width:680px;background:#fff;border-radius:14px;overflow:hidden}
.pm-mh{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border-bottom:1px solid #edf0f5}
.pm-mh h3{margin:0;font-size:18px;color:#172033}
.pm-x{width:34px;height:34px;border:0;border-radius:8px;background:#f1f4f8;font-size:22px;cursor:pointer}
.pm-mb{padding:20px 22px;max-height:68vh;overflow-y:auto}
.pm-g{margin-bottom:16px}
.pm-g label{display:block;margin-bottom:7px;font-size:12px;font-weight:700;color:#344054}
.pm-g input[type=text],.pm-g input[type=number],.pm-g select,.pm-g textarea{width:100%;padding:10px 12px;border:1px solid #dce2ea;border-radius:8px;font-size:13px;font-family:inherit;box-sizing:border-box}
.pm-g small{display:block;margin-top:5px;color:#929bad;font-size:11px}
.pm-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.pm-checks{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:4px 10px;max-height:150px;overflow-y:auto;border:1px solid #dce2ea;border-radius:8px;padding:10px}
.pm-checks label{display:flex;align-items:center;gap:6px;margin:0;font-weight:400;font-size:12px}
.pm-inline{display:flex;gap:22px;flex-wrap:wrap}
.pm-inline label{display:flex;align-items:center;gap:7px;font-weight:600}
.pm-mf{display:flex;justify-content:flex-end;gap:10px;padding:15px 22px;background:#f9fafc;border-top:1px solid #edf0f5}
@media(max-width:600px){.pm-row{grid-template-columns:1fr}}
</style>

<div class="pm">

    <div class="pm-head">
        <div>
            <h2>Payment Methods</h2>
            <p>Add payment methods by country and currency. Active methods appear in the user panel automatically.</p>
        </div>
        <button type="button" class="pm-btn" onclick="pmCreate()">+ Add Payment Method</button>
    </div>

    @if(session('success'))<div class="pm-alert ok">✓ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="pm-alert err">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="pm-alert err"><strong>Please fix the following:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="pm-stats">
        <div class="pm-stat"><span>Total Methods</span><h3>{{ $stats['total'] }}</h3></div>
        <div class="pm-stat"><span>Active</span><h3>{{ $stats['active'] }}</h3></div>
        <div class="pm-stat"><span>Inactive</span><h3>{{ $stats['inactive'] }}</h3></div>
        <div class="pm-stat"><span>User Accounts Added</span><h3>{{ number_format($stats['accounts']) }}</h3></div>
    </div>

    <div class="pm-card">
        <form method="GET" class="pm-filter">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / code">
            <select name="type">
                <option value="">All Types</option>
                @foreach($types as $k => $label)
                    <option value="{{ $k }}" @selected(request('type') === $k)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">All Status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <button class="pm-btn" style="padding:9px 15px;font-size:12px">Filter</button>
            <a href="{{ route('super-admin.payment-methods.index') }}" class="pm-btn light" style="padding:9px 15px;font-size:12px;text-decoration:none">Reset</a>
        </form>

        <div class="pm-scroll">
            <table class="pm-table">
                <thead>
                <tr>
                    <th>Method</th><th>Type</th><th>Countries</th><th>Currencies</th>
                    <th>Deposit</th><th>Withdraw</th><th>Fee</th><th>Accounts</th><th>Status</th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($methods as $m)
                    @php
                        $payload = [
                            'name' => $m->name, 'type' => $m->type, 'icon' => $m->icon,
                            'description' => $m->description,
                            'countries' => $m->countries ?? [], 'currencies' => $m->supported_currencies ?? [],
                            'deposit_enabled' => $m->deposit_enabled, 'withdraw_enabled' => $m->withdraw_enabled,
                            'deposit_account' => $m->deposit_account, 'deposit_instructions' => $m->deposit_instructions,
                            'fee' => $m->withdraw_fee_percent, 'status' => $m->status, 'sort' => $m->sort_order,
                            'url' => route('super-admin.payment-methods.update', $m),
                        ];
                    @endphp
                    <tr>
                        <td>
                            <div class="pm-name">
                                <div class="pm-ico">{{ $m->icon ?: '💳' }}</div>
                                <div><strong>{{ $m->name }}</strong><small>{{ $m->code }}</small></div>
                            </div>
                        </td>
                        <td>{{ $types[$m->type] ?? $m->type }}</td>
                        <td>
                            @if($m->countries)
                                @foreach(array_slice($m->countries, 0, 4) as $c)<span class="pm-tag">{{ $c }}</span>@endforeach
                                @if(count($m->countries) > 4)<span class="pm-tag">+{{ count($m->countries) - 4 }}</span>@endif
                            @else <span class="pm-tag">All countries</span> @endif
                        </td>
                        <td>
                            @if($m->supported_currencies)
                                @foreach(array_slice($m->supported_currencies, 0, 4) as $c)<span class="pm-tag">{{ $c }}</span>@endforeach
                                @if(count($m->supported_currencies) > 4)<span class="pm-tag">+{{ count($m->supported_currencies) - 4 }}</span>@endif
                            @else <span class="pm-tag">All currencies</span> @endif
                        </td>
                        <td class="{{ $m->deposit_enabled ? 'pm-on' : 'pm-off' }}">{{ $m->deposit_enabled ? 'On' : 'Off' }}</td>
                        <td class="{{ $m->withdraw_enabled ? 'pm-on' : 'pm-off' }}">{{ $m->withdraw_enabled ? 'On' : 'Off' }}</td>
                        <td>{{ rtrim(rtrim((string) $m->withdraw_fee_percent, '0'), '.') ?: '0' }}%</td>
                        <td>{{ number_format($m->accounts_count) }}</td>
                        <td>
                            <form method="POST" action="{{ route('super-admin.payment-methods.toggle', $m) }}">
                                @csrf @method('PATCH')
                                <button class="pm-status {{ $m->status }}" title="Click to toggle">{{ ucfirst($m->status) }}</button>
                            </form>
                        </td>
                        <td>
                            <div class="pm-act">
                                <button type="button" class="pm-ib edit" data-m="{{ json_encode($payload) }}" onclick="pmEdit(this)"><i class="fas fa-edit"></i></button>
                                <form method="POST" action="{{ route('super-admin.payment-methods.destroy', $m) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($m->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="pm-ib del"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="pm-empty">No payment methods yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($methods->hasPages())<div class="pm-pg">{{ $methods->links() }}</div>@endif
    </div>
</div>

{{-- ============ MODAL ============ --}}
<div class="pm-ov" id="pmModal">
    <div class="pm-modal">
        <div class="pm-mh">
            <h3 id="pmTitle">Add Payment Method</h3>
            <button type="button" class="pm-x" onclick="pmClose()">&times;</button>
        </div>

        <form method="POST" id="pmForm" action="{{ route('super-admin.payment-methods.store') }}">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="pmSpoof" disabled>

            <div class="pm-mb">
                <div class="pm-row">
                    <div class="pm-g"><label>Name *</label><input type="text" name="name" id="f_name" maxlength="100" required></div>
                    <div class="pm-g"><label>Type *</label>
                        <select name="type" id="f_type">
                            @foreach($types as $k => $label)<option value="{{ $k }}">{{ $label }}</option>@endforeach
                        </select>
                        <small>Type decides what the user must enter (number / bank details / wallet address).</small>
                    </div>
                </div>

                <div class="pm-row">
                    <div class="pm-g"><label>Icon (emoji)</label><input type="text" name="icon" id="f_icon" maxlength="10" placeholder="📱"></div>
                    <div class="pm-g"><label>Sort Order</label><input type="number" name="sort_order" id="f_sort" min="0" value="0"></div>
                </div>

                <div class="pm-g"><label>Description</label><textarea name="description" id="f_desc" rows="2" maxlength="500"></textarea></div>

                <div class="pm-g">
                    <label>Available in Countries</label>
                    <div class="pm-checks" id="f_countries">
                        @foreach($countryOptions as $code => $label)
                            <label><input type="checkbox" name="countries[]" value="{{ $code }}"> {{ $label }}</label>
                        @endforeach
                    </div>
                    <small>Leave all unchecked = available in every country.</small>
                </div>

                <div class="pm-g">
                    <label>Supported Currencies</label>
                    <div class="pm-checks" id="f_currencies">
                        @foreach($currencyOptions as $code)
                            <label><input type="checkbox" name="supported_currencies[]" value="{{ $code }}"> {{ $code }}</label>
                        @endforeach
                    </div>
                    <small>Leave all unchecked = every currency.</small>
                </div>

                <div class="pm-g pm-inline">
                    <label><input type="checkbox" name="deposit_enabled" value="1" id="f_dep" checked> Allow Deposit</label>
                    <label><input type="checkbox" name="withdraw_enabled" value="1" id="f_wd" checked> Allow Withdraw</label>
                </div>

                <div class="pm-g">
                    <label>Deposit Account (where users send money)</label>
                    <textarea name="deposit_account" id="f_acc" rows="3" maxlength="1000" placeholder="e.g. 01XXXXXXXXX (Personal) / Bank: ..., A/C: ..., SWIFT: ..."></textarea>
                </div>

                <div class="pm-g">
                    <label>Deposit Instructions</label>
                    <textarea name="deposit_instructions" id="f_ins" rows="3" maxlength="2000"></textarea>
                </div>

                <div class="pm-row">
                    <div class="pm-g"><label>Withdraw Fee (%)</label><input type="number" name="withdraw_fee_percent" id="f_fee" step="0.01" min="0" max="100" value="0"></div>
                    <div class="pm-g"><label>Status</label>
                        <select name="status" id="f_status"><option value="active">Active</option><option value="inactive">Inactive</option></select>
                    </div>
                </div>
            </div>

            <div class="pm-mf">
                <button type="button" class="pm-btn light" onclick="pmClose()">Cancel</button>
                <button type="submit" class="pm-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
const pmStoreUrl = @json(route('super-admin.payment-methods.store'));

function pmOpen(){document.getElementById('pmModal').classList.add('show');document.body.style.overflow='hidden';}
function pmClose(){document.getElementById('pmModal').classList.remove('show');document.body.style.overflow='';}

function pmChecks(boxId, values){
    document.querySelectorAll('#'+boxId+' input[type=checkbox]').forEach(function(c){
        c.checked = values.indexOf(c.value) !== -1;
    });
}

function pmCreate(){
    document.getElementById('pmForm').reset();
    document.getElementById('pmForm').action = pmStoreUrl;
    document.getElementById('pmSpoof').disabled = true;
    document.getElementById('pmTitle').textContent = 'Add Payment Method';
    pmChecks('f_countries', []); pmChecks('f_currencies', []);
    document.getElementById('f_dep').checked = true;
    document.getElementById('f_wd').checked = true;
    pmOpen();
}

function pmEdit(btn){
    const m = JSON.parse(btn.dataset.m);
    document.getElementById('pmForm').action = m.url;
    document.getElementById('pmSpoof').disabled = false;
    document.getElementById('pmTitle').textContent = 'Edit Payment Method';
    document.getElementById('f_name').value = m.name || '';
    document.getElementById('f_type').value = m.type;
    document.getElementById('f_icon').value = m.icon || '';
    document.getElementById('f_sort').value = m.sort || 0;
    document.getElementById('f_desc').value = m.description || '';
    document.getElementById('f_acc').value = m.deposit_account || '';
    document.getElementById('f_ins').value = m.deposit_instructions || '';
    document.getElementById('f_fee').value = m.fee || 0;
    document.getElementById('f_status').value = m.status;
    document.getElementById('f_dep').checked = !!m.deposit_enabled;
    document.getElementById('f_wd').checked = !!m.withdraw_enabled;
    pmChecks('f_countries', m.countries || []);
    pmChecks('f_currencies', m.currencies || []);
    pmOpen();
}

document.getElementById('pmModal').addEventListener('click', function(e){ if(e.target === this) pmClose(); });
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') pmClose(); });
</script>

@endsection