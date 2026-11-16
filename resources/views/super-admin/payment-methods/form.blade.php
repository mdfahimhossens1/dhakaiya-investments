@extends('layouts.admin')

@section('title', $method->exists ? 'Edit Payment Method' : 'Create Payment Method')

@section('content')
<div class="admin-page">

    <div class="page-head">
        <div>
            <h1>{{ $method->exists ? 'Edit Payment Method' : 'Create Payment Method' }}</h1>
            <p>Configure how users can deposit and withdraw using this method.</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" class="btn-link">← Back to list</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $method->exists
              ? route('admin.payment-methods.update', $method)
              : route('admin.payment-methods.store') }}">
        @csrf
        @if($method->exists) @method('PUT') @endif

        <div class="card pad">

            {{-- ==== BASIC ==== --}}
            <h2 class="section-title">Basic Info</h2>

            <div class="grid-2">
                <div class="form-group">
                    <label>Method Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $method->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Slug <span class="req">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $method->slug) }}"
                           placeholder="bkash" required>
                    <small>Unique identifier — lowercase letters, numbers, dash.</small>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $method->icon) }}"
                           placeholder="💳" maxlength="10">
                </div>
                <div class="form-group">
                    <label>Type <span class="req">*</span></label>
                    <select name="type" required>
                        @foreach(['mobile','bank','wallet','card','crypto','other'] as $t)
                            <option value="{{ $t }}"
                                @selected(old('type', $method->type) === $t)>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description"
                       value="{{ old('description', $method->description) }}"
                       placeholder="Short description shown to users">
            </div>

            {{-- ==== CURRENCIES ==== --}}
            <h2 class="section-title">Supported Currencies</h2>

            <div class="form-group">
                <label>Currency Codes <span class="req">*</span></label>
                <input type="text" name="supported_currencies_display"
                       id="currency-input"
                       value="{{ implode(',', old('supported_currencies', $method->supported_currencies ?? ['BDT'])) }}">
                <small>
                    কমা দিয়ে আলাদা করুন। সব currency-এর জন্য <code>*</code> লিখুন।
                    উদাহরণ: <code>BDT</code>, <code>BDT,USD,EUR</code>, <code>*</code>
                </small>
            </div>

            <div class="chips-preview" id="currency-preview"></div>

            {{-- ==== FIELDS ==== --}}
            <h2 class="section-title">Account Fields</h2>
            <p class="hint">
                ইউজার এই method-এ অ্যাকাউন্ট যোগ করার সময় কোন কোন ফিল্ড পূরণ করবে।
            </p>

            <div id="fields-container">
                @php
                    $fields = old('fields', $method->fields ?? []);
                @endphp
                @foreach($fields as $i => $f)
                    <div class="field-row" data-index="{{ $i }}">
                        <input type="text" name="fields[{{ $i }}][key]"
                               value="{{ $f['key'] ?? '' }}" placeholder="key" class="w-key">
                        <input type="text" name="fields[{{ $i }}][label]"
                               value="{{ $f['label'] ?? '' }}" placeholder="Label" class="w-label">
                        <select name="fields[{{ $i }}][type]" class="w-type">
                            @foreach(['text','email','number','select','textarea'] as $t)
                                <option value="{{ $t }}" @selected(($f['type'] ?? '') === $t)>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="fields[{{ $i }}][options]"
                               value="{{ is_array($f['options'] ?? null) ? implode(',', $f['options']) : ($f['options'] ?? '') }}"
                               placeholder="options (comma)" class="w-options">
                        <label class="checkbox">
                            <input type="hidden" name="fields[{{ $i }}][required]" value="0">
                            <input type="checkbox" name="fields[{{ $i }}][required]" value="1"
                                   @checked(!empty($f['required']))>
                            Required
                        </label>
                        <button type="button" class="btn-icon danger remove-field">✕</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-field" class="btn-outline">+ Add Field</button>

            {{-- ==== STATUS ==== --}}
            <h2 class="section-title">Display</h2>

            <div class="grid-2">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" min="0"
                           value="{{ old('sort_order', $method->sort_order ?? 0) }}">
                </div>
                <div class="form-group checkbox-group">
                    <input type="hidden" name="is_active" value="0">
                    <label>
                        <input type="checkbox" name="is_active" value="1"
                               @checked(old('is_active', $method->is_active ?? true))>
                        Active (visible to users)
                    </label>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.payment-methods.index') }}" class="btn-link">Cancel</a>
            <button type="submit" class="btn-primary">
                {{ $method->exists ? 'Update Method' : 'Create Method' }}
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ===== Currency input → hidden array + chips =====
    const input   = document.getElementById('currency-input');
    const preview = document.getElementById('currency-preview');
    const form    = input.closest('form');

    // Remove previous hidden inputs (on edit re-render)
    form.querySelectorAll('input[name="supported_currencies[]"]').forEach(e => e.remove());

    function renderCurrencies() {
        const raw = input.value.split(',').map(s => s.trim().toUpperCase()).filter(Boolean);
        const uniq = [...new Set(raw)];

        // Clean old hidden
        form.querySelectorAll('input[name="supported_currencies[]"]').forEach(e => e.remove());

        // Chips
        preview.innerHTML = uniq.map(c =>
            `<span class="chip">${c === '*' ? 'ALL' : c}</span>`
        ).join('');

        // Hidden inputs
        uniq.forEach(c => {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'supported_currencies[]';
            h.value = c;
            preview.appendChild(h);
        });
    }

    input.addEventListener('input', renderCurrencies);
    renderCurrencies();

    // ===== Dynamic fields =====
    const container = document.getElementById('fields-container');
    const addBtn    = document.getElementById('add-field');
    let index = container.querySelectorAll('.field-row').length;

    function newFieldRow(i) {
        return `
        <div class="field-row" data-index="${i}">
            <input type="text" name="fields[${i}][key]" placeholder="key" class="w-key">
            <input type="text" name="fields[${i}][label]" placeholder="Label" class="w-label">
            <select name="fields[${i}][type]" class="w-type">
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="number">Number</option>
                <option value="select">Select</option>
                <option value="textarea">Textarea</option>
            </select>
            <input type="text" name="fields[${i}][options]" placeholder="options (comma)" class="w-options">
            <label class="checkbox">
                <input type="hidden" name="fields[${i}][required]" value="0">
                <input type="checkbox" name="fields[${i}][required]" value="1">
                Required
            </label>
            <button type="button" class="btn-icon danger remove-field">✕</button>
        </div>`;
    }

    addBtn.addEventListener('click', () => {
        container.insertAdjacentHTML('beforeend', newFieldRow(index++));
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-field')) {
            e.target.closest('.field-row').remove();
        }
    });
})();
</script>
@endpush

