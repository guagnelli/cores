<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-04-07 09:01:28 --> Severity: Warning --> filesize(): stat failed for ./uploads/Plan_implementacion_CORES_v2_010218.pdf /Applications/mappstack/apache2/htdocs/cores2/application/controllers/Reportes_estaticos.php 83
ERROR - 2018-04-07 09:01:28 --> Severity: Warning --> readfile(./uploads/Plan_implementacion_CORES_v2_010218.pdf): failed to open stream: No such file or directory /Applications/mappstack/apache2/htdocs/cores2/application/controllers/Reportes_estaticos.php 98
ERROR - 2018-04-07 09:03:18 --> Severity: Warning --> filesize(): stat failed for ./uploads/Plan_implementacion_CORES_v2_010218.pdf /Applications/mappstack/apache2/htdocs/cores2/application/controllers/Reportes_estaticos.php 83
ERROR - 2018-04-07 09:03:18 --> Severity: Warning --> readfile(./uploads/Plan_implementacion_CORES_v2_010218.pdf): failed to open stream: No such file or directory /Applications/mappstack/apache2/htdocs/cores2/application/controllers/Reportes_estaticos.php 98
ERROR - 2018-04-07 09:10:02 --> 404 Page Not Found: Dec/informacion_general
ERROR - 2018-04-07 09:12:08 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:08 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:12:09 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:09 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:12:10 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:12 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:12 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:12:13 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:34 --> Could not find the language line "index"
ERROR - 2018-04-07 09:12:34 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:20:29 --> Could not find the language line "index"
ERROR - 2018-04-07 09:20:29 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:30:12 --> Query error: ERROR:  column "cve_presupuestal" does not exist
LINE 1: SELECT "id_indicador", "cve_presupuestal", "numerador", "den...
                               ^ - Invalid query: SELECT "id_indicador", "cve_presupuestal", "numerador", "denominador", "trimestre", "porcentaje_aprobados", "anio", "id_programa_proyecto"
FROM "dec"."h_indicadores"
ERROR - 2018-04-07 09:33:20 --> Could not find the language line "index"
ERROR - 2018-04-07 09:33:20 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 09:33:31 --> Could not find the language line "index"
ERROR - 2018-04-07 09:33:31 --> Query error: ERROR:  column hi.cve_presupuestal does not exist
LINE 8: ...                left join dec.h_indicadores hi ON(hi.cve_pre...
                                                             ^
HINT:  Perhaps you meant to reference the column "ui.clave_presupuestal". - Invalid query: select count(distinct IPT.unidad) total_unidades from (select D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                            	sum(hi.numerador) as numerador,
                                            	sum(hi.denominador) as denominador,
                                            round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                            from catalogos.unidades_instituto UI
                                            	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                            	inner join catalogos.regiones R on R.id_region = D.id_region
                                            	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                            	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                            where UI.anio = 2017 and UI.nivel_atencion = 1 and UI.id_region = 3 and UI.id_tipo_unidad = 4 and UI.id_delegacion = 8
                                            group by D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                            order by 1,2,3,4) IPT
                                            where IPT.numerador > 0
ERROR - 2018-04-07 09:35:38 --> Could not find the language line "index"
ERROR - 2018-04-07 09:35:38 --> Severity: Warning --> Division by zero /Applications/mappstack/apache2/htdocs/cores2/application/controllers/Dec.php 118
ERROR - 2018-04-07 09:45:33 --> Could not find the language line "index"
ERROR - 2018-04-07 09:49:32 --> Could not find the language line "index"
ERROR - 2018-04-07 10:07:59 --> Could not find the language line "index"
ERROR - 2018-04-07 10:07:59 --> Severity: Notice --> Array to string conversion /Applications/mappstack/apache2/htdocs/cores2/application/models/Dec_model.php 287
ERROR - 2018-04-07 10:08:30 --> Could not find the language line "index"
ERROR - 2018-04-07 10:55:53 --> Could not find the language line "index"
ERROR - 2018-04-07 10:55:53 --> Severity: Notice --> Undefined index: id_delegacion /Applications/mappstack/apache2/htdocs/cores2/application/models/Dec_model.php 328
ERROR - 2018-04-07 10:55:53 --> Query error: ERROR:  syntax error at or near ")"
LINE 3: ...= 2017 and UI.nivel_atencion = 2 and UI.id_delegacion = ) TU
                                                                   ^ - Invalid query: select count(TU) total_unidades from
                              (select * from catalogos.unidades_instituto UI
                              where UI.anio = 2017 and UI.nivel_atencion = 2 and UI.id_delegacion = ) TU
ERROR - 2018-04-07 10:56:30 --> Could not find the language line "index"
ERROR - 2018-04-07 10:56:30 --> Severity: Notice --> Undefined index: id_delegacion /Applications/mappstack/apache2/htdocs/cores2/application/models/Dec_model.php 328
ERROR - 2018-04-07 10:56:30 --> Query error: ERROR:  syntax error at or near ")"
LINE 3: ...= 2017 and UI.nivel_atencion = 2 and UI.id_delegacion = ) TU
                                                                   ^ - Invalid query: select count(TU) total_unidades from
                              (select * from catalogos.unidades_instituto UI
                              where UI.anio = 2017 and UI.nivel_atencion = 2 and UI.id_delegacion = ) TU
ERROR - 2018-04-07 10:56:33 --> Could not find the language line "index"
ERROR - 2018-04-07 10:56:33 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 10:56:46 --> Could not find the language line "index"
ERROR - 2018-04-07 10:57:04 --> Could not find the language line "index"
ERROR - 2018-04-07 10:58:23 --> Could not find the language line "index"
ERROR - 2018-04-07 11:01:50 --> Could not find the language line "index"
ERROR - 2018-04-07 11:09:04 --> Could not find the language line "index"
ERROR - 2018-04-07 11:09:12 --> Could not find the language line "index"
ERROR - 2018-04-07 11:26:51 --> Could not find the language line "index"
ERROR - 2018-04-07 11:27:21 --> Could not find the language line "index"
ERROR - 2018-04-07 11:28:16 --> Could not find the language line "index"
ERROR - 2018-04-07 11:34:46 --> Could not find the language line "index"
ERROR - 2018-04-07 11:34:46 --> Severity: Notice --> Undefined variable: filtros /Applications/mappstack/apache2/htdocs/cores2/application/views/dec/informacion_general/por_delegacion.php 314
ERROR - 2018-04-07 11:35:08 --> Could not find the language line "index"
