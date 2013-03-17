<fieldset>
	<legend>Stock price of <em>{$comdata['companyName']}</em></legend>
	Last Trade: {$comdata['stockPrice'][0]}
	<br />
	52wk Range: {$comdata['stockPrice'][1]}-{$comdata['stockPrice'][2]}
</fieldset>
{include 'test-test'}
