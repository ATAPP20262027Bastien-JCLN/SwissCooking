drop user if exists 'SwissCookingAdmin'@'localhost';
create user 'SwissCookingAdmin'@'localhost' identified by 'SwissCookingAdminPassword';
revoke all privileges on *.* from 'SwissCookingAdmin'@'localhost';
grant all privileges on SwissCookingDB.* to 'SwissCookingAdmin'@'localhost';
flush privileges;
show grants for 'SwissCookingAdmin'@'localhost';