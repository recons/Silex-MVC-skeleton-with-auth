--------------------------------------------------------
--  File created - среда-июня-08-2016   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Type ROLES_ARR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TYPE "SERGEY"."ROLES_ARR" AS VARRAY(20) OF VARCHAR2(25);

/
--------------------------------------------------------
--  DDL for Sequence GROUPS_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."GROUPS_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 50 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence IDG
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."IDG"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 23 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence IDS
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."IDS"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 45 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence MARKS_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."MARKS_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 21 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence PEOPLE_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."PEOPLE_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 40 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence SUBJECTS_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."SUBJECTS_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 44 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Sequence USERS_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SERGEY"."USERS_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 41 CACHE 20 NOORDER  NOCYCLE  NOPARTITION ;
--------------------------------------------------------
--  DDL for Table GROUPS
--------------------------------------------------------

  CREATE TABLE "SERGEY"."GROUPS" 
   (	"ID" NUMBER(*,0), 
	"NAME" VARCHAR2(50 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table MARKS
--------------------------------------------------------

  CREATE TABLE "SERGEY"."MARKS" 
   (	"ID" NUMBER, 
	"STUDENT_ID" NUMBER, 
	"SUBJECT_ID" NUMBER, 
	"TEACHER_ID" NUMBER, 
	"VALUE" NUMBER
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table PEOPLE
--------------------------------------------------------

  CREATE TABLE "SERGEY"."PEOPLE" 
   (	"ID" NUMBER(*,0), 
	"FIRST_NAME" VARCHAR2(20 BYTE), 
	"LAST_NAME" VARCHAR2(20 BYTE), 
	"PATHER_NAME" VARCHAR2(20 BYTE), 
	"GROUP_ID" NUMBER, 
	"TYPE" CHAR(1 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table SUBJECTS
--------------------------------------------------------

  CREATE TABLE "SERGEY"."SUBJECTS" 
   (	"ID" NUMBER, 
	"NAME" VARCHAR2(50 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table USERS
--------------------------------------------------------

  CREATE TABLE "SERGEY"."USERS" 
   (	"ID" NUMBER, 
	"USERNAME" VARCHAR2(255 BYTE), 
	"SALT" VARCHAR2(32 BYTE), 
	"PASSWORD" VARCHAR2(88 BYTE), 
	"ROLES" VARCHAR2(255 BYTE) DEFAULT 'ROLE_USER', 
	"STATUS" VARCHAR2(20 BYTE) DEFAULT 'inactive', 
	"CREATED_AT" NUMBER
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for View AVERAGE_MARKS_BY_SUBJECTS
--------------------------------------------------------

  CREATE OR REPLACE FORCE EDITIONABLE VIEW "SERGEY"."AVERAGE_MARKS_BY_SUBJECTS" ("TEACHER_ID", "SUBJECT_ID", "AVG") AS 
  select p.id teacher_id, s.id subject_id, avg(m.value) avg
  from marks m
  join people p on p.id = m.teacher_id
  join subjects s on s.id = m.subject_id
  group by p.id, s.id;
--------------------------------------------------------
--  DDL for View AVERAGE_MARKS_BY_YEAR
--------------------------------------------------------

  CREATE OR REPLACE FORCE EDITIONABLE VIEW "SERGEY"."AVERAGE_MARKS_BY_YEAR" ("YEAR", "AVG") AS 
  select substr(g.name, 8, 4) year, avg(m.value) avg
  from marks m
  join people p on p.id = m.student_id
  join groups g on g.id = p.group_id
  where decode(REGEXP_INSTR (substr(g.name, 8, 4), '[^[:digit:]]'),0, 1, 0) = 1
  group by substr(g.name, 8, 4);
--------------------------------------------------------
--  DDL for View MARKS_TEACHER_20
--------------------------------------------------------

  CREATE OR REPLACE FORCE EDITIONABLE VIEW "SERGEY"."MARKS_TEACHER_20" ("ID", "FIRST_NAME", "LAST_NAME", "PATHER_NAME", "GROUP_ID", "TYPE") AS 
  select p."ID",p."FIRST_NAME",p."LAST_NAME",p."PATHER_NAME",p."GROUP_ID",p."TYPE" from people p
  join marks m on p.id = m.student_id
  where p.type = 's' and m.teacher_id = 20;
--------------------------------------------------------
--  DDL for View STUDENTS2013
--------------------------------------------------------

  CREATE OR REPLACE FORCE EDITIONABLE VIEW "SERGEY"."STUDENTS2013" ("ID", "FIRST_NAME", "LAST_NAME", "PATHER_NAME", "GROUP_ID", "TYPE") AS 
  select p."ID",p."FIRST_NAME",p."LAST_NAME",p."PATHER_NAME",p."GROUP_ID",p."TYPE" from people p
  join groups g on p.group_id = g.id
  where g.name like '%2016' and p.type = 's';
--------------------------------------------------------
--  DDL for View TEACHERS
--------------------------------------------------------

  CREATE OR REPLACE FORCE EDITIONABLE VIEW "SERGEY"."TEACHERS" ("ID", "FIRST_NAME", "LAST_NAME", "PATHER_NAME", "GROUP_ID", "TYPE") AS 
  select "ID","FIRST_NAME","LAST_NAME","PATHER_NAME","GROUP_ID","TYPE" from people where type = 'p';
--------------------------------------------------------
--  DDL for Index USERS_USERNAME
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."USERS_USERNAME" ON "SERGEY"."USERS" ("USERNAME") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index MARKS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."MARKS_PK" ON "SERGEY"."MARKS" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index PEOPLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."PEOPLE_PK" ON "SERGEY"."PEOPLE" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index USERS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."USERS_PK" ON "SERGEY"."USERS" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SUBJECTS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."SUBJECTS_PK" ON "SERGEY"."SUBJECTS" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index GROUPS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SERGEY"."GROUPS_PK" ON "SERGEY"."GROUPS" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table MARKS
--------------------------------------------------------

  ALTER TABLE "SERGEY"."MARKS" ADD CONSTRAINT "MARKS_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SERGEY"."MARKS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SUBJECTS
--------------------------------------------------------

  ALTER TABLE "SERGEY"."SUBJECTS" ADD CONSTRAINT "SUBJECTS_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SERGEY"."SUBJECTS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table USERS
--------------------------------------------------------

  ALTER TABLE "SERGEY"."USERS" ADD CONSTRAINT "USERS_CHK1" CHECK (status in ('inactive', 'active')) ENABLE;
  ALTER TABLE "SERGEY"."USERS" ADD CONSTRAINT "USERS_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SERGEY"."USERS" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("STATUS" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("ROLES" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("PASSWORD" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("SALT" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("USERNAME" NOT NULL ENABLE);
  ALTER TABLE "SERGEY"."USERS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PEOPLE
--------------------------------------------------------

  ALTER TABLE "SERGEY"."PEOPLE" ADD CONSTRAINT "PEOPLE_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SERGEY"."PEOPLE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table GROUPS
--------------------------------------------------------

  ALTER TABLE "SERGEY"."GROUPS" ADD CONSTRAINT "GROUPS_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SERGEY"."GROUPS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table PEOPLE
--------------------------------------------------------

  ALTER TABLE "SERGEY"."PEOPLE" ADD CONSTRAINT "FK_PEOPLE_GROUPS" FOREIGN KEY ("GROUP_ID")
	  REFERENCES "SERGEY"."GROUPS" ("ID") ENABLE;
--------------------------------------------------------
--  DDL for Trigger GROUP_NAME_UPDATE
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."GROUP_NAME_UPDATE" 
before update on groups
for each row
declare cnt number;
begin
  select count(*) into cnt from marks m
  join people p on p.id = m.student_id
  where p.group_id = :new.id;
    if (cnt > 0)
    then
        RAISE_APPLICATION_ERROR(-20005, 'Group name cannot be changed');
        rollback;
    end if;
end;
/
ALTER TRIGGER "SERGEY"."GROUP_NAME_UPDATE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger GROUP_PPL_DEL
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."GROUP_PPL_DEL" 
before delete on groups
for each row
begin
    update people set group_id = 12 where group_id = :old.id;
end;
/
ALTER TRIGGER "SERGEY"."GROUP_PPL_DEL" ENABLE;
--------------------------------------------------------
--  DDL for Trigger GROUPS_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."GROUPS_BIR" 
BEFORE INSERT ON groups 
FOR EACH ROW

BEGIN
  SELECT groups_seq.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;
/
ALTER TRIGGER "SERGEY"."GROUPS_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger GROUPS_NAME_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."GROUPS_NAME_BIR" 
BEFORE INSERT ON groups 
for each row
declare cnt number;
BEGIN
  select count(*) into cnt from groups where name = :new.name;
  if (cnt > 0) then
  RAISE_APPLICATION_ERROR(-20002, 'Group with same name already exists');
                rollback;
            end if;
END;
/
ALTER TRIGGER "SERGEY"."GROUPS_NAME_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger HUMAN_MARKS_DEL
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."HUMAN_MARKS_DEL" 
before delete on people
for each row
begin
    delete marks where student_id = :old.id;
end;
/
ALTER TRIGGER "SERGEY"."HUMAN_MARKS_DEL" ENABLE;
--------------------------------------------------------
--  DDL for Trigger MARKS_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."MARKS_BIR" 
BEFORE INSERT ON marks 
FOR EACH ROW

BEGIN
  SELECT marks_seq.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;
/
ALTER TRIGGER "SERGEY"."MARKS_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger MARKS_VALUE_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."MARKS_VALUE_BIR" 
BEFORE INSERT ON MARKS 
for each row
BEGIN
  if (:new.value < 2 or :new.value > 5) then
  RAISE_APPLICATION_ERROR(-20001, 'Mark value should be in [2..5]');
                rollback;
            end if;
END;
/
ALTER TRIGGER "SERGEY"."MARKS_VALUE_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger MARK_TEACHER_UPDATE
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."MARK_TEACHER_UPDATE" 
before update on marks
for each row
begin
    if (:new.teacher_id <> :old.teacher_id)
    then
        RAISE_APPLICATION_ERROR(-20005, 'Teacher cannot be changed');
        rollback;
    end if;
end;
/
ALTER TRIGGER "SERGEY"."MARK_TEACHER_UPDATE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger PEOPLE_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."PEOPLE_BIR" 
BEFORE INSERT ON people 
FOR EACH ROW

BEGIN
  SELECT people_seq.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;
/
ALTER TRIGGER "SERGEY"."PEOPLE_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger PEOPLE_TYPE_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."PEOPLE_TYPE_BIR" 
BEFORE INSERT ON people 
for each row
BEGIN
  if (:new.type <> 's' and :new.type <> 'p') then
  RAISE_APPLICATION_ERROR(-20003, 'Wrong human type '||:new.type);
                rollback;
            end if;
END;
/
ALTER TRIGGER "SERGEY"."PEOPLE_TYPE_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SUBJECT_DEL
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."SUBJECT_DEL" 
before delete on subjects
for each row
declare cnt number;
begin
    select count(*) into cnt from marks where subject_id = :old.id;
    if (cnt > 0)
    then
        rollback;
    end if;
end;
/
ALTER TRIGGER "SERGEY"."SUBJECT_DEL" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SUBJECT_NAME_UPDATE
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."SUBJECT_NAME_UPDATE" 
before update on subjects
for each row
declare cnt number;
begin
  select count(*) into cnt from marks where subject_id = :new.id;
    if (cnt > 0)
    then
        RAISE_APPLICATION_ERROR(-20004, 'Subject name cannot be changed');
        rollback;
    end if;
end;
/
ALTER TRIGGER "SERGEY"."SUBJECT_NAME_UPDATE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SUBJECTS_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."SUBJECTS_BIR" 
BEFORE INSERT ON subjects 
FOR EACH ROW

BEGIN
  SELECT subjects_seq.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;
/
ALTER TRIGGER "SERGEY"."SUBJECTS_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger USERS_BIR
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SERGEY"."USERS_BIR" 
BEFORE INSERT ON users
FOR EACH ROW

BEGIN
  SELECT users_seq.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;
/
ALTER TRIGGER "SERGEY"."USERS_BIR" ENABLE;
--------------------------------------------------------
--  DDL for Procedure AVERAGE_BY_SUBJECT
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."AVERAGE_BY_SUBJECT" as
begin
for r in(
select s.id, s.name, avg(m.value) avg from marks m
    join subjects s on m.subject_id = s.id
    group by s.id, s.name
    )
    loop
    DBMS_OUTPUT.PUT_LINE('id:'||r.id||' ,name:'||r.name||', avg:'||r.avg);
    end loop;
    end;

/
--------------------------------------------------------
--  DDL for Procedure AVERAGE_FROM_PERIOD
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."AVERAGE_FROM_PERIOD" (year_start in number, year_end in number) as
cursor curs(year_start in number, year_end in number) is
select to_number(substr(g.name, 8, 4)) year, avg(m.value) avg from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    where decode(REGEXP_INSTR (substr(name, 8, 4), '[^[:digit:]]'),0,substr(name, 8, 4), 0) > year_start and
    decode(REGEXP_INSTR (substr(name, 8, 4), '[^[:digit:]]'),0,substr(name, 8, 4),0) < year_end
    group by substr(g.name, 8, 4)
    order by year asc;
x curs%ROWTYPE;
old_avg number := 0;
diff number;
begin
    for x in curs(year_start, year_end)
    loop
        diff := x.avg - old_avg;
        old_avg := x.avg;
        DBMS_OUTPUT.PUT_LINE('year:'||x.year||' ,avg:'||x.avg||', diff:'||diff);
    end loop;
end;

/
--------------------------------------------------------
--  DDL for Procedure AVERAGE_MORE_THAN_3
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."AVERAGE_MORE_THAN_3" as
begin
for r in(
  select * from ( 
    select g.id, g.name, avg(m.value) avg from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    group by g.id, g.name 
    ) t where t.avg > 3
    )
    loop
    DBMS_OUTPUT.PUT_LINE(' id:'||r.id||' name:'||r.name||' average:'||r.avg);
    end loop;
    end;

/
--------------------------------------------------------
--  DDL for Procedure GROUPS_AVERAGE_BY_SUBJECT
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."GROUPS_AVERAGE_BY_SUBJECT" (subject_id_1 in number, subject_id_2 in number) as
begin
for r in(
select * from groups g where g.id in
(
  select t1.group_id from 
(select p.group_id, avg(m.value) avg from people p
join marks m on m.student_id = p.id
where m.subject_id = subject_id_1
group by p.group_id) t1,
(select p.group_id, avg(m.value) avg from people p
join marks m on m.student_id = p.id
where m.subject_id = subject_id_2
group by p.group_id) t2
where t1.group_id = t2.group_id and t1.avg > t2.avg
)
)
loop
DBMS_OUTPUT.PUT_LINE('id:'||r.id||' name:'||r.name);
end loop;
end;

/
--------------------------------------------------------
--  DDL for Procedure GROUPS_AVERAGE_BY_YEAR
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."GROUPS_AVERAGE_BY_YEAR" (year_start in number, year_end in number) as
begin
for r in(
select g.id, g.name, avg(m.value) avg from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    where substr(g.name, 8, 4) > year_start and substr(g.name, 8, 4) < year_end
    group by g.id, g.name
)
loop
DBMS_OUTPUT.PUT_LINE('id:'||r.id||' name:'||r.name||' , avg:'||r.avg);
end loop;
end;

/
--------------------------------------------------------
--  DDL for Procedure GROUPS_BY_SUBJECT
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."GROUPS_BY_SUBJECT" (subject_id in number) as
begin
for r in(
select g.name from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    join subjects s on s.id = m.subject_id
    where m.subject_id = subject_id
    group by g.name) 
    loop
    DBMS_OUTPUT.PUT_LINE(r.name);
    end loop;
    end;

/
--------------------------------------------------------
--  DDL for Procedure GROUPS_MAX_AVERAGE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."GROUPS_MAX_AVERAGE" (subject_id in number, max out number) as
begin
select max(avg(m.value)) into max from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    where m.subject_id = subject_id
    group by g.id;
end;

/
--------------------------------------------------------
--  DDL for Procedure GROUPS_MIN_AVERAGE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."GROUPS_MIN_AVERAGE" (teacher_id in number, group_id out number) as
begin
select id into group_id from (
select g.id, avg(m.value) avg from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    where m.teacher_id = teacher_id
    group by g.id
    ) where rownum = 1 order by avg asc;
end;

/
--------------------------------------------------------
--  DDL for Procedure MOSTLY_SUCCESSFULL_STUDENTS
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."MOSTLY_SUCCESSFULL_STUDENTS" as
begin
for r in(
select * from (select p.first_name, p.last_name, g.name, avg(m.value) avg from groups g
    join people p on p.group_id = g.id
    join marks m on m.student_id = p.id
    group by p.first_name, p.last_name, g.name) where rownum <= 5 order by avg desc
    )
    loop
    DBMS_OUTPUT.PUT_LINE(r.first_name||' '||r.last_name||', '||r.name);
    end loop;
    end;

/
--------------------------------------------------------
--  DDL for Procedure STUDENTS_MORE_THAN_AVERAGE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."STUDENTS_MORE_THAN_AVERAGE" (average in number) as
begin
for r in(
select * from (select p.first_name, p.last_name, avg(m.value) avg from people p
    join marks m on m.student_id = p.id
    group by p.first_name, p.last_name) where avg > average)
    loop
    DBMS_OUTPUT.PUT_LINE(r.first_name||' '||r.last_name||', '||r.avg);
    end loop;
    end;

/
--------------------------------------------------------
--  DDL for Procedure SUBJECT_MIN_AVERAGE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SERGEY"."SUBJECT_MIN_AVERAGE" (group_id in number,subject_id out number, min_average out number) as
begin
select id into subject_id from (
select m.subject_id id, avg(m.value) avg from people p
    join marks m on m.student_id = p.id
    where p.group_id = group_id
    group by m.subject_id
) where rownum = 1 order by avg asc;

select min(avg(m.value)) into min_average from people p
    join marks m on m.student_id = p.id
    where p.group_id != group_id and m.subject_id = subject_id
    group by m.subject_id;
end;

/
