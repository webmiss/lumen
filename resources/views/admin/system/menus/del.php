<?php
use App\Http\Library\Inc;
?>
<form action="<?php echo Inc::getUrl(MODULE.'/'.CONTROLLER.'/delData');?>" method="post" id="Form">
<table class="table_add">
	<tr>
		<td class="center sub">
			<br/><label class="webmis_bottom">彻底删除<input type="submit" class="noDisplay" /></label>
			<input type="hidden" id="DelID" name="id" value="" />
		</td>
	</tr>
</table>
</form>