DROP TABLE SalesAssoc;
DROP TABLE Quote;
DROP TABLE LineItem;

CREATE TABLE SalesAssoc (
	salesAID INT(12) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(32),
	passwd VARCHAR(10),
	accumComm DECIMAL(8,2),
	address VARCHAR(128)
);

#Notes for status: 
#0 - in-progress
#1 - finalized
#2 - sanctioned
#3 - ordered
#4 - unresolved
CREATE TABLE Quote (
	quoteID INT(12) PRIMARY KEY AUTO_INCREMENT,
	salesAID INT(12), 
	status INT(1),
	procDateTime TIMESTAMP,
	commission DECIMAL(8,2),
	sNotes VARCHAR(4096),
	csutomerID INT(3) NOT NULL,
	FOREIGN KEY(salesAID) REFERENCES SalesAssoc(salesAID)
);

CREATE TABLE LineItem (
	lineID INT(12) PRIMARY KEY AUTO_INCREMENT,
	quoteID INT(12), 
	description VARCHAR(1024),
	price DECIMAL(8,2),
	FOREIGN KEY(quoteID) REFERENCES Quote(quoteID)
);


