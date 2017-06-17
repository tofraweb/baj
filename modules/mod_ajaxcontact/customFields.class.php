<?php
/**
 * @package    Ajax Contact
 * @author     Douglas Machado {@link http://ideal.fok.com.br}
 * @author     Created on 25-Mar-2009
 * @license		GNU/GPL, see license.txt in Joomla root directory
 * Ajax Contact is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses. 
 */


defined( '_JEXEC' ) or die( 'Restricted access' );

//Copyrights
//DO NOT COPY OR REDISTRIBUTE

/**
 *
 * Abstract fieldType class.
 *
 */
class fieldType {
	var $id		= null;
	var $value	= null;
	var $label	= null;
	var $name	= null;
	var $attributes = '';
	var $arrayFieldElements = null;
	var $required	= null;
	var $suffix	= '';

	function fieldType( $value, $label, $id,$params ) {
		$this->value	= $value;
		$this->label	= $label;
		$this->name		= 'ac_cf_'.$id;
		$this->id		= $id;
		$this->required	= $params['required'];
		$this->suffix	= $params['suffix'];
		$this->type		= $params['type'];
		$this->params	= $params;
		$this->document = &JFactory::getDocument();
		//echo $this->value;
		if(strpos($value,'|')){
			$this->arrayFieldElements = explode("|",$value);
		}else{
			$this->arrayFieldElements = array($value);
		}
		//echo '<pre>'; print_r($value); echo '</pre>'; exit;
	}
	function getInputHTML() {
		return "\n".'<input class="inputbox '.($this->required ? ' required':'').'" 
					 title="'.$this->label.'"
					type="text" name="' . $this->name . '" id="' . $this->name.$this->suffix . '"  
					'.$this->getFieldValue().' />'.'<br />';
	}
	function getLabel(){
		//echo '<pre>'; print_r($this->params); exit;
		if( 
				($this->params['labelType'] != 'field')	OR
				($this->params['labelType'] == 'field'	AND 
						($this->type	== 'selectlist'	OR 
						$this->type		== 'checkbox'	OR 
						$this->type		== 'radiobutton' )
				)
		){
			return "\n".'<label for="'.$this->getInputName().$this->suffix.'" class="ac-field-label'.($this->required ? ' requiredField':'').'">'.$this->label.'</label>';//.'<br />';
		}
		return '';
	}
	function getInputName(){
		return $this->name;
	}
	function getFieldHTML(){
		$html	= '';
		if($this->type != 'hidden' AND $this->type != 'freetext' ){
			$html .= "\n".'<div class="ac-cf-container ac-cf-type-'.$this->type.'" id="ac-cf-container-'.$this->id.'" >';
			$html .= $this->getLabel();
			$html .= $this->getInputHTML();
			$html .= '</div>';
		}else{
			$html .= $this->getLabel();
			$html .= $this->getInputHTML();
		}
		return $html;
	}
	function getFieldJS() {
		/*if($this->params['labelType'] == 'field'){
			if ($this->type != 'select' AND $this->type != 'checkbox' AND $this->type != 'radiobutton' ) {
				return " rel=\"{$this->label}\" onfocus=\"if(this.value=='{$this->label}') this.value='';\" 
						onblur=\"if(this.value=='') this.value='{$this->label}';\" 
						";
			}
		}*/
		
		return '';
	}
	
	function getFieldValue(){
		$js	= $this->getFieldJS();
		if($js){
			return $js." value=\"{$this->label}\"";
		}
		return 'value="" ';
	}
	function getValidationScript() {
		$script = '';
		if($this->params['labelType'] == 'field'){
			$script	= "\n\t"
			// ." console.log('".$this->getInputName().$this->suffix."');  \n"
			."new OverText(document.id('".$this->getInputName().$this->suffix."'));"
			."\n";
		}
		return $script;
	}
}
class fieldType_date extends fieldType {
	function getInputHTML() {
		$fieldAttributes	= ' class="inputbox'.($this->required ? ' required':'').'" 
					'.$this->attributes. ' '
					.$this->getFieldJS()
					. ' title="'.$this->label.'"'
					;
		
		$html	= JHTML::_('calendar', '', $this->getInputName(), $this->getInputName(), JText::_('CF_DATE_FORMAT'), $fieldAttributes);
		return $html;
	}
}

