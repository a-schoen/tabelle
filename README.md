A dynamic table (AngularJS/PHP/MySQL). Using Github as a place to show the code, since the site it was developed for is password protected.

=================================

#Setup:

1. adjust file paths (if necessary) in the following files:

 /public_html/ajax/send_ajax.php ("$path")
 
 /public_html/lib/js/main.js ("$scope.ajaxUrl" on line 5)

2. create mysql table (insert name):

  CREATE TABLE name (
  
  	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  	
  	jahre VARCHAR(10),
  	
  	projekt VARCHAR(100),
  	
  	beschreibung text,
  	
  	summe INT(10)
  
  );

3. set database info and table name here:

 /php/config.php

=================================
