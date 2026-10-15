<x-mail::message>
# Bonjour {{ $registration->prenom }},

Félicitations ! Votre demande d'accréditation en tant que représentant(e) pour **{{ $registration->ecole }}** a été validée avec succès.

Votre **Convocation Officielle au format PDF** est jointe à cet e-mail. Elle intègre votre **Pass QR Code unique**, indispensable pour enregistrer votre arrivée sur place.

<x-mail::panel>
**Informations de l'accréditation :**
- **Établissement :** {{ $registration->ecole }}
- **Code d'accès :** #CONV-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}
</x-mail::panel>

Conservez ce document précieusement sur votre appareil mobile ou imprimez-le.

Cordialement,<br>
**Ezzoukhry Mouhssine**<br>
*Head of Data & Digitalization — Forum Ben M'sik*
</x-mail::message>