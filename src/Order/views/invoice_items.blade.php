<table class="items" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="5%">No.</th>
			<th width="40%" align="left">Item</th>
			<th width="15%" align="center">Quantity</th>
			<th width="15%" align="right">Satuan</th>
			<th width="25%" align="right">Total</th>
		</tr>
	</thead>
	<tbody>
		@foreach($order->items as $index => $item)
		<tr>
			<td align="center">{{$index + 1}}.</td>
			<td>
				{{$item->name}}
				@if ($item->specific !== null)
				({{$item->specific}})
				@endif
			</td>
			<td align="center">{{$item->quantity}} {{$item->unit->short_name}}</td>
			<td align="right">{{Money::format($item->unit_price)}}</td>
			<td align="right">
				@if ($item->type === 'service' AND $item->hasConfigurations)
					<strong>*</strong>
				@endif
				{{Money::format($item->total)}}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>