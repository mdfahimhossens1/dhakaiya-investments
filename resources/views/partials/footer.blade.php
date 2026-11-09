<footer class="app-footer">

    <p>
        © {{ date('Y') }}
        <strong>Dhakaiya Investment</strong>.
        All rights reserved.
    </p>

    <div class="footer-right">

        <span>Privacy Policy</span>
        <span>Terms & Conditions</span>
        <span>Support</span>

        <span class="developer-credit">
            Developed by
            <strong>FKLabsAgency</strong>
        </span>

    </div>

</footer>


<style>
    .app-footer {
        background: #fff;
        border-top: 1px solid var(--border);
        padding: 18px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .app-footer p {
        font-size: 11px;
        color: var(--muted);
        margin: 0;
    }

    .app-footer p strong {
        color: var(--primary);
    }

    .footer-right {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .footer-right span {
        font-size: 11px;
        color: var(--muted);
        cursor: pointer;
    }

    .footer-right .developer-credit {
        color: #64748b;
        border-left: 1px solid var(--border);
        padding-left: 18px;
        cursor: default;
    }

    .developer-credit strong {
        color: var(--primary);
        font-weight: 700;
    }

    @media(max-width: 768px) {
        .app-footer {
            padding: 15px;
            justify-content: center;
            text-align: center;
        }

        .footer-right {
            justify-content: center;
            gap: 12px;
        }

        .footer-right .developer-credit {
            border-left: 0;
            padding-left: 0;
            width: 100%;
        }
    }
</style>