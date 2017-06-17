<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="n3tTemplateCredits">
  <p><b><?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_VERSION'); ?>: </b><a href="<?php echo JURI::base(); ?>index.php?option=com_n3ttemplate&amp;view=info"><?php echo $version; ?></a></p>
  <p><b>Copyright: </b>(c) 2010-2012 Pavel Poles - n3t.cz. All rights reserved.</p>
  <p><b><?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_LICENSE'); ?>: </b><a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU/GPLv3</a></p>
  <p><b><?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_CREDITS'); ?>: </b></p>
  <p>
    <b><?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_DE_TRANSLATION'); ?>: </b>M. Cigdem Cebe<br />
  </p>
  <p><?php echo JText::sprintf('MOD_N3TTEMPLATE_CREDITS_REVIEW', '<a href="http://extensions.joomla.org/extensions/edition/editor-buttons/15910" target="_blank">Joomla! Extensions Directory</a>'); ?></p>
  <p><?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_DONATE'); ?></p>
</div>
<div class="n3tTemplateDonate" style="text-align: center;">  
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php echo JText::_('MOD_N3TTEMPLATE_CREDITS_PAYPAL'); ?>" style="border: none;" />
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
  </form>
</div>
