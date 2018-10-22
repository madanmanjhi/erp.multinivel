erp.multinivel 
=
_ERP BASE MLM - Versión: 3.9 - 
[NetworkSoft DEV](http://network-soft.com)_

21-10-2018
-  
### update web
```mysql
UPDATE empresa_multinivel
  SET web = 'http://demo.networksoft.com.mx' 
  WHERE id_tributaria LIKE '98765432-1'
 ```
20-10-2018
-
### create blockchain
```mysql
CREATE TABLE blockchain
 (
     id int PRIMARY KEY AUTO_INCREMENT,
     apikey varchar(100) DEFAULT 0000,
     currency varchar(4) DEFAULT 'USD',
     test int DEFAULT 0 COMMENT '1 is Actived',
     estatus varchar(3) DEFAULT 'ACT'
 );
```