CREATE TABLE IF NOT EXISTS #__n3ttemplate_autotemplates (
  id INT NOT NULL AUTO_INCREMENT ,
  category_id INT NOT NULL DEFAULT 0 ,
  template_id INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (id),  
  UNIQUE uq_category_id (category_id) )
ENGINE = MyISAM DEFAULT CHARSET=utf8;