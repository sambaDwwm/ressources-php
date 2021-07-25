DROP FUNCTION IF EXISTS `apphp_text_encode`;

DELIMITER $$
CREATE FUNCTION  `apphp_text_encode` (x VARCHAR(1024)) 
	RETURNS varchar(1024) CHARSET utf8
		
	BEGIN
		DECLARE TextString VARCHAR(1024) ; 
		SET TextString = x ;
		
		IF INSTR( x , '"' ) 
		THEN SET TextString = REPLACE(TextString, '"','&#34;') ; 
		END IF ;		
		IF INSTR( x , "'" ) 
		THEN SET TextString = REPLACE(TextString, "'",'&#39;') ; 
		END IF ;		
		IF INSTR( x , "\\" ) 
		THEN SET TextString = REPLACE(TextString, "\\",'&#92;') ; 
		END IF ;		

		RETURN TextString ; 	
	END$$
DELIMITER ;


DROP FUNCTION IF EXISTS `apphp_text_encode_overlib`;

DELIMITER $$
CREATE FUNCTION  `apphp_text_encode_overlib` (x VARCHAR(1024)) 
	RETURNS varchar(1024) CHARSET utf8
		
	BEGIN
		DECLARE TextString VARCHAR(1024) ; 
		SET TextString = x ; 
		
		IF INSTR( x , '"' ) 
		THEN SET TextString = REPLACE(TextString, '"','&#92;&#34;') ; 
		END IF ; 		
		IF INSTR( x , "'" ) 
		THEN SET TextString = REPLACE(TextString, "'",'&#92;&#39;') ; 
		END IF ;		
		IF INSTR( x , "\\" ) 
		THEN SET TextString = REPLACE(TextString, "\\",'&#92;&#92;') ; 
		END IF ;
		
		RETURN TextString ; 
	END$$
DELIMITER ;
