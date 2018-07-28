<table class="total" width="100%">
	<tr>
		<td width="60%">
			@include('sales.order::invoice_note')
		</td>
		<td valign="top" align="right">
			<table width="100%">
				@foreach($order->adjustments as $adjustment)
					<tr v-for="adj in order.adjustments" :key="adj.id">
					    <td align="right">
					    	{{ucwords($adjustment->type)}}
					    	@if($adjustment->adjustment_rule === 'percentage')
					    		{{$adjustment->adjustment_value}}%</template>
					    	@endif
					    : </td>
					    <td align="right">{{Money::format($adjustment->adjustment_total)}}</td>
					</tr>
				@endforeach
				<tr>
				    <td align="right" width="40%">Total Bayar :</td>
				    <td align="right">{{Money::format($order->total)}}</td>
				</tr>
			  	<tr>
				    <td align="right">Telah Dibayar :</td>
				    <td align="right">{{Money::format($order->paid_off)}}</td>
			  	</tr>
			  	<tr>
				    <td align="right">Sisa :</td>
				    <td align="right">{{Money::format($order->remaining)}}</td>
			 	</tr>
			</table>
		</td>
	</tr>
	
</table>