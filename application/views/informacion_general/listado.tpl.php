<label class="control-label"><br><?php echo $form['label']; ?></label>
<div class="form-group form-group-sm">
	<?php echo $this->form_complete->create_element(
	    array(
	        'id'=>$tipo,
	        'type'=>'dropdown',
	        'first' => array(''=>$form['seleccione']),
	        'options' => $datos,
	        'attributes'=>array('class'=>'form-control',
	            
	        )+$form['evento']
	    )
	); ?>
</div>
<span class="material-input"></span>  