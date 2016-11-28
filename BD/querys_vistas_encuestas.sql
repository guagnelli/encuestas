--Cálculo de promedios versión dos, incluye ponderación y "valido no aplica" además de is_bono
--Desc.-Cálculo de promedio de cursos por grupo, validado, validador, rol de validador y rol de validado
 select grupo_cve, evaluador, rol_evaluador, evaluado, rol_evaluado, sum(netos) as total, sum(no_puntua) as no_puntua_reg, 
 sum(nos_) total_no, sum(no_aplica_promedio) as total_no_aplica_cuenta_promedio, sum(puntua) as puntua_reg, 
 (sum(netos) - sum(no_puntua)) as base_reg,
(round(sum(puntua)::numeric * 100/(sum(netos) - sum(no_puntua))::numeric,3)) as porcentaje
from (
--Cuenta el total de respuestas en "Si" los que puntuan
select COUNT(res.texto) as puntua, 0 as no_puntua, 0 as netos, ev.group_id "grupo_cve", 0 as nos_, 0 as no_aplica_promedio,
ev.evaluador_user_cve "evaluador", ev.evaluador_rol_id "rol_evaluador", ev.evaluado_user_cve "evaluado", ev.evaluado_rol_id "rol_evaluado"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822 and pre.is_bono = 1 and res.texto in('Si', 'Casi siempre', 'Siempre') 
group by ev.group_id, ev.evaluador_user_cve, ev.evaluador_rol_id, ev.evaluado_user_cve, ev.evaluado_rol_id
union
--Cuenta el total de base, que aplican para bono  
select 0 as puntua, 0 as no_puntua, COUNT(res.texto) as netos, ev.group_id "grupo_cve", 0 as nos_, 0 as no_aplica_promedio,
ev.evaluador_user_cve "evaluador", ev.evaluador_rol_id "rol_evaluador", ev.evaluado_user_cve "evaluado", ev.evaluado_rol_id "rol_evaluado"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822  and pre.is_bono = 1
group by ev.group_id, ev.evaluador_user_cve, ev.evaluador_rol_id, ev.evaluado_user_cve, ev.evaluado_rol_id
union
--se obtiene el total de "no aplica" y que es valido para no aplica No puntua
select 0 as puntua, COUNT(res.texto) as no_puntua, 0 as netos, ev.group_id "grupo_cve", 0 as nos_, 0 as no_aplica_promedio,
ev.evaluador_user_cve "evaluador", ev.evaluador_rol_id "rol_evaluador", ev.evaluado_user_cve "evaluado", ev.evaluado_rol_id "rol_evaluado"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822  and pre.is_bono = 1 and pre.valido_no_aplica = 1 
and res.texto in('No aplica', 'No envió mensaje')
group by ev.group_id, ev.evaluador_user_cve, ev.evaluador_rol_id, ev.evaluado_user_cve, ev.evaluado_rol_id
union
--Cuenta el total de respuestas en "No" 
select 0 as puntua, 0 as no_puntua, 0 as netos, ev.group_id "grupo_cve", COUNT(res.texto) as nos_, 0 as no_aplica_promedio,
ev.evaluador_user_cve "evaluador", ev.evaluador_rol_id "rol_evaluador", ev.evaluado_user_cve "evaluado", ev.evaluado_rol_id "rol_evaluado"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822 and pre.is_bono = 1 and res.texto in('No', 'Casi nunca', 'Nunca', 'Algunas veces') 
group by ev.group_id, ev.evaluador_user_cve, ev.evaluador_rol_id, ev.evaluado_user_cve, ev.evaluado_rol_id
union
--Cuenta el total de respuestas en "No aplica" y "No envió mensaje" que se contabilizan para el promedio 
select 0 as puntua, 0 as no_puntua, 0 as netos, ev.group_id "grupo_cve", 0 as nos_, COUNT(res.texto) as no_aplica_promedio,
ev.evaluador_user_cve "evaluador", ev.evaluador_rol_id "rol_evaluador", ev.evaluado_user_cve "evaluado", ev.evaluado_rol_id "rol_evaluado"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822  and pre.is_bono = 1 and pre.valido_no_aplica = 0 
and res.texto in('No aplica', 'No envió mensaje')
group by ev.group_id, ev.evaluador_user_cve, ev.evaluador_rol_id, ev.evaluado_user_cve, ev.evaluado_rol_id
) as calculos_promedio
group by grupo_cve, evaluador, rol_evaluador, evaluado, rol_evaluado
;



