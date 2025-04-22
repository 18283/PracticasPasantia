Create procedure PA_RegistrosDeMarcacion (@CI varchar(24))
as
begin
	select U.USERID, U.NAME, M.MachineAlias, C.CHECKTIME, C.CHECKTYPE 
	from CHECKINOUT as C,Machines as M,USERINFO as U 
	where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = @CI);
end

/*ALTER PROCEDURE PA_RegistrosDeMarcacionEntreFechas
    @CI VARCHAR(24),
    @fechaInicio DATETIME,
    @fechaFin DATETIME
AS
BEGIN
    SELECT 
        U.USERID, 
        U.NAME, 
        M.MachineAlias, 
        C.CHECKTIME, 
        CASE 
            WHEN C.CHECKTYPE = 'I' THEN 'Entrada'
            WHEN C.CHECKTYPE = 'O' THEN 'Salida'
            ELSE C.CHECKTYPE
        END AS CHECKTYPE
    FROM CHECKINOUT AS C, Machines AS M, USERINFO AS U
    WHERE 
        C.sn = M.sn 
        AND U.USERID = C.USERID 
        AND U.BADGENUMBER = @CI 
        AND C.CHECKTIME >= @fechaInicio 
        AND C.CHECKTIME <= @fechaFin;
END*/

ALTER PROCEDURE PA_RegistrosDeMarcacionEntreFechas
    @CI VARCHAR(24),
    @fechaInicio DATETIME = NULL,
    @fechaFin DATETIME = NULL
AS
BEGIN
    -- Si no se especifica la fecha fin, se usa la fecha actual
    IF @fechaFin IS NULL
        SET @fechaFin = GETDATE()

    -- Si no se especifica la fecha inicio, se usa el primer día del mes de la fecha fin
    IF @fechaInicio IS NULL
        SET @fechaInicio = DATEFROMPARTS(YEAR(@fechaFin), MONTH(@fechaFin), 1)

    SELECT 
        U.BADGENUMBER, 
        U.NAME, 
        M.MachineAlias, 
        C.CHECKTIME, 
        CASE 
            WHEN C.CHECKTYPE = 'I' THEN 'Entrada'
            WHEN C.CHECKTYPE = 'O' THEN 'Salida'
            ELSE C.CHECKTYPE
        END AS CHECKTYPE
    FROM CHECKINOUT AS C, Machines AS M, USERINFO AS U
    WHERE 
        C.sn = M.sn 
        AND U.USERID = C.USERID 
        AND U.BADGENUMBER = @CI 
        AND C.CHECKTIME >= @fechaInicio 
        AND C.CHECKTIME <= @fechaFin;
END


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

execute PA_RegistrosDeMarcacionEntreFechas '8943725', '2025-03-01', '2025-04-21';

execute PA_RegistrosDeMarcacionEntreFechas '8943725'; --Las fechas son por defecto del dia actual, todo el mes

EXECUTE PA_PersonalDeDPTO 3;

execute PA_ListaDeDPTO;

-----------------------------------------------------------------------------------------------------------------------

select U.BADGENUMBER AS CI,U.NAME AS NOMBRE,M.MachineAlias MARCADOR,C.CHECKTIME FECHA,C.CHECKTYPE 'ING/SALIDA' 
from CHECKINOUT as C,Machines as M,USERINFO as U 
where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = '8217852');



select U.USERID, U.NAME, M.MachineAlias, C.CHECKTIME, C.CHECKTYPE 
from CHECKINOUT as C,Machines as M,USERINFO as U 
where (C.sn = M.sn and U.USERID = C.USERID) and (U.BADGENUMBER = '8943725');