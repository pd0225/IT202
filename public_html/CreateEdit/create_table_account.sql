CREATE TABLE Account (
    id int auto_increment,
    AccountNum varchar(12) NOT NULL,
    user_id int,
    AccountType varchar(20),
    OpenedDate DATETIME default CURRENT_TIMESTAMP
    AccountBalance decimal(12,2) default 0.00,
    primary key (id)
)