INSERT INTO SalesAssoc VALUES(1,'John Doe','passcode',6193.58, '304 North Rd DeKalb,IL' );
INSERT INTO SalesAssoc (name,passwd,accumComm,address) VALUES('Mary Beth','pass1234',1239.40, '305 South Rd DeKalb,IL' );

INSERT INTO Quote VALUES(1,1,0,CURRENT_TIME,1.4,'Secret Note test.', 149,'cool@yahoo.com');
INSERT INTO Quote (salesAID,status,procDateTime,commission,sNotes,customerID,cusContact) VALUES(2,4,CURRENT_TIME,1.5,'Secret Note test part 2.',20,'swag@gmail.com');

INSERT INTO LineItem VALUES(1,1,'This is a Line Item.',45.45);
INSERT INTO LineItem (quoteID,description,price) VALUES(2,'Line Item 2',5000.00);
