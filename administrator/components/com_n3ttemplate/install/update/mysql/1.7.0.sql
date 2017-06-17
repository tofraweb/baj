ALTER TABLE #__n3ttemplate_categories  
  ADD note VARCHAR(255) NOT NULL DEFAULT ''
  AFTER title;

ALTER TABLE #__n3ttemplate_categories
  ADD plugin VARCHAR(100) NOT NULL DEFAULT '' 
  AFTER note;
  
ALTER TABLE #__n3ttemplate_categories
  ADD plugin_params TEXT NULL
  AFTER params;
    
ALTER TABLE #__n3ttemplate_templates  
  ADD note VARCHAR(255) NOT NULL DEFAULT ''
  AFTER title;

ALTER TABLE #__n3ttemplate_categories
  CHANGE title title VARCHAR(255) NOT NULL DEFAULT ''; 

ALTER TABLE #__n3ttemplate_templates
  CHANGE title title VARCHAR(255) NOT NULL DEFAULT '';           