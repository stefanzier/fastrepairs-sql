DROP TABLE CustomerBills;
DROP TABLE ProblemReports;
DROP TABLE Problems;
DROP TABLE RepairJobs;
DROP TABLE RepairLog;
DROP TABLE RepairPersons;
DROP TABLE GroupContracts;
DROP TABLE SingleContracts;
DROP TABLE Printers;
DROP TABLE Computers;
DROP TABLE RepairItems;
DROP TABLE ServiceContracts;
DROP TABLE Customers;

-- Initialize tables
CREATE TABLE Customers (
  name       VARCHAR(20),
  phone      VARCHAR(20) PRIMARY KEY
);

CREATE TABLE RepairItems (
  itemId VARCHAR(20),
  model  VARCHAR(20),
  price  DECIMAL(10, 2),
  year   INT,
  serviceContractType VARCHAR(20),
  CHECK (serviceContractType IN ('NONE', 'SINGLE', 'GROUP')),
  PRIMARY KEY(itemId)
);

CREATE TABLE Printers (
  itemId VARCHAR(20),
  FOREIGN KEY (itemId) REFERENCES RepairItems(itemId),
  PRIMARY KEY (itemId)
);

CREATE TABLE Computers (
  itemId VARCHAR(20),
  FOREIGN KEY (itemId) REFERENCES RepairItems(itemId),
  PRIMARY KEY (itemId)
);
CREATE TABLE ServiceContracts (
  contractId VARCHAR(20) PRIMARY KEY,
  startDate  DATE,
  endDate    DATE,
  phone      VARCHAR(20),
  FOREIGN KEY (phone) REFERENCES Customers(phone)
);

CREATE TABLE SingleContracts (
  contractId VARCHAR(20),
  machineId  VARCHAR(20),
  PRIMARY KEY(contractId),
  FOREIGN KEY (contractId) REFERENCES ServiceContracts(contractId),
  FOREIGN KEY (machineId) REFERENCES RepairItems(itemId)
);

CREATE TABLE GroupContracts (
  contractId  VARCHAR(20),
  computerId  VARCHAR(20),
  printerId   VARCHAR(20),
  PRIMARY KEY(contractId),
  FOREIGN KEY (contractId) REFERENCES ServiceContracts(contractId),
  FOREIGN KEY (computerId) REFERENCES Computers(itemId),
  FOREIGN KEY (printerId) REFERENCES Printers(itemId)
);

CREATE TABLE RepairPersons (
  employeeNo VARCHAR(20) PRIMARY KEY,
  name       VARCHAR(20),
  phone      VARCHAR(20)
);

CREATE TABLE RepairJobs (
  repairId           VARCHAR(20) PRIMARY KEY,
  employeeNo         VARCHAR(20),
  phone              VARCHAR(20),
  machineID          VARCHAR(20),
  serviceContractId  VARCHAR(20),
  timeOfArrival      TIMESTAMP,
  status             VARCHAR(20),
  CHECK (status IN ('UNDER_REPAIR', 'READY', 'DONE')),
  FOREIGN KEY (phone) REFERENCES Customers(phone),
  FOREIGN KEY (machineId) REFERENCES RepairItems(itemId),
  FOREIGN KEY (employeeNo) REFERENCES RepairPersons(employeeNo)
);

CREATE TABLE RepairLog (
  repairId           VARCHAR(20) PRIMARY KEY,
  employeeNo         VARCHAR(20),
  phone              VARCHAR(20),
  machineID          VARCHAR(20),
  serviceContractId  VARCHAR(20),
  timeOfArrival      TIMESTAMP,
  status             VARCHAR(20),
  CHECK (status IN ('DONE')),
  FOREIGN KEY (phone) REFERENCES Customers(phone),
  FOREIGN KEY (machineId) REFERENCES RepairItems(itemId),
  FOREIGN KEY (employeeNo) REFERENCES RepairPersons(employeeNo)
);

CREATE TABLE Problems (
  code        VARCHAR(20) PRIMARY KEY,
  description VARCHAR(100)
);

CREATE TABLE ProblemReports (
  itemId VARCHAR(20),
  code   VARCHAR(20),
  FOREIGN KEY (itemId) REFERENCES RepairItems(itemId),
  FOREIGN KEY (code) REFERENCES Problems(code),
  PRIMARY KEY(itemId, code)
);

CREATE TABLE CustomerBills (
  billId VARCHAR(20) PRIMARY KEY,
  machineId VARCHAR(20),
  phone VARCHAR(20),
  timeIn TIMESTAMP NULL,
  timeOut TIMESTAMP NULL,
  repairPersonId VARCHAR(20),
  laborHours DECIMAL(5, 2),
  costOfParts DECIMAL(10, 2),
  total DECIMAL(10, 2),
  FOREIGN KEY (machineId) REFERENCES RepairItems(itemId),
  FOREIGN KEY (phone) REFERENCES Customers(phone),
  FOREIGN KEY (repairPersonId) REFERENCES RepairPersons(employeeNo)
);
