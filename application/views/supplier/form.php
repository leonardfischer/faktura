<?php
$property_count = 8;

$left_column = array_slice($properties, 0, $property_count);
$right_column = array_slice($properties, $property_count);
?>
<div class="well">
	<form class="form-horizontal" method="post" id="supplier-form">
		<fieldset>
			<legend><?=$title; ?></legend>

			<div class="row">
				<div class="col-sm-6">
					<?php foreach($left_column as $key => $property): if ($property['form']): ?>
					<div class="form-group" data-property="<?=$key ?>">
						<label class="col-md-4 control-label" for="input<?=ucfirst($key) ?>">
							<?=$property['label'] ?>
							<?php if ($supplier->is_mandatory($key)): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-8">
							<?php
							if ($property['form_type'] == FORM_TYPE_TEXTAREA)
							{
								echo Form::textarea('input' . ucfirst($key), $supplier->get($key), array('class' => 'form-control'));
							}
							elseif($property['form_type'] == FORM_TYPE_TEXT)
							{
								echo Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'text', 'placeholder' => $property['label'], 'class' => 'form-control'));
							}
							elseif($property['form_type'] == FORM_TYPE_MONEY)
							{
								echo '<div class="input-group"><span class="input-group-addon"><i class="icon-euro"></i></span>' . Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'text', 'placeholder' => $property['label'], 'class' => 'form-control money')) . '</div>';
							}
							elseif($property['form_type'] == FORM_TYPE_EMAIL)
							{
								echo Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'email', 'placeholder' => $property['label'], 'class' => 'form-control'));
							}
							elseif($property['form_type'] == FORM_TYPE_CHECKBOX)
							{
								echo Form::checkbox('input' . ucfirst($key), 'on', (bool) $supplier->get($key));
							}
							?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>
					<?php endif; endforeach; ?>
				</div>
				<div class="col-sm-6">
					<?php foreach($right_column as $key => $property): if ($property['form']): ?>
						<div class="form-group" data-property="<?=$key ?>">
							<label class="col-md-4 control-label" for="input<?=ucfirst($key) ?>">
								<?=$property['label'] ?>
								<?php if ($supplier->is_mandatory($key)): ?><span class="text-danger">*</span><?php endif; ?>
							</label>
							<div class="col-md-8">
								<?php
								if ($property['form_type'] == FORM_TYPE_TEXTAREA)
								{
									echo Form::textarea('input' . ucfirst($key), $supplier->get($key), array('class' => 'form-control'));
								}
								elseif($property['form_type'] == FORM_TYPE_TEXT)
								{
									echo Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'text', 'placeholder' => $property['label'], 'class' => 'form-control'));
								}
								elseif($property['form_type'] == FORM_TYPE_MONEY)
								{
									echo '<div class="input-group"><span class="input-group-addon"><i class="icon-euro"></i></span>' . Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'text', 'placeholder' => $property['label'], 'class' => 'form-control money')) . '</div>';
								}
								elseif($property['form_type'] == FORM_TYPE_EMAIL)
								{
									echo Form::input('input' . ucfirst($key), $supplier->get($key), array('type' => 'email', 'placeholder' => $property['label'], 'class' => 'form-control'));
								}
								elseif($property['form_type'] == FORM_TYPE_CHECKBOX)
								{
									echo '<div class="checkbox">' . Form::checkbox('input' . ucfirst($key), 'on', (bool) $supplier->get($key)) . '</div>';
								}
								?>
								<span class="help-inline text-danger hidden"></span>
							</div>
						</div>
					<?php endif; endforeach; ?>
				</div>
			</div>

			<hr />

			<div id="form-message" class="hidden alert"></div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<button type="button" id="save-button" class="btn btn-success"><?=__('Save') ?></button>
					<a href="<?=Route::url('supplier')?>" class="btn btn-default"><?=__('Cancel') ?></a>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<script type="text/javascript">
	Factura
		.set('supplier-form', 'form-invoice')
		.set('form.ajax_url', '<?=$ajax_url ?>')
		.set('form.loading-message', '<img src="<?=URL::base() ?>assets/img/loading-on-grey.gif" /> <?=__('Loading, please wait...') ?>')
		.set('form.success-message', '<?=__('The data has been saved') ?>');
</script>