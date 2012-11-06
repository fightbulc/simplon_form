<?php

	class __Form
	{
		public static $conf;
		public static $skeleton;
		public static $rules;
		public static $hookup;
		public static $release;


		// ##########################################


		public static function factory($form_definitions_file, $initial_values = '')
		{
			// system based
			if(strpos($form_definitions_file, 'system::') !== FALSE)
			{
				$form_definitions_file = PATH_SYSTEM.'/forms/'.str_replace('system::', '', $form_definitions_file);
			}

			// app based
			else
			{
				$form_definitions_file = PATH_APP.'/forms/'.$form_definitions_file;
			}

			Form::$skeleton = array();
			Form::$rules    = array();
			Form::$hookup   = array();
			Form::$release  = array();
			Form::$conf     = Read::file($form_definitions_file.'_form.php', $initial_values);

			// cross site request forgery
			Form::csrf();

			// run through fields
			foreach(Form::$conf['fields'] as $field_id => $field_setup)
			{
				// global mark setting
				if(Form::$conf['config']['mark'] !== TRUE)
				{
					$field_setup['mark'] = FALSE;
				}

				if(empty($field_setup['group']))
				{
					// set default group
					$field_setup['group'] = 'default';
				}

				// set field label
				$form['labels'][$field_id] = $field_setup['label'];

				// handle read only fields
				if($field_setup['metas']['readonly'] === TRUE)
				{
					$field_setup['attr']['disabled'] = 'disabled';
					$field_setup['attr']['class'] = 'readonly '.$field_setup['attr']['class'];
					$field_setup['rules'] = array();
				}

				// set defined and posted value ... and clean da shizzle
				$field_setup['defined_value'] = $field_setup['value'];
				$field_setup['value'] = Form::get_value($field_id, $field_setup);

				// handle hookups (turn optional fields into required)
				if( ! empty($field_setup['metas']['hookup']))
				{
					foreach($field_setup['metas']['hookup'] as $hu_val => $hu_fields)
					{
						if($field_setup['value'] == $hu_val)
						{
							foreach($hu_fields as $hu_field_id)
							{
								Form::$hookup[] = $hu_field_id;
							}
						}

						Session::cache('jsinline', 'system.form.hookup("'.$field_id.'", "'.$hu_val.'", ["'.Format::array_join($hu_fields, '","').'"])');
					}
				}

				if(in_array($field_id, Form::$hookup))
				{
					$field_setup['rules'] = array('required');
				}

				// handle releases (turn required fields into optional)
				if( ! empty($field_setup['metas']['release']))
				{
					foreach($field_setup['metas']['release'] as $rl_val => $rl_fields)
					{
						if($field_setup['value'] == $rl_val)
						{
							foreach($rl_fields as $rl_field_id)
							{
								Form::$release[] = $rl_field_id;
							}
						}

						Session::cache('jsinline', 'system.form.release("'.$field_id.'", "'.$rl_val.'", ["'.Format::array_join($rl_fields, '","').'"])');
					}
				}

				if(in_array($field_id, Form::$release))
				{
					$field_setup['rules'] = array();
				}

				// if no rules array, set it as an array
				if(empty($field_setup['rules']))
				{
					$field_setup['rules'] = array();
				}

				// create html field widget
				$widget = Form::$field_setup['type']($field_id, $field_setup);

				// cache field type & value
				Form::$skeleton['types'][$field_id] = $field_setup['type'];
				Form::$skeleton['values'][$field_id] = $field_setup['value'];

				// cache field rules
				Form::rules($field_id, $field_setup['rules']);
				Form::$skeleton['rules'] = Form::$rules;

				$is_single_buttonset = FALSE;

				// no label for single checkbox/radio fields
				if(in_array($field_setup['type'], array('radio', 'checkbox')) && empty($field_setup['options']))
				{
					$is_single_buttonset = $field_setup['metas']['buttonset'] ? TRUE : FALSE;
				}

				// set labels
				Form::$skeleton['labels'][$field_id] = $field_setup['label'];

				if( ! isset($field_setup['metas']['label']))
				{
					$field_setup['metas']['label'] = TRUE;
				}

				// handle label
				if($field_setup['metas']['label'] === TRUE && ! $is_single_buttonset && ! ($field_setup['type'] == 'upload' || $field_setup['type'] == 'hidden' || $field_setup['type'] == 'date' && ($field_setup['metas']['range'] === TRUE || $field_setup['metas']['time'])))
				{
					$label = Form::label($field_id, $field_setup['label'].':'.Form::field_mark($field_id, $field_setup));
				}
				else
				{
					$label = NULL;
				}

				// set form view
				Form::$skeleton['view'][$field_id] = array(
					'label'		=> $label,
					'widget'	=> $widget,
					'note'		=> '<div class="form_field_note">'.$field_setup['note'].'</div>',
				);

				// group input results
				if(Form::is_submitted())
				{
					$multi = Format::string_explode(str_replace(']', '', $field_id), '[');

					$data = array(
						'old' => Format::string_clean($field_setup['defined_value']),
						'set' => Format::string_clean($field_setup['value']),
					);

					// if multidimensional
					if(count($multi) > 1)
					{
						if(count($multi) == 1) Form::$skeleton['data'][$field_setup['group']][$multi[0]] = $data;
						if(count($multi) == 2) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]] = $data;
						if(count($multi) == 3) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]] = $data;
						if(count($multi) == 4) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]] = $data;
						if(count($multi) == 5) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]] = $data;
						if(count($multi) == 6) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]][$multi[5]] = $data;
						if(count($multi) == 7) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]][$multi[5]][$multi[6]] = $data;
						if(count($multi) == 8) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]][$multi[5]][$multi[6]][$multi[7]] = $data;
						if(count($multi) == 9) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]][$multi[5]][$multi[6]][$multi[7]][$multi[8]] = $data;
						if(count($multi) == 10) Form::$skeleton['data'][$field_setup['group']][$multi[0]][$multi[1]][$multi[2]][$multi[3]][$multi[4]][$multi[5]][$multi[6]][$multi[7]][$multi[8]][$multi[9]] = $data;
					}

					// if one dimension
					else
					{
						Form::$skeleton['data'][$field_setup['group']][$field_id] = $data;
					}
				}

				// helper: set most used view structure
				Form::$skeleton['group'][$field_id] =
					( ! empty($field_setup['note']) ? Form::$skeleton['view'][$field_id]['note'] : NULL)
					.( ! empty(Form::$skeleton['view'][$field_id]['label']) ? Form::$skeleton['view'][$field_id]['label'].'<br>' : NULL)
					.'<error></error>'
					.Form::$skeleton['view'][$field_id]['widget']
				;
			}

			// if we have rules validate the form as soon its submitted
			if(Form::is_submitted() && ! empty(Form::$rules))
			{
				$input = Validation::factory(Form::$skeleton);
				Form::$skeleton['valid'] = $input->valid();

				if( ! Form::$skeleton['valid'])
				{
					Form::$skeleton['validation_failed'] = '<a name="form"></a><div class="form_validation_failed">'.(empty(Form::$conf['config']['error']) ? Lang::get('system::form_validation_failed') : Form::$conf['config']['error']).'</div>';
					foreach($input->error_messages as $field_id => $error)
					{
						if(empty(Form::$skeleton['view'][$field_id]['label']))
						{
							$error = '<br>'.$error;
						}

						Form::$skeleton['group'][$field_id] = str_replace('<error></error>', $error, Form::$skeleton['group'][$field_id]);
						Form::$skeleton['error'][$field_id] = $error;
					}
				}
			}

			// at last create basic fields
			Form::$skeleton['view']['open']   = Form::open(Form::$conf['config']['submit']['url'], array('id' => Form::$conf['config']['id'], 'method' => ! empty(Form::$conf['config']['method']) ? Form::$conf['config']['method'] : 'post', 'upload' => Form::$conf['config']['upload'] ? TRUE : FALSE));
			Form::$skeleton['view']['submit'] = Form::submit('submit', ( ! empty(Form::$conf['config']['submit']['label']) ? Form::$conf['config']['submit']['label'] : Lang::get('system::form_submit_string')), array('class' => 'button')).( ! empty(Form::$conf['config']['cancel']['url']) ? '<span class="form_cancel_container"><span class="form_cancel_prestring">'.Lang::get('system::form_cancel_prestring').'</span>'.Html::anchor(Form::$conf['config']['cancel']['url'], ( ! empty(Form::$conf['config']['cancel']['label']) ? Form::$conf['config']['cancel']['label'] : Lang::get('system::form_cancel_string')), array('class' => 'form_cancel')).'</span>' : NULL);

			// secure post form
			$secure_me = NULL;

			if(Form::$conf['config']['method'] != 'get')
			{
				$secure_me = Form::hidden('csrf', array('value' => session_id())).Form::hidden('fuid', array('value' => empty($_POST['fuid']) ? Security::create_uid() : $_POST['fuid']));
			}

			Form::$skeleton['view']['close']  = $secure_me.Form::close();

			return (object) Form::$skeleton;
		}


		// ##########################################


		/**
		 * Generates an opening HTML form tag.
		 *
		 */
		public static function open($url = '', $attr = NULL)
		{
			$url = Format::url($url);

			// Add the form action to the attributes
			$attr['action'] = $url;

			// Set character set
			$attr['accept-charset'] = 'utf-8';

			if ( ! isset($attr['method']))
			{
				// Use POST method
				$attr['method'] = 'post';
			}

			if($attr['upload'])
			{
				// enable upload
				$attr['enctype'] = 'multipart/form-data';
			}

			$aname_tag = Form::$conf['config']['filterform'] !== TRUE ? '<a name="form"></a>' : NULL;

			return $aname_tag.'<form id="'.$attr['id'].'" action="'.$attr['action'].($attr['method'] != 'get' ? (Form::$conf['config']['filterform'] !== TRUE ? '#form' : NULL) : NULL).'" method="'.$attr['method'].'" accept-charset="'.$attr['accept-charset'].'"'.($attr['enctype'] ? ' enctype="'.$attr['enctype'].'"' : NULL).'>';
		}


		// ##########################################


		/**
		 * Creates the closing form tag.
		 *
		 */
		public static function close()
		{
			return '</form>';
		}


		// ##########################################


		/**
		 * Cross site request forgery
		 *
		 */
		public static function csrf()
		{
			if(Form::is_submitted() && $_POST['csrf'] != session_id())
			{
				unset($_POST);
			}
		}


		// ##########################################


		/**
		 * Checks if a form was submitted (submit_add, submit_edit, submit)
		 *
		 */
		public static function is_submitted()
		{
			return empty($_POST['submit']) ? FALSE : TRUE;
		}


		// ##########################################


		/**
		 * Returns the value of a $_POST variable
		 *
		 */
		public static function get_value($field_id, $field_setup = FALSE)
		{
			$array_field = Format::string_explode(str_replace(']', '', $field_id), '[');

			// if multidimensional
			if(count($array_field) > 1)
			{
				$post_value = NULL;

				foreach($array_field as $key)
				{
					if(empty($post_value))
					{
						$post_value = $_POST[$key];
					}
					elseif($field_setup['type'] != 'date_select')
					{
						$post_value = $post_value[$key];
					}
				}

				// field type = date
				if($field_setup['type'] == 'date_select' && Read::array_search('day', $post_value, 'key::in') && Read::array_search('month', $post_value, 'key::in') && Read::array_search('year', $post_value, 'key::in'))
				{
					$post_value = Format::array_join(array($post_value['year'], $post_value['month'], $post_value['day']), '-');
				}
			}

			// one dimension
			else
			{
				// blisr fields
				if($field_setup['type'] == 'blisr')
				{
					$post_value = Form::multistring_post_values($_POST[Form::blisr_unique_id($field_id, TRUE)]);
				}

				// autocomplete multiple fields
				elseif($field_setup['type'] == 'autocomplete' && $field_setup['metas']['multiple'] === TRUE)
				{
					$post_value = Form::multistring_post_values($_POST[$field_id.'_autocomplete_receiver']);
				}

				// handle date fields
				elseif($field_setup['type'] == 'date')
				{
					if($field_setup['metas']['range'] === TRUE)
					{
						if(empty($_POST[$field_id.'_from']) && $field_setup['metas']['readonly'] !== TRUE)
						{
							$_POST[$field_id.'_from'] = Format::date(Base::get_date(), 'human');
						}

						if(empty($_POST[$field_id.'_to']) && $field_setup['metas']['readonly'] !== TRUE)
						{
							$_POST[$field_id.'_to'] = Format::date(Base::get_date(), 'human');
						}

						$post_value = array(Format::date($_POST[$field_id.'_from'], 'db'), Format::date($_POST[$field_id.'_to'], 'db'));

						if($field_setup['metas']['time'])
						{
							if(empty($_POST[$field_id.'_from_time']))
							{
								$_POST[$field_id.'_from_time'] = '12:00';
							}

							if(empty($_POST[$field_id.'_to_time']))
							{
								$_POST[$field_id.'_to_time'] = '12:00';
							}

							$post_value[0] = Format::array_join(array($post_value[0], $_POST[$field_id.'_from_time']), ' ');
							$post_value[1] = Format::array_join(array($post_value[1], $_POST[$field_id.'_to_time']), ' ');
						}
					}
					else
					{
						if(empty($_POST[$field_id]) && $field_setup['metas']['readonly'] !== TRUE)
						{
							$_POST[$field_id] = Format::date(Base::get_date(), 'human');
						}

						$post_value = Format::date($_POST[$field_id], 'db');

						if($field_setup['metas']['time'])
						{
							if(empty($_POST[$field_id.'_time']))
							{
								$_POST[$field_id.'_time'] = '12:00';
							}

							$post_value = Format::array_join(array($post_value, $_POST[$field_id.'_time']), ' ');
						}
					}
				}

				// handle phone numbers
				elseif($field_setup['type'] == 'phone')
				{
					$post_value = preg_replace('#^\+#', '00', $_POST[$field_id]);
					$post_value = preg_replace('#\D+#', '', $post_value);
				}

				// handle url
				elseif($field_setup['type'] == 'url')
				{
					$post_value = Format::string_trim($_POST[$field_id], '/');
				}

				// all others
				else
				{
					$post_value = $_POST[$field_id];
				}
			}

			$value = Form::is_submitted() && ! $field_setup['metas']['readonly'] ? $post_value : $field_setup['value'];

			// add on values for special fields
			if(Form::is_submitted() && $field_setup['type'] == 'url' && ! empty($value) && strpos($value, '://') === FALSE)
			{
				$value = 'http://'.$value;
			}

			return ! is_array($value) ? trim($value) : $value;
		}


		// ##########################################


		/**
		 * Prepare validation rules for Validation class
		 *
		 */
		public static function rules($field_id, $array)
		{
			if( ! empty($array))
			{
				foreach($array as $key => $rule)
				{
					if($key === 'callback')
					{
						$params = $rule;
						$rule   = $key;

						/*
							call callback via ajax blur event:
							saves the user submit hit, isnt that something, eh?
						*/

						$method = Format::array_keys($params, TRUE);

						if(empty($params[$method]['args']))
						{
							$params[$method]['args'] = array();
						}
						else
						{
							foreach($params[$method]['args'] as $callback_args_key => $callback_args_value)
							{
								$params[$method]['args'][$callback_args_key] = (string) $callback_args_value;
							}
						}

						if(empty($params[$method]['crystal']))
						{
							$params[$method]['crystal'] = array();
						}

						Session::cache('jsinline', "system.form.callback.init(".json_encode(array('field_id' => $field_id, 'url' => Format::url($params[$method]['url']), 'args' => $params[$method]['args'], 'crystal' => $params[$method]['crystal'])).")");
					}
					else
					{
						if(strpos($rule, '::') !== FALSE)
						{
							list($rule, $params) = Format::string_explode($rule);
						}

						$params = array($params);
					}

					Form::$rules[$field_id][$rule] = $params;
				}
			}
		}


		// ##########################################


		/**
		 * Creates a form label.
		 *
		 */
		public static function label($field_id, $label = '', $attr = '')
		{
			$attr['class'] = 'form_label '.$attr['class'];

			if($label == '')
			{
				// Use the field_id as text
				$label = ucwords(str_replace('_', ' ', $field_id));
			}

			// Set the label target
			$attr['for'] = str_replace('[', '_', str_replace(']', '', $field_id));

			return '<label'.HTML::attributes($attr).'>'.$label.'</label>';
		}


		// ##########################################


		public static function field_mark($field_id, $field_setup)
		{
			return '<small id="'.$field_id.'_mark" class="form_field_mark_'.( ! in_array('required', $field_setup['rules']) && $field_setup['mark'] !== FALSE ? 'show' : 'hide').'">'.($field_setup['metas']['readonly'] === TRUE ? Lang::get('system::form_readonly_mark') : Lang::get('system::form_optional_mark')).'</small>';
		}


		// ##########################################


		/**
		 * In case an array name is passed along
		 *
		 */
		public static function valid_attr_id($field_id)
		{
			return str_replace(' ', '_', str_replace('[', '_', str_replace(']', '', $field_id)));
		}


		// ##########################################


		public static function set_multi_values($values)
		{
			return join('---', $values);
		}


		// ##########################################


		public static function get_multi_values($values)
		{
			return Format::string_explode($values, '---');
		}


		// ##########################################


		/**
		 * Creates a form submit field.
		 *
		 */
		public static function submit($field_id, $value)
		{
			$field_setup['attr']['type']  = 'submit';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $value;
			$field_setup['attr']['class'] = 'button';

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function button($field_id, $field_setup)
		{
			$field_setup['attr']['type']  = 'button';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $field_setup['value'];
			$field_setup['attr']['class'] = 'button '.$field_setup['attr']['class'];

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function hidden($field_id, $field_setup)
		{
			$field_setup['attr']['type']  = 'hidden';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $field_setup['value'];

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function input($field_id, $field_setup)
		{
			$field_setup['attr']['type']        = 'text';
			$field_setup['attr']['id']          = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']        = $field_id;
			$field_setup['attr']['value']       = Format::string_htmlspecialchars($field_setup['value']);
			$field_setup['attr']['class']       = 'singleline_textfield '.$field_setup['attr']['class'];
			$field_setup['attr']['placeholder'] = $field_setup['placeholder'];

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function password($field_id, $field_setup)
		{
			$field_setup['attr']['type']  = 'password';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $field_setup['value'];
			$field_setup['attr']['class'] = 'singleline_textfield '.$field_setup['attr']['class'];

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function textarea($field_id, $field_setup)
		{
			$field_setup['attr']['id']          = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']        = $field_id;
			$field_setup['attr']['class']       = 'multiline_textfield '.$field_setup['attr']['class'];
			$field_setup['attr']['placeholder'] = $field_setup['placeholder'];

			return '<textarea'.Html::attributes($field_setup['attr']).'>'.$field_setup['value'].'</textarea>';
		}


		// ##########################################


		public static function select($field_id, $field_setup, $raw = FALSE)
		{
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['class'] = 'singleline_selectfield '.$field_setup['attr']['class'];

			if($raw === FALSE)
			{
				$classes = $field_setup['attr']['class'];
				unset($field_setup['attr']['class']);
			}

			$options = array();

			foreach($field_setup['options'] as $k => $v)
			{
				// optgroups
				if(is_array($v))
				{
					$optgroup_options = array();

					foreach($v as $sk => $sv)
					{
						$selected = NULL;

						if("$sk" == $field_setup['value'])
						{
							$selected = ' selected';
						}

						$optgroup_options[] = '<option value="'.$sk.'"'.$selected.'>'.$sv.'</option>';
					}

					$options[] = '<optgroup label="'.$k.'">'.Format::array_join($optgroup_options).'</optgroup>';
				}

				// options only
				else
				{
					$selected = NULL;

					if("$k" == $field_setup['value'])
					{
						$selected = ' selected';
					}

					$options[] = '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
				}
			}

			return $raw === TRUE ? '<select'.Html::attributes($field_setup['attr']).'>'.Format::array_join($options).'</select>' : '<div class="'.$classes.'"><select'.Html::attributes($field_setup['attr']).'>'.Format::array_join($options).'</select></div>';
		}


		// ##########################################


		public static function checkbox($field_id, $field_setup)
		{
			$field_setup['attr']['type']  = 'checkbox';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = 1;
			$field_setup['attr']['class'] = 'checkboxfield '.($field_setup['metas']['buttonset'] !== TRUE && $field_setup['metas']['button'] === TRUE ? 'form_button ' : NULL).$field_setup['attr']['class'];

			// multiple fields
			if( ! empty($field_setup['options']))
			{
				$checkbox_options = array();

				// set a default field template
				if(empty($field_setup['tmpl']))
				{
					if($field_setup['metas']['buttonset'] !== TRUE)
					{
						$field_setup['tmpl'] = '<div class="form_multiple_checkbox_radio_item">%f%l</div>';
						$label_width = NULL;
					}
					else
					{
						$field_setup['tmpl'] = '%f%l';
						$label_width = 'width:'.(100 / count($field_setup['options'])).'%';
					}
				}

				$post_values = Form::get_value($field_id);

				foreach($field_setup['options'] as $rk => $rlabel)
				{
					$field_setup['attr']['id']      = Form::valid_attr_id($field_id.'_'.$rk);
					$field_setup['attr']['name']    = $field_id.'['.$rk.']';
					$field_setup['attr']['value']   = "$rk";

					// include "checked" attribute if element was selected
					if($post_values[$rk] === "$rk" || ( ! Form::is_submitted() && is_array($field_setup['value']) && in_array($rk, $field_setup['value'])))
					{
						$field_setup['attr']['checked'] = 'checked';
					}
					else
					{
						unset($field_setup['attr']['checked']);
					}

					$checkbox_options[] = str_replace('%f', '<input'.Html::attributes($field_setup['attr']).'>', str_replace('%l', Form::label($field_setup['attr']['id'], $rlabel, array('class' => 'form_label_multiple'.($field_setup['metas']['buttonset'] !== TRUE && $field_setup['metas']['button'] === TRUE ? ' form_label_button' : NULL), 'style' => $label_width)), $field_setup['tmpl']));
				}

				$widget = '<div class="form_multiple_checkbox_radio_container clearfix'.($field_setup['metas']['buttonset'] === TRUE ? ' form_buttonset' : NULL).'">'.Format::array_join($checkbox_options).'</div>';
			}

			// single field
			else
			{
				if(Form::get_value($field_id) == 1 || ( ! Form::is_submitted() && $field_setup['value'] == 1))
				{
					$field_setup['attr']['checked'] = 'checked';
				}

				$widget = '<input'.Html::attributes($field_setup['attr']).'>';

				if($field_setup['metas']['buttonset'] === TRUE)
				{
					$widget = '<div class="form_buttonset form_single_buttonset">'.Form::label($field_id, $field_setup['label']).$widget.'</div>';
				}
			}

			return $widget;
		}


		// ##########################################


		public static function radio($field_id, $field_setup)
		{
			$field_setup['attr']['type']  = 'radio';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $field_setup['value'];
			$field_setup['attr']['class'] = 'radiofield '.($field_setup['metas']['buttonset'] !== TRUE && $field_setup['metas']['button'] === TRUE ? 'form_button ' : NULL).$field_setup['attr']['class'];

			// radio options
			if( ! empty($field_setup['options']))
			{
				$radio_options = array();

				// set a default field template
				if(empty($field_setup['tmpl']))
				{
					if($field_setup['metas']['buttonset'] !== TRUE)
					{
						$field_setup['tmpl'] = '<div class="form_multiple_checkbox_radio_item">%f%l</div>';
						$label_width = NULL;
					}
					else
					{
						$field_setup['tmpl'] = '%f%l';
						$label_width = 'width:'.(100 / count($field_setup['options'])).'%';
					}
				}

				$post_value = Form::get_value($field_id);

				foreach($field_setup['options'] as $rk => $rlabel)
				{
					$field_setup['attr']['id']      = Form::valid_attr_id($field_id.'_'.$rk);
					$field_setup['attr']['value']   = "$rk";

					// include "checked" attribute if element was selected

					if($post_value === "$rk" || ( ! Form::is_submitted() && $field_setup['value'] === "$rk"))
					{
						$field_setup['attr']['checked'] = 'checked';
					}
					else
					{
						unset($field_setup['attr']['checked']);
					}

					$radio_options[] = str_replace('%f', '<input'.Html::attributes($field_setup['attr']).'>', str_replace('%l', Form::label($field_setup['attr']['id'], $rlabel, array('class' => 'form_label_multiple'.($field_setup['metas']['buttonset'] !== TRUE && $field_setup['metas']['button'] === TRUE ? ' form_label_button' : NULL), 'style' => $label_width)), $field_setup['tmpl']));
				}

				$widget = '<div class="form_multiple_checkbox_radio_container clearfix'.($field_setup['metas']['buttonset'] === TRUE ? ' form_buttonset' : NULL).'">'.Format::array_join($radio_options).'</div>';
			}

			return $widget;
		}


		// ##########################################


		public static function upload($field_id, $field_setup)
		{
			Form::$conf['config']['upload'] = TRUE;

			$field_setup['attr']['type']  = 'file';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['class'] = 'uploadfield '.$field_setup['attr']['class'];

			Session::cache('js', array(
				'system::plugins/uploadify/jquery.uploadify.3.2.min.js'
			));

			if($field_setup['metas'])
			{
				$field_setup['metas']['label'] = $field_setup['label'];
				Session::cache('jsinline', 'var '.$field_id.'_upload = '.json_encode($field_setup['metas']));
			}

			return '<div class="uploadfield_container"><input'.Html::attributes($field_setup['attr']).'></div>';
		}


		// ##########################################


		public static function number($field_id, $field_setup)
		{
			$field_setup['value'] = preg_replace('#[^,\.\d+]#', '', $field_setup['value']);

			$field_setup['attr']['type']  = 'text';
			$field_setup['attr']['id']    = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']  = $field_id;
			$field_setup['attr']['value'] = $field_setup['value'];
			$field_setup['attr']['class'] = 'singleline_textfield '.$field_setup['attr']['class'];
			$field_setup['attr']['placeholder'] = $field_setup['placeholder'];

			return '<input'.Html::attributes($field_setup['attr']).'>';
		}


		// ##########################################


		public static function phone($field_id, $field_setup)
		{
			$field_setup['value'] = preg_replace('#\+#', '00', $field_setup['value']);
			return Form::number($field_id, $field_setup);
		}


		// ##########################################


		public static function time($field_id, $field_setup)
		{
			// standard values

			if($field_setup['metas']['value'])
			{
				list($hour, $minutes) = Format::string_explode($field_setup['metas']['value'], ':');
			}
			else
			{
				$hours   = '12';
				$minutes = '00';
			}

			// prepare passed values
			if( ! empty($field_setup['value']))
			{
				list($hours, $minutes, $seconds) = Format::string_explode($field_setup['value'], ':');
			}

			$field_setup['value'] = Format::array_join(array($hours, $minutes), ':');

			$time_values = array();

			for($h=0;$h<24;$h++)
			{
				$h = $h < 10 ? '0'.$h : $h;

				for($m=0;$m<60;empty($field_setup['steps']) ? $m += 30 : $m += $field_setup['steps'])
				{
					$m = $m < 10 ? '0'.$m : $m;
					$time_values[] = $h.':'.$m;
				}
			}

			if( ! empty($field_setup['metas']['add']))
			{
				foreach($field_setup['metas']['add'] as $add_time)
				{
					$time_values[] = $add_time;
				}
			}

			asort($time_values);

			// reverse values

			if($field_setup['metas']['reverse'] === TRUE)
			{
				arsort($time_values);
			}

			return Form::select(
				$field_id,
				array(
					'options' => Format::array_format($time_values, array('keyval' => TRUE)),
					'value'   => $field_setup['value'],
					'attr'    => $field_setup['attr']
				)
			);
		}


		// ##########################################


		public static function date($field_id, $field_setup)
		{
			$field_setup['attr']['type']    = 'text';
			$field_setup['attr']['id']      = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']    = $field_id;
			$field_setup['attr']['class']   = 'singleline_textfield widget_datepicker'.($field_setup['metas']['range'] === TRUE ? ' widget_datepicker_range' : NULL).' '.$field_setup['attr']['class'];
			$field_setup['attr']['months']  = empty($field_setup['metas']['months']) ? 1 : $field_setup['metas']['months'];
			$field_setup['metas']['format'] = empty($field_setup['metas']['format']) ? 'day.month.year' : $field_setup['metas']['format'];

			// date range request
			if($field_setup['metas']['range'] === TRUE)
			{
				if($field_setup['metas']['time'])
				{
					list($field_setup['value'][0], $time_from) = Format::string_explode($field_setup['value'][0], ' ');
					list($field_setup['value'][1], $time_to) = Format::string_explode($field_setup['value'][1], ' ');
				}

				$field_setup['attr']['name']  = Form::valid_attr_id($field_id).'_from';
				$field_setup['attr']['id']    = Form::valid_attr_id($field_id).'_from';
				$field_setup['attr']['value'] = empty($field_setup['value'][0]) ? NULL : Format::date($field_setup['value'][0], 'human', Format::prepare_date_format($field_setup['metas']['format'], 'php'));
				$from_field = '<input'.Html::attributes($field_setup['attr']).'>';
				$widget_from = Form::label(Form::valid_attr_id($field_id).'_from', $field_setup['label']['from'].':'.Form::field_mark($field_id, $field_setup)).'<br>'.$from_field;

				$field_setup['attr']['name']  = Form::valid_attr_id($field_id).'_to';
				$field_setup['attr']['id']    = Form::valid_attr_id($field_id).'_to';
				$field_setup['attr']['value'] = empty($field_setup['value'][1]) ? NULL : Format::date($field_setup['value'][1], 'human', Format::prepare_date_format($field_setup['metas']['format'], 'php'));
				$to_field = '<input'.Html::attributes($field_setup['attr']).'>';
				$widget_to = Form::label(Form::valid_attr_id($field_id).'_to', $field_setup['label']['to'].':'.Form::field_mark($field_id, $field_setup)).'<br>'.$to_field;

				if($field_setup['metas']['time'])
				{
					$widget = '
					<div class="fl mr">
						'.$widget_from.'
					</div>
					<div class="fl mr">
						<br>'.Form::time($field_id.'_from_time', array('value' => $time_from, 'attr' => array('class' => 'widget_datepicker_time'), 'metas' => $field_setup['metas']['time']['start'])).'
					</div>
					<div class="fl mr">
						'.$widget_to.'
					</div>
					<div class="fl">
						<br>'.Form::time($field_id.'_to_time', array('value' => $time_to, 'attr' => array('class' => 'widget_datepicker_time'), 'metas' => $field_setup['metas']['time']['end'])).'
					</div>
					';
				}
				else
				{
					$widget = '
					<div class="fl mr">
						'.$widget_from.'
					</div>
					<div class="fl">
						'.$widget_to.'
					</div>
					';
				}
			}

			// single date
			else
			{
				if($field_setup['metas']['time'])
				{
					list($field_setup['value'], $time) = Format::string_explode($field_setup['value'], ' ');
					$field_setup['attr']['value'] = empty($field_setup['value']) ? NULL : Format::date($field_setup['value'], 'human', Format::prepare_date_format($field_setup['metas']['format'], 'php'));

					$field_setup['attr']['class']  = 'widget_datepicker_datetime '.$field_setup['attr']['class'];
					$from_field = '<input'.Html::attributes($field_setup['attr']).'>';

					$widget = '
					<div class="fl mr">
						'.Form::label(Form::valid_attr_id($field_id), $field_setup['label'].':'.Form::field_mark($field_id, $field_setup)).'<br>'.$from_field.'
					</div>
					<div class="fl">
						<br>'.Form::time($field_id.'_time', array('value' => $time, 'attr' => array('class' => 'widget_datepicker_time'), 'metas' => $field_setup['metas']['time'])).'
					</div>
					';
				}
				else
				{
					$field_setup['attr']['value'] = Format::date($field_setup['value'], 'human', Format::prepare_date_format($field_setup['metas']['format'], 'php'));
					$widget = '<input'.Html::attributes($field_setup['attr']).'>';
				}
			}

			// cache required assets
			if(empty($field_setup['metas']['readonly']))
			{
				Session::cache('js', 'system::plugins/ui/jquery.ui.datepicker.min.js');
				Session::cache('jsinline', Form::valid_attr_id($field_id).'_dates = $("' . ($field_setup['metas']['range'] === TRUE ? '#'.Form::valid_attr_id($field_id).'_from, #'.Form::valid_attr_id($field_id).'_to' : '#'.$field_setup['attr']['id']).'").datepicker({'.($field_setup['metas']['range'] === TRUE ? ' onSelect : function(selectedDate) { system.form.date_range(selectedDate, "'.Form::valid_attr_id($field_id).'", this, $(this)); },' : NULL).' changeMonth: true, changeYear: true, showWeek : true, firstDay : 1, numberOfMonths : '.$field_setup['attr']['months'].', showButtonPanel : false, dateFormat: "'.Format::prepare_date_format($field_setup['metas']['format'], 'js').'" })');
			}

			return $widget;
		}


		// ##########################################


		public static function date_select($field_id, $field_setup)
		{
			$field_setup['attr']['class'] = 'singleline_selectfield '.$field_setup['attr']['class'];

			if(Read::array_search('date', $field_setup['rules']))
			{
				Form::rules($field_id, array('date'));
			}

			if(Form::is_submitted())
			{
				$field_setup['value'] = Form::get_value($field_id);
			}

			// get busy: value for all 3 fields is passed as one unique string
			list($year, $month, $day) = Format::string_explode($field_setup['value'], '-');

			// day widget
			$widget_day = Form::select(
				$field_id.'[day]',
				array(
					'options' => Format::array_format(range(1, 31), array('keyval' => TRUE, 'label' => ( ! empty($field_setup['labels']['day']) ? $field_setup['labels']['day'] : 'Day'))),
					'value'   => $day,
					'attr'    => array(
						'class'    => $field_setup['attr']['class'],
						'css'      => $field_setup['attr']['css'],
						'onchange' => $field_setup['attr']['onchange'],
						'disabled' => $field_setup['attr']['disabled'],
					)
				)
			);

			// month widget
			$widget_month = Form::select(
				$field_id.'[month]',
				array(
					'options' => Format::array_format(range(1, 12), array('keyval' => TRUE, 'label' => ( ! empty($field_setup['labels']['month']) ? $field_setup['labels']['month'] : 'Month'))),
					'value'   => $month,
					'attr'    => array(
						'class'    => $field_setup['attr']['class'],
						'css'      => $field_setup['attr']['css'],
						'onchange' => $field_setup['attr']['onchange'],
						'disabled' => $field_setup['attr']['disabled'],
					)
				)
			);

			// year widget
			$widget_year = Form::select(
				$field_id.'[year]',
				array(
					'options' => Format::array_format(range( ! empty($field_setup['options']['years']['from']) ? $field_setup['options']['years']['from'] : (date(Y) - 10),  ! empty($field_setup['options']['years']['to']) ? $field_setup['options']['years']['to'] : date(Y)), array('keyval' => TRUE, 'label' => ( ! empty($field_setup['labels']['year']) ? $field_setup['labels']['year'] : 'Year'))),
					'value'   => $year,
					'attr'    => array(
						'class'    => $field_setup['attr']['class'],
						'css'      => $field_setup['attr']['css'],
						'onchange' => $field_setup['attr']['onchange'],
						'disabled' => $field_setup['attr']['disabled'],
					)
				)
			);

			if(empty($field_setup['format']))
			{
				// set template format
				$field_setup['format'] = '%d&nbsp;%m&nbsp;%y';
			}

			// construct complete widget
			$widget = $field_setup['format'];
			$widget = str_replace('%d', $widget_day, $widget);
			$widget = str_replace('%m', $widget_month, $widget);
			$widget = str_replace('%y', $widget_year, $widget);

			return $widget;
		}


		// ##########################################


		public static function url($field_id, $field_setup)
		{
			$field_setup['attr']['type']        = 'text';
			$field_setup['attr']['id']          = Form::valid_attr_id($field_id);
			$field_setup['attr']['name']        = $field_id;
			$field_setup['attr']['value']       = $field_setup['value'];
			$field_setup['attr']['class']       = 'singleline_textfield urlfield '.$field_setup['attr']['class'];
			$field_setup['attr']['placeholder'] = $field_setup['placeholder'];

			return '<input'.Html::attributes($field_setup['attr']).'>'.Html::anchor('', Html::image('system::arrow.png'), array('class' => 'form_url_field_goto', 'rel' => $field_id));
		}

  }

?>