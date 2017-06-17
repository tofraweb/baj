<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: default.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form action="index.php?option=com_poweradmin&view=changeposition" name="adminForm">    
	<div id="jsn-position-listing">
		<?php 
		for($i = 0; $i < count($this->positions); $i++){
			$position = $this->positions[$i];
			if (JString::trim($position) == ''){
				
			}else{
				if ( in_array( $position, $this->old_positions ) ){
				   $class   = "position-selector active_item";
				   $checked = 'checked="checked"';
				}else{
				   $class   = "position-selector";
				   $checked = "";
				}
			    ?>
				<div class="<?php echo $class;?>" id="<?php echo $position;?>" style="display: none;">
					<div class="position-contain">
						<?php echo $position;?>
					</div>				
				</div>
				<?php
			}			
		}
		?>		
	</div>
	<input type="hidden" name="option" value="com_poweradmin" />
	<input type="hidden" name="view" value="changeposition" />
	<input type="hidden" name="task" value="changeposition.setPosition" />
</form>