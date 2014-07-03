<!-- START Obsattributedesc module -->
<div id="obsAttrDescs" style="display:none;">
	{foreach from=$attrDesc key=idAttr item=desc}
	<div style="display:none;" id="obsAttrDesc_{$idAttr}">{$desc}</div>
	{/foreach}
</div>
<script>

function obsShowDesc(select, text) {

	if( $('.obsAttrRadio').length == 0 ){

		if( $('#obsAttrDescGroup_'+$(select).attr('name')).length ){

			$('#obsAttrDescGroup_'+$(select).attr('name')).html(text);
		}
		else{

			$(select).closest('div').parent().after('<div id="obsAttrDescGroup_'+$(select).attr('name')+'" class="obsDescElement">'+text+'</div>');

		}

	}
}

function obsHideDesc(select) {

	if( $('#obsAttrDescGroup_'+$(select).attr('name')).length ){

		$('#obsAttrDescGroup_'+$(select).attr('name')).html('');
	}

}

/* This takes url's and transforms them into an anchor-tag with the text: 'read more...' */
function urlifyString(string){
	var strSplittedToArray = string.split('http');

	if ( string.length > 1 && strSplittedToArray.length > 1){
   	return strSplittedToArray[0] + '<a href="http' + strSplittedToArray[1] + '" target="blank"> <p>Read more...</	p></a>'
  }

  return string;
}

$('document').ready( function() {

	/*This adds the attribute info text to the selected options on page load*/
	$('#attributes input').each(function(n, o) {

		if ( $(o).is(':checked') ){
			value = $(this).val();
			if( $('#obsAttrDesc_'+value).length ) {
				text = urlifyString($('#obsAttrDesc_'+value).html());
				if(text != ''){
					obsShowDesc(this, text);
				}
			}
		}
	});


	$('#attributes input').change(function() {
		/*This adds the attribute info text to the selected option when user changes it*/
		value = $(this).val();
		if( $('#obsAttrDesc_'+value).length ) {
			text = urlifyString($('#obsAttrDesc_'+value).html());
			if(text != ''){
				obsShowDesc(this, text);
			}
			else{
				obsHideDesc(this);
			}
		}
	});

	$('#attributes select').trigger('change');
});


</script>

