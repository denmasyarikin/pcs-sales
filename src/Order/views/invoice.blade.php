 <div class="sales-order-print-invoice">
	<div class="print-content">
		@include('sales.order::invoice_header')
		@include('sales.order::invoice_info')
		@include('sales.order::invoice_items')
		@include('sales.order::invoice_total')
		@include('sales.order::invoice_footer')
    </div>
</div>

<style type="text/css">
	@page {
	  size: auto;
	  margin: 0mm;
	}

	.sales-order-print-invoice {
	  margin: 0 auto;
	  padding: 15px 30px;
	  min-width: 610px;
	  max-width: 794px;
	  background: #ffffff;
	}

	.sales-order-print-invoice .print-content {
	  padding: 4px;
	}

	.sales-order-print-invoice .header,
	.sales-order-print-invoice .info,
	.sales-order-print-invoice .items {
		padding-bottom: 5px;
	}

	.sales-order-print-invoice .box {
	  border: 2px solid;
	  padding: 8px;
	  border-radius: 4px;
	  min-height: 90px;
	}

	.sales-order-print-invoice .box .empty {
		text-align: center;
		padding-top: 35px;
	}

	.sales-order-print-invoice .items thead th {
	  border-top: 2px solid;
	  border-bottom: 2px solid;
	}

	.sales-order-print-invoice .items tbody td {
	  border-bottom: 1px dashed;
	}
</style>