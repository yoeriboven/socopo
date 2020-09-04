<div class="row settings_panel">
	<div class="col-md-3 mb-3">
		<h4 class="card-title">Invoices</h4>
	</div>
	<div class="col-md-9">
		<table id="invoicesTable" class="table text-nowrap">
			<thead>
				<tr>
					<th>Paid at</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				@foreach (auth()->user()->receipts as $receipt)
			        <tr>
			            <td>{{ $receipt->paid_at->toFormattedDateString() }}</td>
			            <td>{{ $receipt->amount() }}</td>
			            <td><a href="{{ $receipt->receipt_url }}" target="_blank">Download</a></td>
			        </tr>
			    @endforeach
			</tbody>
        </table>
	</div>
</div>
