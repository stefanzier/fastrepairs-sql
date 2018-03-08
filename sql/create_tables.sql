-- Initialize tables
CREATE TABLE ServiceContract (
  contractId VARCHAR(20) PRIMARY KEY,
  startDate  DATE,
  endDate    DATE,
  phone      VARCHAR(20),
  FOREIGN KEY phone REFERENCES Customer(phone)
);

CREATE TABLE Single (
  contractId VARCHAR(20),
  machineId  VARCHAR(20),
  PRIMARY KEY(contractId, machineId)
  FOREIGN KEY contractId REFERENCES ServiceContract(contractId)
);

CREATE TABLE Group (
  contractId  VARCHAR(20),
  computerId  VARCHAR(20),
  printerId   VARCHAR(20),
  PRIMARY KEY(contractId, computerId, printerId),
  FOREIGN KEY contractId REFERENCES ServiceContract(contractId)
);

CREATE TABLE Customer (
  name       VARCHAR(20),
  phone      VARCHAR(20) PRIMARY KEY
);

CREATE TABLE RepairJob (
  repairId,          VARCHAR(20) PRIMARY KEY,
  phone              VARCHAR(20),
  machineID          VARCHAR(20),
  serviceContractId  VARCHAR(20),
  timeOfArrival      DATE,
  ownersInformation  VARCHAR(20),
  status             CHECK status IN ('UNDER_REPAIR', 'READY', 'DONE'),
  FOREIGN KEY phone REFERENCES Customer(phone),
  FOREIGN KEY machineId REFERENCES RepairItems(itemId),
  FOREIGN KEY employeeNo REFERENCES RepairPerson(employeeNo)
);

CREATE TABLE RepairItem (
  itemId VARCHAR(20) PRIMARY KEY,
  model  VARCHAR(20),
  price  DECIMAL(10, 2),
  year   NUMBER
);

CREATE TABLE RepairPerson (
  employeeNo VARCHAR(20) PRIMARY KEY,
  name       VARCHAR(20),
  phone      VARCHAR(20),
);

CREATE TABLE Problem (
  code   VARCHAR(20) PRIMARY KEY,
);

CREATE TABLE ProblemReport (
  itemId VARCHAR(20),
  code   VARCHAR(20),
  FOREIGN KEY itemId REFERENCES RepairItem(itemId),
  FOREIGN KEY code REFERENCES Problem(code)
);

CREATE TABLE CustomerBill (
  repairId VARCHAR(20) PRIMARY KEY,
  machineModel VARCHAR(20),
  customerName VARCHAR(20),
  phone VARCHAR(20),
  timeIn DATE,
  timeOut DATE,
  repairPersonId VARCHAR(20),
  laborHours DECIMAL(5, 2),
  costOfParts DECIMAL(10, 2),
  total DECIMAL(10, 2),
  machineId VARCHAR(20),
  FOREIGN KEY repairId REFERENCES RepairJob(repairId),
  FOREIGN KEY machineModel REFERENCES RepairItem(model),
  FOREIGN KEY customerName REFERENCES Customer(name),
  FOREIGN KEY phone REFERENCES Customer(phone),
  FOREIGN KEY timeIn REFERENCES RepairJob(timeOfArrival),
  FOREIGN KEY repairPersonId REFERENCES RepairPerson(employeeNo),
  FOREIGN KEY machineId REFERENCES RepairJob(machineId)
);
