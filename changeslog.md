erp.multinivel 
=
_ERP BASE MLM - Versión: 3.9 - 
[NetworkSoft DEV](http://network-soft.com)_

24-10-2018
-
### update hashkey
```mysql
ALTER TABLE blockchain_wallet 
MODIFY hashkey varchar(120) DEFAULT '0';
```

23-10-2018
-
### create wallets

```mysql
CREATE TABLE blockchain_wallet
    (
        id int PRIMARY KEY AUTO_INCREMENT,
        id_usuario int DEFAULT 2 COMMENT '1 es la empresa',
        hashkey varchar(100) DEFAULT 0000,
        porcentaje int DEFAULT 100,
        estatus varchar(3) DEFAULT 'ACT'
    );
```

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