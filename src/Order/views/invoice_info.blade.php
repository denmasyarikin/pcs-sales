<table class="info" width="100%">
	<tr>
		<td width="50%">
            <div class="box">
              <table width="100%">
                <tr>
					<td width="30%">ID</td>
					<td>: {{$order->code}}</td>
                </tr>
                <tr>
					<td>Tanggal</td>
					<td>: {{$order->created_at->format('d M Y H:i')}}</td>
                </tr>
                <tr>
					<td>Estimasi</td>
					@if ($order->estimated_finish_date)
						<td>: {{date('d M Y H:i', strtotime($order->estimated_finish_date))}}</td>
					@else
						<td>: -</td>
					@endif
                </tr>
                <tr>
					<td>Jatuh Tempo</td>
					@if ($order->due_date)
						<td>: {{date('d M Y H:i', strtotime($order->due_date))}}</td>
					@else
						<td>: -</td>
					@endif
                </tr>
              </table>
            </div>
  		</td>
		<td>
            <div class="box">
            	@if ($order->customer !== null)
	            <table width="100%">
	                <tr>
						<td width="25%">Konsumen</td>
						<td>: {{$order->customer->name}}</td>
	                </tr>
	                <tr>
						<td>Alamat</td>
						@if ($order->customer->address)
							<td>: {{$order->customer->address}}</td>
						@else
							<td>: -</td>
						@endif
	                </tr>
	                <tr>
						<td>Telephon</td>
						@if ($order->customer->telephone)
							<td>: {{$order->customer->telephone}}</td>
						@else
							<td>: -</td>
						@endif
					</tr>
	                <tr>
						<td>Email</td>
						@if ($order->customer->email)
							<td>: {{$order->customer->email}}</td>
						@else
							<td>: -</td>
						@endif
	                </tr>
	            </table>
	            @else
	            	<div class="empty">No Customer</div>
	            @endif
            </div>
  		</td>
  	</tr>
</table>