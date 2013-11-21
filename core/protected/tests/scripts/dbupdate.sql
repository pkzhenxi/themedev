# updates 2 rows to get an install of webstore to think the license has been accepted and a password set

update `xlsws_configuration` set `key_value`='8d943379b9ef52397390a456a55cb39c' where `key_name`='LSKEY';
update `xlsws_configuration` set `key_value`=1 where `key_name`='INSTALLED';