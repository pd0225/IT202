CREATE TABLE Transactions(
    id int auto_increment,
    acc_src_id int not null,
    acc_dest_id int null,
    Amount decimal(12,2),
    `type` varchar(10), --deposit, withdraw, transfer, etc
    memo TEXT,
    expected_total decimal (12,2)
    created datetime default current_timestamp,
    PRIMARY KEY (id),
    FOREIGN KEY (acc_src_id) references Account.id,
    FOREIGN KEY (acc_dest_id) references Account.id
)