
/*
 * Editor client script for DB table _CplDocApproval
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'php/table._CplDocApproval.php',
		table: '#_CplDocApproval',
		fields: [
			{
				"label": "Po Number:",
				"name": "SagePoLine.PO_NO"
			},
			{
				"label": "Item Code:",
				"name": "SagePoLine.stock_ordercode"
			},
			{
				"label": "Quantity Ordered",
				"name": "SagePoLine.Quantity_Ordered"
			},
			{
				"label": "MIPO Number",
				"name": "sage_international_order.MIPO_NO"
			},
			{
				"label": "MIPO Quantity",
				"name": "sage_international_order.MIPO_Quantity"
			},
			{
				"label": "MIPO Date",
				"name": "sage_international_order.MIPO_Date",
				"type":  "datetime"
			}
		]
	} );
	$('#_CplDocApproval').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            buttons: { label: '&gt;', fn: function () { this.submit(); } }
        } );
    } );
 
	var table = $('#_CplDocApproval').DataTable( {
		dom: 'Bfrtip',
		ajax: 'php/table._CplDocApproval.php',
		// serverSide: true,
		columns: [
			{
				"data": "SagePoLine.PO_NO"
			},
			{
				"data": "SagePoLine.stock_ordercode"
			},
			{
				"data": "SagePoLine.Quantity_Ordered"
			},
			{
				"data": "sage_international_order.MIPO_NO"
			},
			{
				"data": "sage_international_order.MIPO_Quantity"
			},
			{
				"data": "sage_international_order.MIPO_Date"
			},
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor },
			{ extend: 'remove', editor: editor }
		]
	} );
} );

}(jQuery));

