INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (1, 0, 'Lorem ipsum', 'Lorem ipsum texts', '', 'load_expanded=1\n\n', '', 1, 1);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (2, 1, 'Headings', '', '', 'load_expanded=0\n\n', NULL, 1, 1);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (3, 1, 'Paragraphs', '', '', 'load_expanded=0\n\n', NULL, 1, 2);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (4, 0, 'Load modules', 'Insert {loadposition} code', 'position', 'load_expanded=0\n\n', '', 1, 2);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (5, 0, 'Youtube', 'Youtube.com videos', '', 'load_expanded=0\n\n', '', 1, 3);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (6, 5, 'Text link', 'Insert text link to video on youtube server', '', 'load_expanded=0\n\n', '', 1, 4);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (7, 6, 'Top rated', 'Standard feed - Top rated videos', 'youtube', 'load_expanded=0\n\n', 'source=standard_feed\nstandard_feed_mode=top_rated\nregion=\ncategory=\nuser=\nplaylist=\nmax_results=25\nformat=\noutput=link\ncustom_output=\n\n', 1, 1);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (8, 6, 'Most viewed - Sport', 'Standard feed - Most viewed videos from category Sport', 'youtube', 'load_expanded=0\n\n', 'source=standard_feed\nstandard_feed_mode=most_viewed\nregion=\ncategory=Sports\nuser=\nplaylist=\nmax_results=25\nformat=\noutput=link\ncustom_output=\n\n', 1, 2);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (9, 6, 'Most popular - Great Britain', 'Standard feed - Most popular videos for region Great Britain', 'youtube', 'load_expanded=0\n\n', 'source=standard_feed\nstandard_feed_mode=most_popular\nregion=GB\ncategory=\nuser=\nplaylist=\nmax_results=25\nformat=\noutput=link\ncustom_output=\n\n', 1, 3);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (10, 5, 'Preview link', 'Insert Image link to video on youtube server', '', 'load_expanded=0\n\n', '', 1, 5);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (11, 10, 'Joomla! Official Channel', 'User Uploaded videos. User joomla', 'youtube', 'load_expanded=0\n\n', 'source=user_uploads\nstandard_feed_mode=top_rated\nregion=\ncategory=\nuser=joomla\nplaylist=\nmax_results=25\nformat=\noutput=bigpreview\ncustom_output=\n\n', 1, 4);
INSERT INTO `#__n3ttemplate_categories` (`id`, `parent_id`, `title`, `note`, `plugin`, `params`, `plugin_params`, `published`, `ordering`) VALUES (12, 5, 'JW All Video plugin - Joomla! 1.6 Tutorials', 'Inserts JW All video plugin code. Displays Playlist videos', 'youtube', 'load_expanded=0\n\n', 'source=playlist\nstandard_feed_mode=top_rated\nregion=\ncategory=\nuser=\nplaylist=8772D3C5B83A8E13\nmax_results=25\nformat=\noutput=jw_allvideo\ncustom_output=\n\n', 1, 6);

INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (1, 2, 'h1', '', '<h1>Sed commodo vehicula sollicitudin</h1>', NULL, 1, 1);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (2, 2, 'h2', '', '<h2>Sed luctus diam in lacus</h2>', NULL, 1, 2);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (3, 2, 'h3', '', '<h3>Donec velit tortor, porttitor in</h3>', NULL, 1, 3);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (4, 2, 'h4', '', '<h4>Donec fringilla enim eget odio</h4>', NULL, 1, 4);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (5, 2, 'h5', '', '<h5>Duis semper pretium aliquet</h5>', NULL, 1, 5);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (6, 2, 'h6', '', '<h6>Ut ac justo tempor augue</h6>', NULL, 1, 6);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (7, 3, '1 paragraph', '', '<p>Vestibulum a ante nunc, imperdiet suscipit diam. Donec a sapien sem, et  dictum orci. Donec a sapien fringilla eros porttitor sagittis vel quis  dolor. Cras in sem quis dolor vestibulum rutrum at quis neque. Vivamus  placerat, metus non sodales congue, mi dui dapibus purus, consectetur  ultrices nisi turpis et ante. Mauris venenatis malesuada enim convallis  dapibus. Curabitur dolor elit, malesuada a rutrum id, sodales sed arcu.  Vivamus placerat dictum turpis, eget tincidunt tellus tincidunt non.  Cras nec sapien eget sapien mattis accumsan. In ultrices lacus in mi  hendrerit aliquam.</p>', NULL, 1, 1);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (8, 3, '2 paragraphs', '', '<p>Fusce eu neque eget eros porttitor eleifend in nec orci. Aenean pretium  erat eu neque pellentesque at tempor lacus tempor. Curabitur a est ac  sem pellentesque molestie. Vivamus imperdiet neque vitae diam interdum  pretium. Etiam erat risus, convallis vel cursus eleifend, elementum sed  dolor. Quisque nibh nisi, vehicula pulvinar iaculis eget, venenatis sed  mi. Vestibulum mattis nisl dolor, nec luctus sapien. Aliquam eu orci et  mauris ultricies mollis pretium non nunc. Morbi pulvinar ante ut lacus  viverra pellentesque. Maecenas feugiat, eros id pharetra consequat, orci  sapien consequat tellus, et feugiat dui tellus nec est. Morbi  ultricies, leo in pharetra malesuada, eros nulla ultrices mi, nec  placerat sapien risus sit amet urna. Aliquam pulvinar tellus eu felis  ultricies venenatis. Sed pretium tempor erat, vitae pharetra neque  condimentum at. Pellentesque vel massa turpis. Integer eu tellus quam.</p>\r\n<p>Vivamus semper dolor metus. Nulla fringilla luctus mi sit amet  consequat. Cras condimentum leo a massa dapibus interdum elementum nibh  hendrerit. Proin sit amet libero ut magna dictum sagittis eu in nisi.  Donec et nisi lacus, a dignissim elit. Phasellus sit amet ligula justo, a  viverra lorem. Aenean eros diam, vestibulum sed gravida a, sodales eu  nulla. Sed vitae pellentesque arcu. Maecenas tellus risus, accumsan  laoreet vulputate id, vehicula eget turpis. Ut ut augue nulla, quis  cursus mi. Maecenas eros enim, gravida et aliquet quis, mattis ac dolor.  Duis accumsan consequat purus, sit amet vulputate arcu fringilla at.  Sed lacinia, eros sed consectetur adipiscing, purus ipsum tristique  nibh, vel hendrerit metus leo mattis ligula.</p>', NULL, 1, 2);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (9, 3, '3 paragraphs', '', '<p>Ut mattis, felis ut semper tempus, orci lorem viverra metus, a venenatis  odio augue semper augue. Duis vulputate, eros dapibus convallis  tristique, libero tellus egestas turpis, at adipiscing metus quam  pretium ligula. Duis interdum nisi non risus semper sed blandit sapien  ornare. Fusce lacus turpis, vulputate a aliquet id, fringilla quis  felis. Pellentesque vel felis in nunc elementum aliquet vitae sed leo.  Nulla ut elit et erat elementum porta. Class aptent taciti sociosqu ad  litora torquent per conubia nostra, per inceptos himenaeos. Duis luctus  vehicula tincidunt. Nunc ac diam mauris. Integer eget urna eros, sed  dapibus erat. Morbi lacinia nisl quis dolor faucibus molestie.  Suspendisse bibendum blandit porttitor.</p>\r\n<p>Praesent et pharetra risus. In feugiat tortor vitae turpis aliquam quis  tincidunt ipsum consequat. Maecenas vitae dapibus sem. Duis neque quam,  hendrerit a mattis at, aliquet in nunc. Ut risus tellus, ultricies ac  luctus ut, dictum eget nisl. Donec egestas auctor odio. Fusce et sem  quis orci porta lobortis. Maecenas bibendum bibendum nulla eu molestie.  Etiam dui ipsum, dictum non molestie ac, molestie et enim. Nunc bibendum  pellentesque massa, a fermentum leo placerat vel. Sed eu ante justo,  lobortis condimentum lacus. Sed at mauris sit amet urna ornare  tincidunt. Sed nec odio a mi pellentesque tempus rhoncus sit amet nunc.  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eu  tincidunt sapien. Etiam dapibus feugiat nibh consectetur volutpat. Proin  iaculis lorem malesuada quam ullamcorper vulputate. Quisque nisl nunc,  lacinia id tempor a, molestie eu urna.</p>\r\n<p>Integer consequat elit ut neque placerat et porta metus eleifend. Sed  tincidunt nulla eget lacus venenatis blandit. Cras sodales imperdiet  mauris commodo mattis. Nulla euismod lobortis nisi a porta. Vestibulum  tempor consequat sem, in mattis nulla condimentum eu. Duis nec erat  turpis. Vestibulum vulputate hendrerit ipsum, ut rutrum nunc sodales ut.  Morbi molestie vehicula orci vehicula lacinia. In hac habitasse platea  dictumst. Duis ut libero quis mauris ultrices dapibus sed a metus. Sed a  mauris eget mauris venenatis suscipit. Vestibulum congue fringilla  accumsan.</p>', NULL, 1, 3);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (10, 3, '4 paragraphs', '', '<p>Aliquam fermentum dapibus erat at eleifend. Vestibulum vel nisl libero,  sit amet imperdiet tortor. Fusce sit amet libero non sem egestas  convallis. Vestibulum viverra, orci mollis venenatis ornare, elit urna  luctus risus, vel sollicitudin velit leo nec purus. Sed vitae auctor  sem. Sed ut turpis in odio convallis luctus. Aenean tempor lacinia erat,  et aliquet augue volutpat et. In mattis bibendum metus ut dictum.  Nullam tincidunt, ligula ut tempor vehicula, tellus magna luctus leo, id  porta nisi dolor et nibh. Proin vitae libero massa, tincidunt bibendum  nisi. Suspendisse dolor tortor, adipiscing lacinia lacinia quis,  consectetur at mi. Phasellus non nisi in orci feugiat fringilla. Etiam  tempus imperdiet luctus. Fusce vitae purus ut tortor luctus pretium vel  at lacus. Aliquam nec lorem eget libero viverra malesuada non sed neque.  Cras eu ornare urna. Pellentesque condimentum purus sit amet arcu  egestas pretium. Vivamus eu erat sed elit tincidunt vulputate.</p>\r\n<p>In eleifend vulputate lobortis. Morbi ultrices lectus eu augue interdum  sit amet condimentum elit aliquam. Lorem ipsum dolor sit amet,  consectetur adipiscing elit. Proin nec felis metus, sollicitudin  pharetra augue. Ut et bibendum neque. Donec aliquam posuere tempor. Nunc  enim dolor, euismod at hendrerit quis, ultricies ut sem. Curabitur  cursus ante a mauris pulvinar non laoreet arcu faucibus. Suspendisse  fringilla pulvinar fringilla. Praesent blandit tortor ut magna vehicula  quis blandit urna euismod. Fusce fringilla, nibh non fringilla suscipit,  lectus ante ornare purus, quis rhoncus velit diam a justo. Vestibulum  ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia  Curae; Aliquam faucibus, elit non euismod egestas, mauris risus  consequat dui, vel luctus urna tortor in neque. Nulla quis nibh erat.  Nunc lacinia sagittis urna et scelerisque. Mauris ac leo nisl, vel  posuere nunc. Proin velit mi, aliquet in tempus ac, rutrum eget justo.  Sed dolor tortor, aliquet eget ultricies et, cursus et erat. Donec  rutrum odio et turpis tempor faucibus. Integer rutrum rhoncus ornare.</p>\r\n<p>Praesent augue sapien, cursus ut congue ut, condimentum a dui. Sed at  augue sed ligula accumsan aliquet. Quisque dictum pulvinar congue. Cras  feugiat mauris elementum turpis scelerisque ac cursus mi mattis. Aenean  laoreet consectetur odio et tincidunt. Ut elit eros, scelerisque eget  eleifend eget, porta vel nulla. Duis eu leo felis. Donec vel elit arcu,  at ultricies nulla. Nulla vel est et mi molestie posuere suscipit quis  lacus. Vestibulum vitae turpis eu neque varius accumsan vitae vitae sem.  Pellentesque fringilla volutpat laoreet. Pellentesque velit ipsum,  auctor in imperdiet ut, laoreet in lorem. Morbi et odio felis, non  pulvinar orci.</p>\r\n<p>Sed vitae urna sagittis augue lacinia fermentum. Mauris varius neque  eget libero laoreet quis facilisis eros condimentum. Curabitur sagittis  scelerisque vehicula. In vulputate nisl id nisi congue venenatis.  Suspendisse potenti. Nunc sodales tempor massa vitae varius. Nulla massa  sem, faucibus in fringilla sit amet, mollis et lorem. Nam et congue  ligula. Sed ac odio in massa fringilla vestibulum eu at erat.  Pellentesque auctor, ipsum et pretium porta, nisi quam porttitor turpis,  vel sollicitudin odio elit sit amet libero.</p>', NULL, 1, 4);
INSERT INTO `#__n3ttemplate_templates` (`id`, `category_id`, `title`, `note`, `template`, `params`, `published`, `ordering`) VALUES (11, 1, 'Lorem ipsum dolor sit amet', 'Short lorem ipsum sentence', '<p>Lorem ipsum dolor sit amet.</p>', NULL, 1, 1);