<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/
?>
<fieldset style="width: 80%; float: left;">
<legend>Release notes</legend>
<p><b>1.7.6</b></p>
<ul>
<li><b>(#)</b> Phoca Maps Plugin - fehlerhafte Sortierung</li>
<li><b>(#)</b> Automatische Templates - falsche Templates wurden in den Editor geladen, wenn andere automatische Template Positionen genutzt wurden.</li>
<li><b>(!)</b> Vorbereitung auf Joomla! 2.5</li>
<li><b>(+)</b> Neues AcePolls Plugin - erlaubt die Auswahl von Umfragen aus der AcePolls Komponente.</li>
<li><b>(+)</b> Ordner Plugin - Mp3 Browser Unterst&uuml;tzung hinzugef&uuml;gt.</li>
</ul>
<p><b>1.7.5</b></p>
<ul>
<li><b>(#)</b> Youtube Plugin - Uploads der User anzeigen anstatt der User-Favoriten.</li>
<li><b>(#)</b> Youtube Plugin - Lange Namen und Beschreiben wurden nur in Teilen angezeigt.</li>
<li><b>(#)</b> Benutzeroberfl&auml;che J15! - Namen der Buttons in der Bearbeitung der Kategorien verbessert.</li>
</ul>
<?php if (JRequest::getCmd('option') == 'com_n3ttemplate' ) { ?>
<p><b>1.7.4</b></p>
<ul>
<li><b>(^)</b> Verbesserung der Benutzeroberfl&auml;che</li>
</ul>
<p><b>1.7.3</b></p>
<ul>
<li><b>(+)</b> Hilfe hinzugef&uuml;gt</li>
<li><b>(^)</b> Verbesserung der Benutzeroberfl&auml;che</li>
<li><b>(#)</b> Fehlerhaftes Verhalten des JCE Editors behoben.</li>
</ul>
<p><b>1.7.2</b></p>
<ul>
<li><b>(+)</b> Automatisches Templates k&ouml;nnen sofort in den Editor geladen werden, oder automatisch an den Anfang oder das Ende des Beitrags hinzugef&uuml;gt werden.</li>
<li><b>(+)</b> Neues Datei Plugin - erlaubt das Ausw&auml;hlen von Dateien.</li>
<li><b>(+)</b> Neues Ordner Plugin - erlaubt das Ausw&auml;hlen von Ordnern.</li>
<li><b>(+)</b> Neues Phoca Map Plugin - erlaubt das Ausw&auml;hlen von Karten aus der Phoca Map Komponente.</li>
<li><b>(^)</b> Das automatische Anzeigen von Templates im Editor wird nun von der Zugriffsebene bzgl. der Anzeige dieses Eintrags beeinflusst.</li>
<li><b>(^)</b> Die Anzeige eines Templates als Link in einem Beitrag wird nun von der Zugriffsebene bzgl. der Anzeige dieses Eintrags beeinflusst.</li>
</ul>
<p><b>1.7.1</b></p>
<ul>
<li><b>(+)</b> Unterst&uuml;tzung f&uuml;r das indirekte Einf&uuml;gen hinzugef&uuml;gt (Einf&uuml;gen als Link). Das Template wird ersetzt, wenn der Beitrag angezeigt wird.</li>
<li><b>(+)</b> Unterst&uuml;tzung f&uuml;r das automatische Laden eines Templates f&uuml;r Content-Kategorien hinzugef&uuml;gt.</li>
<li><b>(+)</b> Neues Modul Plugin - Erlaubt die Modulauswahl</li>
<li><b>(+)</b> Position Plugin - Option f&uuml;r benutzerdefinierten Code hinzugef&uuml;gt.</li>
<li><b>(+)</b> Position Plugin - Unterst&uuml;tzung von Modules Anywhere hinzugef&uuml;gt</li>
<li><b>(^)</b> Youtube Plugin - Benutzerdefinierte Code Funktionalit&auml;t und Hilfe ge&auml;ndert.</li>
<li><b>(#)</b> Fehlerhafte Zeichencodierung auf Servern mit voreingestellter Standard Codierung entfernt.</li>
<li><b>(+)</b> Deutsche &Uuml;bersetzung hinzugef&uuml;gt (Dank an M. Cigdem Cebe)</li>
</ul>
<p><b>1.7.0</b></p>
<ul>
<li><b>(+)</b> Unterst&uuml;tzung von Plugins hinzugef&uuml;gt.</li>
<li><b>(+)</b> Neues Position Plugin - Erlaubt die Auswahl der Position des Templates.</li>
<li><b>(+)</b> Neues Youtube Plugin - Erlaubt die Auswahl eines Videos vom youtube.com Server.</li>
<li><b>(^)</b> Kleine Verbesserungen am Quellcode und Funktionen.</li>
</ul>
<p><b>1.6.2</b></p>
<ul>
<li><b>(#)</b> JavaScript Fehler f&uuml;r Joomla! 1.5 mit deaktiviertem Mootols Upgrade Plugin gel&ouml;st.</li>
</ul>
<p><b>1.6.1</b></p>
<ul>
<li><b>(#)</b> JavaScript Fehler in Google Chrome Browser gel&ouml;st.</li>
</ul>
<p><b>1.6.0</b></p>
<ul>
<li><b>(+)</b> Unterst&uuml;tzung von Joomla! 1.6 und 1.7 hinzugef&uuml;gt.</li>
<li><b>(^)</b> Medien Dateien in den Medien Ordner verschoben.</li>
<li><b>(^)</b> Festlegung von Zugriffsebenen f&uuml;r Buttons an den Joomla! Standard angepasst.</li>
<li><b>(+)</b> M&ouml;glichkeit zur Festlegung von Zugriffsebenen f&uuml;r Kategorien und Templates.</li>
<li><b>(#)</b> Fehlfunktionen w&auml;hrend der Bearbeitung im Frontend mit aktiviertem SEF beseitigt.</li>
</ul>
<?php } ?>
</fieldset>

<fieldset>
<legend>Legende</legend>
<ul>
<li><b>(*)</b> Security Fix</li>
<li><b>(#)</b> Bug Fix</li>
<li><b>(+)</b> Erg&auml;nzung</li>
<li><b>(^)</b> &Auml;nderung</li>
<li><b>(-)</b> Gel&ouml;scht</li>
<li><b>(!)</b> Hinweis</li>
</ul>
</fieldset>
