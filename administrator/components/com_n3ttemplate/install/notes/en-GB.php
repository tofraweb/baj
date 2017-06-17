<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
?>
<fieldset style="width: 80%; float: left;">
<legend>Release notes</legend>
<p><b>1.7.6</b></p>
<ul>
<li><b>(#)</b> Phoca Maps plugin - wrong ordering</li>
<li><b>(#)</b> Automatic templates - wrong templates were loaded in editor, when other automatic templates position were used</li>
<li><b>(!)</b> Getting Joomla! 2.5 ready</li>
<li><b>(+)</b> New plugin AcePolls - allows choosing of poll from AcePolls component</li>
<li><b>(+)</b> Folder plugin - Mp3 Browser support added</li>
</ul>
<p><b>1.7.5</b></p>
<ul>
<li><b>(#)</b> Youtube plugin - user uploads displayed instead of user favorites</li>
<li><b>(#)</b> Youtube plugin - for long names and descriptions only part was displayed</li>
<li><b>(#)</b> User interface J15! - correct button names in category edit</li>
</ul>
<?php if (JRequest::getCmd('option') == 'com_n3ttemplate' ) { ?>
<p><b>1.7.4</b></p>
<ul>
<li><b>(^)</b> User interface improvements</li>
</ul>
<p><b>1.7.3</b></p>
<ul>
<li><b>(+)</b> Help added</li>
<li><b>(^)</b> User interface improvements</li>
<li><b>(#)</b> JCE editor wrong behavior solved</li>
</ul>
<p><b>1.7.2</b></p>
<ul>
<li><b>(+)</b> Automatic templates can be know loaded in editor, or automatically added at the beginning or end of article</li>
<li><b>(+)</b> New plugin File - allows choosing of file</li>
<li><b>(+)</b> New plugin Folder - allows choosing of folder</li>
<li><b>(+)</b> New plugin Phoca Maps - allows choosing of map from Phoca Maps component</li>
<li><b>(^)</b> Displaying automatic template in editor is now influenced by Display Access Level property of item</li>
<li><b>(^)</b> Displaying template inserted as link to article is now influenced by Display Access Level property of item</li>
</ul>
<p><b>1.7.1</b></p>
<ul>
<li><b>(+)</b> Added support for indirect inserting (Insert as link). Template is replaced when article is displayed.</li>
<li><b>(+)</b> Added support for automatic loading templates for content categories.</li>
<li><b>(+)</b> New plugin Module - allows to choose module</li>
<li><b>(+)</b> Position plugin - custom code option added</li>
<li><b>(+)</b> Position plugin - Modules Anywhere support added</li>
<li><b>(^)</b> Youtube plugin - custom code functionality and help revised</li>
<li><b>(#)</b> Removed wrong character encoding on servers with preset default encoding.</li>
<li><b>(+)</b> German translation added (thanks to M. Cigdem Cebe)</li>
</ul>
<p><b>1.7.0</b></p>
<ul>
<li><b>(+)</b> Plugins support added</li>
<li><b>(+)</b> New plugin Position - allows to choose template position</li>
<li><b>(+)</b> New plugin Youtube - allows to choose video from server youtube.com</li>
<li><b>(^)</b> Small code and functionality improvements</li>
</ul>
<p><b>1.6.2</b></p>
<ul>
<li><b>(#)</b> Solved JavaScript error for Joomla! 1.5 with disabled Mootools Upgrade plugin</li>
</ul>
<p><b>1.6.1</b></p>
<ul>
<li><b>(#)</b> Solved JavaScript error in Google Chrome browser</li>
</ul>
<p><b>1.6.0</b></p>
<ul>
<li><b>(+)</b> Added Joomla! 1.6 and 1.7 support</li>
<li><b>(^)</b> Media files moved to media folder</li>
<li><b>(^)</b> Button access level definition changed to Joomla! standard</li>
<li><b>(+)</b> Possibility to define access level for categories and templates</li>
<li><b>(#)</b> Removed malfunctionality while editing in frontend with SEF enabled</li>
</ul>
<?php } ?>
</fieldset>

<fieldset>
<legend>Legend</legend>
<ul>
<li><b>(*)</b> Security Fix</li>
<li><b>(#)</b> Bug Fix</li>
<li><b>(+)</b> Addition</li>
<li><b>(^)</b> Change</li>
<li><b>(-)</b> Removed</li>
<li><b>(!)</b> Note</li>
</ul>
</fieldset>
