<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!empty($field))
{
	if(!empty($this->id))
	{
		$data_image = $this->data_model->get_one($this->table, $field,' WHERE id = '.$this->id);
		$image    = !empty($data_image) ? $this->id.'/'.$data_image : '';
	}else if($this->init == 'param')
	{
		$image    = !empty($data[$field]) ? $name.'/'.$data[$field] : '';
	}
	echo form_label(ucfirst($label), $label);
	if(!empty($image))
	{
		?>
		<div class="image" data="<?php echo $field ?>">
			<a href="#">
				<img src="<?php echo image_module($this->table, $image) ?>" class="img-responsive image-thumbnail image" style="object-fit: cover;width: 200px;height: 140px;" data-toggle="modal" data-target="#img_<?php echo $field?>">
			</a>
			<span><a href="#del_image" class="del_image"><i class="fa fa-close" style="position: relative;top: -135px;right: -180px;color: red;"></i></a></span>
		</div>

		<div class="modal fade" id="img_<?php echo $field?>" tabindex="-1" role="dialog" aria-labelledby="img_<?php echo $field?>">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="img_title_<?php echo $field?>"><?php echo $field;?></h4>
		      </div>
		      <div class="modal-body" style="text-align: center;">
		        <img src="<?php echo image_module($this->table, $image); ?>" class="img-thumbnail img-responsive">
		      </div>
		      <div class="modal-footer">
		      </div>
		    </div>
		  </div>
		</div>
		<?php
	}
	$array_input = array(
		'name'   => $field,
		'class'  => 'form-control',
		'accept' => @$this->accept[$field],
		$required => $required,
		'value'  => $data[$field]
		);
	if(!empty($this->attribute[$field]))
	{
		$attr = $this->attribute[$field];
		if(is_array($attr))
		{
			foreach ($attr as $attr_key => $attr_value)
			{
				$array_input[$attr_key] = $attr_value;
			}
		}else{
			$array_input[$attr] = $attr;
		}
	}
	echo !empty($this->file_error[$field]) ? msg($this->file_error[$field],'danger') : '';
	echo form_upload($array_input);

	if(!empty($this->id) || ($this->init == 'param'))
	{
		echo form_hidden($field,$data[$field]);
	}else{
		echo form_hidden($field,'');
	}
	// $this->session->set_userdata('link_js', base_url().'templates/admin/');
}