@php
    $maintenance = \App\Models\SystemSetting::getMaintenanceData();
@endphp
@include('errors.maintenance', ['maintenance' => $maintenance])