---Cálculo de promedios versión dos, incluye ponderación y valido no aplica además de is bono
---Obtener el promedio total de las encuestas contestadas de un curso y por grupo
select grupo_cve, sum(netos) as total, sum(no_puntua) as no_puntua_reg, sum(puntua) as puntua_reg, 
(sum(netos) - sum(no_puntua)) as base_reg,
(round(sum(puntua)::numeric * 100/(sum(netos) - sum(no_puntua))::numeric,3)) as porcentaje
from (
--Cuenta el total de respuestas en "Si" los que puntuan
select COUNT(res.texto) as puntua, 0 as no_puntua, 0 as netos, ev.group_id "grupo_cve"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822 and pre.is_bono = 1 and res.texto in('Si') 
group by ev.group_id
union
--Cuenta el total de base, que aplican para bono  
select 0 as puntua, 0 as no_puntua, COUNT(res.texto) as netos, ev.group_id "grupo_cve"
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822  and pre.is_bono = 1
group by ev.group_id
union
--se obtiene el total de "no aplica" y que es valido para no aplica
select 0 as puntua, COUNT(res.texto) as no_puntua, 0 as netos, ev.group_id "grupo_cve" 
from encuestas.sse_evaluacion ev 
join encuestas.sse_respuestas res on res.reactivos_cve = ev.reactivos_cve
join encuestas.sse_preguntas pre on pre.preguntas_cve = ev.preguntas_cve
join encuestas.sse_encuesta_curso encc on encc.encuesta_cve = res.encuesta_cve    
where encc.course_cve = 822  and pre.is_bono = 1 and pre.valido_no_aplica = 1 
and res.texto in('No aplica')
group by ev.group_id
) as calculos_promedio
group by grupo_cve
;

---Obtiene información de cursos asociados con usuario 
SELECT mco.shortname "clave_curso", mco.fullname, mr."name", mu.firstname, mu.lastname, mco.id "curso_id"
,mco.tipocur, mccg.tutorizado, mu.id "iduser"
FROM mdl_course mco
left join mdl_course_config mccg on mccg.course = mco.id
INNER JOIN mdl_context mctx ON mctx.instanceid = mco.id
INNER JOIN mdl_role_assignments mrass ON mctx.id = mrass.contextid
INNER JOIN mdl_role mr ON mr.id = mrass.roleid
INNER JOIN mdl_user mu on mu.id = mrass.userid
WHERE mr."name" like '%Alumno%' and mccg.tutorizado = 1;
mu.id=487;

---Obtiene reglas de evaluación de una encuestas, evaluador y evaluado 
select *, mrdo."name" , mrdor."name"  
 from encuestas.sse_encuesta_curso cce
 join encuestas.sse_encuestas enc on enc.encuesta_cve = cce.encuesta_cve
 join encuestas.sse_reglas_evaluacion re on re.reglas_evaluacion_cve = enc.reglas_evaluacion_cve
 left join mdl_role mrdor on mrdor.id = re.rol_evaluador_cve
 left join mdl_role mrdo on mrdo.id = re.rol_evaluado_cve 
 where cce.course_cve  = 838;

 
 --obtiene las reglas que podrian aplicar a la encuesta 
 WITH RECURSIVE busca_excepcion AS (
SELECT reg.reglas_evaluacion_cve, reg.rol_evaluado_cve, reg.rol_evaluador_cve, reg.is_excepcion, reg.tutorizado, reg.is_bono, reg.ord_prioridad
FROM encuestas.sse_reglas_evaluacion reg
JOIN encuestas.sse_encuestas enc ON enc.reglas_evaluacion_cve=reg.reglas_evaluacion_cve
JOIN encuestas.sse_encuesta_curso encc ON encc.encuesta_cve=enc.encuesta_cve
WHERE reg.rol_evaluador_cve = 5 AND reg.tutorizado = 1 AND encc.course_cve = 838  
UNION all 
select bex.is_excepcion, rer.rol_evaluado_cve, rer.rol_evaluador_cve, rer.is_excepcion, rer.tutorizado, rer.is_bono, rer.ord_prioridad 
from busca_excepcion bex
join encuestas.sse_reglas_evaluacion rer on rer.reglas_evaluacion_cve = bex.is_excepcion
)
select * FROM busca_excepcion OFFSET 0;

 
 --Vista para obtener información del curso especifica
drop view encuestas.view_datos_curso;
create view encuestas.view_datos_curso as
select cur.id as idc, shortname as clave, fullname as namec,
CASE ccfg.tipocur 
WHEN 0 THEN CASE SUBSTRING(cur.shortname from '%#\"GPC#\"%' FOR '#')  
WHEN 'GPC' THEN 'Curso basado en GPC' 
ELSE 'Curso' END
WHEN 1 THEN 'Diplomado' 
ELSE 'Error'  
END tipo_curso,
CASE ccfg.tipocur 
WHEN 0 THEN CASE SUBSTRING(cur.shortname from '%#\"GPC#\"%' FOR '#')  
WHEN 'GPC' THEN 3 
ELSE 2 end 
WHEN 1 THEN 1 
ELSE 0  
END as tipo_curso_id,
ccfg.horascur,
TO_CHAR(TO_TIMESTAMP(cur.startdate),'YYYY') anio,
--bono
ccfg.lastdate fecha_fin,
DATE(TO_CHAR(TO_TIMESTAMP(cur.startdate),'YYYY-MM-DD')) fecha_inicio,
CASE ccfg.tipocur 
WHEN 0 THEN 1
WHEN 1 THEN 3 
ELSE 0  
END alcance_curso,
CASE 
WHEN (ccfg.horascur > 120) THEN 6
WHEN (ccfg.horascur > 80 AND ccfg.horascur <= 120) THEN 3
WHEN (ccfg.horascur >= 40 AND ccfg.horascur <= 80) THEN 2
WHEN (ccfg.horascur < 40 AND ccfg.horascur > 0) THEN 1
ELSE 0
END puntaje_duracion
from mdl_course cur 
join mdl_course_config ccfg ON(ccfg.course = cur.id);


 --Consulta general para obtener datos del curso y el rol a partir de la vista de cursos
