<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Zea extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('esg_model');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		// $this->load->library('upload');
		$this->load->library('pagination');
	}

	var $table         = '';
	var $formName      = 'form_1';
	var $view          = '';
	var $init          = '';
	var $heading       = '';
	var $edit_status   = true;
	var $paramname     = '';
	var $where         = '';
	var $encrypt       = TRUE;
	var $file_error    = array();
	var $edit_link     = 'edit/';
	var $limit         = 12;
	var $id            = 0;
	var $delete        = false;
	var $delete_type   = 'submit';
	var $edit          = false;
	var $save          = false;
	var $options       = array();
	var $required      = array();
	var $data          = array();
	var $input         = array();
	var $link          = array();
	var $label         = array();
	var $attribute     = array();
	var $field         = array();
	var $image         = array();
	var $type          = array();
	var $accept        = array();
	var $checkbox      = array();
	var $radio         = array();
	var $orderby       = array('index'=>'id','sort'=>'DESC');
	var $multiselect   = array();
	var $elementid     = array();
	var $value         = array();
	var $startCollapse = array();
	var $endCollapse   = array();
	var $collapse      = array();
	var $param         = array();
	var $plaintext     = array();
	var $selected      = array();
	var $money         = array();
	var $clearget      = array();
	var $jointable     = array();

	public function init($text = '')
	{
		if(!empty($text))
		{
			switch($text)
			{
				case 'roll':
					$this->init = $text;
				break;
				case 'edit':
					$this->init = $text;
				break;
				case 'param':
					$this->init = $text;
				break;
				default:
					$this->init = '';
				break;
			}
		}
	}

	public function set_param($table = '',$name = '', $post = array())
  {
    if(!empty($table))
    {
      $data = array();
      foreach ($post as $key => $value)
      {
        $data[$key] = $value;
      }
      $param = $this->db->query('SELECT * FROM '.$table.' WHERE name = ?', $name)->row_array();
      if($param)
      {
        return $this->db->update($table, $data, "`name` = '{$name}'");
      }else{
        return $this->db->insert($table, $data);
      }
    }
  }

	public function join($table = '', $cond = '', $field = '')
	{
		if(!empty($table) && !empty($cond) && !empty($field))
		{
			$this->jointable['table']     = $table;
			$this->jointable['condition'] = $cond;
			$this->jointable['field']     = $field;
		}
	}

	public function setLimit($limit = 0)
	{
		$this->limit = @intval($limit);
	}

	public function setWhere($sql = '')
	{
		if(!empty($sql))
		{
			$this->where = $sql;
		}
	}

	public function setFormName($name = '')
	{
		if(!empty($name))
		{
			$this->formName = $name;
		}
	}

	public function setParamName($name = '')
	{
		if(!empty($name))
		{
			$this->paramname = $name;
		}
	}

	public function setParam($param = array())
	{
		if(!empty($param))
		{
			$this->param = $param;
		}
	}

	public function open_collapse($id = 'collapse1', $title = 'Collapsible Panel', $type = 'default')
	{
		$collapse = !empty($this->collapse[$id]) ? 'collapse' : '';
		?>
		<br>
		<div class="panel-group">
			<div class="panel panel-<?php echo $type ?>">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#<?php echo $id; ?>"><?php echo $title ?></a>
					</h4>
				</div>
				<div id="<?php echo $id ?>" class="panel-collapse <?php echo $collapse ?>">
					<div class="panel-body">

		<?php
	}

	public function close_collapse()
	{
		?>
					</div>
					<div class="panel-footer">Panel Footer</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function search()
	{
		?>
		<form method="get" action="<?php echo !empty($this->view) ? base_url($this->view) : ''; ?>" class="form-inline pull-right">
			<input type="text" name="keyword" class="form-control" placeholder="keyword">
			<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span></button>
		</form>
		<hr>
		<div class="clearfix"></div>
		<?php
	}

	public function setImage($field = '', $module = '', $src = '')
	{
		if(!empty($field) && !empty($module))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->image[$field]['module'] = $module;
					$this->image[$field]['src']    = $src;
				}
			}
		}
	}

	public function setEditStatus($edit_status = true)
	{
		if(is_bool($edit_status))
		{
			$this->edit_status = $edit_status;
		}
	}

	public function setHeading($heading = '')
	{
		$this->heading = $heading;
	}

	public function setView($view = '')
	{
		$this->view = $view;
		$this->data_model->setView($view);
	}

	public function get_all($sql = '')
	{
		if(!empty($sql))
		{
			return $this->db->query($sql)->result_array();
		}
	}

	public function tableOptions($field = '', $table = '', $index= '', $label = '', $ex = '')
	{
		if(!empty($table) && !empty($index) && !empty($label))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					// $data       = $this->get_all("SELECT `{$index}`,`{$label}` FROM `{$table}` {$ex}");
					$this->db->select($index);
					$this->db->select($label);
					$this->db->from($table);
					if(!empty($ex))
					{
						$this->db->where($ex);
					}
					$data = $this->db->get()->result_array();
					$options    = array();
					$options[0] = 'None';
					if(!empty($data))
					{
						foreach ($data as $dkey => $dvalue)
						{
							$options[$dvalue[$index]] = $dvalue[$label];
						}
						$this->options[$field] = $options;
					}else{
						$this->options[$field] = $options;
					}
				}
			}
		}
	}
	public function setOptions($field = '',$options = array())
	{
		if(!empty($field) && !empty($options))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->options[$field] = $options;
				}
			}
		}
	}

	public function setMultiSelect($field = '', $table = '', $col = '')
	{
		if(!empty($field) && !empty($table) && !empty($col))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->multiselect[$field]['data'] = $this->get_all("SELECT {$col} FROM `{$table}` WHERE 1");
				}
			}
		}
	}

	public function setType($field = '', $type = '') /*untuk input type text*/
	{
		if(!empty($field) && !empty($type))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->type[$field] = $type;
				}
			}
		}
	}

	public function setEncrypt($encrypt = TRUE)
	{
		$this->encrypt = $encrypt;
	}

	public function setElementId($field = '', $id = '')
	{
		if(!empty($field) && !empty($id))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->elementid[$field] = $id;
				}
			}
		}
	}

	public function setSelected($field = '', $value = '0')
	{
		if(!empty($field))
		{
			foreach ($this->input as $ikey => $ivalue)
			{
				if($ivalue['text'] == $field)
				{
					$this->selected[$field] = $value;
				}
			}
		}
	}

	public function setValue($field = '', $value = '0')
	{
		if(!empty($field))
		{
			foreach ($this->input as $ikey => $ivalue)
			{
				if($ivalue['text'] == $field)
				{
					$this->value[$field] = $value;
				}
			}
		}
	}

	public function setField($fields = array())
	{
		$this->field = $fields;
	}

	public function setData()
	{
		if(!empty($this->input))
		{
			foreach ($this->input as $key => $value)
			{
				if($this->init == 'edit' || $this->init == 'param')
				{
					$this->data[$key] = '';
				}else if($this->init == 'roll')
				{
					$this->data[0][$key] = '';
					if(!in_array('id', $this->input))
					{
						$this->data[0]['id'] = 0;
					}
				}
			}
		}
	}

	public function setLink($field = '', $link = '', $get = '')
	{
		if(!empty($field) && !empty($link))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					if(!empty($get))
					{
						$this->link['link_get'][$field] = $get;
					}
					$this->link[$field] = $link;
				}
			}
		}
	}

	public function setClearGet($field = '')
	{
		if(!empty($field))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->clearget[$field] = 1;
				}
			}
		}
	}

	public function setMoney($field = '', $type = 'Rp')
	{
		if(!empty($field))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->money[$field] = $type;
				}
			}
		}
	}

	public function setEditLink($edit_link = '')
	{
		$this->edit_link = $edit_link;
	}

	public function addInput($text = '', $type = '')
	{
		$this->input[$text] = array('text'=>$text, 'type'=>$type);
	}

	public function setTable($table = '', $index = '', $sort = '')
	{
		if(!empty($table))
		{
			$this->table = $table;
		}

		if(!empty($index) && !empty($sort))
		{
			$this->orderby['index'] = $index;
			$this->orderby['sort'] = $sort;
		}
	}

	public function setRequired($input = array())
	{
		if(!empty($input) && is_array($input))
		{
			foreach ($input as $key => $value)
			{
				foreach ($this->input as $ikey => $ivalue)
				{
					if($ivalue['text'] == $value)
					{
						$this->required[$value] = 'required';
					}
				}
			}
		}else{
			if($input == 'All')
			{
				foreach ($this->input as $ikey => $ivalue)
				{
					$this->required[$ivalue['text']] = 'required';
				}
			}
		}
	}

	public function startCollapse($field = '', $title = 'panel')
	{
		if(!empty($field))
		{
			foreach ($this->input as $ikey => $ivalue)
			{
				if($ivalue['text'] == $field)
				{
					$this->startCollapse[$field] = $field;
					if(!empty($title))
					{
						$this->startCollapse['title'][$field] = $title;
					}
				}
			}
		}
	}

	public function setCollapse($field = '', $collapse = FALSE)
	{
		$title = $this->startCollapse['title'];
		if(!empty($title))
		{
			foreach ($title as $key => $value)
			{
				if($key == $field)
				{
					$this->collapse[$field] = $collapse;
				}
			}
		}
	}

	public function endCollapse($field = '')
	{
		if(!empty($field))
		{
			foreach ($this->input as $ikey => $ivalue)
			{
				if($ivalue['text'] == $field)
				{
					$this->endCollapse[$field] = $field;
				}
			}
		}
	}

	public function setId($id = 0)
	{
		$this->id = @intval($id);
	}

	public function setLabel($field = '', $text = '')
	{
		if(!empty($field) && !empty($text))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->label[$field] = $text;
				}
			}
		}
	}

	public function setPlaintext($field = '', $text = '')
	{
		if(!empty($field) && !empty($text))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->plaintext[$field] = $text;
				}
			}
		}
	}

	public function setAttribute($field = '', $text = '')
	{
		if(!empty($field) && !empty($text))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->attribute[$field] = $text;
				}
			}
		}
	}


	public function setCheckBox($field = '', $option = array())
	{
		if(!empty($field) && !empty($option))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->checkbox[$field] = $option;
				}
			}
		}
	}

	public function setRadio($field = '', $option = array())
	{
		if(!empty($field) && !empty($option))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->radio[$field] = $option;
				}
			}
		}
	}

	public function setDelete($delete = true, $type = 'submit')
	{
		if(is_bool($delete))
		{
			$this->delete = $delete;
		}
		if(!empty($type))
		{
			$this->delete_type = $type;
		}
	}

	public function setEdit($edit = true)
	{
		if(is_bool($edit))
		{
			$this->edit = $edit;
		}
	}

	public function setSave($save = true)
	{
		if(is_bool($save))
		{
			$this->save = $save;
		}
	}

	public function setAccept($field = '', $accept = '')
	{
		if(!empty($field) && !empty($accept))
		{
			foreach ($this->input as $key => $value)
			{
				if($value['text'] == $field)
				{
					$this->accept[$field] = $accept;
				}
			}
		}
	}
	public function check_type($type = '', $title = '')
	{
		$result = FALSE;
		if(!empty($this->accept))
		{
			$types = explode(',',$this->accept[$title]);
			$data = array();
			$forbiden = array('*','php','exe','deb','PHP','EXE','DEB');
			foreach($types AS $c => $d)
			{
				$tmp = $d;
		    if(preg_match('~/~',$tmp))
		    {
		    	$tmp = explode('/',$tmp);
		      $tmp = @$tmp[1];
		      if(!empty($tmp))
		      {
			      if(!in_array($tmp, $forbiden))
			      {
		      		if(strtolower($tmp) == 'jpeg')
		      		{
		      			$data[] = '.jpg';
		      		}
		      		$data[] = '.'.$tmp;
			      }
		      }
		    }else{
		    	$data[] = $tmp;
		    }
			}
			$types = $data;
		}else{
			$types = explode(',','.jpg,.jpeg,.png,.bmp,.gif,.JPG,.JPEG,.PNG,.BMP,.GIF');
		}
		if(!empty($type))
		{
			$types = array_unique($types);
			foreach ($types as $key => $value)
			{
				if(strtolower($value) == '.'.$type)
				{
					$result = TRUE;
					$this->file_error[$title] = '';
					break;
				}else{
					$this->file_error[$title] = 'your file type is not allowed';
				}
			}
		}
		return $result;
	}

	private function getInput($is_array = true)
	{
		$input = array();
		foreach ($this->input as $key => $value)
		{
			$input[] = $value['text'];
		}
		if(!in_array('id', $input))
		{
			$input[] = 'id';
		}
		if(!$is_array)
		{
			$input = implode('`,`', $input);
			$input = '`'.$input.'`';
		}
		return $input;
	}

	private function getData()
	{
		$data = array();
		if($this->init == 'roll')
		{
			$this->data_model->orderBy($this->orderby['index'], $this->orderby['sort']);
			if(!empty($this->where))
			{
				$this->data_model->setWhere($this->where);
			}
			if(!empty($this->jointable))
			{
				$this->data_model->join($this->jointable['table'],$this->jointable['condition'], $this->jointable['field']);
			}
			$data = $this->data_model->get_data_list($this->table, $this->field, $this->getInput(), $this->limit);
		}else if($this->init == 'edit'){
			$data = $this->data_model->get_one_data($this->table, 'WHERE id = '.$this->id);
		}
		return $data;
	}

	public function getParam()
	{
		if(!empty($this->paramname))
		{
			$data = array();
			$data = $this->db->query('SELECT value FROM '.$this->table.' WHERE name = ?', $this->paramname)->row_array();
			return $data;
		}
	}

	public function form()
	{
		if(!empty($this->input))
		{
			$data = $this->getData();
			$message = array();
			if(!empty($_POST))
			{
				$message    = $this->action();
				$data       = $this->getData();
			}
			if(!empty($message))
			{
				msg($message['msg'],$message['alert']);
			}
			if($this->init == 'edit' || $this->init == 'param')
			{
				if($this->init == 'param')
				{
					if(!empty($this->param))
					{
						$name = $this->paramname;
						$data = json_decode($this->param['value'], 1);
					}else{
						$this->param = $this->getParam();
						$name = $this->paramname;
						$data = json_decode($this->param['value'], 1);
					}
				}
				$action = !empty($this->view) ? base_url($this->view).'/'.$this->id : '';
				?>
				<form method="post" action="<?php echo $action ?>" enctype="multipart/form-data" name="<?php echo $this->formName ?>" id="<?php echo $this->formName ?>">
					<div class="panel panel-default">
						<div class="panel panel-heading">
							<h4 class="panel-title">
								<?php
								if($this->init == 'edit')
								{
									if(!empty($this->edit_status))
									{
										echo !empty($this->id) ? 'Edit ' : 'Add ';
									}
									echo $this->heading;
								}else{
									echo $this->heading;
								}
								?>
							</h4>
						</div>
						<div class="panel panel-body">
							<?php
							if(empty($data))
							{
								$this->setData();
								$data = $this->data;
							}
							foreach ($this->input as $key => $value)
							{
								if($this->init == 'param')
								{
									if(!array_key_exists($value['text'], $data))
									{
										$data[$value['text']] = '';
									}
								}
								if(array_key_exists($value['text'], $data))
								{
									$field    = !empty($value['text']) ? $value['text'] : '';
									$label    = !empty($this->label[$field]) ? $this->label[$field] : $field;
									$required = !empty($this->required[$field]) ? $this->required[$field] : '';

									if(!empty($this->startCollapse))
									{
										if(@$this->startCollapse[$value['text']] == $value['text'])
										{
											$collapse_title = !empty($this->startCollapse['title'][$value['text']]) ? $this->startCollapse['title'][$value['text']] : '';
											$this->open_collapse($value['text'], @$collapse_title);
										}
									}
									switch($value['type'])
									{
										case 'text':
											include 'input/text.php';
											break;
										case 'button':
											include 'input/button.php';
											break;
										case 'password':
											include 'input/password.php';
											break;
										case 'plaintext':
											include 'input/plaintext.php';
											break;
										case 'textarea':
											include 'input/textarea.php';
											break;
										case 'checkbox':
											include 'input/checkbox.php';
											break;
										case 'radio':
											include 'input/radio.php';
											break;
										case 'dropdown':
											include 'input/dropdown.php';
											break;
										case 'upload':
										case 'image':
										case 'file':
											include 'input/upload.php';
											break;
										case 'uploads':
										case 'images':
										case 'files':
										case 'multifiles':
										case 'gallery':
											include 'input/uploads.php';
											break;
										case 'multiselect':
											include 'input/multiselect.php';
											break;
										case 'hidden':
											include 'input/hidden.php';
											break;
									}
									if(!empty($this->endCollapse))
									{
										if(@$this->endCollapse[$value['text']] == $value['text'])
										{
											$this->close_collapse();
										}
									}
								}else{
									echo '<b>unknown Column '.$value['text'].' in table '.$this->table.'</b><br>';
								}
							}
							?>
						</div>
						<div class="panel panel-footer">
							<!-- <button class="btn btn-default" onclick="window.history.back();" data-toggle="tooltip" title="go back"><i class="fa fa-arrow-left"></i></button> -->
							<?php
							echo form_button(array(
								'name'    => $this->formName,
								'id'      => 'submit',
								'value'   => 'true',
								'type'    => 'success',
								'content' => '<i class="fa fa-floppy-o"></i> submit',
								'class'   => 'btn btn-success'));
							echo form_button(array(
								'name'    => 'reset',
								'id'      => 'reset',
								'value'   => 'true',
								'type'    => 'reset',
								'content' => '<i class="fa fa-undo"></i> reset',
								'class'   => 'btn btn-warning'));
							?>
						</div>
					</div>
				</form>
				<?php
			}else if($this->init == 'roll')
			{
				$pagination = $data['pagination'];
				$data       = $data['data'];
				$pagination = !empty($data) ? $pagination : '';
				$message    = array();
				$page = !empty($_GET['page']) ? '?page='.$_GET['page'] : '';
				?>
				<h4 class="panel-title">
					<?php echo $this->heading;?>
				</h4>
				<br>
				<form method="post" action="<?php echo !empty($this->view) ? base_url($this->view).$page : ''; ?>" enctype="multipart/form-data" name="<?php echo $this->formName ?>" id="<?php echo $this->formName ?>">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped" table_name="<?php echo $this->table; ?>">
							<thead>
								<tr>
									<?php
									foreach ($this->input as $key => $value)
									{
										if(empty($data))
										{
											$this->setData();
											$data = $this->data;
										}
										if($value['type'] == 'order')
										{
											$max = $this->data_model->get_one($this->table, 'MAX('.$this->orderby['index'].')', 'WHERE '.$this->where);
										}
										if(array_key_exists($value['text'], $data[0]))
										{
											$field    = !empty($value['text']) ? $value['text'] : '';
											$label    = !empty($this->label[$field]) ? $this->label[$field] : $field;
											if($value['type'] == 'checkbox')
											{
												?>
												<th>
													<div class="checkbox">
														<label>
															<input id="selectAll<?php echo $label;?>" add="<?php echo $label; ?>" class="selectAll" type="checkbox"><?php echo ucwords($label) ?>
														</label>
													</div>
												</th>
												<?php
											}else{
												echo '<th>'.ucwords($label).'</th>';
											}
										}
									}
									if($this->edit == true)
									{
										?>
										<th>
											EDIT
										</th>
										<?php
									}
									if($this->delete == true)
									{
										?>
										<th>
											<div class="checkbox">
												<label>
													<input id="selectAllDel" type="checkbox">DELETE
												</label>
											</div>
										</th>
										<?php
									}
								 ?>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!empty($data))
								{
									foreach ($data as $dkey => $dvalue)
									{
										if(!empty($dvalue['id']))
										{
											?>
											<tr data-id="<?php echo $dvalue['id'] ?>">
												<?php
												foreach ($this->input as $ikey => $ivalue)
												{
													$field    = !empty($ivalue['text']) ? $ivalue['text'] : '';
													$label    = !empty($this->label[$field]) ? $this->label[$field] : $field;
													$required = !empty($ivalue['required']) ? $ivalue['required'] : '';
													$image    = !empty($this->image[$field]) ? $this->image[$field] : '';



													if(isset($dvalue[$ikey]))
													{
														echo '<td>';
															switch ($ivalue['type'])
															{
																case 'text':
																	include 'input/text.php';
																	break;
																case 'plaintext':
																	include 'input/plaintext.php';
																	break;
																case 'thumbnail':
																	include 'input/thumbnail.php';
																	break;
																case 'link':
																	include 'input/link.php';
																	break;
																case 'textarea':
																	include 'input/textarea.php';
																	break;
																case 'checkbox':
																	include 'input/checkbox.php';
																	break;
																case 'dropdown':
																	include 'input/dropdown.php';
																	break;
																case 'order':
																	include 'input/order.php';
																	break;
																case 'upload':
																case 'image':
																case 'file':
																	include 'input/upload.php';
																	break;
																case 'multiselect':
																	include 'input/multiselect.php';
																	break;
																case 'hidden':
																	include 'input/hidden.php';
																	break;
															}
														echo '</td>'  ;
													}
												}
												if($this->edit == true)
												{
													?>
													<td>
														<a href="<?php echo $this->edit_link.$dvalue['id'] ?>"> <span class="fa fa-pencil"></span></a>
													</td>
													<?php
												}
												if($this->delete == true)
												{
													?>
													<td>
														<div class="checkbox">
															<label>
																<input type="checkbox" class="del_check" name="del_row[]" value="<?php echo $dvalue['id']; ?>"> <span class="glyphicon glyphicon-trash"></span>
															</label>
														</div>
													</td>
													<?php
												}
												?>
											</tr>
											<?php
										}
									}
									$tot_col = count($this->input);
									foreach ($this->input as $inputkey => $inputvalue)
									{
										if($inputvalue['type'] == 'checkbox' || $inputvalue['type'] == 'text')
										{
											$tot_col--;
										}
									}
									if($this->edit == true)
									{
										$tot_col = $tot_col+1;
									}
									?>
									<tr>
										<td colspan="<?php echo $tot_col; ?>"><?php echo !empty($pagination) ? $pagination : ''; ?></td>
										<?php
										foreach ($this->input as $inputkey => $inputvalue)
										{
											if($inputvalue['type'] == 'checkbox' || $inputvalue['type'] == 'text')
											{
												$add_text = $inputvalue['type'] == 'text' ? 'Save ' : '';
												?>
												<td>
													<button type="submit" name="<?php echo $inputvalue['text'] ?>" value="1" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-floppy-saved"></span> <?php echo $add_text.' '.$inputvalue['text'] ?>
													</button>
												</td>
												<?php
											}
										}
										if($this->delete)
										{
											?>
											<td>
												<button type="<?php echo $this->delete_type ?>" name="delete_<?php echo $this->formName?>" value="1" class="btn btn-danger btn-sm">
													<span class="glyphicon glyphicon-trash"></span> DELETE
												</button>
											</td>
											<?php
										}
										?>
									</tr>
									<?php
								}
								?>
								<!-- <button class="btn btn-default" onclick="window.history.back();" data-toggle="tooltip" title="go back"><i class="fa fa-arrow-left"></i></button> -->
							</tbody>
						</table>
					</div>
				</form>
				<?php
			}
		}
	}

	public function action()
	{
		if(!empty($_POST))
		{
			if($this->init == 'edit' || $this->init == 'param')
			{
				$data    = array();
				$last_id = 0;
				if(!empty($_POST))
				{
					foreach ($_POST as $key => $value)
					{
						if(empty($_POST['del_row']) && ($this->init == 'edit'))
						{
							if(is_array($value))
							{
								$_POST[$key] = ','.implode(',',$value).',';
							}
						}
					}
					if(!empty($_POST[$this->formName]))
					{
						unset($_POST[$this->formName]);
						unset($_POST[$this->security->get_csrf_token_name()]);
						if(isset($_POST['password']))
						{
							if(empty($this->encrypt))
							{
								$_POST['password'] = $_POST['password'];
							}else{
								$_POST['password'] = encrypt($_POST['password']);
							}
						}
						if(!empty($this->table))
						{
							$data['msg']   = 'Data Failed to Save';
							$data['alert'] = 'danger';

							$upload  = array();
							$uploads = array();
							$title   = '';

							foreach ($this->input as $key => $value)
							{
								$upload_type = array('upload','image','file');
								$uploads_type = array('uploads','images','files','gallery','multifiles');
								if(in_array($value['type'], $upload_type))
								{
									$upload[] = $value['text'];
								}
								if(in_array($value['type'], $uploads_type))
								{
									$uploads[] = $value['text'];
								}
								if($value['text'] != 'csrf_esg')
								{
									$_POST[$value['text']] = @$_POST[$value['text']];
								}
							}

							foreach ($this->input as $key => $value)
							{
								if($value['type'] == 'text')
								{
									$title = $value['text'];
									break;
								}
							}
							if($this->init == 'edit')
							{
								if($this->data_model->set_data($this->table, $this->id, $_POST))
								{
									$data['msg']   = 'Data Saved Successfully';
									$data['alert'] = 'success';
								}
							}else if($this->init == 'param')
							{
								$data_param = array();
								if(!empty($_POST))
								{
									$data_param['name'] = $this->paramname;
									$data_param['value'] = json_encode($_POST);
								}
								if($this->set_param($this->table, $this->paramname, $data_param))
								{
									$data['msg']   = 'Data Saved Successfully';
									$data['alert'] = 'success';
								}
							}
							$last_id = $this->db->insert_id();
							if(!empty($upload))
							{
								$i = 0;
								$dir_image = '';
								if($this->init == 'edit')
								{
									$dir_image = !empty($this->id) ? $this->id : $last_id;
								}else if($this->init == 'param'){
									$dir_image = $this->paramname;
								}
								foreach ($upload as $u_key => $u_value)
								{
									$_POST[$u_value] = !empty($_POST[$title]) ? $u_value.'_'.str_replace(' ','_',$_POST[$title]) : $u_value.'_image';
									if(!empty($_FILES[$upload[$i]]['name']) && empty($_FILES[$upload[$i]]['error']))
									{
										$module = !empty($this->table) ? 'modules/'.$this->table : 'uploads';
										$dir = FCPATH.'images/'.$module.'/'.$dir_image.'/';
										if(!is_dir($dir))
										{
											mkdir($dir, 0777,1);
										}
										$ext = pathinfo($_FILES[$upload[$i]]['name']);
										if($this->check_type($ext['extension'],$u_value))
										{
											$file_name = $_POST[$u_value].'_'.time().'.'.$ext['extension'];
											if($this->init == 'edit')
											{
												$file_name_exist = $this->data_model->get_one($this->table, $u_value);
											}else if($this->init == 'param')
											{
												$data_image      = json_decode($data_param['value'],1);
												$file_name_exist = $data_image[$u_value];
											}
											if(empty($_POST[$u_value]))
											{
												foreach(glob($dir.'/'.$u_value.'_*') as $file)
												{
													unlink($file);
												}
											}else if(empty($file_name_exist))
											{
												foreach(glob($dir.'/'.$u_value.'_*') as $file)
												{
													unlink($file);
												}
											}
											copy($_FILES[$upload[$i]]['tmp_name'], $dir.$file_name);
											if($this->init == 'edit')
											{
												$update_file = array($u_value => $file_name);
												$this->data_model->set_data($this->table, $dir_image, $update_file);
											}else if($this->init == 'param'){
												foreach ($_POST as $dp_key => $dp_value)
												{
													if($dp_key=='image' || preg_match('~_image~', $dp_key))
													{
														$_POST[$u_value] = $file_name;
													}
												}
												$data_param['value'] = json_encode($_POST);
												$data_param['name']  = $dir_image;
												$this->set_param($this->table, $dir_image, $data_param);
											}
										}
									}
									$i++;
								}
							}
							if(!empty($uploads))
							{
								$i = 0;
								$dir_image = '';
								if($this->init == 'edit')
								{
									$dir_image = !empty($this->id) ? $this->id : $last_id;
								}else if($this->init == 'param'){
									$dir_image = $this->paramname;
								}
								foreach ($uploads as $u_key => $u_value)
								{
									$_POST[$u_value] = !empty($_POST[$title]) ? $u_value.'_'.str_replace(' ','_',$_POST[$title]) : 'image';
									$files_ready     = true;

									foreach ($_FILES[$uploads[$i]]['error'] as $err_key => $err_value )
									{
										if(!empty($err_value))
										{
											$files_ready = false;
										}
									}
									if($files_ready)
									{
										$module = !empty($this->table) ? 'modules/'.$this->table : 'uploads';
										$dir = FCPATH.'images/'.$module.'/gallery'.'/'.$dir_image.'/';
										if(!is_dir($dir))
										{
											mkdir($dir, 0777,1);
										}
										$exts = array();
										$files_name = array();
										foreach ($_FILES[$uploads[$i]]['name'] as $n_key => $n_value)
										{
											$exts[$n_key]       = pathinfo($n_value);
											$files_name[$n_key] = $_POST[$u_value].'_'.$n_key.'_'.time().'.'.$exts[$n_key]['extension'];
										}
										$files_upload = array();
										$j = 0;
										foreach ($_FILES[$uploads[$i]]['tmp_name'] as $n_key => $n_value)
										{
											$files_upload[$j]['tmp'] = $n_value;
											$j++;
										}
										$j = 0;
										foreach ($files_name as $f_key => $f_value)
										{
											$files_upload[$j]['name'] = $f_value;
											$j++;
										}
										if(!empty($files_upload))
										{
											foreach(glob($dir.'/'.$u_value.'_*') as $file)
											{
												unlink($file);
											}
										}
										foreach ($files_upload as $fu_key => $fu_value)
										{
											copy($fu_value['tmp'], $dir.$fu_value['name']);
										}
										$file_name = json_encode($files_name);
										if($this->init == 'edit')
										{
											$update_file = array($u_value => $file_name);
											$this->data_model->set_data($this->table, $dir_image, $update_file);
										}else if($this->init == 'param')
										{
											foreach ($_POST as $dp_key => $dp_value)
											{
												if($dp_key=='image')
												{
													$_POST[$dp_key] = $file_name;
												}
											}
											$data_param['value'] = json_encode($_POST);
											$data_param['name']  = $dir_image;
											$this->set_param($this->table, $dir_image, $data_param);
										}
									}
									$i++;
								}
							}
						}else{
							$data['msg']   = 'Table Undefined';
							$data['alert'] = 'danger';
						}
					}else{
						// $data['msg']   = 'Please Press Submit Button to Save';
						// $data['alert'] = 'warning';
					}
				}
				return $data;
			}else if($this->init == 'roll')
			{
				$current_data = $this->getdata();
				$current_data = $current_data['data'];
				$data = array();
				if(!empty($this->table))
				{
					foreach ($this->input as $inputkey => $inputvalue)
					{
						if($inputvalue['type'] == 'checkbox')
						{
							if(!empty($_POST[$inputvalue['text']]))
							{
								$data_checkbox = array();
								$currentdatai = 0;
								foreach ($current_data as $currnetdatakey => $currentdatavalue)
								{
									$data_checkbox[$currentdatai] = $currentdatavalue['id'];
									$currentdatai++;
								}
								if(!empty($data_checkbox))
								{
									$data['msg']   = 'No Data Selected to '.$inputvalue['text'];
									$data['alert'] = 'success';
									foreach ($data_checkbox as $dc_key => $dc_id)
									{
										$data['msg']   = 'Data '.ucfirst($inputvalue['text']).' Successfully';
										$data['alert'] = 'success';

										if(!empty($_POST[$inputvalue['text'].'_row']))
										{
											if(in_array($dc_id, $_POST[$inputvalue['text'].'_row']))
											{
												$this->db->update($this->table, array($inputvalue['text']=>1), 'id = '.$dc_id);
											}else{
												$this->db->update($this->table, array($inputvalue['text']=>0), 'id = '.$dc_id);
											}
										}else{
											$this->db->update($this->table, array($inputvalue['text']=>0), 'id = '.$dc_id);
										}
									}
								}
							}
						}
						if($inputvalue['type'] == 'text')
						{
							if(!empty($_POST[$inputvalue['text']]))
							{
								$data_text = array();
								$currentdatai = 0;
								foreach ($current_data as $currnetdatakey => $currentdatavalue)
								{
									$data_text[$currentdatai] = $currentdatavalue['id'];
									$currentdatai++;
								}
								if(!empty($data_text))
								{
									$data['msg']   = 'No Data Selected to '.$inputvalue['text'];
									$data['alert'] = 'success';
									foreach ($data_text as $dt_key => $dt_id)
									{
										$data['msg']   = 'Data '.ucfirst($inputvalue['text']).' Successfully';
										$data['alert'] = 'success';

										if(!empty($_POST[$inputvalue['text'].'_row']))
										{
											$this->db->update($this->table, array($inputvalue['text']=>$_POST[$inputvalue['text'].'_row'][$dt_id]), 'id = '.$dt_id);
										}
									}
								}
							}
						}
					}
					if(!empty($_POST['delete_'.$this->formName]))
					{
						$data['msg']   = 'No Data Selected to Delete';
						$data['alert'] = 'success';
						if(!empty($_POST['del_row']))
						{
							$data['msg']   = 'Data Deleted Successfully';
							$data['alert'] = 'success';
							$this->data_model->del_data($this->table, $_POST['del_row']);
						}
					}
				}else{
					$data['msg'] = 'Table Undefined';
					$data['alert'] = 'danger';
				}
				return $data;
			}
		}
	}

	/*=====================================================
	 * $data[]  = array(
				'id'      => $id
			, 'par_id'  => $par_id
			, 'title'   => $title);
	 *====================================================*/
	function array_path($data, $par_id = 0, $separate = ' / ', $prefix = '', $load_parent = '')
	{
		$output = array();
		foreach((array)$data AS $dt)
		{
			if($dt['par_id'] == $par_id)
			{
				if(empty($load_parent))
				{
					$text = ($par_id==0) ? $prefix.$dt['title'] : $prefix.$separate.$dt['title'];
					$output[$dt['id']] = $text;
				}else{
					$output[$dt['id']] = ($par_id==0) ? $prefix.$dt['title'] : $prefix.$separate.$dt['title'];
					$text = ($par_id==0) ? $prefix.$load_parent : $prefix.$separate.$load_parent;
				}
				$r = $this->array_path($data, $dt['id'], $separate, $text, $load_parent);
				if(!empty($r)) {
					foreach($r AS $i => $j)
						$output[$i] = $j;
				}
			}
		}
		return $output;
	}
	function createOption($arr, $select='')
	{
		$output = '';
		$valueiskey = $check_first = false;
		foreach((array)$arr AS $key => $dt){
			if(is_array($dt)){
				list($value, $caption) = array_values($dt);
				if(empty($caption)) $caption = $value;
			}else{
				if(!$check_first) {
					if((is_numeric($key) && $key != 0)
					|| (is_string($key) && !is_numeric($key))) {
						$valueiskey = true;
					}
					$check_first = true;
				}
				if(empty($dt) && !empty($key)) $dt = $key;
				$value = $valueiskey ? $key : $dt;
				$caption = $dt;
			}
			if(isset($select)){
				if(is_array($select)) $selected = (in_array($value, $select)) ? ' selected="selected"':'';
				else    $selected = ($value==$select) ? ' selected="selected"':'';
			}else{
				$selected = '';
			}
			$output .= "<option value=\"$value\"$selected>$caption</option>";
		}
		return $output;
	}
}