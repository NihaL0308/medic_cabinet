<x-mail::message>
# Confirmation de votre rendez-vous

Bonjour **{{ $appointment->patient->name }}**,

Votre rendez-vous a été enregistré avec succès.

<x-mail::panel>
**Médecin :** {{ $appointment->medecin->name }} ({{ $appointment->medecin->specialite }})  
**Service :** {{ $appointment->service->name }}  
**Date :** {{ $appointment->appointment_date->format('d/m/Y') }}  
**Heure :** {{ $appointment->appointment_time }}
@if($appointment->notes)
**Notes :** {{ $appointment->notes }}
@endif
</x-mail::panel>

En cas de besoin, veuillez nous contacter.

Cordialement,  
**L'équipe du Cabinet Médical**
</x-mail::message>