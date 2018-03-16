-- Create a trigger to remove a RepairJob when it's status is 'DONE', and insert into
-- a new table "RepairLog"

-- Move the information into RepairLog
CREATE OR REPLACE TRIGGER job_done_log_trig
    AFTER  UPDATE ON RepairJobs
    FOR EACH ROW
	BEGIN
		--If the status is DONE, move data to Repair Log
		IF (:new.status = 'DONE') THEN
			INSERT INTO RepairLog VALUES			
				(:new.repairId, :new.employeeNo, :new.phone, :new.machineID, 
				:new.serviceContractId, :new.timeOfArrival, :new.status);
		END IF;
    END;
/
Show errors;

-- Delete the row
CREATE OR REPLACE TRIGGER job_done_delete_trig
    AFTER  UPDATE ON RepairJobs
	BEGIN
		--If the status is DONE, delete the row
		DELETE FROM RepairJobs WHERE status = 'DONE';
    END;
/
Show errors;

-- Create a trigger to verify that serviceIds inserted into RepairJobs are valid
-- (Between the start and end date)
CREATE OR REPLACE TRIGGER check_contract_trig
	BEFORE INSERT ON RepairJobs
	FOR EACH ROW
	DECLARE
		sid VARCHAR(20);
		tin TIMESTAMP;
		contractStart DATE; 
		contractEnd DATE;
	BEGIN
		
		sid := :new.serviceContractId;
		tin := :new.timeOfArrival;
		IF(sid IS NULL) THEN
			RETURN;
		END IF;
		SELECT startDate INTO contractStart FROM ServiceContracts WHERE contractId = sid;
		SELECT endDate INTO contractEnd FROM ServiceContracts WHERE contractId = sid;

		--If the date isn't within the range, error
		IF(NOT(tin BETWEEN contractStart and contractEnd)) THEN
			RAISE_APPLICATION_ERROR(-20101, 'The given contract cannot be used at this time. (Expired or not active yet.)');
    		ROLLBACK;
		END IF;
	END;
/
Show errors;