drop view encuestas.view_datos_usuario;
create view encuestas.view_datos_usuario as
SELECT 
--usuerio
u.id AS iduser, u.username AS nom_usuario, u.firstname,  u.lastname,
--nomina
--CURP	
--Fecha de Nacimiento	
--Sexo	
--Fecha de ingreso al IMSS	
--RFC
--Num. de red	
--Teléfono particular	
--tutorias
tutor.emailpart coreo_personal,
tutor.emaillab correo_institucional,
tutor.cve_departamento,
cat.cve_categoria,
cat.nom_nombre categoria,
tutor.num_ant_anio,
tutor.num_ant_quincena,
tutor.num_ant_dias,
tutor.fch_pre_registro,	
--curso
vcg.idc,
vcg.clave,
vcg.namec,
vcg.fecha_inicio,
vcg.fecha_fin,
vcg.tipo_curso_id,
vcg.tipo_curso,
vcg.horascur,
vcg.anio,
--Rol	
r.id rol_id,
r.name rol,
--bono
vcg.alcance_curso,
vcg.puntaje_duracion
FROM  mdl_role_assignments ra 
JOIN public.mdl_user u ON ra.userid = u.id
JOIN mdl_role r on (r.id = ra.roleid AND r.id IN (14,18,32,33,30))
JOIN mdl_context ct ON ct.id = ra.contextid
left join encuestas.view_datos_curso vcg on (vcg.idc = ct.instanceid)
LEFT JOIN tutorias.mdl_usertutor tutor ON(u.username = tutor.nom_usuario)
LEFT JOIN nomina.ssn_categoria cat ON(cat.cve_categoria = tutor.cve_categoria)
;
r.id= 5
WHERE 
TO_CHAR(vcg.fecha_inicio,'YYYY') = '2015' 
and u.id = 1423
and tutor.fch_pre_registro = '2010-01-07'
vcg.anio = '2016'


 --Consulta general para obtener datos del curso y el rol
 SELECT 
--usuerio
u.id AS iduser, u.username AS nom_usuario, u.firstname,  u.lastname,
--nomina
--CURP	
--Fecha de Nacimiento	
--Sexo	
--Fecha de ingreso al IMSS	
--RFC
--Num. de red	
--Teléfono particular	
--tutorias
tutor.emailpart coreo_personal,
tutor.emaillab correo_institucional,
tutor.cve_departamento,
cat.cve_categoria,
cat.nom_nombre categoria,
tutor.num_ant_anio,
tutor.num_ant_quincena,
tutor.num_ant_dias,
tutor.fch_pre_registro,	
--curso
cur.id curso_id,
cur.shortname clave_curso,
cur.fullname curso,
DATE(TO_CHAR(TO_TIMESTAMP(cur.startdate),'YYYY-MM-DD')) fecha_inicio,
ccfg.lastdate fecha_fin,
CASE ccfg.tipocur 
WHEN 0 THEN CASE SUBSTRING(cur.shortname from '%#\"GPC#\"%' FOR '#')  
WHEN 'GPC' THEN 'Curso basado en GPC' 
ELSE 'Curso' 
END
WHEN 1 THEN 'Diplomado' 
ELSE 'Error'  
END tipo_curso,
ccfg.horascur,
TO_CHAR(TO_TIMESTAMP(cur.startdate),'YYYY') anio,
--Rol	
r.id rol_id,
r.name rol,
--bono
CASE ccfg.tipocur 
WHEN 0 THEN 1
WHEN 1 THEN 3 
ELSE 0  
END alcance_curso,
CASE 
WHEN (ccfg.horascur > 120) THEN 6
WHEN (ccfg.horascur > 80 AND ccfg.horascur <= 120) THEN 3
WHEN (ccfg.horascur >= 40 AND ccfg.horascur <= 80) THEN 2
WHEN (ccfg.horascur < 40 AND ccfg.horascur > 0) THEN 1
ELSE 0
END puntaje_duracion
FROM  mdl_role_assignments ra 
JOIN public.mdl_user u ON ra.userid = u.id
JOIN mdl_role r on (r.id = ra.roleid AND r.id IN (14,18,32,33))
JOIN mdl_context ct ON ct.id = ra.contextid
JOIN mdl_course cur ON(ct.instanceid = cur.id)
JOIN mdl_course_config ccfg ON(ccfg.course = cur.id)
LEFT JOIN tutorias.mdl_usertutor tutor ON(u.username = tutor.nom_usuario)
LEFT JOIN nomina.ssn_categoria cat ON(cat.cve_categoria = tutor.cve_categoria)
WHERE TO_CHAR(TO_TIMESTAMP(cur.startdate),'YYYY') = '2015'
order by anio;

 
 
 
 