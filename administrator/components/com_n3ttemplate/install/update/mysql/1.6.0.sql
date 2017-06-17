ALTER TABLE #__n3ttemplate_categories
  ADD access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0
  AFTER published;

ALTER TABLE #__n3ttemplate_templates
  ADD access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0
  AFTER published;