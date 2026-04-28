<x-mail::message>
# {{ __('app.email_appointment_subject') }}

{{ __('app.email_greeting', ['name' => $appointment->patient->name]) }}

{{ __('app.email_appointment_saved') }}

<x-mail::panel>
**{{ __('app.email_doctor') }}:** {{ $appointment->medecin->name }} ({{ $appointment->medecin->specialite }})  
**{{ __('app.email_service') }}:** {{ $appointment->service->name }}  
**{{ __('app.email_date') }}:** {{ $appointment->appointment_date->format('d/m/Y') }}  
**{{ __('app.email_time') }}:** {{ $appointment->appointment_time }}
@if($appointment->notes)
**{{ __('app.email_notes') }}:** {{ $appointment->notes }}
@endif
</x-mail::panel>

{{ __('app.email_contact') }}

**{{ __('app.email_signature') }}**
</x-mail::message>