class fieldType_text extends fieldType { 
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}

class fieldType_multitext extends fieldType {
	function getInputHTML() {
		$html = '<textarea name="' . $this->name . '"
					id="' . $this->name.$this->suffix . '"
					class="inputbox text_area'.($this->required ? ' required':'').'" '
					.parent::getFieldJS()
					.' title="'.$this->label.'" '
					.' cols="40" rows="8" >'
						.$this->getFieldValue()
					.'</textarea>';
		return $html;
	}
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
	
	function getFieldValue(){
		$js	= $this->getFieldJS();
		if($js){
			return $this->label;
		}
		return '';
	}
}

class fieldType_cc extends fieldType {
	function getInputHTML() {
		$html = '<input type="text" name="' . $this->name . '" 
					id="' . $this->name.$this->suffix . '"
					class="inputbox text_area'.($this->required ? ' required validate-email':'').'" '
					.$this->getFieldValue()
					.' title="'.$this->label.'" '
				.' />';
		return $html;
	}
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}

class fieldType_selectlist extends fieldType{
	function getInputHTML() {
		$html = '<select name="' . $this->name. '"
					id="' . $this->name.$this->suffix . '" 
					class="inputbox text_area'.($this->required ? ' required':'').'" 
					title="'.$this->label.'"
				>';
		$html .= '<option value="">'.JText::_('MOD_AJAXCONTACT_PLEASE_SELECT_ONE').'</option>';
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(strpos($fieldElement, '::') > 0){
				$fieldElement = explode('::', $fieldElement);
			}else{
				$fieldElement = array($fieldElement,$fieldElement);
			}
			$html .= '<option value="'.JText::_($fieldElement[0]).'"';
			$html .= '>' . JText::_($fieldElement[1]) . '</option>';
		}
		$html .= '</select>'; 
		return $html;
	}
	
	function getValidationScript() {
		$script = '';
		return $script;
	}
}
class fieldType_radiobutton extends fieldType {
	var $_selectCounter	= 0;
	function getInputHTML() {
		$html = '';
		$i = 0;
		$html .= '<ul style="margin:0;padding:0;list-style-type:none">';
		$this->_selectCounter = 0;
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(!empty($fieldElement)) {
				if(strpos($fieldElement, '::') > 0){
					$fieldElement = explode('::', $fieldElement);
				}else{
					$fieldElement = array($fieldElement,$fieldElement);
				}
				$html .= '<li style="background-image:none;padding:0">';
				$html .= '<input type="radio" name="' . $this->name . '" value="'.$fieldElement[0].'" id="' . $this->name . '_' . $i . '" ';
				$html .= ' class="ac-radiobutton'.($this->required ? ' required validate-radio':'').'" /><label for="' . $this->name. '_' . $i . '">'.JText::_($fieldElement[1]).'</label>';
				$html .= '</li>'; $i++;
				$this->_selectCounter++;
			}
		}
		$html .= '</ul>';
		return $html;
	}
	
	function getValidationScript() {
		$script = '';
		return $script;
	}
}
class fieldType_checkbox extends fieldType{
	var $_selectCounter	= 0;
	function getInputHTML() {
		$this->_selectCounter = 0;
		$html = '';
		$html .= '<div class="ac-checkbox-container">';
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(strpos($fieldElement, '::') > 0){
				$fieldElement = explode('::', $fieldElement);
			}else{
				$fieldElement = array($fieldElement,$fieldElement);
			}
			$html .= '<div class="ac-option-container">';
			$html .= '<input type="checkbox" name="' . $this->name. '[]" value="'.$fieldElement[0].'" id="' . $this->name. '_' . $this->_selectCounter . '" ';
			
			$html .= ' '.$this->attributes.' ';
			$html .= ' class="ac-checkbox'.($this->required ? ' required  validate-checkbox':'').'" /><label for="' . $this->name. '_' . $this->_selectCounter . '">'.JText::_($fieldElement[1]).'</label>';
			$html .= '</div>';
			$this->_selectCounter++;
		}
		$html .= '</div>';
		return $html;
	}
	function getValidationScript() {
		$script = '';
		return $script;
	}
}