@push('styles')
<style>
.admin-page { max-width: 900px; margin: 0 auto; }
.page-head {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 18px; flex-wrap: wrap; gap: 10px;
}
.page-head h1 { font-size: 22px; color: #1e293b; margin: 0 0 4px; }
.page-head p { color: #84919f; font-size: 13px; margin: 0; }
.btn-link { color: #087f5b; text-decoration: none; font-size: 13px; font-weight: 600; }
.card { background: #fff; border: 1px solid #e8edf0; border-radius: 12px; padding: 24px; }
.card.pad { padding: 24px; }
.section-title {
    font-size: 13px; text-transform: uppercase; letter-spacing: .5px;
    color: #64748b; margin: 22px 0 12px; padding-top: 16px;
    border-top: 1px solid #eef2f4;
}
.section-title:first-child { margin-top: 0; padding-top: 0; border: 0; }
.hint { font-size: 12px; color: #94a3b8; margin: -6px 0 12px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group { margin-bottom: 14px; }
.form-group label {
    display: block; font-size: 12px; font-weight: 600;
    color: #334155; margin-bottom: 6px;
}
.form-group input[type=text], .form-group input[type=number],
.form-group input[type=email], .form-group select, .form-group textarea {
    width: 100%; padding: 9px 12px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 13px; color: #1e293b;
    background: #fff; outline: none; transition: .15s;
}
.form-group input:focus, .form-group select:focus { border-color: #087f5b; box-shadow: 0 0 0 3px rgba(8,127,91,.08); }
.form-group small { display: block; font-size: 11px; color: #94a3b8; margin-top: 5px; }
.req { color: #dc2626; }
.chips-preview { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 6px; }
.chip {
    background: #effaf5; color: #087f5b; padding: 3px 9px;
    border-radius: 6px; font-size: 11px; font-weight: 700;
}
.field-row {
    display: grid;
    grid-template-columns: 1fr 1.4fr 120px 1.4fr 90px 40px;
    gap: 8px; align-items: center; margin-bottom: 8px;
}
.field-row input, .field-row select {
    padding: 8px 10px; border: 1px solid #e2e8f0;
    border-radius: 6px; font-size: 12px;
}
.checkbox {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: #475569;
}
.checkbox-group label { display: flex; align-items: center; gap: 8px; margin-top: 22px; }
.btn-primary {
    background: #087f5b; color: #fff; padding: 10px 18px;
    border-radius: 8px; border: none; cursor: pointer;
    font-size: 13px; font-weight: 600;
}
.btn-primary:hover { background: #065f46; }
.btn-outline {
    background: #fff; color: #087f5b; border: 1px dashed #087f5b;
    padding: 8px 14px; border-radius: 8px; cursor: pointer;
    font-size: 12px; font-weight: 600; margin-top: 6px;
}
.btn-icon {
    border: 1px solid #e2e8f0; background: #fff; padding: 5px 8px;
    border-radius: 6px; cursor: pointer; font-size: 12px;
}
.btn-icon.danger { color: #b91c1c; }
.form-actions {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 14px; margin-top: 18px;
}
.alert-error {
    background: #fef2f2; border: 1px solid #fecaca;
    color: #991b1b; padding: 12px 16px; border-radius: 8px;
    margin-bottom: 16px; font-size: 12px;
}
.alert-error ul { margin: 0; padding-left: 18px; }

@media(max-width: 720px) {
    .grid-2 { grid-template-columns: 1fr; }
    .field-row { grid-template-columns: 1fr 1fr; }
    .field-row .w-options { grid-column: span 2; }
}
</style>
@endpush