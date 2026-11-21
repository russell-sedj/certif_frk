@if (config('demo.enabled'))
    <div class="demo-banner" role="status">
        <span>
            <strong>DÉMO</strong> : données fictives, projet non officiel
        </span>
        <form method="POST" action="{{ route('demo.reset') }}" onsubmit="return confirm('Réinitialiser toutes les données de la démo ?');">
            @csrf
            <button type="submit">Réinitialiser</button>
        </form>
    </div>

    <style>
        .demo-banner {
            position: sticky;
            top: 0;
            z-index: 2000;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 8px 16px;
            background: #fff3cd;
            border-bottom: 1px solid #ffecb5;
            color: #664d03;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            font-size: 13px;
            line-height: 1.4;
            text-align: center;
        }

        .demo-banner strong {
            letter-spacing: 0.08em;
        }

        .demo-banner form {
            margin: 0;
        }

        .demo-banner button {
            padding: 3px 10px;
            border: 1px solid #664d03;
            border-radius: 4px;
            background: transparent;
            color: #664d03;
            font: inherit;
            font-size: 12px;
            cursor: pointer;
        }

        .demo-banner button:hover {
            background: #664d03;
            color: #fff;
        }
    </style>
@endif
