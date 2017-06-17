CREATE TABLE IF NOT EXISTS #__n3ttemplate_categories (
  id INT NOT NULL AUTO_INCREMENT ,
  parent_id INT NOT NULL DEFAULT 0 ,
  title VARCHAR(255) NOT NULL DEFAULT '' ,
  note varchar(255) NOT NULL DEFAULT '' , 
  plugin VARCHAR(100) NOT NULL DEFAULT '' ,
  params TEXT NULL ,
  plugin_params TEXT NULL ,
  published TINYINT(1) NOT NULL DEFAULT 0 ,
  access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 ,
  checked_out TINYINT(1) NOT NULL DEFAULT 0 ,
  checked_out_time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  ordering INT NOT NULL DEFAULT 0,  
  PRIMARY KEY (id),
  INDEX idx_parent_id (parent_id ASC) )
ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__n3ttemplate_templates (
  id INT NOT NULL AUTO_INCREMENT ,
  category_id INT NOT NULL DEFAULT 0 ,
  title VARCHAR(255) NOT NULL DEFAULT '' ,
  note VARCHAR(255) NOT NULL DEFAULT '' ,
  template TEXT NOT NULL ,
  params TEXT NULL ,
  published TINYINT(1) NOT NULL DEFAULT 0 ,
  access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 ,
  display_access TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 ,
  checked_out TINYINT(1) NOT NULL DEFAULT 0 ,
  checked_out_time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  ordering INT NOT NULL DEFAULT 0,  
  PRIMARY KEY (id),
  INDEX idx_category_id (category_id ASC) )
ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS #__n3ttemplate_autotemplates (
  id INT NOT NULL AUTO_INCREMENT ,
  category_id INT NOT NULL DEFAULT 0 ,
  position VARCHAR(50) NOT NULL DEFAULT '' ,
  template_id INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (id),  
  UNIQUE uq_autotemplates (category_id, position) )
ENGINE = MyISAM DEFAULT CHARSET=utf8;