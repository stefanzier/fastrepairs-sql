

-- Insert test values into tables

-- CustomerBills
INSERT INTO Customers VALUES('Bob Vance', '(408)-257-1375');
INSERT INTO Customers VALUES('Michael Scott', '(408)-335-7131');
INSERT INTO Customers VALUES('Jim Halpert', '(317)-333-2587');
INSERT INTO Customers VALUES('Lexi Pierre', '(408)-718-3676');
INSERT INTO Customers VALUES('Stefanz', '(650)-995-4925');
INSERT INTO Customers VALUES('Test', '34');

-- Repair Items
INSERT INTO RepairItems VALUES('item001', 'm001', 200.00, 2010, 'SINGLE');
INSERT INTO RepairItems VALUES('item002', 'm002', 300.00, 2015, 'GROUP');
INSERT INTO RepairItems VALUES('item003', 'm001', 200.00, 2010, 'NONE');

-- Printers and Computers
INSERT INTO Computers VALUES('item001');
INSERT INTO Printers VALUES('item002');
INSERT INTO Computers VALUES('item003');

-- Repair Persons
INSERT INTO RepairPersons VALUES('emp001', 'Tony Stark', '(888)-100-1000');
INSERT INTO RepairPersons VALUES('emp002', 'John Smith', '(555)-212-2020');
INSERT INTO RepairPersons VALUES('emp003', 'Lexi Pierre', '(408)-316-7813');

-- Service Contracts
INSERT INTO ServiceContracts VALUES('sc001', DATE'2010-01-01', DATE'2020-01-01', '(408)-257-1375');
INSERT INTO ServiceContracts VALUES('sc002', DATE'2015-01-01', DATE'2025-01-01', '(408)-335-7131');
INSERT INTO ServiceContracts VALUES('sc003', DATE'2017-01-01', DATE'2027-01-01', '(317)-333-2587');
INSERT INTO ServiceContracts VALUES('sc004', DATE'2017-01-01', DATE'2027-01-01', '(408)-718-3676');
INSERT INTO ServiceContracts VALUES('sc005', DATE'2017-01-01', DATE'2027-01-01', '(650)-995-4925');
INSERT INTO ServiceContracts VALUES('sc006', DATE'2000-01-01', DATE'2001-01-01', '34');

-- Single Contracts
INSERT INTO SingleContracts VALUES('sc001', 'item001');
INSERT INTO SingleContracts VALUES('sc002', 'item002');
INSERT INTO SingleContracts VALUES('sc004', NULL);
INSERT INTO SingleContracts VALUES('sc005', NULL);

-- Group Contracts
--INSERT INTO GroupContracts VALUES('sc003', '', '');
INSERT INTO ServiceContracts VALUES('sc006', NULL, NULL);

-- Repair Jobs
INSERT INTO RepairJobs VALUES('job001', 'emp001', '(408)-257-1375', 'item001', 'sc001', TIMESTAMP'2018-03-01 10:35:05.00', 'DONE');
INSERT INTO RepairJobs VALUES('job002', 'emp002', '(408)-335-7131', 'item002', 'sc002', TIMESTAMP'2018-03-10 12:45:34.00', 'READY');
INSERT INTO RepairJobs VALUES('job003', 'emp003', '(317)-333-2587', 'item003', 'sc003', TIMESTAMP'2018-02-02 15:43:40.00', 'UNDER_REPAIR');

-- Problems
INSERT INTO Problems VALUES('pr01', 'Dead battery.');
INSERT INTO Problems VALUES('pr02', 'Broken keyboard.');
INSERT INTO Problems VALUES('pr03', 'Water damage.');
INSERT INTO Problems VALUES('pr04', 'Damaged screen.');
INSERT INTO Problems VALUES('pr05', 'Faulty disc drive.');

-- Problem Reports
INSERT INTO ProblemReports VALUES('item001', 'pr01');
INSERT INTO ProblemReports VALUES('item001', 'pr03');
INSERT INTO ProblemReports VALUES('item002', 'pr03');
INSERT INTO ProblemReports VALUES('item003', 'pr05');

commit;
