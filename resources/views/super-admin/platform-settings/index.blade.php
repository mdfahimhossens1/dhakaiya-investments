@extends('layouts.app')

@section('title', 'Platform Settings')

@section('page-title', 'Platform Settings')

@section('content')

<div class="platform-settings-page">

    {{-- Header --}}
    <div class="settings-page-header">
        <div>
            <h2>Platform Settings</h2>
            <p>Manage your platform configuration, branding and preferences.</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="settings-alert settings-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="settings-alert settings-alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="settings-layout">

        {{-- Sidebar Tabs --}}
        <div class="settings-sidebar">

            <div class="settings-sidebar-heading">
                SETTINGS MENU
            </div>

            <button type="button" class="settings-tab active" data-tab="general">
                <i class="fas fa-sliders-h"></i>
                <span>General Settings</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="branding">
                <i class="fas fa-palette"></i>
                <span>Branding Settings</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="contact">
                <i class="fas fa-address-book"></i>
                <span>Contact Information</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="social">
                <i class="fas fa-share-alt"></i>
                <span>Social Media</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="localization">
                <i class="fas fa-globe"></i>
                <span>Localization</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="seo">
                <i class="fas fa-search"></i>
                <span>SEO Settings</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="footer">
                <i class="fas fa-shoe-prints"></i>
                <span>Footer Settings</span>
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="settings-tab" data-tab="maintenance">
                <i class="fas fa-tools"></i>
                <span>Maintenance</span>
                <i class="fas fa-chevron-right"></i>
            </button>

        </div>

        {{-- Main Settings Form --}}
        <div class="settings-content">

            <form action="{{ route('super-admin.platform-settings.update') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="platformSettingsForm">

                @csrf
                @method('PUT')

                {{-- GENERAL SETTINGS --}}
                <div class="settings-panel active" id="tab-general">

                    <div class="settings-panel-header">
                        <div>
                            <h3>General Settings</h3>
                            <p>Configure the basic information of your platform.</p>
                        </div>

                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="platform_name">
                                Platform Name <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="platform_name"
                                id="platform_name"
                                value="{{ old('platform_name', $settings['platform_name'] ?? 'Dhakaiya Investment') }}"
                                placeholder="Enter platform name"
                                required
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="platform_tagline">Platform Tagline</label>

                            <input
                                type="text"
                                name="platform_tagline"
                                id="platform_tagline"
                                value="{{ old('platform_tagline', $settings['platform_tagline'] ?? '') }}"
                                placeholder="Enter platform tagline"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="platform_description">Platform Description</label>

                            <textarea
                                name="platform_description"
                                id="platform_description"
                                rows="5"
                                placeholder="Describe your platform..."
                            >{{ old('platform_description', $settings['platform_description'] ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- BRANDING SETTINGS --}}
                <div class="settings-panel" id="tab-branding">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Branding Settings</h3>
                            <p>Manage your platform logo, favicon and theme color.</p>
                        </div>

                        <i class="fas fa-palette"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label>Platform Logo</label>

                            @if(!empty($settings['logo']))
                                <div class="settings-current-image">
                                    <img src="{{ asset('storage/' . $settings['logo']) }}"
                                         alt="Platform Logo">
                                </div>
                            @endif

                            <input
                                type="file"
                                name="logo"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small>Supported formats: JPG, PNG, WEBP. Maximum 2MB.</small>
                        </div>

                        <div class="settings-form-group">
                            <label>Platform Favicon</label>

                            @if(!empty($settings['favicon']))
                                <div class="settings-current-image favicon-preview">
                                    <img src="{{ asset('storage/' . $settings['favicon']) }}"
                                         alt="Platform Favicon">
                                </div>
                            @endif

                            <input
                                type="file"
                                name="favicon"
                                accept=".jpg,.jpeg,.png,.ico"
                            >

                            <small>Supported formats: JPG, PNG, ICO. Maximum 1MB.</small>
                        </div>

                        <div class="settings-form-group">
                            <label for="primary_color">Primary Theme Color</label>

                            <div class="settings-color-input">
                                <input
                                    type="color"
                                    name="primary_color"
                                    id="primary_color"
                                    value="{{ old('primary_color', $settings['primary_color'] ?? '#172554') }}"
                                >

                                <span id="colorValue">
                                    {{ old('primary_color', $settings['primary_color'] ?? '#172554') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CONTACT SETTINGS --}}
                <div class="settings-panel" id="tab-contact">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Contact Information</h3>
                            <p>Configure the official support and contact details.</p>
                        </div>

                        <i class="fas fa-address-book"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="support_email">Support Email</label>

                            <input
                                type="email"
                                name="support_email"
                                id="support_email"
                                value="{{ old('support_email', $settings['support_email'] ?? '') }}"
                                placeholder="support@example.com"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="support_phone">Support Phone</label>

                            <input
                                type="text"
                                name="support_phone"
                                id="support_phone"
                                value="{{ old('support_phone', $settings['support_phone'] ?? '') }}"
                                placeholder="+8801XXXXXXXXX"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="contact_address">Office Address</label>

                            <textarea
                                name="contact_address"
                                id="contact_address"
                                rows="4"
                                placeholder="Enter office address"
                            >{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- SOCIAL MEDIA --}}
                <div class="settings-panel" id="tab-social">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Social Media Settings</h3>
                            <p>Connect your official social media profiles.</p>
                        </div>

                        <i class="fas fa-share-alt"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="facebook_url">
                                <i class="fab fa-facebook"></i> Facebook URL
                            </label>

                            <input
                                type="url"
                                name="facebook_url"
                                id="facebook_url"
                                value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                                placeholder="https://facebook.com/yourpage"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="youtube_url">
                                <i class="fab fa-youtube"></i> YouTube URL
                            </label>

                            <input
                                type="url"
                                name="youtube_url"
                                id="youtube_url"
                                value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}"
                                placeholder="https://youtube.com/@yourchannel"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="linkedin_url">
                                <i class="fab fa-linkedin"></i> LinkedIn URL
                            </label>

                            <input
                                type="url"
                                name="linkedin_url"
                                id="linkedin_url"
                                value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}"
                                placeholder="https://linkedin.com/company/yourcompany"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="instagram_url">
                                <i class="fab fa-instagram"></i> Instagram URL
                            </label>

                            <input
                                type="url"
                                name="instagram_url"
                                id="instagram_url"
                                value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                                placeholder="https://instagram.com/yourpage"
                            >
                        </div>

                    </div>
                </div>

                {{-- LOCALIZATION --}}
                <div class="settings-panel" id="tab-localization">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Localization Settings</h3>
                            <p>Configure the platform's language, timezone and display currency.</p>
                        </div>

                        <i class="fas fa-globe"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="default_language">Default Language</label>

                            <select name="default_language" id="default_language">
                                <option value="English"
                                    {{ old('default_language', $settings['default_language'] ?? 'English') === 'English' ? 'selected' : '' }}>
                                    English
                                </option>

                                <option value="Bangla"
                                    {{ old('default_language', $settings['default_language'] ?? 'English') === 'Bangla' ? 'selected' : '' }}>
                                    Bangla
                                </option>
                            </select>
                        </div>

                        <div class="settings-form-group">
                            <label for="timezone">Timezone</label>

                            <select name="timezone" id="timezone">

                                @foreach(timezone_identifiers_list() as $timezone)
                                    <option
                                        value="{{ $timezone }}"
                                        {{ old('timezone', $settings['timezone'] ?? 'Asia/Dhaka') === $timezone ? 'selected' : '' }}
                                    >
                                        {{ $timezone }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="settings-form-group">
                            <label for="display_currency">Display Currency</label>

                            <select name="display_currency" id="display_currency">
                                <option value="BDT"
                                    {{ old('display_currency', $settings['display_currency'] ?? 'BDT') === 'BDT' ? 'selected' : '' }}>
                                    BDT - Bangladeshi Taka
                                </option>

                                <option value="USD"
                                    {{ old('display_currency', $settings['display_currency'] ?? 'BDT') === 'USD' ? 'selected' : '' }}>
                                    USD - US Dollar
                                </option>
                            </select>

                            <small>This controls the currency label displayed in the interface only.</small>
                        </div>

                    </div>
                </div>

                {{-- SEO SETTINGS --}}
                <div class="settings-panel" id="tab-seo">

                    <div class="settings-panel-header">
                        <div>
                            <h3>SEO Settings</h3>
                            <p>Manage search engine metadata for your platform.</p>
                        </div>

                        <i class="fas fa-search"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="meta_title">Meta Title</label>

                            <input
                                type="text"
                                name="meta_title"
                                id="meta_title"
                                value="{{ old('meta_title', $settings['meta_title'] ?? '') }}"
                                placeholder="Enter meta title"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="meta_description">Meta Description</label>

                            <textarea
                                name="meta_description"
                                id="meta_description"
                                rows="4"
                                placeholder="Enter meta description"
                            >{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                        </div>

                        <div class="settings-form-group">
                            <label for="meta_keywords">Meta Keywords</label>

                            <textarea
                                name="meta_keywords"
                                id="meta_keywords"
                                rows="3"
                                placeholder="keyword1, keyword2, keyword3"
                            >{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- FOOTER SETTINGS --}}
                <div class="settings-panel" id="tab-footer">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Footer Settings</h3>
                            <p>Customize the platform footer information.</p>
                        </div>

                        <i class="fas fa-shoe-prints"></i>
                    </div>

                    <div class="settings-card">

                        <div class="settings-form-group">
                            <label for="footer_description">Footer Description</label>

                            <textarea
                                name="footer_description"
                                id="footer_description"
                                rows="5"
                                placeholder="Write footer description..."
                            >{{ old('footer_description', $settings['footer_description'] ?? '') }}</textarea>
                        </div>

                        <div class="settings-form-group">
                            <label for="copyright_text">Copyright Text</label>

                            <input
                                type="text"
                                name="copyright_text"
                                id="copyright_text"
                                value="{{ old('copyright_text', $settings['copyright_text'] ?? '© 2026 Dhakaiya Investment. All rights reserved.') }}"
                                placeholder="Enter copyright text"
                            >
                        </div>

                    </div>
                </div>

                {{-- MAINTENANCE SETTINGS --}}
                <div class="settings-panel" id="tab-maintenance">

                    <div class="settings-panel-header">
                        <div>
                            <h3>Maintenance Settings</h3>
                            <p>Configure maintenance status and informational message.</p>
                        </div>

                        <i class="fas fa-tools"></i>
                    </div>

                    <div class="settings-card">

                        <div class="maintenance-warning">
                            <i class="fas fa-exclamation-triangle"></i>

                            <div>
                                <strong>Maintenance Mode</strong>

                                <p>
                                    This setting currently stores the maintenance preference.
                                    It does not automatically block website visitors.
                                </p>
                            </div>
                        </div>

                        <div class="settings-toggle-row">

                            <div>
                                <strong>Enable Maintenance Mode</strong>
                                <small>Save your maintenance preference.</small>
                            </div>

                            <label class="settings-switch">
                                <input
                                    type="checkbox"
                                    name="maintenance_mode"
                                    value="1"
                                    {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}
                                >

                                <span class="settings-slider"></span>
                            </label>

                        </div>

                        <div class="settings-form-group">
                            <label for="maintenance_message">Maintenance Message</label>

                            <textarea
                                name="maintenance_message"
                                id="maintenance_message"
                                rows="5"
                                placeholder="Enter maintenance message..."
                            >{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Save Button --}}
                <div class="settings-save-bar">

                    <div>
                        <i class="fas fa-shield-alt"></i>
                        Changes will be saved to platform settings.
                    </div>

                    <button type="submit" class="settings-save-btn">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<style>
