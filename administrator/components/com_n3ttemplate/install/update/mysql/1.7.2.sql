ALTER TABLE #__n3ttemplate_autotemplates 
  DROP INDEX uq_category_id;
  
ALTER TABLE #__n3ttemplate_autotemplates 
  ADD position VARCHAR( 50 ) NOT NULL DEFAULT '' 
  AFTER category_id;
  
ALTER TABLE #__n3ttemplate_autotemplates
  ADD UNIQUE uq_autotemplates ( category_id , position );
  
UPDATE #__n3ttemplate_autotemplates
  SET position='editor'
  WHERE 1;
  
ALTER TABLE #__n3ttemplate_templates 
  ADD display_access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0  
  AFTER access;
  
UPDATE #__n3ttemplate_templates
  SET display_access = access
  WHERE 1;  
  