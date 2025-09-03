<?php 

class FOT_TYPES {



	function field($name,$id,$type,$class="",$desc="",$note=false,$attr=[],$options=[],$tax=[]){
		$attributes = '';
		if (!empty($attr)) {
			foreach ($attr as $key => $value) {
				$attributes .= $key.'="'.$value.'"';
			}
		}


		echo '<feild-control class="'.$class.' type-'.$type.'">';

			echo '<span>'.$name.'</span>';

			echo '<div class="field--content">';

				switch($type) {

					case 'text': $this->text($id,$attributes); break;

					case 'checkbox': $this->checkbox($id,$attributes); break;

					case 'number': $this->number($id,$attributes); break;

					case 'email': $this->email($id,$attributes); break;

					case 'select': $this->select($id,$attributes,$options); break;

					case 'textarea': $this->textarea($id,$attributes); break;
					case 'editor': $this->editor($id,$attributes); break;

					case 'file': $this->file($id,$attributes); break;

					case 'custom_fields': $this->custom_fields($id,$attributes); break;
					case 'group': $this->group($id,$attributes,$options); break;

					case 'taxonomy_select': $this->taxonomy_select($id,$attributes,$tax); break;

				}

			echo '</div>';

			echo $desc != '' ? '<note '.($note == true ? 'class="special"' : '' ).'>'.$desc.'</note>' : '';

		echo '</feild-control>';



	}





	function text($id,$attr){

		echo '<input '.$attr.' type="text" class="opt-field" name="'.$id.'"  value="'.get_option($id).'" />';

	}	
    	
	function editor($id,$attr){
        
		wp_editor(get_option($id),$id,array(
        'textarea_name' => $id,
        'textarea_rows' => 10,
        'editor_class'=>'opt-field'
      ) );

	}	
    
    function group($id,$attr,$options) {
        echo '<input type="hidden" class="opt-field group--values" value="'.get_option($id).'" name="'.$id.'" >';
        $saved_data = array_filter((ARRAY)json_decode(get_option($id),1));
        if(!empty($saved_data)){
           foreach($saved_data as $i =>  $el){
               echo '<div class="group-holder">';
          
                    foreach($options as  $opt){
                        $func = $opt['type'];
                        echo '<div class="field--content">';
                            echo '<label>'.$opt['name'].'</label>';
                            $new_id = $i > 0 ? $id.'_'.$opt['id'].'_'.$i : $id.'_'.$opt['id']; 
                            $this->$func($new_id,$opt['attr']);
                        echo '</div>';
                    }    
                    
                if($i > 0) {
                    echo '<i class="fa-solid fa-xmark remove-box"></i>';
                }
                echo '</div>'; 
           } 
        }else {
            echo '<div class="group-holder">';
      
                foreach($options as $opt){
                    $func = $opt['type'];
                    echo '<div class="field--content">';
                        echo '<label>'.$opt['name'].'</label>';
                        $this->$func($id.'_'.$opt['id'],'');
                    echo '</div>';
                }    
                
    
            echo '</div>';    
        }
        
    
        
        echo '<div class="add-more ">'. ( is_rtl() ? 'اضافة عنصر جديد' : 'add new box' ) .'</div>';
    }
    
	function checkbox($id,$attr){

		echo '<label class="checkbox-label" for="'.$id.'">';

			echo '<input  type="hidden" class="opt-field" id="'.$id.'" name="'.$id.'"  '.(get_option($id) =='on' ? 'value="on" ' : '').' />';

			echo '<div class="toggle '.(get_option($id) =='on' ? 'active' : '').'"></div>';

		echo '</label>';

	}



	function number($id,$attr){

		echo '<input '.$attr.' type="number" class="opt-field" name="'.$id.'"  value="'.get_option($id).'" />';

	}



