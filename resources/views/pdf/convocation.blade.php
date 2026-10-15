<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Convocation Officielle - Forum Ben M'sik</title>
    <style>
        @page {
            margin: 0cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            background-color: #ffffff;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* En-tête Flat Design */
        .header {
            border-bottom: 4px solid #1e293b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .association-tag {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #4f46e5;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .header h1 {
            color: #1e293b;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        /* Badge de référence plat */
        .conv-badge {
            float: right;
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Message d'accueil */
        .greeting {
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.6;
            color: #374151;
        }

        /* Bloc d'informations plat (Card) */
        .info-card {
            background-color: #f9fafb;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            margin-bottom: 30px;
        }

        .table-info {
            width: 100%;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .table-info tr:last-child td {
            border-bottom: none;
        }

        .table-info td.label {
            font-weight: 600;
            width: 38%;
            color: #4b5563;
        }

        .table-info td.value {
            color: #111827;
            font-weight: 500;
        }

        /* Section QR Code en Flat Design */
        .qr-section {
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        .qr-section h3 {
            margin: 0 0 8px 0;
            color: #111827;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .qr-section p {
            font-size: 11px;
            color: #6b7280;
            margin: 10px 0 0 0;
            max-width: 320px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.4;
        }

        .qr-code-box {
            background: #ffffff;
            padding: 8px;
            display: inline-block;
            border: 1px solid #e5e7eb;
        }

        /* Pied de page */
        .footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="conv-badge">RÉF : #CONV-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="association-tag">AJMOVE — Pôle Digitalisation</div>
            <h1>Convocation Officielle</h1>
            <p>Forum d'Orientation Ben M'sik — 3ème Édition</p>
        </div>

        <div class="greeting">
            Bonjour <strong>{{ ucfirst($registration->prenom) }} {{ strtoupper($registration->nom) }}</strong>,<br>
            Nous avons le plaisir de vous confirmer votre accréditation officielle pour représenter votre établissement lors de notre événement.
        </div>

        <div class="info-card">
            <table class="table-info">
                <tr>
                    <td class="label">Établissement :</td>
                    <td class="value"><strong>{{ $registration->ecole }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Filière / Spécialité :</td>
                    <td class="value">{{ $registration->filiere ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Représentant(e) :</td>
                    <td class="value">{{ strtoupper($registration->nom) }} {{ ucfirst($registration->prenom) }}</td>
                </tr>
                <tr>
                    <td class="label">Adresse E-mail :</td>
                    <td class="value">{{ $registration->email }}</td>
                </tr>
            </table>
        </div>

        <div class="qr-section">
            <h3>Pass d'Accès Sécurisé</h3>
            <div class="qr-code-box">
                @php
                $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(140, 1),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                );
                $writer = new \BaconQrCode\Writer($renderer);
                $qrImage = base64_encode($writer->writeString(route('admin.checkin.scan', ['id' => $registration->id])));
                @endphp
                <img src="data:image/svg+xml;base64,{{ $qrImage }}" width="140" height="140" alt="QR Code Check-in">
            </div>
            <p>Veuillez présenter ce QR Code (sur votre smartphone ou imprimé) à l'accueil le jour du forum pour valider votre entrée.</p>
        </div>

        <div class="footer">
            • Document officiel généré par le système
        </div>
    </div>

</body>

</html>