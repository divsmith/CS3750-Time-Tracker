
# Get total man-hours volunteered between two dates provided.

SET
@start_date = '2016-11-01 00:00:00',
	
@end_date = '2016-11-30 00:00:00';

SELECT 
	
SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(s.end_time, s.start_time)) * s.count))


FROM road_home_db.shift AS s 

WHERE s.end_time IS NOT NULL

AND s.start_time > @start_date

AND s.start_time < @end_Date;



# Get man-hours by location and department. Just remove one to group by just one field.

SELECT
SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(s.end_time, s.start_time)) * s.count)),

s.location,

s.department


FROM road_home_db.shift AS s

WHERE s.end_time IS NOT NULL

AND s.start_time > @start_date

AND s.start_time < @end_Date

GROUP BY s.location, s.department;


# Get all unfinished shifts;

SELECT
s.first_name,
s.last_name,
s.email,
DATE_FORMAT(s.start_time,"%W %D %M %Y %H:%i %P"),
s.department
FROM shift AS s
WHERE s.end_time IS NULL LIMIT 1000


# Complete all unfinished shifts from yesterday.

SET SQL_SAFE_UPDATES=0;



UPDATE shift as sh

LEFT JOIN (
	
	SELECT
 id,

	DATE_ADD(start_time, INTERVAL 1 HOUR) AS 'end'

	FROM shift
        
	) 
as s

ON s.id = sh.id

SET sh.end_time = s.end

WHERE sh.end_time IS NULL

AND sh.start_time < DATE(NOW());


# Complete a shift with the given id;

SET @id = 5;



UPDATE shift SET end_time = NOW() WHERE id = @id;


# check admin credentials - valid if result set rowcount is 1;

SET @username = 'admin', @pass = 'test';

SELECT username FROM admin AS a
WHERE a.username = @username 
AND a.pass = @pass;