<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAT a valider</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #C41E3A 0%, #8B1120 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">KEYMEX</h1>
                            <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.8); font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">Marketing</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #1a1a1a; font-size: 22px; font-weight: 600;">
                                Bonjour {{ $bat->advisor_name }},
                            </h2>

                            <p style="margin: 0 0 25px 0; color: #4a4a4a; font-size: 16px; line-height: 1.6;">
                                Un nouveau BAT (Bon A Tirer) est pret pour votre validation. Merci de le consulter et de nous donner votre retour.
                            </p>

                            <!-- BAT Details Card -->
                            <table role="presentation" style="width: 100%; background-color: #f8f9fa; border-radius: 12px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 15px 0; color: #C41E3A; font-size: 18px; font-weight: 600;">
                                            {{ $bat->title }}
                                        </h3>

                                        @if($bat->description)
                                        <p style="margin: 0 0 15px 0; color: #666; font-size: 14px; line-height: 1.5;">
                                            {{ $bat->description }}
                                        </p>
                                        @endif

                                        <table role="presentation" style="width: 100%;">
                                            @if($bat->supportType)
                                            <tr>
                                                <td style="padding: 5px 0; color: #888; font-size: 13px; width: 120px;">Type :</td>
                                                <td style="padding: 5px 0; color: #333; font-size: 13px; font-weight: 500;">{{ $bat->supportType->name }}</td>
                                            </tr>
                                            @endif
                                            @if($bat->format)
                                            <tr>
                                                <td style="padding: 5px 0; color: #888; font-size: 13px;">Format :</td>
                                                <td style="padding: 5px 0; color: #333; font-size: 13px; font-weight: 500;">{{ $bat->format->name }}</td>
                                            </tr>
                                            @endif
                                            @if($bat->quantity)
                                            <tr>
                                                <td style="padding: 5px 0; color: #888; font-size: 13px;">Quantite :</td>
                                                <td style="padding: 5px 0; color: #333; font-size: 13px; font-weight: 500;">{{ number_format($bat->quantity, 0, ',', ' ') }} ex.</td>
                                            </tr>
                                            @endif
                                            @if($bat->grammage)
                                            <tr>
                                                <td style="padding: 5px 0; color: #888; font-size: 13px;">Grammage :</td>
                                                <td style="padding: 5px 0; color: #333; font-size: 13px; font-weight: 500;">{{ $bat->grammage }}</td>
                                            </tr>
                                            @endif
                                            @if($bat->delivery_time)
                                            <tr>
                                                <td style="padding: 5px 0; color: #888; font-size: 13px;">Delai :</td>
                                                <td style="padding: 5px 0; color: #333; font-size: 13px; font-weight: 500;">{{ $bat->delivery_time }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 10px 0 30px 0;">
                                        <a href="{{ $bat->validation_url }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #C41E3A 0%, #8B1120 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 10px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(196, 30, 58, 0.3);">
                                            Voir et valider le BAT
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #888; font-size: 13px; line-height: 1.5; text-align: center;">
                                Ce lien est valide pendant 30 jours.<br>
                                Si vous ne pouvez pas cliquer sur le bouton, copiez ce lien dans votre navigateur :<br>
                                <span style="color: #C41E3A; word-break: break-all;">{{ $bat->validation_url }}</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 40px; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #888; font-size: 12px; text-align: center; line-height: 1.5;">
                                Cet email a ete envoye automatiquement par l'equipe Marketing KEYMEX.<br>
                                Merci de ne pas repondre directement a cet email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
