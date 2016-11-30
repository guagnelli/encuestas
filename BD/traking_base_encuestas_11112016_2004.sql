-- Ejecución LEAS 11-11-2016 
ALTER TABLE encuestas.sse_respuestas ADD COLUMN texto_real varchar(1000) null; -- Se agrego el campo valor real, para agilizar los cálculos, es decir, "casi siempre" equivale a "si", "casi nunca" equivale a "No", "No envió mensaje" a "No aplica"

--- Modifica la entidad "sse_result_evaluacion" del esquema de encuestas -- Ejecución LEAS 15/11/2016
CREATE TABLE encuestas.sse_result_evaluacion_encuesta_curso (
	evaluacion_resul_cve int8 NOT NULL DEFAULT nextval('encuestas.sse_result_evaluacion_evaluacion_resul_cve_seq'::regclass),
	encuesta_cve int4 NOT NULL,
	course_cve int4 NOT NULL,
	group_id int4 NOT NULL,
	evaluado_user_cve int4 NOT NULL,
	evaluador_user_cve int4 NOT NULL,
	total_puntua_si int4 NOT NULL DEFAULT 0,
	total_nos int4 NOT NULL DEFAULT 0,
	total_no_puntua_napv int4 NOT NULL DEFAULT 0,
	total_reactivos_bono int4 NOT NULL DEFAULT 0,
	base int4 NOT NULL DEFAULT 0,
	calif_emitida numeric(6,3) NOT NULL DEFAULT 0,
	CONSTRAINT sse_result_evaluacion_encuesta_cursopkey PRIMARY KEY (evaluacion_resul_cve)
)
WITH (
	OIDS=FALSE
);

ALTER SEQUENCE encuestas.sse_indicador RESTART WITH 1; --reinicia contador de "encuestas.sse_indicador"

alter table encuestas.sse_preguntas add
  CONSTRAINT fkpre_indicador
  FOREIGN KEY (tipo_indicador_cve) 
  REFERENCES  encuestas.sse_indicador(indicador_cve);
  
 ---Agregrega fecha en la insersión de un registro Ejecución LEAS 17-11-2016
ALTER TABLE encuestas.sse_result_evaluacion_encuesta_curso ADD COLUMN fecha_add timestamp ;--agrega columna 
alter table encuestas.sse_result_evaluacion_encuesta_curso alter column fecha_add set default current_timestamp; --agrega current timestamp

alter table encuestas.sse_evaluacion alter column fecha set default current_timestamp; ç

-- Ejecución LEAS fecha 23/11/2016
---Agregar entidad para la administracin por bloque 
CREATE TABLE encuestas.sse_curso_bloque_grupo (
	course_cve int4 NOT NULL,
	mdl_groups_cve int4 NOT NULL,
	bloque int4 NOT NULL,
	CONSTRAINT sse_curso_bloque_grupopkey PRIMARY KEY (course_cve, mdl_groups_cve, bloque)
)
WITH (
	OIDS=FALSE
);

ALTER TABLE encuestas.sse_reglas_evaluacion ADD COLUMN eval_is_satisfaccion numeric (1);--saber si es encuesta de satisfacción o de desempeño

ALTER TABLE encuestas.sse_result_evaluacion_encuesta_curso DROP COLUMN eval_is_satisfaccion;--demas equivocación


