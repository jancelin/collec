<script>
$(document).ready(function() { 
var type_init = {if $containerSearch.container_type_id > 0}{$containerSearch.container_type_id}{else}0{/if};
	function searchType() { 
	var family = $("#container_family_id").val();
	console.log ("famille : "+family);
	var url = "index.php";
	$.getJSON ( url, { "module":"containerTypeGetFromFamily", "container_family_id":family } , function( data ) {
		if (data != null) {
		console.log ("data is not null");
			options = '<option value="">Sélectionnez...</option>';			
			 for (var i = 0; i < data.length; i++) {
			        options += '<option value="' + data[i].container_type_id + '"';
			        if (data[i].container_type_id == type_init) {
			        	options += ' selected ';
			        }
			        options += '>' + data[i].container_type_name + '</option>';
			      };
			$("#container_type_id").html(options);
			}
		} ) ;
	}
	$("#container_family_id").change(function (){
	searchType();
	 });
	searchType();
});

</script>

<div class="row">
<form class="form-horizontal protoform" id="container_search" action="index.php" method="GET">
<input id="moduleBase" type="hidden" name="moduleBase" value="{if strlen($moduleBase)>0}{$moduleBase}{else}container{/if}">
<input id="action" type="hidden" name="action" value="{if strlen($action)>0}{$action}{else}List{/if}">
<input id="isSearch" type="hidden" name="isSearch" value="1">
<div class="form-group">
<label for="name" class="col-md-2 control-label">uid ou identifiant :</label>
<div class="col-md-4">
<input id="name" type="text" class="form-control" name="name" value="{$containerSearch.name}" title="uid, identifiant principal, identifiants secondaires (p. e. : cab:15 possible)" >
</div>
<label for="object_status_id" class="col-md-2 control-label">Statut :</label>
<div class="col-md-4">
<select id="object_status_id" name="object_status_id" class="form-control">
<option value="" {if $containerSearch.object_status_id == ""}selected{/if}>Sélectionnez...</option>
{section name=lst loop=$objectStatus}
<option value="{$objectStatus[lst].object_status_id}" {if $objectStatus[lst].object_status_id == $containerSearch.object_status_id}selected{/if}>
{$objectStatus[lst].object_status_name}
</option>
{/section}
</select>
</div>
</div>
<div class="form-group">
<label for="container_family_id" class="col-md-2 control-label">UID entre :</label>
<div class="col-md-2">
<input id="uid_min" name="uid_min" class="nombre form-control" value="{$containerSearch.uid_min}">
</div>
<div class="col-md-2">
<input id="uid_max" name="uid_max" class="nombre form-control" value="{$containerSearch.uid_max}">
</div>

<label for="container_family_id" class="col-md-2 control-label">Famille :</label>
<div class="col-md-4">
<select id="container_family_id" name="container_family_id" class="form-control">
<option value="" {if $containerSearch.container_family_id == ""}selected{/if}>Sélectionnez...</option>
{section name=lst loop=$containerFamily}
<option value="{$containerFamily[lst].container_family_id}" {if $containerFamily[lst].container_family_id == $containerSearch.container_family_id}selected{/if}>
{$containerFamily[lst].container_family_name}
</option>
{/section}
</select>
</div>

</div>
<div class="form-group">
<label for="limit" class="col-md-2 control-label">Nbre limite à afficher :</label>
<div class="col-md-2">
<input type="number" id="limit" name="limit" value="{$containerSearch.limit}" class="form-control">
</div>
<div class="col-md-2">
<input type="submit" class="btn btn-success" value="{$LANG['message'][21]}">
</div>
<label for="container_type_id" class="col-md-2 control-label">Type :</label>
<div class="col-md-4">
<select id="container_type_id" name="container_type_id" class="form-control">
<option value="" {if $containerSearch.container_type_id == ""}selected{/if}>Sélectionnez...</option>
{section name=lst loop=$container_type}
<option value="{$container_type[lst].container_type_id}" {if $container_type[lst].container_type_id == $containerSearch.container_type_id}selected{/if} title="{$container_type[lst].container_type_description}">
{$container_type[lst].container_type_name}
</option>
{/section}
</select>
</div>
</div>
</form>
</div>