class fieldType_name extends fieldType {
	function getInputHTML() {
		$html	= '<input type="text" 
					name="name" 
					id="name'.$this->suffix.'" '
					. ' class="inputbox '.(($this->required) ? ' required':'').'" '
					. $this->getFieldValue()
					.' title="'.$this->label.'" '
				. '  />';
		return $html;
	}
	
	function getInputName(){
		return 'name';
	}
	
	function getFieldValue(){
		$user	= &JFactory::getUser();
		$js		= $this->getFieldJS();
		if($js){
			return $js. ' value="'.
				(($this->params['autopopulate'] AND $user->id)? $user->get('name') : $this->label ).'"';
		}
		return 'value="'.(($this->params['autopopulate'] AND $user->id)? $user->get('name') : '' ).'" ';
	}
	
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}

class fieldType_lastname extends fieldType {
	function getInputHTML() {
		$user		= &JFactory::getUser();
		$html	= '<input type="text" 
					name="lastname" 
					id="lastname'.$this->suffix.'" '
					. ' class="inputbox '.($this->required ? ' required':'').'"'
					. $this->getFieldValue()
					.' title="'.$this->label.'" '
				. ' />';
		return $html;
	}
	function getInputName(){
		return 'lastname';
	}
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}

class fieldType_email extends fieldType {
	function getInputHTML() {
		$user		= &JFactory::getUser();
		$html	= '<input type="text" 
					id="email'.$this->suffix.'" 
					name="email" '
					. ' class="inputbox '.($this->required ? ' required validate-email':'').'" '
					. $this->getFieldValue()
					.' title="'.$this->label.'" '
					. ' />';

		return $html;
	}
	function getInputName(){
		return 'email';
	}
	
	function getFieldValue(){
		$user	= &JFactory::getUser();
		$js		= $this->getFieldJS();
		if($js){
			return $js. ' value="'.
				(($this->params['autopopulate'] AND $user->id)? $user->get('email') : $this->label ).'"';
		}
		return 'value="'.(($this->params['autopopulate'] AND $user->id)? $user->get('email') : '' ).'" ';
	}
	
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}
class fieldType_textemail extends fieldType_text { 
	function getInputHTML() {
		$user		= &JFactory::getUser();
		$html	= '<input type="text" 
					id="'.$this->name.'" 
					name="'.$this->name.'" '
					. ' class="inputbox '.($this->required ? ' required validate-email':'').'" '
					. $this->getFieldValue()
					.' title="'.$this->label.'" '
					. ' />';

		return $html;
	}
}
class fieldType_subject extends fieldType {
	function getInputHTML() {
		if(count($this->arrayFieldElements) > 1){
		$html = '<select 
					name="' . $this->getInputName(). '"
				 	id="' . $this->getInputName().$this->suffix . '" 
					class="inputbox text_area'.($this->required ? ' required':'').'" 
					title="'.$this->label.'"
				>';
		$html .= '<option value="">'.JText::_('MOD_AJAXCONTACT_PLEASE_SELECT_ONE').'</option>';
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(strpos($fieldElement, '::') > 0){
				$fieldElement = explode('::', $fieldElement);
			}else{
				$fieldElement = array($fieldElement,$fieldElement);
			}
			$html .= '<option value="'.JText::_($fieldElement[0]).'"';
			$html .= '>' . JText::_($fieldElement[1]) . '</option>';
		}
		$html .= '</select>'; 
		}else{
			$html	= '<input type="text" name="' . $this->getInputName(). '" id="' . $this->getInputName().$this->suffix . '" '
				. ' class="inputbox '.($this->required ? ' required':'').'" '
				. $this->getFieldValue()
				. '  />';
		}
		return $html;
	}
	function getInputName(){
		return 'subject';
	}
	function getFieldHTML(){
		$html	= '';
		$html .= "\n".'<div class="ac-cf-container" id="ac-cf-container-'.$this->id.'" >';
			//if($this->params['labelType'] == 'label'){
				$html .= $this->getLabel();
			//}
			$html .= $this->getInputHTML();
		$html .= "</div>";
		return $html;
	}
}
?>
