<div class="sales-order-print-receipt">
	<div class="print-content">
		<table class="header" width="100%">
			<tr>
				<td width="60%"><h2>{{$order->code}}</h2></td>
				<td align="right"><h2>{{Setting::get('system.core.company_name')}}</h2></td>
			</tr>
		</table>
		<p class="company-info">
			{{Setting::get('system.core.company_address')}} <br>
			Tlp. {{Setting::get('system.core.company_telephone')}}, Email {{Setting::get('system.core.company_email')}}
		</p>
		<hr>
		<table width="100%">
			<tr>
				<td>{{$order->created_at->format('d M Y H:i')}}</td>
				<td align="right">CS: {{$order->cs_name}}</td>
			</tr>
		</table>
		<hr style="margin-bottom:10px">
		@if ($order->customer)
		<table class="customer" width="100%">
			<tr>
				<td width="10%">Nama</td>
				<td width="50%">: {{$order->customer->name}}</td>
				<td>Est :</td>
				@if ($order->estimated_finish_date) 
				<td align="right">{{date('d m y', strtotime($order->estimated_finish_date))}}</td>
				@else
				<td align="right">-</td>
				@endif
			</tr>
			<tr>
				<td>Telp.</td>
				<td>: {{$order->customer->telephone}}</td>
				<td>Due :</td>
				@if ($order->due_date)
				<td align="right">{{date('d m y', strtotime($order->due_date))}}</td>
				@else
				<td align="right">-</td>
				@endif
			</tr>
			<tr>
				<td style="vertical-align:top;">Alamat</td>
				@if ($order->customer->address)
				<td style="vertical-align:top;" colspan="3">: {{$order->customer->address}}</td>
				@else
				<td>-</td>
				@endif
			</tr>
		</table>
		@endif
		<hr>
		<table class="item" width="100%">
			@foreach($order->items as $index => $item)
	          <tr v-for="item in order.items" :key="item.id">
	            <td width="65%">
					{{$item->name}}
					@if ($item->specific !== null)
					({{$item->specific}})
					@endif
	            </td>
	            <td align="right" width="10%">{{$item->quantity}}</td>
	            <td align="right">
					@if ($item->type === 'service' AND $item->hasConfigurations)
						<strong>*</strong>
					@endif
					{{$item->total}}
				</td>
	          </tr>
	        @endforeach
	    </table>
	    <hr style="margin-bottom:10px">

	    <table width="100%">
	    	<tr>
	    		<td width="39%" valign="top">
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
	    		<td valign="top">
	    			<table width="100%">
			            <tbody>
			            	@if ($order->adjustment_total !== 0)
							<tr v-if="order.adjustment_total !== 0">
								<td align="right">Total Item :</td>
								<td align="right">{{$order->item_total}}</td>
							</tr>
			              	@endif
			              	@foreach($order->adjustments as $adjustment)
								<tr v-for="adj in order.adjustments" :key="adj.id">
								    <td align="right">
								    	{{ucwords($adjustment->type)}}
								    	@if($adjustment->adjustment_rule === 'percentage')
								    		{{$adjustment->adjustment_value}}%</template>
								    	@endif
								    : </td>
								    <td align="right">{{$adjustment->adjustment_total}}</td>
								</tr>
							@endforeach
							<tr>
								<td align="right">Total :</td>
								<td align="right">{{$order->total}}</td>
							</tr>
							<tr>
								<td align="right">Dibayar :</td>
								<td align="right">{{$order->paid_off}}</td>
							</tr>
							<tr>
								<td align="right">Sisa :</td>
								<td align="right">{{$order->remaining}}</td>
							</tr>
			            </tbody>
			        </table>
	    		</td>
	    	</tr>
	    </table>
	    <br>
	    <div class="myfooter" style="display:block;text-align:center">Terima kasih</div>
	</div>
</div>

<style type="text/css">
	@page {
	  size: align-self 4px;
	  margin: 6mm 0mm 0mm 0mm;
	}

	.sales-order-print-receipt {
		font-size: 12px;
		margin: 0 auto;
		min-width: 200px;
		max-width: 240px;
		padding: 0;
	}

	@media print {
		.sales-order-print-receipt {
			margin: 0;
		}
	}

	.sales-order-print-receipt .header,
	.sales-order-print-receipt .customer,
	.sales-order-print-receipt .company-info {
	  margin-bottom: 10px;
	}
	.sales-order-print-receipt .company-info,
	.sales-order-print-receipt .my-footer {
	  text-align: center;
	}
	.sales-order-print-receipt .print-content {
	  padding: 0 4px;
	}
	.sales-order-print-receipt hr,
	.sales-order-print-receipt tfoot {
	  border-top: dashed 1px;
	}
	.sales-order-print-receipt h3,
	.sales-order-print-receipt p {
	  margin: 0;
	}
	h2 {margin: 0}
	td {font-size: 12px}
</style>