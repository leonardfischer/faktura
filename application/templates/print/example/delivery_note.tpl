<header>
	<small>Company address</small><br />
	{if !empty($invoice->shipping_address)}
		{$invoice->shipping_address|nl2br}
	{else}
		{if !empty($customer->company)}{$customer->company}<br />{/if}
		{if !empty($customer->name)}{$customer->name}<br />{/if}
		{if !empty($customer->addition)}{$customer->addition}<br />{/if}
		{if !empty($customer->addition2)}{$customer->addition2}<br />{/if}
		{$customer->street} {$customer->street_no}<br />
		{$customer->zip_code} {$customer->city}<br />
    {/if}
</header>

<h1>Lieferschein</h1>

<table class="mt5 mb5">
	<thead>
	<tr>
		<th class="left">Liefertermin</th>
		<th class="left">Auftrag vom</th>
		<th class="left">Datum</th>
		<th class="right">RE-Nummer</th>
	</tr>
	</thead>
	<tbody class="border-top">
	<tr>
		<td class="left">{date('d.m.Y')}</td>
		<td class="left">{date('d.m.Y', strtotime($invoice->invoice_date))}</td>
		<td class="left">{date('d.m.Y')}</td>
		<td class="right">{$invoice->invoice_no}</td>
	</tr>
	</tbody>
</table>

<p>Wir liefern Ihnen wie folgt:</p>

{foreach $pages as $page_no => $page}

{if $page_no > 1}
<div class="page-break"></div>

<table class="mt5 mb5">
	<colgroup>
		<col width="20%">
		<col width="30%">
		<col width="50%">
		<col width="25%">
	</colgroup>
	<tbody>
	<tr>
		<td>Seite {$page_no}</td>
		<td>RE-Nummer {$invoice->invoice_no}</td>
		<td>Datum {date('d.m.Y')}</td>
	</tr>
	</tbody>
</table>
{/if}

<table class="mt5 spacing position-tables">
	<colgroup>
		<col width="10%">
		<col width="90%">
	</colgroup>
	<thead class="border-top border-bottom">
	<tr>
		<th class="left">Menge</th>
		<th class="left">Bezeichnung</th>
	</tr>
	</thead>
	<tbody class="border-top">
	{foreach $page as $position}
	<tr>
		<td class="left">{number_format($position.amount, 2, '.', '')}</td>
		<td class="left">{$position.description}</td>
	</tr>
    {/foreach}
	</tbody>
</table>
{/foreach}

<p class="mt20">Unterschrift Empfänger: ___________________________________</p>

<script>
	window.addEvent('domready', function() {
		// Prepare the print view (break pages etc.)
		prepare_print_view();

		// And call the print-dialog, if available.
		if (window.print) window.print();
	});

	function prepare_print_view () {
		// Nothing to do here.
	}
</script>