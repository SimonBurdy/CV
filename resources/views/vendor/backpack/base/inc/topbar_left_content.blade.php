<!-- This file is used to store topbar (left) items -->

{{-- <li class="nav-item px-3"><a class="nav-link" href="#">Dashboard</a></li>
<li class="nav-item px-3"><a class="nav-link" href="#">Users</a></li>
<li class="nav-item px-3"><a class="nav-link" href="#">Settings</a></li> --}}


@php
        $acc = new \App\Services\ReportingService();
        $digital = $acc->forDates(config('settings.billing_year').'-01-01',today()->format('Y-m-d'))->forProjetType('digital');

@endphp


<li class="nav-item px-3 bg-blue"><strong>Digital :</strong>  20 000 € - MB :
    18 000 € 
</li>
