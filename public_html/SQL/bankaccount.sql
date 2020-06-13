CREATE TABLE Account (
	AccountID int NOT NULL,
	Users int,
	Contact,
	Deposit int,
	Withdrawal int,
	AccountType int,
	PRIMARY KEY (CustomerID),
	FOREIGN KEY (Users) REFERENCES Users (Users)
);