.platform-settings-page {
    padding: 24px;
    color: #1e293b;
}

.settings-page-header {
    margin-bottom: 24px;
}

.settings-page-header h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 6px;
}

.settings-page-header p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.settings-alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
}

.settings-alert-success {
    background: #dcfce7;
    color: #166534;
}

.settings-alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

.settings-alert ul {
    padding-left: 20px;
    margin-bottom: 0;
}

.settings-layout {
    display: grid;
    grid-template-columns: 245px minmax(0, 1fr);
    gap: 20px;
    align-items: start;
}

.settings-sidebar {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px;
    position: sticky;
    top: 20px;
}

.settings-sidebar-heading {
    padding: 10px 12px 15px;
    color: #94a3b8;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
}

.settings-tab {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    margin-bottom: 4px;
    border: none;
    background: transparent;
    color: #64748b;
    border-radius: 7px;
    font-size: 12px;
    text-align: left;
    cursor: pointer;
    transition: .2s;
}

.settings-tab > i:first-child {
    width: 18px;
    text-align: center;
}

.settings-tab span {
    flex: 1;
}

.settings-tab > i:last-child {
    font-size: 9px;
    opacity: 0;
}

.settings-tab:hover {
    background: #f8fafc;
    color: #172554;
}

.settings-tab.active {
    background: #eff6ff;
    color: #172554;
    font-weight: 700;
}

