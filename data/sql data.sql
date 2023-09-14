SELECT
to_char(swl.scheduled_date,'YYYY') as Agno,
to_char(swl.scheduled_date,'MM') as Mes,
ost2.code as Codigo,
ost2.description as Unidad,
tipomodalidad(substr(swl.modality_type,1,2),rpc.description) as Modalidad,
tipodia(swl.scheduled_date) as TipoDia,
tipohorario(swl.scheduled_date) as TipoHorario,
mod.code as CodigoEquipo,
mod.description as Equipo,
sum(case when swl.status_key = 40 then 1 else 0 end) as Agendados,
sum(case when swl.status_key = 50 then 1 else 0 end) as Cancelados,
sum(case when swl.status_key > 90 then 1 else 0 end) as Realizados,
sum(case when swl.status_key = 160 then 1 else 0 end) as Informados,
avg(case when swl.status_key = 160 then diashabiles(vrt.exam_done_date,vrt.approved_date) else 1 end) as DiasInforme,
count(distinct case when swl.status_key > 90 then ppsp.resource_id_key else Null end) as Tecnicos,
count(distinct case when swl.status_key = 160 then repv.person_key else Null end) as Radiologos
FROM site_worklist swl
inner join org_structure ost on ost.org_structure_key = swl.org_structure_key
inner join org_structure ost2 on ost2.org_structure_key = ost.parent_org_structure_key
inner join vwreportingtime2 vrt on vrt.sps_id = swl.sps_id
inner join rp_code rpc on (swl.rp_code_key = rpc.rp_code_key)
inner join pps pps on pps.pps_key = swl.pps_key
inner join modality mod on mod.modality_key = pps.modality_key 
left join pps_person_reference ppsp on (swl.pps_key = ppsp.pps_key and ppsp.person_reference_type_key = 8)
left join PAN_REPORT_MAX_VERSION repv on (swl.report_key = repv.report_key)
--where trunc(swl.scheduled_date) >= to_date('01/09/2017','DD/MM/YYYY')
group by 
to_char(swl.scheduled_date,'YYYY'),
to_char(swl.scheduled_date,'MM'),
ost2.code,
ost2.description,
tipomodalidad(substr(swl.modality_type,1,2), rpc.description),
tipodia(swl.scheduled_date),
tipohorario(swl.scheduled_date),
mod.code,
mod.description