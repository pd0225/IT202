CREATE TABLE Customer (
	CustomerID int NOT NULL,
	Users int,
	Contact
	Withdraw int,
	Deposit int,
	PRIMARY KEY (CustomerID),
	FOREIGN KEY (Users) REFERENCES Users (Users)
);
