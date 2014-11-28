<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <div>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    </div>
     <div class="panel panel-default">
		  <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
  <div class="panel-body">
 <div class="table-responsive">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table">
			<thead>
				<tr>
				<td class="left"><strong><?php echo $description_route; ?></strong></td>
				<td colspan="2"><strong><?php echo $description_url; ?></strong></td>
				</tr>
			</thead> 
			<tbody>
			<tr>
				<td class="left"><input type ="text" name ="route" /></td>
				<td colspan="2"><input type="text" name="url" /></td>
				 <td class="left">
					 <button onclick="$('#form').submit();" type="submit" form="form-user-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></button>
				</td>
			</tr>
				<?php foreach($super_seo_urls as $url) { ?>
					<tr>
						<td class="left"><?php echo $url['query']; ?></td>
						<td class="left"><?php echo $url['keyword']; ?></td>
						<td class="left">
							<a href = "<?php echo $url['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-default"><i class="fa fa-trash"></i>
							</a>
							</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			</form>
		</div> <!-- end of  .table-responsive-->
		</div> <!-- end of  .panel-->
		
	</div><!-- end of .container-fluid  -->

</div> <!-- end of #content -->

