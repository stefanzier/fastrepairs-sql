-- Create a trigger to remove a RepairJob when it's status is 'DONE', and insert into
-- a new table "RepairLog"

CREATE OR REPLACE TRIGGER job_done_log_trig
    AFTER  UPDATE ON RepairJobs
    FOR EACH ROW
	BEGIN
		--If the status is DONE, move data to Repair Log and DELETE
		IF (:new.status = 'DONE') THEN
			INSERT INTO RepairLog VALUES			
				(:new.repairId, :new.employeeNo, :new.phone, :new.machineID, 
				:new.serviceContractId, :new.timeOfArrival, :new.status);
		END IF;
    END;
/
Show errors;

CREATE OR REPLACE TRIGGER job_done_delete_trig
    AFTER  UPDATE ON RepairJobs
	BEGIN
		DELETE FROM RepairJobs WHERE status = 'DONE';
    END;
/
Show errors;

