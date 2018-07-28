<table class="header" width="100%">
	<tr>
		<td width="40%">
			@if ($type === 'pdf')
		  		<img width="193" src="{{base_path('public/logo.png')}}" :alt="Setting::get('system.core.company_name')">
	  		@else
		  		<img width="50%" src="{{url('logo.png')}}" :alt="Setting::get('system.core.company_name')">
	  		@endif

	  		<p class="company-info">
	      		{{Setting::get('system.core.company_address')}} <br>
	      		Tlp. {{Setting::get('system.core.company_telephone')}}, Email {{Setting::get('system.core.company_email')}}
	  		</p>
		</td>
		<td align="center" width="30%" style="padding-top: 17px">
	    	<h1 class="display-2">Invoice</h1>
		</td>
		<td align="center" width="10%">
			@if ($type === 'pdf')
				@php
					$arrContextOptions = [
						'ssl' => ["verify_peer" => false, "verify_peer_name" => false]
					];

					$data = file_get_contents(url('/qrcode/' . $order->code . '?size=5'), false, stream_context_create($arrContextOptions));
				@endphp
				<img src="{{'data:image/png;base64,' . base64_encode($data)}}" width="90">
			@else
				<img src="{{url('/qrcode/'.$order->code)}}?size=5" width="100%">
			@endif
		</td>
	</tr>
</table>