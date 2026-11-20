@php
    $isEdit = isset($agent) && $agent;
    $methodTypes = \App\Models\AgentPaymentMethod::METHODS;

    $pms = old('payment_methods', $isEdit
        ? $agent->paymentMethods->map(fn ($p) => [
            'id'             => $p->id,
            'method'         => $p->method,
            'account_name'   => $p->account_name,
            'account_number' => $p->account_number,
            'bank_name'      => $p->bank_name,
        ])->values()->all()
        : []);

    if (empty($pms)) {
        $pms = [[]];
    }
@endphp

<style>
    .pm-row { display: grid; grid-template-columns: 140px 1fr 1fr 1fr auto; gap: 10px; align-items: end; margin-bottom: 10px; }
    .pm-row .ag-field { margin: 0; }
    @media (max-width: 900px) { .pm-row { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 550px) { .pm-row { grid-template-columns: 1fr; } }
</style>

<div class="ag-row">
    <div class="ag-field"><label>Full name *</label><input type="text" name="name" value="{{ old('name', $agent->user->name ?? '') }}" required></div>
    <div class="ag-field"><label>Email *</label><input type="email" name="email" value="{{ old('email', $agent->user->email ?? '') }}" required></div>
</div>
<div class="ag-row">
    <div class="ag-field"><label>Phone *</label><input type="text" name="phone" value="{{ old('phone', $agent->phone ?? '') }}" required></div>
    <div class="ag-field"><label>WhatsApp</label><input type="text" name="whatsapp" value="{{ old('whatsapp', $agent->whatsapp ?? '') }}"></div>
</div>
<div class="ag-row">
    <div class="ag-field">
        <label>Password {{ $isEdit ? '' : '*' }}</label>
        <input type="password" name="password" {{ $isEdit ? '' : 'required' }} autocomplete="new-password">
        @if($isEdit)<small>Leave blank to keep the current password.</small>@endif
    </div>
    <div class="ag-field"><label>Confirm password</label><input type="password" name="password_confirmation" autocomplete="new-password"></div>
</div>
<div class="ag-field"><label>Short bio</label><textarea name="bio" maxlength="1000">{{ old('bio', $agent->bio ?? '') }}</textarea></div>

<div class="ag-field">
    <label>Assigned areas * (country and district)</label>
    @forelse($countries as $country)
        <div class="ag-geo">
            <h4>{{ $country->name }} @unless($country->is_active)<span class="ag-badge gray">inactive</span>@endunless</h4>
            <div class="ag-geo-list">
                @forelse($country->districts as $d)
                    <label class="ag-check">
                        <input type="checkbox" name="district_ids[]" value="{{ $d->id }}" @checked(in_array($d->id, $selected))>
                        {{ $d->name }}
                    </label>
                @empty
                    <small style="color:var(--muted)">No districts yet.</small>
                @endforelse
            </div>
        </div>
    @empty
        <div class="ag-notice warn">
            No countries/districts yet. <a href="{{ route('super-admin.agent-locations.index') }}">Add them first</a>.
        </div>
    @endforelse
</div>

{{-- Agent-er personal account: shudhu research fee-r jonno --}}
<div class="ag-field">
    <label>Payment methods (research fee only)</label>
    <small style="display:block;margin-bottom:10px;">
        Agent er research service fee nebar account. Eta wallet deposit na. User ke shudhu quote accept korar por dekhano hobe.
    </small>

    <div id="pmList">
        @foreach($pms as $i => $pm)
            <div class="pm-row">
                <input type="hidden" name="payment_methods[{{ $i }}][id]" value="{{ $pm['id'] ?? '' }}">
                <div class="ag-field">
                    <select name="payment_methods[{{ $i }}][method]">
                        @foreach($methodTypes as $k => $v)
                            <option value="{{ $k }}" @selected(($pm['method'] ?? 'bkash') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ag-field"><input type="text" name="payment_methods[{{ $i }}][account_name]" value="{{ $pm['account_name'] ?? '' }}" placeholder="Account holder name"></div>
                <div class="ag-field"><input type="text" name="payment_methods[{{ $i }}][account_number]" value="{{ $pm['account_number'] ?? '' }}" placeholder="Number / Account no."></div>
                <div class="ag-field"><input type="text" name="payment_methods[{{ $i }}][bank_name]" value="{{ $pm['bank_name'] ?? '' }}" placeholder="Bank name (bank only)"></div>
                <button type="button" class="ag-btn danger sm pm-remove">&times;</button>
            </div>
        @endforeach
    </div>

    <button type="button" class="ag-btn ghost sm" id="pmAdd"><i class="fa-solid fa-plus"></i> Add payment method</button>
</div>

<template id="pmTpl">
    <div class="pm-row">
        <input type="hidden" name="payment_methods[__i__][id]" value="">
        <div class="ag-field">
            <select name="payment_methods[__i__][method]">
                @foreach($methodTypes as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
            </select>
        </div>
        <div class="ag-field"><input type="text" name="payment_methods[__i__][account_name]" placeholder="Account holder name"></div>
        <div class="ag-field"><input type="text" name="payment_methods[__i__][account_number]" placeholder="Number / Account no."></div>
        <div class="ag-field"><input type="text" name="payment_methods[__i__][bank_name]" placeholder="Bank name (bank only)"></div>
        <button type="button" class="ag-btn danger sm pm-remove">&times;</button>
    </div>
</template>

@unless($isEdit)
    <div class="ag-field">
        <input type="hidden" name="is_verified" value="0">
        <label class="ag-check">
            <input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', 1))>
            Verified (user panel e sathe sathe dekhabe)
        </label>
    </div>
@endunless

<script>
    (function () {
        const list = document.getElementById('pmList');
        const tpl = document.getElementById('pmTpl').innerHTML;
        let idx = list.querySelectorAll('.pm-row').length;

        document.getElementById('pmAdd').addEventListener('click', function () {
            if (list.querySelectorAll('.pm-row').length >= 5) return;
            list.insertAdjacentHTML('beforeend', tpl.replaceAll('__i__', idx++));
        });

        list.addEventListener('click', function (e) {
            const btn = e.target.closest('.pm-remove');
            if (!btn) return;

            const rows = list.querySelectorAll('.pm-row');
            if (rows.length > 1) {
                btn.closest('.pm-row').remove();
            } else {
                rows[0].querySelectorAll('input[type=text]').forEach(i => i.value = '');
                rows[0].querySelector('input[type=hidden]').value = '';
            }
        });
    })();
</script>