	function file($id,$attr){



		$value = get_option($id);



		echo '<input type="hidden" id="'.$id.'_id" />';

		echo '<input type="text" class="opt-field" value="'.$value.'"  '.$attr.' name="'.$id.'" id="'.$id.'" />';

		echo '<div class="upload--file" data-multiple="false" data-type="image" data-field="#'.$id.'" data-name="'.$id.'">'.(is_rtl() ? 'رفع الملف' : 'upload file').'</div>';

		$style='';

		if( empty($value) ) {$style='display:none;';}



		if ( strpos($value,'pdf') !== false  ) {

			echo '<div class=" preview-file" id="'.$id.'_preview" style="'.$style.'">';

				echo '<i class="fa-solid fa-file-pdf"></i>';

				echo '<span>'.end(explode('/', $value)).'</span>';

				echo '<a style="'.$style.'" href="javascript:void(0);" class="remove" data-multiple="false" id="'.$id.'_remove"><i class="fal fa-times"></i></a>';

			echo '</div>';



		}else if(strpos($value,'docx') !== false){

			echo '<div class=" preview-file" id="'.$id.'_preview" style="'.$style.'">';

				echo '<i class="fa-solid fa-book"></i>';

				echo '<span>'.end(explode('/', $value)).'</span>';

				echo '<a style="'.$style.'" href="javascript:void(0);" class="remove" data-multiple="false" id="'.$id.'_remove"><i class="fal fa-times"></i></a>';

			echo '</div>';

		}else if( strpos($value,'ppt') !== false){

			echo '<div class=" preview-file" id="'.$id.'_preview" style="'.$style.'">';

				echo '<i class="fa-solid fa-presentation-screen"></i>';

				echo '<span>'.end(explode('/', $value)).'</span>';

				echo '<a style="'.$style.'" href="javascript:void(0);" class="remove" data-multiple="false" id="'.$id.'_remove"><i class="fal fa-times"></i></a>';

			echo '</div>';

		}else{

			echo '<div class="preview-image">';

				echo '<img class="" id="'.$id.'_preview" style="'.$style.'" src="'.$value.'" />';

				echo '<a style="'.$style.'" href="javascript:void(0);" class="remove" data-multiple="false" id="'.$id.'_remove"><i class="fal fa-times"></i></a>';

			echo '</div>';

		}

	}



 	function file_list($id,$attr) {

		$value = get_option($id);

		echo '<div class="upload--file" data-multiple="false" data-type="image" data-field="#'.$id.'" data-name="'.$id.'">'.(is_rtl() ? 'رفع الملفات' : 'upload files').'</div>';



		echo '<div class="previewList" id="'.$id.'_preview">';

		foreach ((is_array($value)) ? $value : array() as $k => $url) {

			echo '<span><input type="hidden" name="'.$name.'['.$k.']" value="'.$url.'" /><em onClick="this.parent().remove();"><span></span><span></span></em>';

			if (strpos($url,'pdf') !== false ) {

				echo '<div class="sm-preveiew"><i class="fa-solid fa-file-pdf"></i><span>'.end(explode('/', $url)).'</span></div>';

			}else if(strpos($url,'docx') !== false ){

				echo '<div class="sm-preveiew"><i class="fa-solid fa-book"></i><span>'.end(explode('/', $url)).'</span></div>';

			} else if(strpos($url,'ppt') !== false ){

				echo '<div class="sm-preveiew"><i class="fa-solid fa-presentation-screen"></i><span>'.end(explode('/', $url)).'</span></div>';

			}else{

				echo '<img src="'.$url.'" />';

			}

			echo '</span>';

		}

		echo '</div>';

		$style='';

		if( empty($value) ) {$style='display:none;';}

		echo '<a style="'.$style.'" href="javascript:void(0);" class="APBRemoveButton" data-multiple="true" id="'.$id.'_remove">'.( is_rtl() ? 'حذف الكل' : 'delete all' ).'</a>';

		echo isset($field['desc']) ? '<description>'.$field['desc'].'</description>' : '';

	}



	function email($id,$attr){

		echo '<input '.$attr.'  class="opt-field" type="email" name="'.$id.'"  value="'.get_option($id).'" />';

	}



	function textarea($id,$attr){

		echo '<textarea '.$attr.' class="opt-field" name="'.$id.'" >'.get_option($id).'</textarea>';

	}



	function select($id,$attr,$options){

		echo '<select '.$attr.'  class="opt-field" name="'.$id.'">';

			echo '<option value="">';

				echo is_rtl() ? 'اختر' : 'choose';

			echo '</option>';

			foreach ($options as $key => $value) {

				echo '<option value="'.$key.'" '.( $key == get_option($id) ? 'selected="selected"' : '' ).'>';

					echo $value;

				echo '</option>';

			}

		echo '</select>';

	}



	function taxonomy_select($id,$attr,$tax){

		echo '<select '.$attr.'  class="opt-field" name="'.$id.'">';

			echo '<option value="">';

				echo is_rtl() ? 'اختر' : 'choose';

			echo '</option>';

			foreach (get_categories(['taxonomy'=>$tax,'hide_empty'=>0]) as $c) {

				echo '<option value="'.$c->term_id.'" '.( $c->term_id == get_option($id) ? 'selected="selected"' : '' ).'>';

					echo $c->name;

				echo '</option>';

			}

		echo '</select>';

	}







}