.settings-tab.active > i:last-child {
    opacity: 1;
}

.settings-content {
    min-width: 0;
}

.settings-panel {
    display: none;
}

.settings-panel.active {
    display: block;
}

.settings-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.settings-panel-header h3 {
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 5px;
}

.settings-panel-header p {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.settings-panel-header > i {
    font-size: 23px;
    color: #cbd5e1;
}

.settings-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 20px;
}

.settings-form-group {
    margin-bottom: 22px;
}

.settings-form-group:last-child {
    margin-bottom: 0;
}

.settings-form-group label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 8px;
}

.settings-form-group label span {
    color: #dc2626;
}

.settings-form-group input:not([type="color"]),
.settings-form-group textarea,
.settings-form-group select {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid #dbe1e8;
    border-radius: 7px;
    font-size: 13px;
    outline: none;
    color: #334155;
    background: #fff;
    box-sizing: border-box;
}

.settings-form-group input:focus,
.settings-form-group textarea:focus,
.settings-form-group select:focus {
    border-color: #172554;
    box-shadow: 0 0 0 3px rgba(23, 37, 84, .08);
}

.settings-form-group textarea {
    resize: vertical;
}

.settings-form-group small {
    display: block;
    margin-top: 7px;
    color: #94a3b8;
    font-size: 11px;
}

