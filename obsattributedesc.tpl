<!-- START Obsattributedesc module -->
<div id="obsAttrDescs">
{foreach from=$attrDesc key=idAttr item=desc}
		<div style="display:none" id="obsAttrDesc_{$idAttr}">{$desc}</div>
{/foreach}
</div>
<script>

function obsShowDesc(select, text) {

	if( $('.obsAttrRadio').length == 0 ){

		if( $('#obsAttrDescGroup_'+$(select).attr('name')).length ){

				$('#obsAttrDescGroup_'+$(select).attr('name')).html(text);
			}

		else

			$(select).after('<div id="obsAttrDescGroup_'+$(select).attr('name')+'" class="obsDescElement">'+text+'</div>');
	}
}

function obsHideDesc(select) {

	if( $('#obsAttrDescGroup_'+$(select).attr('name')).length ){

		$('#obsAttrDescGroup_'+$(select).attr('name')).html('');
	}

}

$('document').ready( function() {

	$('#attributes input').change(function() {

		value = $(this).val();

		if( $('#obsAttrDesc_'+value).length ) {

			text = $('#obsAttrDesc_'+value).html();

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
<!-- END Obsattributedesc module -->



<!--<input type="radio" class="attribute_radio" name="group_5" value="27" checked="checked">-->



<button type="button" class="btn btn-lg btn-danger" data-toggle="popover" title="Popover title" data-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover</button>