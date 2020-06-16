CREATE TABLE Account (
	id int auto_increment,
	AccountNum varchar(12) NOT NULL,
	UserID int,
	OpenedDate DATETIME default CURRENT_TIMESTAMP
	AccountBalance decimal(12,2) default 0.00,
	AccountType varchar(20),
	PRIMARY KEY (CustomerID),
	FOREIGN KEY (UserID) REFERENCES Users.id
);