.settings-current-image {
    margin-bottom: 12px;
    padding: 12px;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    display: inline-flex;
    background: #f8fafc;
}

.settings-current-image img {
    max-width: 200px;
    max-height: 80px;
    object-fit: contain;
}

.settings-current-image.favicon-preview img {
    width: 48px;
    height: 48px;
}

.settings-color-input {
    display: flex;
    align-items: center;
    gap: 12px;
}

.settings-color-input input {
    width: 55px;
    height: 42px;
    padding: 3px;
    border: 1px solid #dbe1e8;
    border-radius: 7px;
    cursor: pointer;
}

.settings-color-input span {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
}

.maintenance-warning {
    display: flex;
    gap: 12px;
    padding: 15px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 8px;
    color: #92400e;
    margin-bottom: 22px;
}

.maintenance-warning > i {
    margin-top: 2px;
}

.maintenance-warning strong {
    font-size: 12px;
}

.maintenance-warning p {
    font-size: 11px;
    line-height: 1.6;
    margin: 5px 0 0;
}

.settings-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 22px;
}

.settings-toggle-row strong,
.settings-toggle-row small {
    display: block;
}

.settings-toggle-row strong {
    font-size: 12px;
}

.settings-toggle-row small {
    color: #94a3b8;
    font-size: 11px;
    margin-top: 5px;
}

.settings-switch {
    position: relative;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.settings-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.settings-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 20px;
    cursor: pointer;
    transition: .2s;
}

.settings-slider::before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .2s;
}

.settings-switch input:checked + .settings-slider {
    background: #172554;
}

.settings-switch input:checked + .settings-slider::before {
    transform: translateX(20px);
}

.settings-save-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 20px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
}

.settings-save-bar > div {
    color: #64748b;
    font-size: 11px;
}

.settings-save-bar > div i {
    margin-right: 5px;
    color: #16a34a;
}

.settings-save-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    background: #172554;
    color: #fff;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.settings-save-btn:hover {
    background: #1e3a8a;
}

@media(max-width: 900px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }

    .settings-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .settings-sidebar-heading {
        grid-column: 1 / -1;
    }
}

@media(max-width: 600px) {
    .platform-settings-page {
        padding: 15px;
    }

    .settings-sidebar {
        grid-template-columns: 1fr;
    }

    .settings-card {
        padding: 16px;
    }

    .settings-save-bar {
        align-items: stretch;
        flex-direction: column;
    }

    .settings-save-btn {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Settings Tabs
    const tabs = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');

    function activateTab(tabName) {

        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabName);
        });

        panels.forEach(panel => {
            panel.classList.toggle('active', panel.id === 'tab-' + tabName);
        });

        sessionStorage.setItem('platformSettingsTab', tabName);
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            activateTab(this.dataset.tab);
        });
    });

    // Restore active tab after validation errors
    const savedTab = sessionStorage.getItem('platformSettingsTab');

    @if($errors->any())
        const errorFields = @json(array_keys($errors->messages()));

        const fieldTabs = {
            platform_name: 'general',
            platform_tagline: 'general',
            platform_description: 'general',

            logo: 'branding',
            favicon: 'branding',
            primary_color: 'branding',

            support_email: 'contact',
            support_phone: 'contact',
            contact_address: 'contact',

            facebook_url: 'social',
            youtube_url: 'social',
            linkedin_url: 'social',
            instagram_url: 'social',

            default_language: 'localization',
            timezone: 'localization',
            display_currency: 'localization',

            meta_title: 'seo',
            meta_description: 'seo',
            meta_keywords: 'seo',

            footer_description: 'footer',
            copyright_text: 'footer',

            maintenance_mode: 'maintenance',
            maintenance_message: 'maintenance'
        };

        const errorTab = errorFields.length
            ? fieldTabs[errorFields[0]]
            : null;

        activateTab(errorTab || savedTab || 'general');
    @else
        activateTab(savedTab || 'general');
    @endif

    // Primary color preview
    const colorInput = document.getElementById('primary_color');
    const colorValue = document.getElementById('colorValue');

    if (colorInput && colorValue) {
        colorInput.addEventListener('input', function () {
            colorValue.textContent = this.value.toUpperCase();
        });
    }

    // Clear stored tab after successful save
    @if(session('success'))
        sessionStorage.removeItem('platformSettingsTab');
    @endif

});
</script>

@endsection