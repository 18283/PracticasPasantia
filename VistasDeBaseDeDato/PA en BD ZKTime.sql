Create procedure PA_RegistrosDeMarcacion (@CI varchar(24))
as
begin
	select U.USERID, U.NAME, M.MachineAlias, C.CHECKTIME, C.CHECKTYPE 
	from CHECKINOUT as C,Machines as M,USERINFO as U 
	where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = @CI);
end

Create procedure PA_RegistrosDeMarcacionEntreFechas(@CI varchar(24), @fechaInicio datetime, @fechaFin datetime)
as
begin
	select U.USERID, U.NAME, M.MachineAlias, C.CHECKTIME, C.CHECKTYPE 
	from CHECKINOUT as C,Machines as M,USERINFO as U 
	where C.sn = M.sn and U.USERID = C.USERID and U.BADGENUMBER = @CI and C.CHECKTIME >= @fechaInicio and C.CHECKTIME <= @fechaFin;
end

--QUE devuelva una lista del personal, que reciba cod dpto
ALTER procedure PA_PersonalDeDPTO(@IDEPT smallint)
as
begin
	select BADGENUMBER, NAME
	from USERINFO
	where DEFAULTDEPTID=@IDEPT;
end

--CI=BADGENUMBER, id=USERID, nombre=NAME, fecha=CHECKTIME, marcacion=CHECKTYPE,  marcador= MachineAlias
-----------------------------------------------------------------------------------------------------------------------

Alter procedure PA_ListaDeDPTO
as
begin
	SELECT DEPTID, DEPTNAME
	FROM DEPARTMENTS;
end

--drop procedure if exists PA_RegistrosDeMarcacion;

-----------------------------------------------------------------------------------------------------------------------

execute PA_RegistrosDeMarcacion '8943725';

execute PA_RegistrosDeMarcacionEntreFechas '8943725', '2025-04-01', '2025-04-16';

EXECUTE PA_PersonalDeDPTO 3;

execute PA_ListaDeDPTO;

-----------------------------------------------------------------------------------------------------------------------

select U.BADGENUMBER AS CI,U.NAME AS NOMBRE,M.MachineAlias MARCADOR,C.CHECKTIME FECHA,C.CHECKTYPE 'ING/SALIDA' 
from CHECKINOUT as C,Machines as M,USERINFO as U 
where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = '8217852');



select U.USERID, U.NAME, M.MachineAlias, C.CHECKTIME, C.CHECKTYPE 
from CHECKINOUT as C,Machines as M,USERINFO as U 
where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = '8943725');