@if($crud->hasAccess('update'))
	<a href="{{ url($crud->billing_route.'/'.$entry->getKey().'/printPdf') }}" class="btn btn-primary" data-style="zoom-in" target="_blank" ><span class="ladda-label"><i class="las la-file-pdf"></i> PDF</span></a>
@endif