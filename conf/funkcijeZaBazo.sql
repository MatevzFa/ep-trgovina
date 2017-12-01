DELIMITER //

CREATE FUNCTION povprecnaOcena (id INT) RETURNS DECIMAL(3,2)
BEGIN
	
    DECLARE povprecnaOcena DECIMAL(3,2);

    SELECT AVG(o.ocena) INTO povprecnaOcena FROM ocena o WHERE o.izdelek_id = id;
    
    RETURN povprecnaOcena;
	

END //
DELIMITER ;
