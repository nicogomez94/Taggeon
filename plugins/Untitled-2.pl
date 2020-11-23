package MyUP::MyUp;
##------------------------------------------------
## Package    : MyUP::MyUp
## Fecha      : 01/11/2014
##              Ricardo Zito  <rzito@palermo.edu>
##              Leando Diaco  <ldiaco@palermo.edu>
##------------------------------------------------
use strict;
use warnings;


# USE -----------
use JSON;
use SOAP::Lite (readable => 1, outputxml => 1);
use Digest::MD5 qw(md5_hex);
use Text::Iconv;
use LWP::UserAgent;
use Image::Resize;
use Time::Local;
use DateTime;
use UP::Goto;
use MyUP::Conf;
use UP::Connect2_DB;
use UP::SolicitudConfMyUP;
#use Data::Dumper;
#-------------------------------------------------------------------------
#  Constructor ;-)
#-------------------------------------------------------------------------
sub new {
  my $class = shift;
	my $self  = { 
	   REQUEST   => shift,
		SESION    => shift,
		DBHPGW    => UP::Connect2_DB::getDBHpgw(),
		DBHINSCRIPCION => undef, #se usa en /var/www/cgi-bin/myup/getDescripcion.pl => EL pl la inicia y la cierra
                               #se usa en /var/www/cgi-bin/myup/myup_readmision.pl  => EL pl la inicia y la cierra
		ICONVISO  => Text::Iconv->new("utf8","iso-8859-1"),
		PLANREQ   => ''
	};

	$self->{GOTO} = UP::Goto->new($self->{REQUEST},$self->{SESION}); 
   bless $self => $class;
   $self->completar_inscripcion(); # NUEVO ---
	$self->{PLANPREF} = $self->getPreferenciaUsuario('plan_pref','myup');

	$self->{PLANALUMNO}        = $self->getPlanesDelAlumno();
	$self->{CICLOACTUAL}       = $self->getCicloActual();
	$self->{INSC_FIN_ABIERTO}  = $self->estaHabilitadoInscripcion('F');
	$self->{INSC_CUR_ABIERTO}  = $self->estaHabilitadoInscripcion('C');
	$self->{INSC_DEPO_ABIERTO} = 1; #1 activo - 0 inactivo
	$self->{INSC_MOSTRAR_LINK_GRILLA_INS} = ($self->{INSC_CUR_ABIERTO}) ? 1 : $self->estaHabilitadoFechaApertura(); 

	return $self;
}

# Nuevo metodo para continuar con el formulario de Inscripcion
#-------------------------------------------------------------
sub completar_inscripcion{
   my $self = shift;

   my $legajo  = $self->{SESION}->{LEGAJO} || '';
   my $codp          = $self->necesita_update($legajo);

   if($codp) {
      $self->redirect_form_myp(MyUP::Conf->baseURL);
   }

}

#-------------------------------------------------------------
#Cuando se edite este metodo, tambi�n hay que editarlo en el paquete UP/SolicitudMyUP.pm -> sub necesita_update...
sub necesita_update {
   my $self    = shift;
   my $legajo  = shift || return 0;
	#----------------


	if( UP::SolicitudConfMyUP::ESTAHABILITADO ){
		if (not defined $self->{DBHINSCRIPCION}){
			$self->{DBHINSCRIPCION} = UP::Connect2_DB::getDBHinscripcion();
		}
   	my $db_legajo = $self->{DBHINSCRIPCION}->quote($legajo);
   	my $sql=<<SQL; 
   	SELECT   tbinsc.cod_inscripto
   	FROM     tb_inscripto tbinsc, tb_completar_form_myup tbcomp
   	WHERE    legajo = $db_legajo
               AND   tbinsc.cod_inscripto = tbcomp.cod_inscripto
               AND   fecha_actualizacion IS NULL
SQL
   	my $sth = $self->{DBHINSCRIPCION}->prepare($sql);
   	$sth->execute();
   	my $result = $sth->fetchrow_hashref();

   	return $result->{cod_inscripto} || 0;

	}

	return 0;

}

#-------------------------------------------------------------
#Cuando se edite este metodo, tambi�n hay que editarlo en el paquete UP/SolicitudMyUP.pm -> sub redirect_form_myp...
sub redirect_form_myp {
   my $self    = shift;
	my $baseUrl = shift || "https://wwws.palermo.edu";
#---------------------

	
	UP::Connect2_DB::_disconnect('DBHall');
	if( UP::SolicitudConfMyUP::ESTAHABILITADO ){

		my $urlRedirectFormMyUP = UP::SolicitudConfMyUP::SCRIPT_PATH_MYUP;
		my $urlRedirect         = $baseUrl.$urlRedirectFormMyUP;

		my $redirect =<<STR;
         <html>
            <head>
               <script type="text/javascript">
               <!--
                  window.location = "$urlRedirect";
               //-->
               </script>
            </head>
         <body></body>
         </html>
STR
		print "content-type:text/html\n\n";
		print $redirect;
		exit;

	}

	return 0;
}

#-------------------------------------------------------------

sub estaHabilitadoFechaApertura {
	my $self = shift;

	my $sql = "SELECT count(*) as count FROM  myup_link_fecha_insc WHERE  date(now()) BETWEEN date(desde) AND date(hasta)";

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   if ($sth->errstr){
      my $error = $sth->errstr;
      $self->loguearFile("Error sql. Consulta = $sql , Metodo = estaHabilitadoFechaApertura, Error= $error");
      return 0;
   }
	
	my $res= $sth->fetchrow_hashref;
   my $cont = $res->{'count'} || return 0;
   return 1 if ($cont > 0 );
   return 0;
	
}

#-------------------------------------------------------------

sub getDesarrolloProfesional {
	my $self = shift;

	my $baseUrl = MyUP::Conf->baseURL() || '';

	my $ret =<<STR;
	<div> <!--{IFRAME}--> 
		<iframe onload="resizeMailIframe(this);" src="$baseUrl/cgi-bin/homer/dprof2.pl" scrolling="No" width="792px" height="1px" frameborder="0"  border="0" marginwidth="0px" marginheight="0px"></iframe> <!--{/IFRAME}-->
	</div>
STR
	return $ret;
}

#-------------------------------------------------------------

sub getCalendarioAsignaturasActividades {
	my $self = shift;
	my ($calendarioAsignaturas,$calendarioActividades) = ('','');
	
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   
	if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getCalendarioAsignaturasActividades Paquete MyUP::MyUp");
      return ['',''];
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);

   my $fechaBD = $self->{DBHPGW}->quote($self->getFechaActual());

	my $sql=<<SQL;
      SELECT distinct
                     i.strm, i.acad_plan, c.term_category, oc.class_nbr, oc.crse_id, a.descrlong as descr,
                     do2.mon, do2.tues, do2.wed, do2.thurs, do2.fri, do2.sat, do2.sun,
                     do2.meeting_time_start, do2.meeting_time_end,
                     do2.dia, do2.url, do2.facility_id,
                     do2.start_dt, do2.end_dt, p.first_name as name, p.last_name, i.acad_career, i.up_seleccion_asig,
                     car.facility_id as facility_id_novedad, i.stdnt_enrl_status, do2.up_bb_tipo_curso as modalidad, do2.start_dt as fecha_inicio
      FROM
              (
						SELECT max(strm) as strm, acad_career, term_category FROM (
							 SELECT acad_career, strm, '' as term_category FROM ps_up_cic_ac_cu_vw
							 UNION
							 SELECT acad_career, strm, 'F' as term_category FROM ps_up_cic_ac_fi_vw
						) as cu
						GROUP BY acad_career, term_category
              ) as c,
              ps_up_inscr_act_vw as i INNER JOIN ps_up_ofe_ci_ac_vw oc ON i.strm = oc.strm AND i.class_nbr = oc.class_nbr 
              INNER JOIN ps_up_asignatur_vw a ON oc.crse_id = a.crse_id
              INNER JOIN  ps_up_deta_ofer_vw do2 ON
							  oc.crse_id = do2.crse_id AND
							  oc.crse_offer_nbr = do2.crse_offer_nbr AND
							  oc.strm = do2.strm AND
							  oc.session_code = do2.session_code AND
							  oc.class_section = do2.class_section
              LEFT JOIN ps_up_personas_vw p ON p.emplid=do2.emplid
				  LEFT JOIN ps_up_cartdig_nove car ON
							  car.crse_id            = oc.crse_id             AND
							  car.class_nbr          = oc.class_nbr           AND
							  car.strm               = oc.strm                AND
							  car.session_code       = oc.session_code        AND
							  car.dia                = do2.dia                AND
                       car.meeting_time_start = do2.meeting_time_start AND
                       car.meeting_time_end   = do2.meeting_time_end   AND
                       car.fecha_novedad      = $fechaBD
      WHERE
              (
                  c.term_category = 'F' AND (do2.start_dt + interval '5 d') >= CURRENT_DATE 
                  OR
                  c.term_category != 'F'
              ) AND

              i.stdnt_enrl_status IN ('P','E')     AND
				  c.acad_career       = i. acad_career AND
              c.strm              = i.strm         AND
              i.emplid            like $legajoBD 
      ORDER BY stdnt_enrl_status DESC, term_category ASC, dia ASC, meeting_time_start ASC, descr ASC
SQL

	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";
	#$self->logDebug($sql);

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $classNbr    = $res->{'class_nbr'}          || '';
		my $strm        = $res->{'strm'}               || '';
		my $isFinal     = $res->{'term_category'}      || '';
      my $nombreAsig  = $res->{'descr'}              || '';
      my $lunes       = $res->{'mon'}                || 'N';
      my $martes      = $res->{'tues'}               || 'N';
      my $miercoles   = $res->{'wed'}                || 'N';
      my $jueves      = $res->{'thurs'}              || 'N';
      my $viernes     = $res->{'fri'}                || 'N';
      my $sabado      = $res->{'sat'}                || 'N';
      my $domingo     = $res->{'sun'}                || 'N';
		my $horaInicio  = $res->{'meeting_time_start'} || '';
      $horaInicio     = $self->formatTime($horaInicio);
      my $horaFin     = $res->{'meeting_time_end'}   || '';
      $horaFin        = $self->formatTime($horaFin);
      my $nombre      = $res->{'name'}               || '';
      my $apellido    = $res->{'last_name'}          || '';
		my $docente     = "$nombre $apellido";
      my $acadCareer  = $res->{'acad_career'}        || '';
		my $startDt  	 = $res->{'start_dt'}           || '';
		my $url         = $res->{'url'}                || '';
	 	$url = "http://$url" if ($url !~ m/^https?:\/\//i && $url ne '' );
		my $crse_id     = $res->{'crse_id'}            || '';
		my $fecha_inicio  = $res->{'fecha_inicio'}     || '';
		my $fechaInicio   = $self->formatDate($fecha_inicio);

		my $aulaEdificio        = $res->{'facility_id'}         || '';
		my $aulaEdificioNovedad = $res->{'facility_id_novedad'} || '';
		$aulaEdificio = $aulaEdificioNovedad if ($aulaEdificioNovedad ne '');
		$aulaEdificio = $self->trimAula($aulaEdificio);

		my $endDate  = $res->{'end_dt'}    || '';	
		my $acadPlan = $res->{'acad_plan'} || '';	
		my $stdnt_enrl_status = $res->{'stdnt_enrl_status'} || '';

		my $modalidad = $res->{'modalidad'} || '';

		my $aulaLugar = 'Aula: ';
		if($acadCareer eq 'DEPO'){
			$aulaLugar = 'Lugar: ';
		}
	
		my $aula          = ''; 
		$aula = ($aulaEdificio ne '') ? $aulaLugar.$aulaEdificio : $aulaLugar.'Consultar';
		
		my $modalidadTipo = '';
		$modalidadTipo = ($isFinal eq 'F') ? 'del final' : 'del cursado';

		if($modalidad eq 'M'){
			$aula .= " / Online";
		}elsif($modalidad eq 'O'){
			$aula = "Modalidad $modalidadTipo: Online";
		}
		#else{
		#	$aula .= "<br/>Modalidad $modalidadTipo: Presencial";
		#}

		my $dia  = '0';
		my $diaClase = 0;

     	if ($lunes eq 'Y'){
     	   $dia = 'Lunes';
     	   $diaClase = 1;
     	}elsif($martes eq 'Y'){
     	   $dia = 'Martes';
     	   $diaClase = 2;
     	}elsif($miercoles eq 'Y'){
     	   $dia = 'Mi�rcoles';
     	   $diaClase = 3;
     	}elsif($jueves eq 'Y'){
     	   $dia = 'Jueves';
     	   $diaClase = 4;
     	}elsif($viernes eq 'Y'){
     	   $dia = 'Viernes';
     	   $diaClase = 5;
     	}elsif($sabado eq 'Y'){
     	   $dia = 'S�bado';
     	   $diaClase = 6;
     	}elsif($domingo eq 'Y'){
     	   $dia = 'Domingo';
     	   $diaClase = 7;
     	}else{
      	$self->enviarErrorEmail("El dia vino vacio en el metodo getCalendarioAsignaturasActividades para la asignatura $nombreAsig y el legajo $legajo");
			return ['',''];
    	}
	  
	  	my $strCurOrFinal = ($isFinal eq 'F') ? '(F)' : '(C)'; 
		
		$docente = $self->trim($docente);
		#my $strDocente    = ($docente ne '')  ? "Prof: ". uc($docente) : 'Prof: Sin Asignar';
		my $strDocente    = ($docente ne '')  ? "Prof: ". $self->trimNombreDocente($docente,1) : 'Prof: Sin Asignar';
		my $diaMes   		= ($isFinal eq 'F') ? $self->getDiaMes($endDate).' ' : '';
	
		my $strDiaHorario = "$dia $diaMes $horaInicio - $horaFin hs."; 
		my $strDiaInicioCursadas = "Fecha de Inicio: $fechaInicio";

		if($modalidad eq 'O'){
			if($isFinal eq 'F'){
				$strDiaHorario = "$dia $diaMes";
				if($horaInicio ne '00:00' && $horaFin ne '23:59'){
					$strDiaHorario .= " de $horaInicio a $horaFin hs."; 
				}
			}else{
				$strDiaHorario = "";
				$strDiaInicioCursadas = "Inicio de cursada: $fechaInicio";
			}	
		}else{
			$strDiaInicioCursadas = $self->getDiaInicioClases($fecha_inicio,$diaClase);
			if($strDiaInicioCursadas ne ''){
				$strDiaInicioCursadas = "Inicio de cursada: $strDiaInicioCursadas";
			}else{
				$strDiaInicioCursadas = "Inicio de cursada: $fechaInicio";
			}	
		}

		if($acadCareer ne 'UGRD' && $acadCareer ne 'GRAD' && $acadCareer ne 'EXED'){
			
			#$aula = ($aulaEdificio ne '') ? 'Aula: '.$aulaEdificio : 'Aula: Sin Asignar';
			#$aula = ''; #No se muestra el aula hasta que desarrollemos las novedades
		
			$calendarioActividades .=<<STR;
					<li class="perfiles">         
						<p class="titulos">$nombreAsig $strCurOrFinal $crse_id</p>
						<p class="normal">$strDiaHorario </br>$strDocente </br> $aula</p>
		         </li>
STR
		}else{
			my $linkUpVirtual = '';
			use URI::Escape;
			my $url_redirect = uri_escape($url);
			if ($url =~ m/(chamilo)|(moodle)|(https:\/\/acad\.palermo\.edu\/)|(https:\/\/palermo\.blackboard\.com\/)/){
				if ($1){
					if ($self->{GOTO}->tienePermisosUpVirtualChamilo()){
         			$linkUpVirtual .=<<STR;
             <a href="/cgi-bin/myup/goto.pl?app=chamilo&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
      			}
				}elsif ($2){
			      if ($self->{GOTO}->tienePermisosUpVirtualMoodle()){
         			 $linkUpVirtual .=<<STR;
							<a href="/cgi-bin/myup/goto.pl?app=moodle&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
					}
				}elsif ($3){
               if ($self->{GOTO}->tienePermisosUpVirtual()){                        
                  $linkUpVirtual .=<<STR;
             <a href="/cgi-bin/myup/goto.pl?app=up_virtual&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
               }
				}elsif ($4){
					my $leyenda = ($modalidad eq 'O') ? 'Ingresar' : 'Aula virtual';
               $linkUpVirtual .=<<STR;
             		<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">$leyenda</a>
STR
				}
			}elsif($url ne ''){
             $linkUpVirtual .=<<STR;
             		<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">Blog Docente</a>
STR
			}

			#$aula = ($aulaEdificio ne '') ? 'Aula: '.$aulaEdificio : 'Aula: Sin Asignar';
			#$aula = ''; #No se muestra el aula hasta que desarrollemos las novedades

			my $inicioClases = '';
	
			if ($stdnt_enrl_status eq 'P'){

				if($isFinal ne 'F'){
					$inicioClases =<<STR;
				<p class="normal">$strDiaInicioCursadas</p>
STR
				}

				$calendarioAsignaturas .=<<STR;
						<li class="perfiles">         
							<p class="titulos">$nombreAsig $strCurOrFinal $crse_id $acadPlan</p>
							$inicioClases
							<p class="normal">$strDiaHorario</p>
							<p class="normal">Estado: <b>Solicitud a confirmar</b></p>
						</li>
STR
			}else{

				if($isFinal ne 'F'){
               $inicioClases =<<STR;
								$strDiaInicioCursadas</br>
STR
            }
		
				if($strDiaHorario ne ''){
					$strDiaHorario = "$strDiaHorario </br>";
				}
	
				$calendarioAsignaturas .=<<STR;
						<li class="perfiles">         
							<p class="titulos">$nombreAsig $strCurOrFinal $crse_id $acadPlan</p>
							<p class="normal">$strDiaHorario $strDocente </br> $aula $linkUpVirtual </br>$inicioClases</p>
						</li>
STR
			}
		}

	}

	if ($calendarioAsignaturas eq '' && $calendarioActividades eq '' && $self->isActivoPlan($legajo)){
		$calendarioAsignaturas .=<<STR;
		<li class="sinasignatura">         
			<p class="titulos">No se encuentra inscripto en ninguna asignatura.</p>
		</li>
STR

	}
	return [$calendarioAsignaturas,$calendarioActividades];
}

#-------------------------------------------------------------

sub getDiaInicioClases {
   my $self = shift;
	my $fechaInicio = shift || return '';
	my $diaCursada  = shift || return '';

   my $anio = '';
   my $mes  = '';
   my $dia  = '';

	if ($fechaInicio =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
	 	$anio = $1;
	   $mes  = $2;
  		$dia  = $3; 
   }

	
	my $dt = DateTime->new(
								year 	=>$anio,
                    		month =>$mes,
                     	day   =>$dia,
								);

	if(MyUP::Conf->FERIADOS->{$self->formatDate($dt->ymd)}){
   	$dt->add(days =>1);
   }

	while ($dt->day_of_week() != $diaCursada) {
      $dt->add(days =>1);		
      
		if(MyUP::Conf->FERIADOS->{$self->formatDate($dt->ymd)}){
         $dt->add(days =>1);
      }

   }

	return $self->formatDate($dt->ymd);
}

#-------------------------------------------------------------

sub getAsignaturasDocente {
	my $self = shift;
	my $calendarioAsignaturas = '';
	
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   
	if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getCalendarioAsignaturasDocente Paquete MyUP::MyUp");
      return '';
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);

   my $fechaBD = $self->{DBHPGW}->quote($self->getFechaActual());
	
	my $sql=<<SQL;
      SELECT distinct
                     i.strm, c.term_category, oc.class_nbr, oc.crse_id, a.descrlong as descr,
                     do2.mon, do2.tues, do2.wed, do2.thurs, do2.fri, do2.sat, do2.sun,
                     do2.meeting_time_start, do2.meeting_time_end,
                     do2.dia, do2.url, do2.facility_id,
                     do2.start_dt, do2.end_dt,
                     car.facility_id as facility_id_novedad, do2.up_bb_tipo_curso as modalidad
      FROM
              (
						SELECT max(strm) as strm, acad_career, term_category FROM (
							 SELECT acad_career, strm, '' as term_category FROM ps_up_cic_ac_cu_vw
							 UNION
							 SELECT acad_career, strm, 'F' as term_category FROM ps_up_cic_ac_fi_vw
						) as cu
						GROUP BY acad_career, term_category
              ) as c,
              ps_up_inscr_act_vw as i INNER JOIN ps_up_ofe_ci_ac_vw oc ON i.strm = oc.strm AND i.class_nbr = oc.class_nbr 
              INNER JOIN ps_up_asignatur_vw a ON oc.crse_id = a.crse_id
              INNER JOIN  ps_up_deta_ofer_vw do2 ON
							  oc.crse_id = do2.crse_id AND
							  oc.crse_offer_nbr = do2.crse_offer_nbr AND
							  oc.strm = do2.strm AND
							  oc.session_code = do2.session_code AND
							  oc.class_section = do2.class_section
				  LEFT JOIN ps_up_cartdig_nove car ON
							  car.crse_id            = oc.crse_id             AND
							  car.class_nbr          = oc.class_nbr           AND
							  car.strm               = oc.strm                AND
							  car.session_code       = oc.session_code        AND
							  car.dia                = do2.dia                AND
                       car.meeting_time_start = do2.meeting_time_start AND
                       car.meeting_time_end   = do2.meeting_time_end   AND
                       car.fecha_novedad      = $fechaBD 
      WHERE
              i.stdnt_enrl_status = 'E'           AND
              c.acad_career       = i.acad_career AND
              c.strm              = i.strm        AND
              do2.emplid like $legajoBD  
      ORDER BY term_category ASC, dia ASC, meeting_time_start ASC, descr ASC
SQL

	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";

	my	$titulo = <<STR;
					<h2 class="asdocentes"> 		
						Asignaturas docentes
					</h2>
STR

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $classNbr    = $res->{'class_nbr'}          || '';
		my $strm        = $res->{'strm'}               || '';
		my $isFinal     = $res->{'term_category'}      || '';
      my $nombreAsig  = $res->{'descr'}              || '';
      my $lunes       = $res->{'mon'}                || 'N';
      my $martes      = $res->{'tues'}               || 'N';
      my $miercoles   = $res->{'wed'}                || 'N';
      my $jueves      = $res->{'thurs'}              || 'N';
      my $viernes     = $res->{'fri'}                || 'N';
      my $sabado      = $res->{'sat'}                || 'N';
      my $domingo     = $res->{'sun'}                || 'N';
		my $horaInicio  = $res->{'meeting_time_start'} || '';
      $horaInicio     = $self->formatTime($horaInicio);
      my $horaFin     = $res->{'meeting_time_end'}   || '';
      $horaFin        = $self->formatTime($horaFin);
		my $startDt  	 = $res->{'start_dt'}           || '';
		my $url         = $res->{'url'}                || '';
	 	$url = "http://$url" if ($url !~ m/^https?:\/\//i && $url ne '' );
		my $crse_id     = $res->{'crse_id'}            || '';	
		
		my $aulaEdificio        = $res->{'facility_id'}         || '';	
		my $aulaEdificioNovedad = $res->{'facility_id_novedad'} || '';
		$aulaEdificio = $aulaEdificioNovedad if ($aulaEdificioNovedad ne '');
		$aulaEdificio = $self->trimAula($aulaEdificio);
		
		my $endDate   = $res->{'end_dt'}    || '';
		my $modalidad = $res->{'modalidad'} || '';

		my $dia  = '0';
     	if ($lunes eq 'Y'){
     	   $dia = 'Lunes';
     	}elsif($martes eq 'Y'){
     	   $dia = 'Martes';
     	}elsif($miercoles eq 'Y'){
     	   $dia = 'Mi�rcoles';
     	}elsif($jueves eq 'Y'){
     	   $dia = 'Jueves';
     	}elsif($viernes eq 'Y'){
     	   $dia = 'Viernes';
     	}elsif($sabado eq 'Y'){
     	   $dia = 'S�bado';
     	}elsif($domingo eq 'Y'){
     	   $dia = 'Domingo';
     	}else{
      	$self->enviarErrorEmail("El dia vino vacio en el metodo getCalendarioAsignaturasActividades para la asignatura $nombreAsig y el legajo $legajo");
			return '';
    	}
	  
	  	my $strCurOrFinal = ($isFinal eq 'F') ? '(F)' : '(C)'; 
		my $diaMes   		= ($isFinal eq 'F') ? $self->getDiaMes($endDate).' ' : '';
		my $aula          = ''; 
		
		my $linkUpVirtual = '';
		use URI::Escape;
		my $url_redirect = uri_escape($url);
		if ($url =~ m/(chamilo)|(moodle)|(https:\/\/acad\.palermo\.edu\/)|(https:\/\/palermo\.blackboard\.com\/)/){
			if ($1){
				if ($self->{GOTO}->tienePermisosUpVirtualChamilo()){
        			$linkUpVirtual .=<<STR;
             <a href="/cgi-bin/myup/goto.pl?app=chamilo&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
      		}
			}elsif ($2){
		      if ($self->{GOTO}->tienePermisosUpVirtualMoodle()){
        			 $linkUpVirtual .=<<STR;
						<a href="/cgi-bin/myup/goto.pl?app=moodle&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
				}
			}elsif ($3){
              if ($self->{GOTO}->tienePermisosUpVirtual()){                        
                 $linkUpVirtual .=<<STR;
            <a href="/cgi-bin/myup/goto.pl?app=up_virtual&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
              }
			}elsif ($4){
				my $leyenda = ($modalidad eq 'O') ? 'Ingresar a la clase' : 'Aula virtual';
				$linkUpVirtual .=<<STR;
					<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">$leyenda</a>
STR
			}
		}elsif($url ne ''){
            $linkUpVirtual .=<<STR;
            			<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">Blog Docente</a>
STR
		}

			$aula = ($aulaEdificio ne '') ? 'Aula: '.$aulaEdificio : '';
			#$aula = ''; #No se muestra el aula hasta que desarrollemos las novedades
				
#			if(MyUP::Conf->tienePermisosSyllabus($self->{SESION}) && $isFinal ne 'F'){	
#				$calendarioAsignaturas .=<<STR;
#				<li class="perfiles">         
#                  <p class="titulos">$nombreAsig $strCurOrFinal $crse_id</p>         
#                  <p class="normal">$dia $diaMes $horaInicio - $horaFin hs. </br> $aula $linkUpVirtual</p>
#						<p class="normal"><a style="color:#00A3D8; font-size:14px; text-decoration:none" href="/cgi-bin/myup/search_syllabus.pl?action=continuar&filtro=$crse_id&tipo_filtro=crse_id&type-continuar=crse_id&crse_id=$crse_id">Proponer/Editar Syllabus</a></p>
#            </li>
#STR
#			}else{
#				$calendarioAsignaturas .=<<STR;
#            <li class="perfiles">         
#                  <p class="titulos">$nombreAsig $strCurOrFinal $crse_id</p>         
#                  <p class="normal">$dia $diaMes $horaInicio - $horaFin hs. </br> $aula $linkUpVirtual</p>
#            </li>
#STR
#			}
				$calendarioAsignaturas .=<<STR;
            <li class="perfiles">         
                  <p class="titulos">$nombreAsig $strCurOrFinal $crse_id</p>         
                  <p class="normal">$dia $diaMes $horaInicio - $horaFin hs. </br> $aula $linkUpVirtual</p>
            </li>
STR
	}

   if(MyUP::Conf->tienePermisosSyllabus($self->{SESION})){
#            $titulo = <<STR;
#                  <h2 class="asalumnos" style="width:665px;">
#                      Asignaturas docentes   
#                  </h2>
#                  <h2 class="perfiles" style="padding-top:1px;margin-right: 0px; margin-left: 0px; padding-right: 10px; padding-right: 10px !important;">
#                     <a style="color:#00A3D8; font-size:14px; text-decoration:none" href="#" onclick="alert('Hacer Proponer syllabus sin crseId');">Nuevo Syllabus</a>
#                  </h2>
#               <h2 class="perfiles" style="padding-top:1px;margin-left: 0px; padding-right: 0px; padding-right: 0px !important;">
#                     <a style="color:#00A3D8; font-size:14px; text-decoration:none" href="/cgi-bin/myup/search_syllabus.pl">B&uacute;squeda de Syllabus</a>
#                  </h2>
#STR
            $titulo = <<STR;
                  <h2 class="asalumnos" style="width:665px;">
                      Asignaturas docentes   
                  </h2>
                  <h2 class="perfiles" style="padding-top:1px;margin-right: 0px; margin-left: 0px; padding-right: 10px; padding-right: 10px !important;">
                     <a style="color:#00A3D8; font-size:14px; text-decoration:none" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                  </h2>
               <h2 class="perfiles" style="padding-top:1px;margin-left: 0px; padding-right: 0px; padding-right: 0px !important;">
                     <a style="color:#00A3D8; font-size:14px; text-decoration:none" href="/cgi-bin/myup/search_syllabus.pl">B&uacute;squeda de Syllabus</a>
                  </h2>
STR
    }else{
           $titulo = <<STR;
					<h2 class="asalumnos" style="width:860px;">
                      Asignaturas docentes   
                  </h2>
                  <h2 class="perfiles" style="padding-top:1px;margin-left: 0px; padding-right: 0px; padding-right: 0px !important;">
                     <a style="color:#00A3D8; font-size:14px; text-decoration:none" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                  </h2>
STR
   }

	if ($calendarioAsignaturas eq ''){
		$calendarioAsignaturas =<<STR;
		$titulo
       <ul class="perfiles" style="border-bottom:0px; margin-left: 8px;">
			 <li class="sinasignatura">         
				<p class="titulos">No se encuentra dictando ninguna asignatura.</p>
			 </li>
       </ul>
STR
	}else{
			$calendarioAsignaturas = $titulo.'<ul class="perfiles" style="border-bottom:0px; margin-left: 8px;">'.$calendarioAsignaturas."</ul>";
	}
	
	return $calendarioAsignaturas;
}

#-------------------------------------------------------------

sub getPlanesCalificaciones {
	my $self = shift;
  
   my $legajo = $self->{SESION}->{LEGAJO} || '';	

   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getPlanesCalificaciones Paquete MyUP::MyUp");
      return '';
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);
	
	my $sql=<<SQL;
			SELECT pa.acad_plan, ip.descr, pa.up_fin_aprob, pa.up_fin_pend, pa.up_curs_aprob, pa.up_curs_pend, 
                pa.up_avance_cursado, pa.up_avance_finales, pa.up_estado_plan, pa.up_deuda_matricula, pf.acad_prog, pa.up_curs_pend_fin, pa.acad_career
         FROM 
					ps_up_plan_alumnos as pa INNER JOIN ps_up_in_planes_vw ip ON pa.acad_plan = ip.acad_plan
               LEFT JOIN ps_up_plan_fac_vw as pf ON pa.acad_plan = pf.acad_plan
         WHERE emplid like $legajoBD  AND 
					pa.up_estado_plan in ('A','B','E')
         ORDER BY pa.up_estado_plan ASC, descr ASC
SQL
	#print "content-type:text/html\n\n";print $sql;exit;
	#$self->logDebug($sql);

   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

	my $acadPlanParam = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlanParam eq ''){
		$acadPlanParam = $self->{PLANPREF} || '';
	}
	my @arrayPlanes;
	my $pos           = -1;
	my $posAuxDefault = -1;
	my $resAux        = {};
	my $cont          = 0;
	while ( my $res  = $sth->fetchrow_hashref()) { 
      my $acadPlan = $res->{'acad_plan'} || '';

		if ($pos < 0){
			if ($acadPlan eq $acadPlanParam){
				$pos = $cont;
			}else{
				if ($posAuxDefault >= 0){
					my $status        = $res->{'up_estado_plan'} || '';
					my $acadCareer    = $res->{'acad_career'}    || '';
					my $acadProg      = $res->{'acad_prog'}      || '';

					my $statusAux     = $resAux->{'up_estado_plan'} || '';
					my $acadCareerAux = $resAux->{'acad_career'}    || '';
					my $acadProgAux   = $resAux->{'acad_prog'}      || '';
					my $acadPlanAux   = $resAux->{'acad_plan'}      || '';

					if ($status eq 'A'){
						my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
						if ($up_deuda_matricula !~ m/^N$/i){
							my ($puedeRematricular,$strmRematricular) = @{$self->getRematricular($legajo, $acadPlan, $acadProg)};
							$status = 'R' if($puedeRematricular);
						}
					}

					if ($statusAux eq 'A'){
						my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
						if ($up_deuda_matricula !~ m/^N$/i){
							my ($puedeRematricular,$strmRematricular) = @{$self->getRematricular($legajo, $acadPlanAux, $acadProgAux)};
							$statusAux = 'R' if($puedeRematricular);
						}
					}

						
					if($status eq 'E'){
						$status = 1;
					}elsif ($status eq 'A'){
						$status = 2;
					}elsif($status eq 'R'){
						$status = 3;
					}else{
						#status eq 'B' o cualquier otro
						$status = 4;
					}

					if ($acadCareer eq 'GRAD'){
						$acadCareer = 1;
					}elsif($acadCareer eq 'UGRD'){
						$acadCareer = 2;
					}else{
						#$acadCareer eq 'EXCED' o cualquier otro
						$acadCareer = 3;
					}

					if($statusAux eq 'E'){
						$statusAux = 1;
					}elsif ($statusAux eq 'A'){
						$statusAux = 2;
					}elsif($statusAux eq 'R'){
						$statusAux = 3;
					}else{
						#statusAux eq 'B' o cualquier otro
						$statusAux = 4;
					}

					if ($acadCareerAux eq 'GRAD'){
						$acadCareerAux = 1;
					}elsif($acadCareerAux eq 'UGRD'){
						$acadCareerAux = 2;
					}else{
						#$acadCareerAux eq 'EXCED' o cualquier otro
						$acadCareerAux = 3;
					}
#Solicitado por Alejandro el plan por defecto si no tiene guardado preferencias
#1 GRAD>UGRD>EXCED
#y luego entre los empates por el primer puesto:
#2 EGRESADO>ACTIVO>SUSPENDIDO>INACTIVO/BAJA
					if ($acadCareer < $acadCareerAux){
						$posAuxDefault = $cont;
					}elsif($acadCareerAux == $acadCareer){
						if ($status <= $statusAux){
							$posAuxDefault = $cont;
						}
					}
				}else{
					$resAux = $res;
					$posAuxDefault = $cont;
				}
			}
		}

		push @arrayPlanes,$res;
		$cont++;
	}
	if ($pos < 0){
		$pos = ($posAuxDefault < 0) ? 0 : $posAuxDefault;
	}

	my $posAux = -1;
	my $listPlanes = '';
	foreach (@arrayPlanes){
		$posAux++;
		next if ($posAux == $pos);
      my $status = $_->{'up_estado_plan'} || '';
	  	if($status eq 'A' || $status eq 'E' || $status eq 'B'){
			my $acadPlan	 		= $_->{'acad_plan'}         || '';
			my $descr  		 		= $_->{'descr'}             || '';
			my $acadProg			= $_->{'acad_prog'}         || '';
			my $estadoPlan = "";
			$estadoPlan = "BAJA <span style=\"font-size:9px;\"><a style=\"color:#FFF\" class=\"click\" href=\"/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=$acadPlan\">Readmisi&oacute;n</a></span>" if ($status eq 'B');
			$estadoPlan = "EGRESADO" if ($status eq 'E');

			if ($status eq 'A'){
				$estadoPlan = "ACTIVO";
				my $up_deuda_matricula = $_->{'up_deuda_matricula'} || '';
				if ($up_deuda_matricula !~ m/^N$/i){
					$estadoPlan = "REMATRICULAR"; #esto lo agregue por las dudas, si dse descomenta lo de abajo o se borra sacar esto tambien
					#my ($puedeRematricular,$strmRematricular) = @{$self->getRematricular($legajo, $acadPlan, $acadProg)};
					#if($puedeRematricular){
		         #   if ($self->{GOTO}->tienePermisosSistemaDeAlumnos() && $strmRematricular ne ''){
					#		$estadoPlan =<<STR;
					#					<span style="font-size:9px;"><a style="color:#FFF;" href="/cgi-bin/myup/myup_rematricular_confirmar.pl?acad_plan=$acadPlan&strm=$strmRematricular" class="click">REMATRICULAR</a></span>
#STR
      		   #  }else{
					#		$estadoPlan =<<STR;
					#					<span style="font-size:9px;"><a style="color:#FFF;" href="/Intranet/my-up/rematricular_error.html" class="click">REMATRICULAR</a></span>
#STR
					#	}
					#}
				}
			}
			

			$listPlanes .= "<br/>" if ($listPlanes ne '');
 	 		$listPlanes .=<<STR;
				<a href="/cgi-bin/myup/myup.pl?acadPlan=$acadPlan" style="color:#FFF" >$descr</a> - $estadoPlan
STR
		}
	}
	my $ret = '';	

	my $res = $arrayPlanes[$pos];	
	my $status      	=	$res->{'up_estado_plan'} || '';
	if($status eq 'A' || $status eq 'E' || $status eq 'B'){
		my $acadPlan	 	= $res->{'acad_plan'}         || '';
		my $descr  		 	= $res->{'descr'}             || '';
		my $up_fin_aprob  = $res->{'up_fin_aprob'}      || '-';
		my $up_fin_pend   = $res->{'up_fin_pend'}       || '-';
		my $up_curs_pend  = $res->{'up_curs_pend'}      || '0';
		my $up_curs_aprob = $res->{'up_curs_aprob'}     || '0';
		my $up_avance_curs  = $res->{'up_avance_cursado'}	|| '0';
		my $up_avance_fin   = $res->{'up_avance_finales'}  || '0';
		my $acadProg        = $res->{'acad_prog'}          || '';
		my $curs_aprob_pend_final = $res->{'up_curs_pend_fin'}  || '0';

		if ($listPlanes ne ''){
			$listPlanes =<<STR;
			<a style="margin-left:0px;" class="click mails" href="/cgi-bin/myup/myup_selecccionar_plan.pl?acadPlan=$acadPlan">
				Haga click para ver otro plan
			</a> 
STR
		}

		my $estadoPlan = "";
		$estadoPlan = "<span style=\"font-size:9px;\"><a style=\"color:red;\" class=\"click field-tip\" href=\"/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=$acadPlan\">BAJA - Readmisi&oacute;n</a></span>" if ($status eq 'B');
		$estadoPlan = "EGRESADO" if ($status eq 'E');
		
		if ($status eq 'A'){
			$estadoPlan = "ACTIVO";
			my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
			#$up_deuda_matricula = 'Y';
			if ($up_deuda_matricula !~ m/^N$/i){

				if ($self->{GOTO}->tienePermisosSistemaDeAlumnos()){
					$estadoPlan = <<STR;
								<script type="text/javascript">
									window.onload = getRematricular('$acadPlan', '$acadProg');
								</script>
STR

				}else{
					$estadoPlan =<<STR;
                              <span style="font-size:9px;"><a style="color:red;" href="/Intranet/my-up/rematricular_error.html" class="click field-tip">REMATRICULAR</a></span>
STR
				}

			}
		}

		my $avanceCarrera = $up_avance_fin;

	
		$up_curs_aprob = '-' if ($up_curs_aprob eq '0');
		$up_curs_pend  = '-' if ($up_curs_pend eq '0');

		$curs_aprob_pend_final = '-' if ($curs_aprob_pend_final eq '0');

		#my $linkInfoImportante = "/Intranet/my-up/infoimportante.html";

		my $linkInfoImportante = "/cgi-bin/myup/myup_avance_carrera.pl?acadPlan=$acadPlan";
		$ret =<<STR;
 <div id="ficha-mod1" style=" color:#444444">
	<strong>$descr <span class="field-tip" id="estadoPlan">$estadoPlan</span></strong>
   <br>
	 $listPlanes
 </div>
 <div id="ficha-mod3">
			<div id="tabla-rellena">ASIGNATURA</div>
			<div id="tabla-rellena-1"> Cursadas</div>
			<div id="tabla-rellena-1">Finales</div>
			<div id="tabla">Aprobadas</div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">$up_curs_aprob</a></div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">$up_fin_aprob</a></div>
			<div id="tabla">Aprobadas pendientes de final</div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">$curs_aprob_pend_final</a></div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">-</a></div>
			<div id="tabla">Pendientes para completar la carrera</div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">$up_curs_pend</a></div>
			<div id="tabla-1"><a href="$linkInfoImportante" class="mails">$up_fin_pend</a></div>
 </div>
 
 <div style="width:376px; overflow:hidden; float:left;">
    <div class="meter">
    <span style="width: $avanceCarrera%"></span>
    </div>
    <div style="width:376px; overflow:hidden; color:#666666; font-family:Arial, Helvetica, sans-serif; font-size:14px; margin-top:2px;">
    <a style="margin-left:0px;" href="$linkInfoImportante" class="mails">Avance de carrera</a>: $avanceCarrera%
<a href="/cgi-bin/myup/myup_calificaciones.pl?acadPlan=$acadPlan" class="mails">Mis calificaciones</a>
    </div>
</div>
STR
	}

	
	return $ret;

}

#-------------------------------------------------------------

sub getPlanesRematricularAjax {
	my $self = shift;
	my $acadPlan =	$self->{REQUEST}->param('acad_plan') || '';
	my $acadProg = $self->{REQUEST}->param('acad_prog') || '';

	my $legajo = $self->{SESION}->{LEGAJO} || '';
	my $estadoPlan = 'ACTIVO';
	
	my ($puedeRematricular,$strmRematricular) = @{$self->getRematricular($legajo, $acadPlan, $acadProg)};
	if($puedeRematricular){
   	if ($self->{GOTO}->tienePermisosSistemaDeAlumnos() && $strmRematricular ne ''){
			$estadoPlan =<<STR;
							<span style="font-size:9px;"><a style="color:red;" onClick="mostrarPopUp(this);return false;" href="/cgi-bin/myup/myup_rematricular_confirmar.pl?acad_plan=$acadPlan&strm=$strmRematricular" class="click field-tip">REMATRICULAR</a></span>
STR
      }else{
			$estadoPlan =<<STR;
			<span style="font-size:9px;"><a style="color:red;" onClick="mostrarPopUp(this);return false;" href="/Intranet/my-up/rematricular_error.html" class="click field-tip">REMATRICULAR</a></span>
STR
		}

	}

	return $estadoPlan;

}


#-------------------------------------------------------------

sub getNotificaciones {
	my $self 			 = shift;

	my $ret =<<STR;
<script type="text/javascript">
window.onload = getNotificaciones();
</script>
STR
   return $ret;
}

#-------------------------------------------------------------

sub getNotificacionesAjax {
	my $self 			 = shift;
   my $legajo = $self->{SESION}->{LEGAJO} || '';


	my $msjErr =<<STR;
				<div class="sinnotificaciones">
		         <p class="titulos">Sin notificaciones</p>
		      </div>	
STR

	my $o = $self->{SESION}->{O} || '';
   return $msjErr if($o ne '');	

   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getNotificaciones Paquete MyUP::MyUp");
      return $msjErr; 
   }

	my $uid	  = $self->{SESION}->{UID} || '';
	$uid    	  = uc($uid);

	my $notificacionesExcepciones = MyUP::Conf->NOTIFICACIONESEXCEPCIONES;
	return $msjErr if ($legajo eq '00' || $uid =~ m/^($notificacionesExcepciones)/);

   my $hash   = $self->encriptar($uid,$legajo);

		
   if ($uid eq '' || $legajo eq '' || $hash eq '' ){
      $self->enviarErrorEmail("El legajo:$legajo,  el uid:$uid  o el hash (armado entre el uid y el legajo):$hash estan vacios. Metodo getNotificaciones Paquete MyUP::MyUp");
      return $msjErr;
   }

	my $status = '';
	my $ret    = '';
	if (MyUP::Conf->HABILITARPOSTNOTI && !$self->aperturaInscripcion()){
		($status,$ret) = @{$self->getNotificacionesBase(1)};
		if ($status eq 'sincronizacion') {
			($status,$ret) = @{$self->postNotificaciones()};
			($status,$ret) = @{$self->getNotificacionesBase(0)} if ($status eq 'error');
		}
	}else{
		($status,$ret) = @{$self->getNotificacionesBase(0)};
	}
	$ret = $msjErr if ($ret eq '');
   return $ret;
}

#-------------------------------------------------------------

sub actualizarDeudaAlumno {
   my $self = shift;

	my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo actualizarDeudaAlumno Paquete MyUP::MyUp");
   }else{
		$self->{DBHPOLEO} = UP::Connect2_DB::getDBHpoleo();
		my $emplidBD   = $self->{DBHPOLEO}->quote($legajo);
		my $strmquery=<<SQL;
			SELECT emplid,up_fecha,item_nbr,up_descr,due_amt  
			FROM ps_up_deuda_alu_vw 
			WHERE emplid=$emplidBD
			with ur
SQL

		my $sth = $self->{DBHPOLEO}->prepare($strmquery);
		$sth->execute();

		my $error = '';
		if ($sth->errstr){
			$error .= "ERROR $strmquery => ".$sth->errstr."\n";
		}else{
			my $contRows = 0;
			my $strInsert = '';
			while ( my $strms2  = $sth->fetchrow_arrayref()) {
				$contRows++;
				my $emplid = $strms2->[0] || '';
				$emplid    = $self->trim($emplid);
				$emplid    = $self->{DBHPGW}->quote($emplid);

				my $up_fecha = $strms2->[1] || '';
				$up_fecha    = $self->trim($up_fecha);
				$up_fecha    = $self->{DBHPGW}->quote($up_fecha);

				my $item_nbr = $strms2->[2] || '';
				$item_nbr    = $self->trim($item_nbr);
				$item_nbr    = $self->{DBHPGW}->quote($item_nbr);

				my $up_descr = $strms2->[3] || '';
				$up_descr    = $self->trim($up_descr);
				$up_descr    = $self->{DBHPGW}->quote($up_descr);

				my $due_amt = $strms2->[4] || '';
				$due_amt    = $self->trim($due_amt);
				$due_amt    = $self->{DBHPGW}->quote($due_amt);

				my $currency_cd = $strms2->[6] || '';
				$currency_cd    = Conf->trim($currency_cd);
				$currency_cd    = ($currency_cd ne '') ? $dbh2->quote($currency_cd) : "ars";

				$strInsert .= "$emplid,$up_fecha,$item_nbr,$up_descr,$due_amt,$currency_cd\n";
			}

			if ($contRows > 0){
				$strInsert =~ s/\\//g;
				my $deleteSql=<<SQL;
						DELETE FROM ps_up_deuda_alumnos WHERE emplid=$emplidBD
SQL

				my $insertSql=q{
COPY ps_up_deuda_alumnos (emplid,up_fecha,item_nbr,up_descr,due_amt,currency_cd) FROM stdin WITH DELIMITER AS ',' CSV QUOTE AS '''' NULL 'null';
};

				$self->{DBHINSCRIPCION} = UP::Connect2_DB::getDBHinscripcion();
				my @basePostgresql = (['PGW',$self->{DBHPGW}],['INSCRIPCION',$self->{DBHINSCRIPCION}]);

				foreach (@basePostgresql){
					my $nombreBase = $_->[0];
					my $base       = $_->[1];
				
					my $sth2 = $base->prepare("BEGIN");
					$sth2->execute;

					$sth2 = $base->prepare($deleteSql);
					$sth2->execute;

					if ($sth2->errstr){
						$error .= "ERROR DELETE Postgresql $nombreBase: ".$sth2->errstr." $deleteSql\n";
						$sth2 = $base->prepare("ROLLBACK");
						$sth2->execute;
					}else{
						$sth2 = $base->prepare($insertSql);
						$sth2->execute;
						if ($sth2->errstr){
							$error .= "Error sql Postgresql $nombreBase: ".$sth2->errstr." $insertSql\n";
							$sth2 = $base->prepare("ROLLBACK");
							$sth2->execute;
						}else{
							eval{ 
								my $errorEval = "ERROR  metodo pg_putline Postgresql $nombreBase\n";
								local $SIG{__WARN__} = sub { die $errorEval.$_[0]."\n" };
								$base->pg_putline($strInsert) or die $errorEval;
							};
							if ($@){
								$error .= $@."\n$strInsert\n"; 
							}else{
								eval{ 
									my $errorEval = "ERROR  metodo pg_endcopy  Postgresql $nombreBase\n";
									local $SIG{__WARN__} = sub { die $errorEval.$_[0]."\n" };
									$base->pg_endcopy() or die $errorEval;
								};
								if ($@) {
									$error .= $@."\n$strInsert\n"; 
								}else{
									$sth2 = $base->prepare("COMMIT");
									$sth2->execute;
								}
							}
						}
					}
				}

			}else{
				my $deleteSql = "DELETE FROM ps_up_deuda_alumnos WHERE emplid=$emplidBD";
				my $sth2      = $self->{DBHPGW}->prepare($deleteSql);
            $sth2->execute;
			}

		}
		if ($error ne ''){
			$self->enviarErrorEmail("$error.Metodo actualizarDeudaAlumno Paquete MyUP::MyUp");
		}else{			
			$self->deleteCacheJson($self->{SESION}->{LEGAJO},2);			
		}
	}
	return $self->getDeudaAlumnoAjax();
}

#-------------------------------------------------------------

sub getImagen {
my $self  = shift;

   $ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
my $legajo = $self->{SESION}->{LEGAJO} || '';
my $foto = '';
my $sexo = $self->{SESION}->{SEXO} || 'M';
if ($legajo ne ''){
		my $urlPost  = MyUP::Conf->URLPOSTFOTOS;
		my $hash = Digest::MD5::md5_hex($legajo.MyUP::Conf->CLAVECOMPARTIDAFOTOS);
		my $hashPost = {
			legajo => $legajo,
			hash   => $hash,
			sexo   => $sexo
		}; 
		my $ua       = LWP::UserAgent->new;
		my $timeout  = MyUP::Conf->TIMEOUTNOTIFICACIONES;
		$ua->timeout($timeout);
		my $response = $ua->post($urlPost,$hashPost);
		if ($response->is_success ){
			$foto = $response->content; 
			if ($foto eq ''){
				my $uri = "$urlPost?legajo=$legajo&hash$hash&sexo=$sexo";
				$self->enviarErrorEmail("Error getImagen. Post  $uri. Vino sin foto.");
			}
		}else{
			my $uri = "$urlPost?legajo=$legajo&hash$hash&sexo=$sexo";
			$self->enviarErrorEmail("Error getImagen. Post  $uri -> ".$response->status_line);
		}
	}
	
	$foto = "<img src=\"data:image/jpeg;base64,".$self->no_foto($sexo)."\">" if ($foto eq '');
	$foto = <<STR;
			<div style="display: table; height: 232px; width: 217px; margin: 0; padding: 0; text-align:center;">
				<div style="display: table-cell; vertical-align: middle;">
					$foto
				</div> 
			</div>
STR
	 
return $foto;
}
#-------------------------------------------------------------

sub no_foto{
  my $self = shift;
  my $sexo = shift || 'M';
  if ($sexo =~ m/^F$/i){
    return <<CODE;
/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABkAAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkJGQ0M5ODY5NDMzNzExRTQ5QjIzOEYzRkMxOTc0NzJDIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkJGQ0M5ODY4NDMzNzExRTQ5QjIzOEYzRkMxOTc0NzJDIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjA1QTU1NEE0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjA1QTU1NEI0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAABAQEBAQEBAQEBAQEBAQEBAQEB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
CODE
  }else{
    return <<CODE;
/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABkAAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI5NTdDNDE1NDMzNzExRTRBRkU5QzFGMTBGOUE0MDJBIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI5NTdDNDE0NDMzNzExRTRBRkU5QzFGMTBGOUE0MDJBIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjA1QTU1NEE0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjA1QTU1NEI0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAABAQEBAQEBAQEBAQEBAQEBAQEB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
CODE
  }
}

#-------------------------------------------------------------
sub subir_foto {
	my $self = shift;

   my $legajo = $self->{SESION}->{LEGAJO} || '';
	
	if ($legajo ne ''){
		my $imagen = $self->{REQUEST}->param('file1') || return "Error: No selecciono una imagen.";
      my $upload_filehandle = $self->{REQUEST}->upload("file1");
		my $file1 = "";
		while ( <$upload_filehandle> ){
			$file1 .= $_;
		}

		my $ua       = LWP::UserAgent->new;
		my $urlPost  = MyUP::Conf->URLPOSTSUBIRFOTOS; 

		my $hash = Digest::MD5::md5_hex($legajo.MyUP::Conf->CLAVECOMPARTIDAFOTOS);

		use LWP::MediaTypes qw(guess_media_type);
		my $mime = guess_media_type("$imagen");
		my $response = $ua->post($urlPost,
				Content_Type => 'form-data',
				Content => [
					Filedata => [ undef, "$imagen",
								Content_Type => "$mime",
								Content      => "$file1",
								],
								submit => 'Submit',
					legajo => $legajo,
					hash   => $hash,
				],
		);

		if ($response->is_success){
			my $respuesta = $response->content || '';
			return $respuesta if ($respuesta !~ m/^OK/i);
		}else{
	  		return "Error: No se pudo subir la foto. Intente m&aacute;s tarde." 
		}
	}
   return  '';
}

#-------------------------------------------------------------

sub getImagenIframe {
	my $self = shift;
	my $baseUrl = MyUP::Conf->baseURL() || '';

	my $ret =<<STR;
	<div> <!--{IFRAME}--> 
		<iframe onload="resizeMailIframe(this);" src="$baseUrl/cgi-bin/myup/myup_foto.pl?accion=get_imagen" scrolling="No" width="792px" height="1px" frameborder="0"  border="0" marginwidth="0px" marginheight="0px"></iframe> <!--{/IFRAME}-->
	</div>
STR
   return $ret;
}

#-------------------------------------------------------------

sub getDiaMes {
   my $self  = shift;
   my $fecha = shift || return '';

   if ($fecha =~ /^([0-9]{4})-([0-9]{2})-(([0-9]{2}))$/){
      return $3." de ". $self->getMes($2);
   }
   return '';
}


#-------------------------------------------------------------

sub getMes {
   my $self = shift;
   my $mes  = shift || return '';

   if ($mes eq "01"){
      return "Enero";
   }elsif($mes eq "02"){
      return "Febrero";
   }elsif($mes eq "03"){
      return "Marzo";
   }elsif($mes eq "04"){
      return "Abril";
   }elsif($mes eq "05"){
      return "Mayo";
   }elsif($mes eq "06"){
      return "Junio";
   }elsif($mes eq "07"){
      return "Julio";
   }elsif($mes eq "08"){
      return "Agosto";
   }elsif($mes eq "09"){
      return "Septiembre";
   }elsif($mes eq "10"){
      return "Octubre";
   }elsif($mes eq "11"){
      return "Noviembre";
   }elsif($mes eq "12"){
      return "Diciembre";
   }
   return '';
}

#-------------------------------------------------------------

sub _iconv {
	my $self = shift;
	my $str  = shift;
	$str = $self->{ICONVISO}->convert($str);
	return $str;
}


#-------------------------------------------------------------

sub encriptar {
   my $self   = shift;
   my $uid    = shift || '';
   my $legajo = shift || '';
   $uid = lc($uid);
   my $clave = MyUP::Conf->claveSinclairNotificaciones();
   my $hash  = Digest::MD5::md5_hex($uid.$legajo.$clave);
   return $hash;
}

#-------------------------------------------------------------

sub formatTime {
      my $self= shift;
      my $time= shift;
      if ($time =~ /([0-9]+):([0-9]+):([0-9]+)/){
         my $hora = $1;
         my $min = $2;
         $time = $hora.':'.$min;
      }
      return $time;
}



#-------------------------------------------------------------

sub isTipoFecha {
   my $self    = shift;
   my $anio    = shift; #2008
   my $mes     = shift; #10
   my $dia     = shift; #01
   my $fecha   = $dia."/".$mes."/".$anio;
   $fecha   = ($fecha =~ m/^(3[0-1]|[1-2][0-9]|0?[1-9])[-\/](1[0-2]|0?[1-9])[-\/](19[0-9]{2}|20[0-9]{2})$/) ? $fecha : 0;

   if ($fecha) {
      return 1;
   }else{
      return 0;
   }

}


#-------------------------------------------------------------

sub _check_date {
my $self = shift;
my $anio = shift; #2008
my $mes  = shift; #10
my $dia  = shift; #01
#----------------------

   my $meses = {
   1 => 31, 2 => 28, 3 => 31,  4 => 30,  5 => 31,  6 => 30,
   7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31,
   '01' => 31, '02' => 28, '03' => 31,  '04' => 30,  '05' => 31,  '06' => 30,
   '07' => 31, '08' => 31, '09' => 30
   };

   if($mes == '2' or $mes == '02') {
      if ($anio%4 != 0){
         # febrero max dias 28
         if($dia > 28){
            return 0
         }
      }else{
         # febrero max dias 29
         if($dia > 29){
            return 0
         }
      }
   }else{
      my $max = $meses->{$mes};
      if($dia > $max){
         return 0;
      }
   }

return 1;
}


#-------------------------------------------------------------

sub enviarErrorEmail {
   my $self  = shift;
   my $error = shift || '';


	my $subject    = 'Error MyUp.pm '.MyUP::Conf->PROD_DES;


	if ($error =~ m/^El legajo (.)* es incorrecto\. Metodo (getCalendarioAsignaturasActividades|getCalendarioAsignaturasDocente|getPlanesCalificaciones|getNotificaciones|getTicket|getPermisosDeExamen|getDeudaAlumno|getUltimoPoleo) Paquete MyUP::MyUp$/i){
		my $uid = $self->{SESION}->{UID} || '';
		$self->loguearFile("$subject\nUsuario: $uid\n$error\n");
	}else{
		my $from       = 'tecnologia_myup@palermo.edu';
		my $to       	= 'errorhomer@palermo.edu';
		my $cc         = '';
		
		if ($error =~ m/Metodo postNotificaciones Paquete MyUP::MyUp => 500/ && MyUP::Conf->PROD_DES eq 'PRODUCCION'){
			$cc = 'ddiama2@palermo.edu, adeluca@palermo.edu, hperez7@palermo.edu';
			$self->loguearFile("$subject\n$error\n");
		}elsif($error =~ m/^Error en la cabezera del xml del post de la mensajeria/ && MyUP::Conf->PROD_DES eq 'PRODUCCION'){
			$cc = 'hperez7@palermo.edu';
			$self->loguearFile("$subject\n$error\n");
		}elsif($error =~ m/^El campo RESPUESTA devolvio INEX/ && MyUP::Conf->PROD_DES eq 'PRODUCCION'){
			$cc = 'mmarti2@palermo.edu';
			$self->loguearFile("$subject\n$error\n");
		}elsif ($error =~ m/postNotificaciones/ && MyUP::Conf->PROD_DES eq 'PRODUCCION'){
			$cc = 'hperez7@palermo.edu';
			$self->loguearFile("$subject\n$error\n");
		}elsif ($error =~ m/getImagen/ && MyUP::Conf->PROD_DES eq 'PRODUCCION'){
			$cc = 'ddiama2@palermo.edu, adeluca@palermo.edu, hperez7@palermo.edu';
			$self->loguearFile("$subject\n$error\n");
		}

		if(open (MAIL,"|/usr/lib/sendmail -t")){
			print MAIL "To: $to\n";
			print MAIL "From: $from\n";
			print MAIL "CC: $cc\n" if ($cc ne '');
			print MAIL "Subject: $subject\n";
			print MAIL "MIME-Version: 1.0\n";
			print MAIL "Content-Type: multipart/related;\n";
			print MAIL "    boundary=\"-=_sinclair.palermo.edu445bcf43\"\n\n";
			print MAIL "---=_sinclair.palermo.edu445bcf43\n";
			print MAIL "Content-Type: text/html;\n";
			print MAIL "    charset=\"ISO-8859-1\"\n";
			print MAIL "Content-Transfer-Encoding: Quoted Print\n\n";
			print MAIL $error."\n" if ($error ne '');
			print MAIL "---=_sinclair.palermo.edu445bcf43--\n";
			close(MAIL);
		}
	}
}

#-------------------------------------------------------------

#sub no_foto {
#	my $self = shift;
#
#	return <<CODE;
#/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABkAAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI5NTdDNDE1NDMzNzExRTRBRkU5QzFGMTBGOUE0MDJBIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI5NTdDNDE0NDMzNzExRTRBRkU5QzFGMTBGOUE0MDJBIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjA1QTU1NEE0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjA1QTU1NEI0MzJDMTFFNDgxRjFEODRDMEIzQjM0NEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAABAQEBAQEBAQEBAQEBAQEBAQEB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
#CODE
#}

#-------------------------------------------------------------
# este metodo pude ser llamado solo por getNotificaciones
#legajo, uid y hash se valida en el metodo getNotificaciones. 
sub postNotificaciones {
	my $self = shift;

   $ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
   my $uri = MyUP::Conf->URLPEOPLESOFTNOTIFICACIONES;

	if(not defined $uri || $uri eq ''){
      $self->enviarErrorEmail("La uri no esta definida.Metodo postNotificaciones paquete MyUP::MyUp.pm");
		return ['error',''];
   }

	# este metodo pude ser llamado solo por getNotificaciones
	#legajo, uid y hash se valida en el metodo getNotificaciones. 
   my $legajo 			 = $self->{SESION}->{LEGAJO} || '';
	my $legajoBD 		 = $self->{DBHPGW}->quote($legajo);


	my $uid				 = $self->{SESION}->{UID} || '';
	$uid    				 = uc($uid);

   my $hash  			 = $self->encriptar($uid,$legajo);

	my $fechaActual = time();

  	$uri .= "Operation=UP_NOTIFICACIONES_OP.v1&uid=$uid&legajo=$legajo&hash=$hash&action=notificaciones&modulo=I";
	my $ua     = LWP::UserAgent->new;
	my $timeout = MyUP::Conf->TIMEOUTNOTIFICACIONES;
	$ua->timeout($timeout);
	
	#my $hashParam = {
	#	'uid'    => $uid,
	#	'legajo' => $legajo,
	#	'hash'   => $hash,
	#	'action' => 'notificaciones',
	#	'modulo' => 'I'
	#	};
	#my $response = $ua->post($uri,$hashParam);
	my $response = $ua->get($uri);
	#$self->loguearFile($uri);
	#$self->logDebug($uri);

	my $uidBD = $self->{DBHPGW}->quote($uid);

	my $ret = '';
	if ($response->is_success) {
		 my $respuesta = $response->decoded_content;
		 #$self->logDebug($respuesta);
       # DEBUG ------------
		 #my $h = $response->headers_as_string();
		 #$self->logDebug($h."\n".$respuesta."\n\n".$uri) if $legajo eq '9202';
		 # -------------------
		
   	 if($respuesta =~ /^<\?xml version="1.0"\?>/){
				my $som;
				eval {
					$som = SOAP::Custom::XML::Deserializer
								 -> deserialize(join '', $respuesta)
								 -> valueof('//ROOT/');
				};

				if ($@){
       			# DEBUG ------------
					#$self->logDebug("\n\nERROR\n".$h."\n".$respuesta."\n\n".$uri."\n");
					# ------------------
					$self->enviarErrorEmail("Error al Deserializer el XML  Uri:$uri, Respuesta:$respuesta. Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
					return ['error',''];
				}
					#$self->logDebug("paso");
			if (not defined $som){
				$self->enviarErrorEmail("Error al Deserializer el XML  Uri:$uri, Respuesta:$respuesta  .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
				return ['error',''];
			}else{
				if (not defined $som->EMPLID){
					$self->enviarErrorEmail("El campo EMPLID no esta definido Uri:$uri, Respuesta:$respuesta  .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
					return ['error',''];
				}
				my $emplid = $som->EMPLID || '';
				if ($emplid ne $legajo){
					$self->enviarErrorEmail("El campo EMPLID y el Legajo son distintos. Uri:$uri, Emplid:$emplid , Legajo:$legajo  .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
					return ['error',''];
				}	

				if (not defined $som->RESPUESTA){
					$self->enviarErrorEmail("El campo RESPUESTA  no esta definido.Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo Uri:$uri, ");
					return ['error',''];
				}	
				my $campoRespuesta = $som->RESPUESTA || '';
				if ($campoRespuesta ne 'OK'){
					if ($campoRespuesta eq 'NULL'){
						$self->enviarErrorEmail("El campo RESPUESTA devolvio NULL Uri:$uri, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
						return ['error',''];
					}elsif($campoRespuesta eq 'HASH'){
						$self->enviarErrorEmail("El campo RESPUESTA devolvio HASH Uri:$uri, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
						return ['error',''];
					}elsif($campoRespuesta eq 'INEX'){
						$self->enviarErrorEmail("El campo RESPUESTA devolvio INEX Uri:$uri, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
						return ['error',''];
					}elsif($campoRespuesta eq ''){
						$self->enviarErrorEmail("El campo RESPUESTA vino vacio Uri:$uri, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
						return ['error',''];
					}else{
						 $self->enviarErrorEmail("El campo RESPUESTA es incorrecto Uri:$uri, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo Respuesta => $campoRespuesta");
						return ['error',''];
					}
				}else{
					if (not defined $som->MENSAJES){
						$self->enviarErrorEmail("El campo MENSAJES no esta definido en el xml.Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo Uri:$uri, ");
						return ['error',''];
					}

					my $sqlDelete =<<SQL;
							DELETE FROM myup_notificaciones WHERE emplid = $legajoBD AND modulo='I';
SQL
					my $sthDelete = $self->{DBHPGW}->prepare($sqlDelete);
					$sthDelete->execute();

					my $error		= '';
					foreach my $mensaje($som->MENSAJES->MENSAJE) {
						my $hashId ={};
						my $id      = $mensaje->MSGID   || '';
													
						my $titulo  = $mensaje->TITULO  || '';
						
						my $texto  = $mensaje->TEXTO   || '';
						
						my $fechaDesde = $mensaje->DESDE   || '';
													
						my $fechaHasta = $mensaje->HASTA   || '';

						#descomentar cuando peoplesoft lo agregue. Tambien descomentarlo en el paquete CELULAR/Notificaciones.pm
						my $periodicidad = $mensaje->PERIODICIDAD || '0';
						my $prioridad = $mensaje->PRIORIDAD || '';
						##my $periodicidad = 1;
                  ##my $prioridad    = 1;
						
						if ($id eq ''){
							$error .= "El campo ID vino vac�o. MSGID:$id.<br>\n";
						}elsif($id !~ m/^[0-9]+$/){
							$error .= "El campo ID no es num�rico. MSGID:$id.<br>\n";
						}elsif($hashId->{$id}){
							$error .= "El ID $id ya existe (campo duplicado). MSGID:$id.<br>\n";
						}

						if ($titulo eq ''){
							$error .= "El campo TITULO vino vac�o. MSGID=$id.<br>\n";
						}

						if ($texto eq ''){
							$error .= "El campo TEXTO vino vac�o. MSGID=$id.<br>\n";
						}			
						
						if ($fechaDesde ne ''){
							my @arrayFechaDesde = split ("-",$fechaDesde);
							my $cantFechaDesde = @arrayFechaDesde;
							if ($cantFechaDesde == 3){
								my $anioFD = $arrayFechaDesde[0];
								my $mesFD  = $arrayFechaDesde[1];
								my $diaFD  = $arrayFechaDesde[2];
								if ($self->isTipoFecha($anioFD,$mesFD,$diaFD)){
									if (!$self->_check_date($anioFD,$mesFD,$diaFD)){
										$error .= "FECHADESDE incorrecta. FECHADESDE: $fechaDesde. MSGID=$id.<br>\n";
									}
								}else{
									$error .= "FECHADESDE incorrecta FECHADESDE: $fechaDesde. MSGID=$id.<br>\n";
								}
							}else{
								$error .= "Formato FECHADESDE incorrecto FECHADESDE: $fechaDesde. MSGID=$id.<br>\n";
							}
						}else{
							$error .= "FECHADESDE vino vacio. MSGID=$id.<br>\n";
						}	
						
						if ($fechaHasta ne ''){
							my @arrayFechaHasta = split ("-",$fechaHasta);
							my $cantFechaHasta = @arrayFechaHasta;
							if ($cantFechaHasta == 3){
								my $anioFH = $arrayFechaHasta[0];
								my $mesFH  = $arrayFechaHasta[1];
								my $diaFH  = $arrayFechaHasta[2];
								if ($self->isTipoFecha($anioFH,$mesFH,$diaFH)){
									if (!$self->_check_date($anioFH,$mesFH,$diaFH)){
										$error .= "FECHAHASTA incorrecta. FECHAHASTA: $fechaHasta. MSGID=$id.<br>\n";
									}
								}else{
									$error .= "FECHAHASTA incorrecta. FECHAHASTA: $fechaHasta. MSGID=$id.<br>\n";
								}
							}else{
								$error .= "Formato FECHAHASTA incorrecto. FECHAHASTA: $fechaHasta. MSGID=$id.<br>\n";
							}
						}
						if ($prioridad eq ''){
							$error .= "El campo PRIORIDAD vino vac�o. MSGID:$id<br>\n";
						}elsif($prioridad !~ m/^[0-9]+$/){
							$error .= "El campo PRIORIDAD no es num�rico. MSGID:$id => PRIORIDAD $prioridad.<br>\n";
						}

						if ($periodicidad eq ''){
							$error .= "El campo PERIODICIDAD vino vac�o. MSGID:$id.<br>\n";
						}elsif($periodicidad !~ m/^[0-9]+$/){
							$error .= "El campo PERIODICIDAD no es num�rico. MSGID:$id => PERIODICIDAD $periodicidad.<br>\n";
						}
						$hashId->{$id} = $id;
						if ($error eq ''){
								my @arrayFechaDesdeComparacion = split (" ",$fechaDesde);
								my ($yearDesde,$monthDesde,$dayDesde) = split ("-",$arrayFechaDesdeComparacion[0]);
								$monthDesde = $monthDesde - 1;
								my $fechaDesdeComparacion = timelocal('00','00','00',$dayDesde,$monthDesde,$yearDesde);

								my $fechaHastaComparacion = '';
								if ($fechaHasta ne ''){
									my @arrayFechaHastaComparacion = split (" ",$fechaHasta);
									my ($yearHasta,$monthHasta,$dayHasta) = split ("-",$arrayFechaHastaComparacion[0]);
									$monthHasta = $monthHasta - 1;
									$fechaHastaComparacion = timelocal('59','59','23',$dayHasta,$monthHasta,$yearHasta);
								}
		
						 			
		 						#$self->logDebug("$id => $fechaActual >= $fechaDesdeComparacion && ($fechaHasta eq '' || $fechaActual <= $fechaHastaComparacion)<br><br><br>");
								 if($fechaActual >= $fechaDesdeComparacion && ($fechaHasta eq '' || $fechaActual <= $fechaHastaComparacion)){
									
                            $id           = $self->_iconv($id);
                            $titulo       = $self->_iconv($titulo);
                            $texto        = $self->_iconv($texto);
                            $fechaDesde   = $self->_iconv($fechaDesde);
                            $fechaHasta   = $self->_iconv($fechaHasta);
                            $periodicidad = $self->_iconv($periodicidad);
                            $prioridad    = $self->_iconv($prioridad);
									 	
									 $ret .= $self->getTemplateNotificaciones($titulo,$texto);
									 if($fechaHasta eq ''){
										$fechaHasta="null";
									 }else{
										$fechaHasta   = $self->{DBHPGW}->quote($fechaHasta);
									 }  	
									 $fechaDesde   = $self->{DBHPGW}->quote($fechaDesde);
									 $texto 	  		= $self->{DBHPGW}->quote($texto);
									 $titulo 		= $self->{DBHPGW}->quote($titulo);
									 $id     		= $self->{DBHPGW}->quote($id);
									 $periodicidad = $self->{DBHPGW}->quote($periodicidad);
									 $prioridad    = $self->{DBHPGW}->quote($prioridad);

									 my $sqlInsert=<<SQL;
											INSERT INTO myup_notificaciones (id_msj, emplid, uid, titulo, detalle, fecha_desde, fecha_hasta, prioridad, periodicidad, modulo)
											VALUES ($id, $legajoBD, $uidBD,$titulo, $texto, $fechaDesde, $fechaHasta, $prioridad, $periodicidad, 'I');
SQL
							
									 my $sth = $self->{DBHPGW}->prepare($sqlInsert);
									 $sth->execute();
								 
									 if ($sth->errstr){
										$self->enviarErrorEmail("Error al insertar registro (notificacion) en la base.Sql:$sqlInsert .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
									 }	

								}
						}
					}

					if ($error eq ''){
						my  $sqlInsert =<<SQL;
								INSERT INTO myup_notificaciones (emplid, uid, titulo, detalle, fecha_desde, modulo)
								VALUES ($legajoBD, $uidBD,'sincronizacion', 'sincronizacion', now(), 'I');
SQL
						#$self->loguearFile($sqlInsert);
						my $sth = $self->{DBHPGW}->prepare($sqlInsert);
						$sth->execute();

						if ($sth->errstr){
							$self->enviarErrorEmail("Error al insertar el registro de actualizacion Sql:$sqlInsert, Legajo:$legajo, Uid:$uid .Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo");
						}	
					}else{
						$self->enviarErrorEmail("$error. Metodo postNotificaciones paquete MyUP::MyUp - uid $uid - legajo $legajo<br>\nURI: $uri<br>\nRespuesta:<br>\n$respuesta\n");
					}

				}
			}
		}else{
			$self->enviarErrorEmail("Error en la cabezera del xml del post de la mensajeria. Metodo postNotificaciones Paquete MyUP::MyUp $respuesta - uid $uid - legajo $legajo Uri:$uri, ");
		return ['error',''];
		}
			
	}else{
		$self->enviarErrorEmail("Error en la respuesta del post de la mensajeria. Metodo postNotificaciones Paquete MyUP::MyUp => ".$response->status_line." - uid $uid - legajo $legajo Uri:$uri, ");
		return ['error',''];
	}
	return ['ok',$ret];

}

#-------------------------------------------------------------
# este metodo pude ser llamado solo por getNotificaciones
#legajo, uid y hash se valida en el metodo getNotificaciones. 
sub getNotificacionesBase {
	my $self = shift;
	my $validarFechaAlta  = shift || 0;

	# este metodo pude ser llamado solo por getNotificaciones
	#legajo, uid se valida en el metodo getNotificaciones. 
   my $legajo 	 = $self->{SESION}->{LEGAJO} || '';
	my $legajoBD = $self->{DBHPGW}->quote($legajo);

	my $uid = $self->{SESION}->{UID} || '';
	$uid    = uc($uid);
	$uid    = $self->{DBHPGW}->quote($uid);

	my $syncTime  = MyUP::Conf->TIEMPOSINCRONIZACIONNOTIFICACIONES;

	my $sql = "";

	if ($validarFechaAlta){
		$sql=<<SQL;
			SELECT distinct id_msj, titulo, detalle, prioridad
			FROM myup_notificaciones
			WHERE  now()  >= fecha_desde  
				AND (fecha_hasta is null or CURRENT_DATE <= fecha_hasta) 
				AND  (fecha_alta + interval '$syncTime hour') > CURRENT_TIMESTAMP
				AND emplid = $legajoBD
				AND uid    = $uid
				AND modulo = 'I'
		   ORDER BY prioridad DESC
SQL
	}else{
		$sql=<<SQL;
			SELECT distinct id_msj, titulo, detalle, prioridad
			FROM myup_notificaciones
			WHERE  now()  >= fecha_desde  
				AND (fecha_hasta is null or CURRENT_DATE <= fecha_hasta) 
				AND emplid = $legajoBD
				AND uid    = $uid
				AND modulo = 'I'
		   ORDER BY prioridad DESC
SQL
	}


   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	my $ret = '';
	if (!$sth->errstr) {
		my $status = 'ok';
		my $cantRows = $sth->rows;
		while ( my $res  = $sth->fetchrow_hashref()) {
      	my $titulo  = $res->{'titulo'} 	|| '';
      	my $detalle = $res->{'detalle'}  || '';
			my $id_msj  = $res->{'id_msj'} 	|| '';
      	if($titulo ne 'sincronizacion' && $detalle ne 'sincronizacion' && $id_msj ne ''){
				$ret .= $self->getTemplateNotificaciones($titulo,$detalle);
			}
		}
		$status = 'sincronizacion' if ($cantRows == 0 && $ret eq '' && $validarFechaAlta);
		return [$status,$ret];
	}else{
		my $error = $sth->errstr;
		$self->enviarErrorEmail("Error Sql metodo getNotificacionesBase: $sql. Error: " . $error);
		return ['error',''];
	}
}

#-------------------------------------------------------------

sub getTemplateNotificaciones {
	my $self = shift;
	my $titulo = shift || '';
	my $detalle = shift || '';
	
	my $ret =<<STR;
		<div class="aranceles">
    		<span class="titulos" style="color:#B12C00;">$titulo:</span>
			<span class="notifDesc" style="color:#B12C00;">$detalle</span>
		</div>
STR
	return $ret;
}

#-------------------------------------------------------------

sub formatDate {
      my $self= shift;
      my $date= shift;
      if ($date =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
         my $anio = $1;
         my $mes = $2;
         my $dia = $3;

         $date = "$dia/$mes/$anio";
      }
      return $date;
}

#-------------------------------------------------------------

sub getContacto {
	my $self = shift;

	my $baseUrl = MyUP::Conf->baseURL() || '';

	my $ret =<<STR;
		<iframe onload="resizeMailIframe(this);" src="$baseUrl/cgi-bin/myup/myup_contactos.pl" scrolling="No" width="100%" height="1px" frameborder="0"  border="0" marginwidth="0px" marginheight="0px"></iframe> <!--{/IFRAME}-->
STR
return $ret;

}

#-------------------------------------------------------------

sub getNotasNew {
	my $self    = shift;
	my $aprobadas = $self->{REQUEST}->param('aprobadas') || '';
 
   my $legajo = $self->{SESION}->{LEGAJO} || '';

   if ($legajo !~ m/^[0-9]+$/){
      return ['',''];
   }

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';

	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
	
	return ['',''] if (!$self->validarAcadPlan($acadPlan));

   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

   if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }

   my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);

	my $where = '';
	
	if($aprobadas eq 'true'){
		$where = " AND (c.up_aprobado like 'Y' OR up_equiv_ext_pb like 'E')";
	}
	
	my $sql =<<STR;
		(
       SELECT max(strm) as strm,'F' as tipo FROM ps_up_cic_ac_fi_vw GROUP BY tipo
		 UNION
		 SELECT max(strm) as strm, 'C' as tipo FROM ps_up_cic_ac_cu_vw GROUP BY tipo
      )
STR

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hashCicloActualFi = {};
	my $hashCicloActualCu = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $strm = $res->{'strm'} || next;
		my $tipo = $res->{'tipo'} || next;
		if ($tipo eq 'F'){
			$hashCicloActualFi->{$strm} = 1;
		}else{
			$hashCicloActualCu->{$strm} = 1;
		}
	}

	
	$sql =<<STR;
		SELECT   
				distinct c.strm, c.crse_id, c.up_crse_id_cursado, c.crse_grade_off as nota, c.end_dt as fecha_nota, c.up_aprobado as aprobado, c.up_strm_vto_or, 
				c.up_strm_vto_prga,a.descrlong, cf.crse_id_srch, c.up_equiv_ext_pb, a.equiv_crse_id, a2.equiv_crse_id as equiv_crse_id_srch
		FROM ps_up_his_calif_vw as c, ps_up_plan_alumnos as p, ps_up_asignatur_vw a
           LEFT JOIN ps_up_asi_cu_fi_vw cf ON cf.crse_id = a.crse_id 
			  LEFT JOIN ps_up_asignatur_vw a2 ON a2.crse_id  = cf.crse_id_srch
		WHERE
				p.emplid    = $legajoBD    AND
				p.acad_plan like $acadPlanBD  AND
				p.acad_plan = c.acad_plan  AND
				p.emplid    = c.emplid     AND
            p.up_estado_plan in ('A','B','E','I') AND
				c.crse_id   = a.crse_id
            $where 
		Order By  up_crse_id_cursado ASC, c.end_dt DESC, c.crse_grade_off DESC, a.descrlong ASC 
STR

	#$self->logDebug($sql);
	#print "content-type:text/html\n\n";print $sql;exit;

	$sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	if ($sth->errstr){
		my $error = $sth->errstr;
      $self->loguearFile("Error sql. Consulta = $sql , Metodo = getNotasNew, Error= $error");
      return ['',''];
   }

	my $notasCursada = '';
	my $notasFinal   = '';
	
	my $hashCalificacionesFinal = {};

	 my $lineaDivisora = <<STR;
             <div class="lineaDivisora"></div>
STR

	while ( my $res  = $sth->fetchrow_hashref()) {
		my $cursada            = $res->{up_crse_id_cursado} || next;
		my $crseId             = $res->{crse_id}            || next;
		my $crse_id_srch       = $res->{crse_id_srch}       || '';
		my $strm               = $res->{strm}               || '';
		my $equiv_crse_id      = $res->{equiv_crse_id}      || '';
		my $equiv_crse_id_srch = $res->{equiv_crse_id_srch} || '';
		my $fecha              = $res->{fecha_nota}         || '';
		my $aprobado           = $res->{aprobado}           || ''; 
		my $nota               = $res->{nota}               || '';
		my $descr              = $res->{descrlong}          || '';
		my $up_equiv_ext_pb = $res->{up_equiv_ext_pb}       || '';
		$aprobado = 'Y' if ($up_equiv_ext_pb eq 'E');
      

		if ($cursada eq 'Y'){
			if ($aprobado eq 'Y'){
								
				$aprobado = "Aprobado";
				$aprobado = "Equivalencia" if($up_equiv_ext_pb eq 'E');
				my $up_strm_vto_or     = $res->{up_strm_vto_or}     || ''; 
				my $up_strm_vto_prga   = $res->{up_strm_vto_prga}   || '';
				my ($warning,$isVencida) = (0,0);
				if (!$hashCalificacionesFinal->{$crse_id_srch}){
					my	$strmWarning = $up_strm_vto_prga || '';
					$strmWarning = $up_strm_vto_or if ($up_strm_vto_prga eq '');
					$warning = ($hashCicloActualFi->{$strmWarning}) ? 1 : 0;
					$isVencida = $self->isVencidaAsignatura($strmWarning,$hashCicloActualFi) if (!$warning);
				}
				if (!$isVencida){
					$aprobado = ($warning) ? "Aprobado a punto de vencer" : "Aprobado";
					$aprobado =  "Equivalencia" if($up_equiv_ext_pb eq 'E');
				}else{
					$aprobado =  "Vencido";
				}
			}elsif($nota =~ m/^[0-9]+$/ && $nota >= 4){
				my $aInformar = 0;
      		if ($fecha =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
					my $anio = $1;
					my $dt = DateTime->now();
					my $anioActual = $dt->year();
					$aInformar = 1 if ($anio >= $anioActual);
				}
				if ($aInformar){
					$aprobado = "A informar";
				}else{
					$aprobado = "Vencido";
				}
			}elsif($nota eq '' && $hashCicloActualCu->{$strm}){
				$aprobado = "En curso";
			}else{
				my $aInformar = 0;
      		if ($fecha =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
					my $anio = $1;
					my $dt = DateTime->now();
					my $anioActual = $dt->year();
					$aInformar = 1 if ($anio >= $anioActual);
				}
				if ($aInformar){
					$aprobado = "A informar";
				}else{
					$aprobado = "Desaprobado";
				}
			}

			$nota = "Nota: $nota -" if ($nota ne '');
			$nota = '' if($up_equiv_ext_pb eq 'E');
		
			$fecha = $self->formatDate($fecha);
			$fecha =  "Fecha: $fecha<br>" if ($fecha ne '');

			$notasCursada .= $lineaDivisora if ($notasFinal ne '');	

			my $equiv_crse_idParam = $equiv_crse_id; 
			my $crseIdParam        = $crseId;

			$notasCursada .= <<STR;
				<div id="mob" style="border-style: none; margin-bottom: 0px; ">
					<span style="color:#444444;"><strong>$descr. $nota $aprobado<br></strong></span>
					 C&oacute;digo: $crseId<br>
					$fecha
					<a  class="click mails2"  href="/cgi-bin/myup/descripcion_asignatura.pl?crseId=$crseIdParam&equiv_crse_id=$equiv_crse_idParam&strm=$strm">Descripci&oacute;n</a>
				</div>
STR
		}elsif ($cursada eq 'N'){
			$hashCalificacionesFinal->{$crseId} = 1;
			if($nota eq '' && $hashCicloActualFi->{$strm}){
				$aprobado = "En curso";
			}elsif($aprobado eq 'Y'){
				$aprobado = "Aprobado";
				$aprobado =  "Equivalencia" if($up_equiv_ext_pb eq 'E');
			}else{
				my $aInformar = 0;
      		if ($fecha =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
					my $anio = $1;
					my $dt = DateTime->now();
					my $anioActual = $dt->year();
					$aInformar = 1 if ($anio >= $anioActual);
				}
				if ($aInformar){
					$aprobado = "A informar";
				}else{
					$aprobado = "Desaprobado";
				}
			}

			$nota = "Nota: $nota -" if ($nota ne '');
			$nota = '' if($up_equiv_ext_pb eq 'E');
		
			$fecha = $self->formatDate($fecha);
			$fecha =  "Fecha: $fecha<br>" if ($fecha ne '');

			$notasFinal .= $lineaDivisora if ($notasFinal ne '');	


			my $equiv_crse_idParam = '';
			my $crseIdParam        = '';	
			if ($crse_id_srch ne ''){
				$crseIdParam        = $crse_id_srch;
				$equiv_crse_idParam = $equiv_crse_id_srch;
			}else{
				$crseIdParam        = $crseId;
				$equiv_crse_idParam = $equiv_crse_id;
			}

			$notasFinal .= <<STR;
				<div id="mob" style="border-style: none; margin-bottom: 0px; ">
					<span style="color:#444444;"><strong>$descr. $nota $aprobado<br></strong></span>
					 C&oacute;digo: $crseId<br>
					$fecha
					<a  class="click mails2"  href="/cgi-bin/myup/descripcion_asignatura.pl?crseId=$crseIdParam&equiv_crse_id=$equiv_crse_idParam&strm=$strm">Descripci&oacute;n</a>
				</div>
STR
		}
	}
	
	
	return [$notasFinal,$notasCursada];
}

#-------------------------------------------------------------------------

sub getContenidoMinimoAndDescr {
	my $self    = shift;

	my $groupEquiv = shift || '';
	my $crseId  	= shift || '';
	my $strm    	= shift || '';

	return '' if ($groupEquiv eq '' && $crseId eq '');

	my $where = '';

	my $crseIdBD = $self->{DBHINSCRIPCION}->quote($crseId);
   my $groupEquivBD = $self->{DBHINSCRIPCION}->quote($groupEquiv);

	if ($groupEquiv ne '' && $crseId ne ''){
      $where .= " (c.id_crse_id = $crseIdBD OR c.id_group_equiv = $groupEquivBD) ";
	}elsif($crseId ne ''){
		$where .= "c.id_crse_id = $crseIdBD";
	}else{
		$where .= "c.id_group_equiv = $groupEquivBD";
	}


	if ($strm ne ''){
		my $strmBD = $self->{DBHINSCRIPCION}->quote($strm);
		$where .= " AND $strmBD >= cm.strm "; 
	}

	$where .= "AND cm.estado='VALIDADO'";

	my $sql =<<STR;
		SELECT  cm.descripcion, cm.contenido_minimo, cm.strm, cm.fecha_update, cm.estado
      FROM contmin_contenidos cm LEFT JOIN (
                  (SELECT cmc.id_cont_min,cmc.id_crse_id as id_crse_id, cmc.nombre as nombre_asig,null as id_group_equiv,null as nombre_group FROM contmin_crse_id cmc) UNION 
                  (SELECT cmg.id_cont_min,null,null,cmg.id_group_equiv, cmg.nombre FROM contmin_group_equiv cmg )
                 ) as c ON c.id_cont_min=cm.id_cont_min 
      WHERE 
         $where
      ORDER BY cm.strm DESC, c.id_crse_id DESC, cm.fecha_update DESC
      LIMIT 1
STR

   #open(FILE, ">>/tmp/log_debug.txt");
   #print FILE "\n$sql\n";
   #close(FILE);  

	my $sth = $self->{DBHINSCRIPCION}->prepare($sql);
   $sth->execute();

   if ($sth->errstr){
	   return '';
	}

   my $res = $sth->fetchrow_hashref();

	my $contMinimo  = $res->{'contenido_minimo'}  || '';	
	my $descripcion = $res->{'descripcion'} || '';	

	return $contMinimo if ($descripcion eq '');
	return $descripcion;
}

#-------------------------------------------------------------

sub getPlanesInscripcion {
	my $self          = shift;
  	my $acadPlanParam = shift || '';

   my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
		return '';
   }
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);
#	my $sql = <<SQL;
#	  SELECT DISTINCT p.acad_plan, ip.descr, ip.descr254, p.up_estado_plan
#	  FROM  ps_up_plan_alumnos p
#	  INNER JOIN ps_up_in_planes_vw ip ON p.acad_plan = ip.acad_plan
#	  WHERE p.emplid = $legajoBD
#		     AND p.up_estado_plan not in ('E')
#	  ORDER BY p.up_estado_plan asc, ip.descr
#
#SQL
	my $sql = <<SQL;
	  SELECT DISTINCT p.acad_plan, ip.descr, ip.descr254, p.up_estado_plan
	  FROM  ps_up_plan_alumnos p
	  INNER JOIN ps_up_in_planes_vw ip ON p.acad_plan = ip.acad_plan
	  WHERE p.emplid = $legajoBD
	  ORDER BY p.up_estado_plan asc, ip.descr

SQL

	 #print "content-type:text/html\n\n";print $sql;exit;
	 #$self->logDebug($sql);

  	my $sth = $self->{DBHPGW}->prepare($sql);

 	$sth->execute();

	my $checkedAcadPlan = '';
	my $array = [];
	my $acadPlan = '';
	while ( my $res = $sth->fetchrow_hashref()) {
		$acadPlan   = $res->{'acad_plan'} || '';
		$checkedAcadPlan = $acadPlan if ( $checkedAcadPlan eq '');
		$checkedAcadPlan = $acadPlan if ($acadPlan eq $acadPlanParam);
		push @{$array},$res;
   }


	my $htmlPlanes = '';
	foreach my $res (@{$array}){
		my $acadPlan   = $res->{'acad_plan'} || '';
		my $descr254   = $res->{'descr254'}  || '';
		my $descr      = $res->{'descr'}     || '';
		my	$checked = ($acadPlan eq $checkedAcadPlan) ? "checked ='checked'" : '';

		$htmlPlanes.=<<STR;
		 	<input name="acad_plan" value="$acadPlan" $checked  onclick="buscarInscripcionesFiltro()" title="$descr254" type="radio"><span title="$descr254">$descr</span><br> 
STR

	}

	return $htmlPlanes;
	
       
}

#-------------------------------------------------------------

sub getPlanesInscripcionDeportes {
	my $self     = shift;
  my $acadPlan = shift || '';
	
	my $htmlPlanes.=<<STR;
	 	<input name="acad_plan" value="$acadPlan" checked ='checked'  onclick="buscarInscripcionesFiltro()" title="Deportes" type="radio"><span title="Deportes">Deportes</span><br> 
STR
	
	return $htmlPlanes;
}

#-------------------------------------------------------------

sub getPlanesAvanceCarrera {
	my $self    = shift;
  
   my $legajo = $self->{SESION}->{LEGAJO} || '';

   if ($legajo !~ m/^[0-9]+$/){
		return ['',''];
   }

	my $acadPlanP = $self->{REQUEST}->param('acadPlan') || '';

	if ($acadPlanP eq ''){
		$acadPlanP = $self->{PLANPREF} || '';
	}


	if (!$self->validarAcadPlan($acadPlanP)){
		return ['',''];
	}

   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql=<<SQL;
SELECT distinct p.acad_plan, ip.descr, p.up_prom_fin_gral, p.up_prom_fin_apr, p.up_prom_cur_apr, p.up_prom_cur_gral, p.up_estado_plan, p.up_deuda_matricula
FROM ps_up_plan_alumnos as p, ps_up_in_planes_vw ip
WHERE
          p.emplid    = $legajoBD
      AND p.up_estado_plan in ('A','B','E')
      AND p.acad_plan = ip.acad_plan
SQL
	
	#print "content-type:text/html\n\n";print "sql $sql<br>";exit;

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	my $planes  = '';
	my $planes2 = '';
   my $promedioFinal = '';
   my $promedioCursada = '';

	my $cantReg = $sth->rows;
	my $resultado = int($cantReg / 2) + ($cantReg % 2);

	my $cont = 1;
	while ( my $res  = $sth->fetchrow_hashref()) { 
      my $acad_plan        = $res->{'acad_plan'}         || '';
      my $descripcion      = $res->{'descr'}             || '';

      my $up_prom_cur_apr  = $res->{'up_prom_cur_apr'}   || '';
      my $up_prom_cur_gral = $res->{'up_prom_cur_gral'}  || '';

      my $up_prom_fin_apr  = $res->{'up_prom_fin_apr'}   || '';
      my $up_prom_fin_gral = $res->{'up_prom_fin_gral'}  || '';



      my $status           =  $res->{'up_estado_plan'}   || '';

		my $estadoPlan = "";
		if($status eq 'A' || $status eq 'E' || $status eq 'B'){
			$estadoPlan = "BAJA <a href=\"/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=$acad_plan\" class=\"click mails2\">Readmisi&oacute;n</a>" if ($status eq 'B');
			$estadoPlan = "EGRESADO" if ($status eq 'E');
			if ($status eq 'A'){
				$estadoPlan = "ACTIVO";
				my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
				$estadoPlan = "REMATRICULAR" if ($up_deuda_matricula !~ m/^N$/i);

			}
		}
		my $checked =  ''; 
		if ($acad_plan eq $acadPlanP){
			$checked = "checked='checked'";

		}
		if ($cont<=$resultado){
			#$planes .=<<STR;
			#<input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="javascript:location.href='/cgi-bin/myup/myup_calificaciones.pl?acadPlan=$acad_plan'"> $descripcion - $estadoPlan<br>
#STR
			$planes .=<<STR;
         <input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="reloadAvanceCarrera('$acad_plan')"> $descripcion - $estadoPlan<br>
STR

		}else{
			$planes2 .=<<STR;
			<input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="reloadAvanceCarrera('$acad_plan')"> $descripcion - $estadoPlan<br>
STR

		}

		$cont++;	
   }
	

	return [$planes,$planes2];
}

#-------------------------------------------------------------

sub getPlanes {
	my $self    = shift;
  
   my $legajo = $self->{SESION}->{LEGAJO} || '';

   if ($legajo !~ m/^[0-9]+$/){
		return ['','','','',''];
   }

	my $acadPlanP = $self->{REQUEST}->param('acadPlan') || '';

	if ($acadPlanP eq ''){
		$acadPlanP = $self->{PLANPREF} || '';
	}

	my $aprobadas = $self->{REQUEST}->param('aprobadas') || '';
   my $estadoCheck = '';

	if (!$self->validarAcadPlan($acadPlanP)){
		return ['','','','',''];
	}

   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql=<<SQL;
SELECT distinct p.acad_plan, ip.descr, p.up_prom_fin_gral, p.up_prom_fin_apr, p.up_prom_cur_apr, p.up_prom_cur_gral, p.up_estado_plan, p.up_deuda_matricula
FROM ps_up_plan_alumnos as p, ps_up_in_planes_vw ip
WHERE
          p.emplid    = $legajoBD
      AND p.up_estado_plan in ('A','B','E')
      AND p.acad_plan = ip.acad_plan
SQL
	
	#print "content-type:text/html\n\n";print "sql $sql<br>";exit;

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	my $planes  = '';
	my $planes2 = '';
   my $promedioFinal = '';
   my $promedioCursada = '';

	my $cantReg = $sth->rows;
	my $resultado = int($cantReg / 2) + ($cantReg % 2);

	my $cont = 1;
	while ( my $res  = $sth->fetchrow_hashref()) { 
      my $acad_plan        = $res->{'acad_plan'}         || '';
      my $descripcion      = $res->{'descr'}             || '';

      my $up_prom_cur_apr  = $res->{'up_prom_cur_apr'}   || '';
      my $up_prom_cur_gral = $res->{'up_prom_cur_gral'}  || '';

      my $up_prom_fin_apr  = $res->{'up_prom_fin_apr'}   || '';
      my $up_prom_fin_gral = $res->{'up_prom_fin_gral'}  || '';



      my $status           =  $res->{'up_estado_plan'}   || '';

		my $estadoPlan = "";
		if($status eq 'A' || $status eq 'E' || $status eq 'B'){
			$estadoPlan = "BAJA <a href=\"/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=$acad_plan\" class=\"click mails2\">Readmisi&oacute;n</a>" if ($status eq 'B');
			$estadoPlan = "EGRESADO" if ($status eq 'E');
			if ($status eq 'A'){
				$estadoPlan = "ACTIVO";
				my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
				$estadoPlan = "REMATRICULAR" if ($up_deuda_matricula !~ m/^N$/i);

			}
		}
		my $checked =  ''; 
		if ($acad_plan eq $acadPlanP){
			$checked = "checked='checked'";
			$promedioFinal = "<a href=\"#\" class=\"myup_promedio\">Promedio con reprobados: $up_prom_fin_gral</a><br>" if ($up_prom_fin_gral);
			$promedioFinal .= ($promedioFinal ne '') ? " <a href=\"#\" class=\"myup_promedio\">Promedio sin reprobados: $up_prom_fin_apr</a>" : " <a href=\"#\" class=\"myup_promedio\">Promedio sin reprobados: $up_prom_fin_apr</a>" if ($up_prom_fin_apr);

#SE COMENTO $promedioCursada A PEDIDO DE MATIAS. SI SE NECESITA MOSTRAR DE NUEVO SOLO HAY QUE DESCOMENTARLO.

			$promedioCursada = "<br>&nbsp;";
#			$promedioCursada = "<a href=\"#\" class=\"myup_promedio\">Promedio con reprobados: $up_prom_cur_gral</a><br>" if ($up_prom_cur_gral);
#			$promedioCursada .= ($promedioCursada ne '') ? " <a href=\"#\" class=\"myup_promedio\">Promedio sin reprobados: $up_prom_cur_apr</a>" : "<a href=\"#\" class=\"myup_promedio\">Promedio sin reprobados: $up_prom_cur_apr</a>" if ($up_prom_cur_apr);


		}
		if ($cont<=$resultado){
			#$planes .=<<STR;
			#<input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="javascript:location.href='/cgi-bin/myup/myup_calificaciones.pl?acadPlan=$acad_plan'"> $descripcion - $estadoPlan<br>
#STR
			$planes .=<<STR;
         <input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="reload('$acad_plan')"> $descripcion - $estadoPlan<br>
STR

		}else{
			$planes2 .=<<STR;
			<input type="radio" name="acadPlan" value="$acad_plan" $checked onclick="reload('$acad_plan')"> $descripcion - $estadoPlan<br>
STR

		}

		$cont++;	
   }
	
	if($aprobadas eq 'true'){
		$estadoCheck = "checked='checked'";
	}else{
		$estadoCheck = '';	
	}
	
	my $checkAprobadas = <<STR;
		<input type="checkbox" $estadoCheck id="checkAprobadas" onclick="reload('$acadPlanP')" name="filtroAprobadas" value="$acadPlanP" >Ver solo aprobadas<br>
STR

	return [$planes,$planes2,$promedioFinal,$promedioCursada,$checkAprobadas];
}

#-------------------------------------------------------------

sub getPlanBaja {
	my $self = shift;
   my $legajo = $self->{SESION}->{LEGAJO} || '';

   if ($legajo !~ m/^[0-9]+$/){
      return ['','',''];
   }


	my $acadPlan = $self->{REQUEST}->param('plan_baja') || '';
   if ($acadPlan !~ m/^[A-Z0-9]{4,10}$/){
		return ['','',''];
	}

   my $legajoBD   = $self->{DBHPGW}->quote($legajo);
   my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);


	my $sql=<<SQL;
			SELECT p.acad_plan, p.acad_career, ip.descr254
			FROM ps_up_plan_alumnos p, ps_up_in_planes_vw ip
			WHERE
					p.emplid    = $legajoBD    AND
					p.acad_plan = $acadPlanBD  AND
               p.acad_plan = ip.acad_plan AND
               p.up_estado_plan = 'B'
SQL

	
	#print "content-type:text/html\n\n";print $sql;exit;

   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

	my $descrPlan  = '';
	my $acadCareer = '';
	my $codPlan    = '';
	
	while ( my $res  = $sth->fetchrow_hashref()) {
     $codPlan    = $res->{'acad_plan'}   || '';
     $descrPlan  = $res->{'descr254'}    || '';
     $acadCareer = $res->{'acad_career'} || '';
   }

	return [$codPlan,$descrPlan,$acadCareer];

}

#-------------------------------------------------------------

sub getCiclosReadmision {
	my $self       = shift;
	my $acadCareer = shift || return '';

   my $acadCareerBD = $self->{DBHPGW}->quote($acadCareer);

	my $sql=<<SQL;
			SELECT id, descr
			FROM ps_up_cicl_read_vw
			WHERE acad_career = $acadCareerBD
			ORDER BY descr ASC
SQL

	#print "content-type:text/html\n\n";print $sql;exit;


   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

	my $ret = '';
	
	while ( my $res  = $sth->fetchrow_hashref()) {
   	my $id    = $res->{'id'}    || '';
     	my $descr = $res->{'descr'} || '';
		$ret .=<<STR;
			<input type="radio" name="ciclo_readmision" value="$id"> $descr<br>
STR
   }

	return $ret;

}

#-------------------------------------------------------------

sub getAllPlanesBaja {
	my $self = shift;
   my $legajo = $self->{SESION}->{LEGAJO} || '';

   if ($legajo !~ m/^[0-9]+$/){
      return ['','',''];
   }

   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql=<<SQL;
			SELECT p.acad_plan, ip.descr254
			FROM ps_up_plan_alumnos p, ps_up_in_planes_vw ip
			WHERE
					p.emplid         = $legajoBD AND
               p.acad_plan      = ip.acad_plan AND
               p.up_estado_plan = 'B'
SQL


   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

	my $ret  = '';
	
	while ( my $res  = $sth->fetchrow_hashref()) {
     	my $acadPlan   = $res->{'acad_plan'}   || '';
     	my $descrPlan  = $res->{'descr254'}    || '';

		$ret .=<<STR;
			$descrPlan - BAJA <span style="font-size:9px;"><a class="click" href="/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=$acadPlan">Readmisi&oacute;n</a></span></span><br>
STR
   }


	return $ret;

}

#-------------------------------------------------------------

sub readmitirsePlan {
	my $self = shift;
	my $uid  = shift || '';

	my $errorGral = 'No pudo readmitirse porque se encontraron errores durante el proceso de readmisi&oacute;n.';

   my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      return ['ERROR','Legajo incorrecto.'];
   }

	if ($uid eq ''){
		$self->loguearFile("uid ($uid) vac�o.");
      return ['ERROR', $errorGral];
	}
	$uid    = uc($uid);
   
	my $hash  = $self->encriptar($uid,$legajo);
	if ($hash eq ''){
		$self->loguearFile("Hash vac�o.");
      return ['ERROR', $errorGral];
	}
	
	my $acadPlan = $self->{REQUEST}->param('plan_baja') || '';
   if ($acadPlan !~ m/^[A-Z0-9]{4,10}$/){
		return ['Error','El plan es incorrecto.'];
	}

	my $idCicloRead = $self->{REQUEST}->param('ciclo_readmision') || '';
   if ($idCicloRead !~ m/^[0-9]+$/){
      return ['ERROR','El ciclo seleccionado es incorrecto.'];
   }

   my $legajoBD      = $self->{DBHPGW}->quote($legajo);
   my $acadPlanBD    = $self->{DBHPGW}->quote($acadPlan);
   my $idCicloReadBD = $self->{DBHPGW}->quote($idCicloRead);


	my $sql=<<SQL;
         SELECT pf.acad_prog, pa.acad_career, cr.strm
         FROM 
               ps_up_plan_alumnos pa, ps_up_plan_fac_vw pf,  ps_up_cicl_read_vw cr
         WHERE
               cr.id          = $idCicloReadBD AND
               pa.emplid      = $legajoBD      AND
               pa.acad_plan   = $acadPlanBD    AND
               pa.acad_career = cr.acad_career AND 
               pa.acad_plan   = pf.acad_plan
			LIMIT 1
SQL

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();


	if ($sth->rows == 1){
		my $acadProg   = '';
		my $acadCareer = '';
		my $strm       = '';
		
		while ( my $res  = $sth->fetchrow_hashref()) {
			$acadProg   = $res->{'acad_prog'}   || '';
			$acadCareer = $res->{'acad_career'} || '';
			$strm       = $res->{'strm'}        || '';
		}


		my $uri = MyUP::Conf->URLREADMISION;
		if(not defined $uri || $uri eq ''){
			$self->loguearFile("La uri no esta definida.");
			return ['ERROR',$errorGral];
		}

		
		if ($acadCareer eq ''){
			$self->loguearFile("El acadCareer vino vac�o.");
			return ['ERROR',$errorGral];
		}

		if ($acadProg eq ''){
			$self->loguearFile("El acadProg vino vac�o.");
			return ['ERROR',$errorGral];
		}

		if ($strm !~ m/^[0-9]{4}$/){
			$self->loguearFile("El strm $strm es incorrecto. El strm debe tener 4 digitos");
			return ['ERROR',$errorGral];
		}

		my $action = "R";
		$uri .= "Operation=UP_READMISION_OP.v1&sistema=MYUP&uid=$uid&emplid=$legajo&hash=$hash&action=$action&acad_career=$acadCareer&acad_prog=$acadProg&acad_plan=$acadPlan&strm=$strm";

		#my $ua        = LWP::UserAgent->new;
		my $ua = LWP::UserAgent->new(ssl_opts => { verify_hostname => 0 }); 
		$ua->timeout(MyUP::Conf->TIMEOUTMENSAJERIAPSOFT);
		#my $hashParam = {
		#	'uid'         => $uid,
		#	'emplid'      => $legajo,
		#	'hash'        => $hash,
		#	'action'      => $action,
		#	'acad_career' => $acadCareer,
		#	'acad_prog'   => $acadProg,
		#	'acad_plan'   => $acadPlan
		#};
		#my $response = $ua->post($uri,$hashParam);
   	my $response = $ua->get($uri);
		
		my ($status, $msj) = @{$self->validarAltaReadmision($legajo,$uri,$response)};

		if ($status eq 'OK'){
			my $acadCareerBD = $self->{DBHPGW}->quote($acadCareer);
			my $acadPlanBD   = $self->{DBHPGW}->quote($acadPlan);

			$sql =<<STR;
				UPDATE ps_up_plan_alumnos SET up_estado_plan='A' WHERE emplid = $legajoBD AND acad_career = $acadCareerBD AND acad_plan=$acadPlanBD
STR
		
			$sth = $self->{DBHPGW}->prepare($sql);
			$sth->execute;

			if ( $sth->errstr ) {
				my $error = $sth->errstr;
				$self->loguearFile("Error sql: $sql\n".$error);
			}
		}
		return [$status,$msj];
	}else{
		return ['ERROR','No se encontr&oacute; el plan al que desea readmitirse.']
	}
}

#-------------------------------------------------------------

sub loguearFile {
	my $self = shift;
	my $str  = shift || ''; #2008

	use POSIX qw(strftime);
	my $fecha = strftime("%a, %d %b %Y %H:%M:%S %Z", localtime(time()));
	my $log =<<STR;
------------------------------------------------------------------\n
-INICIO\n
------------------------------------------------------------------\n
Fecha: $fecha\n
INICIO\n
$str\n
FIN\n
------------------------------------------------------------------\n
-FIN\n
------------------------------------------------------------------\n\n
STR
	open(FILE, ">>/tmp/log_error_myup.txt");
	print FILE "$log";
	close(FILE);
}

#-------------------------------------------------------------

sub validarAltaReadmision {
	my $self     = shift;
	my $legajo   = shift;
	my $uri      = shift;
	my $response = shift;

	my $errorGral = 'No pudo readmitirse porque se encontraron errores durante el proceso de readmisi&oacute;n.';
	my $error = '';

	my $respuesta = '';
	if (not defined $response){
		$error .= ", " if ($error ne '');
		$error .= "response\n";
	}else{
		if ($response->is_success) {
			$respuesta = $response->decoded_content;
		}else{
			$respuesta = $response->status_line;
		}
	}
	
	if (not defined $uri){
		$error .= ", " if ($error ne '');
		$error .= "uri\n";
		$uri = '';
	}
	
	if (not defined $legajo){
		$error .= ", " if ($error ne '');
		$error .= "legajo\n";
	}
	
	if ($error ne ''){
		$self->loguear($uri,$respuesta,'ERROR',"Las siguientes variables no estan definidas: $error",'validarAltaReadmision myup');
		return ["ERROR",$errorGral];
	}
	
   if ($response->is_success) {
   	if($respuesta =~ /^<\?xml version="1.0"\?>/){
			my $som = SOAP::Custom::XML::Deserializer
								 -> deserialize(join '', $respuesta)
								 -> valueof('//ROOT/');
			if (defined $som){
				if (not defined $som->EMPLID){
					$self->loguear($uri,$respuesta,'ERROR','El campo EMPLID no esta definido','validarAltaReadmision myup');
					return ["ERROR",$errorGral];
				}
				my $emplid = $som->EMPLID || '';
				if ($emplid ne $legajo){
					$self->loguear($uri,$respuesta,'ERROR',"El campo EMPLID es diferente al que se envio $emplid ne $legajo",'validarAltaReadmision myup');
					return ["ERROR",$errorGral];
				}
				if (not defined $som->STATUS){
					$self->loguear($uri,$respuesta,'ERROR','El campo STATUS no esta definido','validarAltaReadmision myup');
					return ["ERROR",$errorGral];
				}

				if (not defined $som->MSG){
					$self->loguear($uri,$respuesta,'ERROR','El campo MSG no esta definido','validarAltaReadmision myup');
					return ["ERROR",$errorGral];
				}

				my $campoStatus = $som->STATUS || '';
				my $campoMsg = $som->MSG || '';
				
				if ($campoStatus ne 'OK'){
					if($campoMsg eq 'HASH'){
						$self->loguear($uri,$respuesta,'ERROR','Veriricar que el uid,legajo,hash que se envian a peoplesoft sean correctos.','validarAltaReadmision myup');
						return ["ERROR",$errorGral];
					}elsif ($campoMsg eq 'NULL'){
						$self->loguear($uri,$respuesta,'ERROR','Veriricar que el uid,legajo,hash,action que se envian a peoplesoft no sean null.','validarAltaReadmision myup');
						return ["ERROR",$errorGral];
					}elsif($campoMsg eq ''){
						$self->loguear($uri,$respuesta,'ERROR','La RESPUESTA vino vac�a.','validarAltaReadmision myup');
						return ["ERROR",$errorGral];
					}else{
						$campoMsg = $self->_iconv($campoMsg) ? $self->_iconv($campoMsg) : $campoMsg;
						 $campoMsg = $errorGral if ($campoMsg eq '');
						$self->loguear($uri,$respuesta,'ERROR',$campoMsg,'validarAltaReadmision myup');
						return ["ERROR",$campoMsg];
					}
				}else{
					my $ret = "Se ha readmitido correctamente.";
					#SI LA RESPUESTA ES OK ENTRA POR ACA
					#$self->loguear($uri,$respuesta,'OK',$ret,'validarAltaReadmision myup');
					return ["OK",$ret];
				}
			}else{
				$self->loguear($uri,$respuesta,'ERROR','No se pudo armar el objeto som. Verificar si existe la etiqueta root y que este escrito en min�scula.','validarAltaReadmision myup');
				return ["ERROR",$errorGral];
			}
		}else{
			$self->loguear($uri,$respuesta,'ERROR','El formato del XML es incorrecto','validarAltaReadmision myup');
			return ["ERROR",$errorGral];
		}
   }else{
		$self->loguear($uri,$respuesta,'ERROR','Error de respuesta.','validarAltaReadmision myup');
		return ["ERROR",$errorGral]; 
	}
}

#-------------------------------------------------------------

sub loguear {
	my $self      = shift;
	my $envio     = shift || '';
	my $respuesta = shift || '';
	my $estado    = shift || '';
	my $detalle   = shift || '';
	my $metodo    = shift || '';
	
	$envio     = $envio     ne '' ? substr($envio, 0, 510)      : $envio;
	#$respuesta = $respuesta ne '' ? substr($respuesta, 0, 2040) : $respuesta;
	$estado    = $estado    ne '' ? substr($estado, 0, 8)       : $estado;
	$detalle   = $detalle   ne '' ? substr($detalle, 0, 2040)   : $detalle;
	$metodo    = $metodo    ne '' ? substr($metodo, 0, 30)      : $metodo;

	$envio     = $self->{DBHINSCRIPCION}->quote($envio);
	$respuesta = $self->{DBHINSCRIPCION}->quote($respuesta);
	$estado    = $self->{DBHINSCRIPCION}->quote($estado);
	$detalle   = $self->{DBHINSCRIPCION}->quote($detalle);
	$metodo    = $self->{DBHINSCRIPCION}->quote($metodo);

	my $sql =<<SQL;
		INSERT INTO myup_log_peoplesoft(envio, respuesta, estado, detalle, metodo)
	    						  VALUES ($envio, $respuesta, $estado, $detalle, $metodo)
SQL

	my $sth = $self->{DBHINSCRIPCION}->prepare($sql);
	$sth->execute;	
	
   if ( $sth->errstr ) {
		$self->loguearFile("$sql");
	}

return 1;
}

#-------------------------------------------------------------

sub javascriptPlanes {
	my $self      = shift;
	my $arrayPlanes = shift || '';

	return '' if ($arrayPlanes eq '');

	my $javascriptPlanes =<<STR;
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
var arrayPlanes = new Array ($arrayPlanes);
//----------------------------------------------------------------------

function ocultarAllPlanes(){
	for(var i = 0; i < arrayPlanes.length; i++) {
		var div_pos = i +1;
		borrarEstadoPlan(div_pos)
		ocultar_div_plan(div_pos);
	}
}

//----------------------------------------------------------------------

function mostrar_div_plan(id) {
	ocultarAllPlanes();
  var obj = document.getElementById('div_plan_' + id);
  obj.style.display =  '';

	var leyendaEstado = document.getElementById('div_estado_plan_' + id);
	var posArray = id-1;
	var estado = arrayPlanes[posArray][0];
	if (estado == "BAJA"){
  		leyendaEstado.innerHTML = '<span class="rojo">' + estado + '</span> <a class="click" href="/cgi-bin/myup/myup_readmision_ciclos.pl?plan_baja=' +  arrayPlanes[posArray][1]  + '" style="font-size:9px;color:#1a75b6;">Readmisi&oacute;n</a>';
	}else if(estado == "ACTIVA"){
  		leyendaEstado.innerHTML = '<span class="verde">' + estado + '</span>';
	}else if(estado == "EGRESADO"){
  		leyendaEstado.innerHTML = '<span class="azul">' + estado + '</span>';
	}
	setClassClickHref();

}

//----------------------------------------------------------------------

function ocultar_div_plan(id) {
  var obj = document.getElementById('div_plan_' + id);
  obj.style.display = 'none';
}
//----------------------------------------------------------------------

function borrarEstadoPlan(id) {
  var obj = document.getElementById('div_estado_plan_' + id);
  obj.innerHTML = '';
}

//----------------------------------------------------------------------

STR

	return $javascriptPlanes;
}

#-------------------------------------------------------------

sub getLinkEditar {
	my $self      = shift;
	my $str = '';
	
	if ($self->{GOTO}->tienePermisosSistemaDeAlumnos()){
      $str .= "| " if ($str ne '');
      $str .=<<STR;
					<a href="/cgi-bin/myup/goto.pl?app=sistema_de_alumnos&page=4"><img src="/Intranet/my-up/img/lapiz.fw.png" border="0" width="13" height="13" /> Editar</a>
STR
   }

   return $str;
}

#----------------------------------------------------------------

sub getLinkEditarDatos {
   my $self      = shift;
   my $str = '';

   if ($self->{GOTO}->tienePermisosSistemaDeAlumnos()){
      $str .= "| " if ($str ne '');
      $str .=<<STR;
               <li><a href="/cgi-bin/myup/goto.pl?app=sistema_de_alumnos&page=4">Editar datos personales</a></li>
STR
   }

   return $str;
}

#-------------------------------------------------------------
sub getBienvenido {
	my $self      = shift;
	
	my $bienvenido = 'Bienvenido';

	if($self->{SESION}->{SEXO} && $self->{SESION}->{SEXO} =~ m/^f$/i){
		$bienvenido = 'Bienvenida';
	}

	return $bienvenido;
}


#-------------------------------------------------------------
# $affiliation =~ /(student|alum|formerstudent)/ && $affiliation !~ /(employee|faculty)/
# alumnos, egresados o desertores
sub getVista1 {
	my $self   				= shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
  $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
  $hash->{myup_imagen}           = $self->getImagen();
  $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	###########################
	# Informacion util
	###########################
  my $registrosNotificaciones   = $self->getNotificaciones();

  my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}


	$hash->{myup_poleo}           = $poleo;

 	my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' && $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}   = $registrosNotificaciones;
  	$hash->{myup_ticket}           = $registrosTicket;
  	$hash->{myup_deudaAlumno}      = $deudaAlumno;
  	$hash->{myup_permisos}         = $permisosDeExamen;
	$hash->{myup_inf_util_estilo}  = $estiloInfUtil;
	$hash->{myup_inf_util_titulo}  = $self->getTituloInfoUtil();
	$hash->{myup_mapasedes_estilo} = $self->getEstiloOcultar();
	$hash->{myup_linksdesarrollo_estilo} = '';
   

	###########################
	# Asignaturas Alumnos
	###########################
   my ($calendarioAsignaturas,$calendarioActividades) = @{$self->getCalendarioAsignaturasActividades()};
   my $estiloAsignaturas = ($calendarioActividades eq '' && $calendarioAsignaturas eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo} = $estiloAsignaturas;
	$hash->{myup_calendario_asignatuaras}        = $calendarioAsignaturas;
   $hash->{myup_calendario_actividades}         = $calendarioActividades;
 
	return $hash;
}

#-------------------------------------------------------------
# $affiliation =~ /employee/ && $affiliation !~ /(student|alum|formerstudent|faculty)/
# Empleados
sub getVista2 {
   my $self      = shift;

	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
   $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
   $hash->{myup_imagen}           = $self->getImagen();
   $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	##########################e
	# Informacion util
	###########################
   my $registrosNotificaciones   = $self->getNotificaciones();

   my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

   my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' &&  $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}   = $registrosNotificaciones;
   $hash->{myup_ticket}           = $registrosTicket;
   $hash->{myup_deudaAlumno}      = $deudaAlumno;
   $hash->{myup_permisos}         = $permisosDeExamen;
	$hash->{myup_inf_util_estilo}  = $estiloInfUtil;
	$hash->{myup_inf_util_titulo}  = $self->getTituloInfoUtil();
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();

	return $hash;
}


#-------------------------------------------------------------
# $affiliation =~ /faculty/ && $affiliation !~ /(student|alum|formerstudent|employee)/
# Profesores
sub getVista3 {
   my $self      = shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
   $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
   $hash->{myup_imagen}           = $self->getImagen();
   $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	###########################
	# Informacion util
	###########################
   my $registrosNotificaciones   = $self->getNotificaciones();

  my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

   my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' && $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}   = $registrosNotificaciones;
   $hash->{myup_ticket}           = $registrosTicket;
   $hash->{myup_deudaAlumno}      = $deudaAlumno;
   $hash->{myup_permisos}         = $permisosDeExamen;
	$hash->{myup_inf_util_estilo}  = $estiloInfUtil;
	$hash->{myup_inf_util_titulo}  = $self->getTituloInfoUtil();


	################################
	# Asignaturas Docentes
	###############################
   my $asignaturasDocente = $self->getAsignaturasDocente;
   my $estiloAsignaturas  = ($asignaturasDocente eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo}  = $estiloAsignaturas;
   $hash->{myup_calendario_asignatuaras_docente} = $asignaturasDocente;
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();
 
	return $hash;
}

#-------------------------------------------------------------
# $affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /employee/ && $affiliation !~ /faculty/
# Alumnos, Egresados, Desertores, y Empleados
sub getVista4 {
   my $self      = shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
  $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
  $hash->{myup_imagen}           = $self->getImagen();
  $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	###########################
	# Informacion util
	###########################
  my $registrosNotificaciones   = $self->getNotificaciones();

	
	my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

  my $estiloInfUtil              = '';
	$hash->{myup_notificaciones}   = $registrosNotificaciones;
  $hash->{myup_ticket}           = $registrosTicket;
  $hash->{myup_deudaAlumno}      = $deudaAlumno;
  $hash->{myup_permisos}         = $permisosDeExamen;
	$hash->{myup_inf_util_estilo}  = $estiloInfUtil;
	$hash->{myup_inf_util_titulo}  = $self->getTituloInfoUtil();


	################################
	# Asignaturas Alumnos
	###############################
   my ($calendarioAsignaturas,$calendarioActividades) = @{$self->getCalendarioAsignaturasActividades()};
   my $estiloAsignaturas  = ($calendarioActividades eq '' && $calendarioAsignaturas eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo}  = $estiloAsignaturas;
	$hash->{myup_calendario_asignatuaras}         = $calendarioAsignaturas;
   $hash->{myup_calendario_actividades}          = $calendarioActividades;
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();
 
	return $hash;
}

#-------------------------------------------------------------
# $affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation !~ /employee/
# Alumnos, Egresados, Desertores y Docentes
sub getVista5 {
   my $self   				= shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
   $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
   $hash->{myup_imagen}           = $self->getImagen();
   $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	###########################
	# Informacion util
	###########################
   my $registrosNotificaciones   = $self->getNotificaciones();

   my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

   my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' && $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}  = $registrosNotificaciones;
   $hash->{myup_ticket}          = $registrosTicket;
   $hash->{myup_deudaAlumno}        = $deudaAlumno;
   $hash->{myup_permisos}           = $permisosDeExamen;
	$hash->{myup_inf_util_estilo} = $estiloInfUtil;
	$hash->{myup_inf_util_titulo} = $self->getTituloInfoUtil();


	################################
	# Asignaturas Alumnos y Docentes
	###############################
   my ($calendarioAsignaturas,$calendarioActividades) = @{$self->getCalendarioAsignaturasActividades()};
   my $asignaturasDocente = $self->getAsignaturasDocente;
   my $estiloAsignaturas  = ($calendarioActividades eq '' && $calendarioAsignaturas eq '' && $asignaturasDocente eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo}  = $estiloAsignaturas;
	$hash->{myup_calendario_asignatuaras}         = $calendarioAsignaturas;
   $hash->{myup_calendario_actividades}          = $calendarioActividades;
   $hash->{myup_calendario_asignatuaras_docente} = $asignaturasDocente;
 	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();

	return $hash;
}

#-------------------------------------------------------------
# $affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation =~ /employee/
# Alumnos, Egresados, Desertores, Profesores y Empleados
sub getVista6 {
   my $self   				= shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
   $hash->{myup_error_subir_foto} = $self->getErrorSubirFoto();
   $hash->{myup_imagen}           = $self->getImagen();
   $hash->{myup_calificaciones}   = $self->getPlanesCalificaciones();

	###########################
	# Informacion util
	###########################
   my $registrosNotificaciones   = $self->getNotificaciones();

   my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

   my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' && $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}  	= $registrosNotificaciones;
   $hash->{myup_ticket}          	= $registrosTicket;
   $hash->{myup_deudaAlumno}        = $deudaAlumno;
 	$hash->{myup_permisos}           = $permisosDeExamen;
	$hash->{myup_inf_util_estilo} 	= $estiloInfUtil;
	$hash->{myup_inf_util_titulo} 	= $self->getTituloInfoUtil();

	################################
	# Asignaturas Alumnos y Docentes
	###############################
   my ($calendarioAsignaturas,$calendarioActividades) = @{$self->getCalendarioAsignaturasActividades()};
   my $asignaturasDocente = $self->getAsignaturasDocente;
   my $estiloAsignaturas  = ($calendarioActividades eq '' && $calendarioAsignaturas eq '' && $asignaturasDocente eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo}  = $estiloAsignaturas;
	$hash->{myup_calendario_asignatuaras}         = $calendarioAsignaturas;
   $hash->{myup_calendario_actividades}          = $calendarioActividades;
   $hash->{myup_calendario_asignatuaras_docente} = $asignaturasDocente;
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();
 
	return $hash;
}

#-------------------------------------------------------------
# $affiliation !~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation =~ /employee/
# Profesores y Empleados
sub getVista7 {
   my $self      = shift;
	
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';

	###########################
	# Informacion util
	###########################
   my $registrosNotificaciones   = $self->getNotificaciones();

   my $registrosTicket           = '';
  my $deudaAlumno               = '';
  my $permisosDeExamen          = '';
  my $poleo 										= '';

  my $arrayAuxInfoUtil = $self->getInformacionUtil();

	foreach my $elem (@{$arrayAuxInfoUtil}){
		if($elem->{'TIPO'} eq 'poleo'){
			$poleo .= $self->getUltimoPoleoHtml($elem);
		}		

		if($elem->{'TIPO'} eq 'permisos_examen'){
			$permisosDeExamen .= $self->getPermisosDeExamenHtml($elem);
		}

		if($elem->{'TIPO'} eq 'equivalencia_pendiente' || $elem->{'TIPO'} eq 'titulo_intermedio'){
			$registrosTicket .= $self->getTicketHtml($elem);
		}

		if($elem->{'TIPO'} eq 'deuda_vencida_accion' || $elem->{'TIPO'} eq 'deuda_vencida' || $elem->{'TIPO'} eq 'adherir_debito'){
			$deudaAlumno .= $self->getDeudaAlumnoHtml($elem);
		}
	}

	$hash->{myup_poleo}           = $poleo;

   my $estiloInfUtil             = ($deudaAlumno eq '' && $permisosDeExamen eq '' && $registrosTicket eq '' && $registrosNotificaciones eq '') ? $self->getEstiloInfUtil() : '';
	$hash->{myup_notificaciones}  = $registrosNotificaciones;
   $hash->{myup_ticket}          = $registrosTicket;
	$hash->{myup_deudaAlumno}        = $deudaAlumno;
   $hash->{myup_permisos}           = $permisosDeExamen;
	$hash->{myup_inf_util_estilo} = $estiloInfUtil;
	$hash->{myup_inf_util_titulo} = $self->getTituloInfoUtil();


	################################
	# Asignaturas Docentes
	###############################
   my $asignaturasDocente = $self->getAsignaturasDocente;
   my $estiloAsignaturas  = ($asignaturasDocente eq '') ? $self->getEstiloAsignaturas() : '';
	$hash->{myup_calendario_asignatuaras_estilo}  = $estiloAsignaturas;
   $hash->{myup_calendario_asignatuaras_docente} = $asignaturasDocente;
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();
 
	return $hash;
}

#-------------------------------------------------------------
# Perfil por defecto
sub getVista8 {
	my $self = shift;
	my $hash = {};
	$hash->{myup_nombre_apellido}  = $self->{SESION}->{CN} || '';
	$hash->{myup_mapasedes_estilo} = '';
   $hash->{myup_linksdesarrollo_estilo} = $self->getEstiloOcultar();

	return $hash;
}

#-------------------------------------------------------------

sub getDeudaAlumnoHtml {
	my $self = shift;
	my $hash = shift || return '';
	
	my $retHtml = '';

	my $mensaje   = $hash->{'MENSAJE'};
	my $link 	    = $hash->{'LINK'};
	my $linkLabel = $hash->{'LINK_LABEL'};
	my $valor  		= $hash->{'VALOR'};
	
	if($hash->{'TIPO'} eq 'deuda_vencida_accion'){
		$retHtml =<<STR;		
		<div class="item-infoutil" id="div_deuda_vencida">$mensaje (<span onclick="actualizarImporteDeuda();" style="cursor:pointer;" title="Actualizar importe"><img src="/Intranet/my-up/img/iconrefresh.png" alt="Actualizar importe" width="15px" height="15px" style="margin: 0px;"> actualizado</span>  $valor hs) <a href="$link" style="font-size:12px;">$linkLabel</a></div>			
STR
	}elsif($hash->{'TIPO'} eq 'deuda_vencida'){
		$retHtml =<<STR;
		<div class="item-infoutil" id="div_deuda_vencida">$mensaje <a href="$link" style="font-size:12px;">$linkLabel</a></div>
STR
	}elsif($hash->{'TIPO'} eq 'adherir_debito'){
		$retHtml =<<STR;		
		<div class="item-infoutil" id="div_deuda_vencida"><a href="$link" style="font-size:12px;">$linkLabel</a></div>
STR
	} 

	return $retHtml;
}

#-------------------------------------------------------------

sub getTicketHtml {
	my $self = shift;
	my $hash = shift || return '';
	
	my $retHtml = '';

	my $mensaje   = $hash->{'MENSAJE'};
	my $icon 	    = $hash->{'ICON'};
	
	$retHtml =<<STR;
			<div class="item-infoutil"><img src="$icon" width="16" height="15" />$mensaje</div>			
STR
	 
	return $retHtml;
}


#-------------------------------------------------------------

sub getPermisosDeExamenHtml {
	my $self = shift;
	my $hash = shift || return '';
	
	my $retHtml = '';

	my $mensaje   = $hash->{'MENSAJE'};
	my $link 	    = $hash->{'LINK'};
	my $linkLabel = $hash->{'LINK_LABEL'};

	$retHtml =<<STR;
			<div class="item-infoutil">$mensaje<a href="$link"> $linkLabel</a></div>	
STR
	 
	return $retHtml;
}

#-------------------------------------------------------------

sub getUltimoPoleoHtml {
	my $self = shift;
	my $hash = shift || return '';

	my $retHtml = '';
	
	my $mensaje = $hash->{'MENSAJE'};
	$retHtml =<<STR;
			<div class="item-infoutil">$mensaje</div>
STR
	
	return $retHtml;
}

#-------------------------------------------------------------

sub getHomeTabsElement {
	my $self = shift;
	my $array = [];

	if (MyUP::Conf->TAB_INFOUTIL->[0]){
		#Se creo el metodo adherirDebitoAutomatico para ser usado por celular ya que no se puede poner en la sesion pq la misma vence en un a�o.
		$self->{SESION}->{ADHERIRDEBITO} = $self->adherirDebitoAutomatico(); 
		my $arrayAux = $self->getInformacionUtil();
				
		my $cantElement = scalar(@{$arrayAux});
		if ($cantElement > 0){
			my $hash = {
					'PREDETERMINADO' => MyUP::Conf->TAB_INFOUTIL->[1],
					#'ELEMENTOS'      => ['Ultimo poleo: hoy','Deuda: $500 <a href="http://www.palermo.edu">Pagar</a>'],
					'ELEMENTOS'      => $arrayAux,
					'TIPO'           => 'infoUtil',
					'LABELTAB'       => "Informaci�n �til",
					'PAGE'           => 'page1' 
			};

			push @{$array},$hash;
		}
	}

	return ['OK',$array];
}

#-------------------------------------------------------------
 
sub getInformacionUtil {
	my $self = shift;
	
	my $array = [];
	my $legajo = $self->{SESION}->{LEGAJO} || '';
	return $array if ($legajo !~ m/^[0-9]+$/); #Se puso esta condicion porque existen usuario como Bedelia, oscarecheverria... que no tienen legajo

	my	($status,$arrayJson) = @{$self->getCacheJson($self->{SESION}->{LEGAJO},2)};
	my $data = '';	

	if ($status eq 'VENCIDA'){
		$data = $self->getTicketNew();
		foreach my $res (@{$data}){
			my $cant = scalar(keys %{$res});
			push @{$array},$res if ($cant > 0);
		}

		$data = $self->getDeudaAlumnoNew();
		my $cant1 = scalar(keys %{$data});
		push @{$array},$data if ($cant1 > 0);

		$data = $self->getPermisosDeExamenNew();
		my $cant2 = scalar(keys %{$data});
		push @{$array},$data if ($cant2 > 0);

		$data = $self->getUltimoPoleoNew();
		my $cant3 = scalar(keys %{$data});
		push @{$array},$data if ($cant3 > 0);

		my $msj = to_json($array);      
		$self->saveCacheJson($self->{SESION}->{LEGAJO},2,$msj);
	}elsif($status eq 'OK'){
		eval {
			$array = decode_json $arrayJson;
		};
		if($@) {
		 my $error = "$@ - $arrayJson";
			$self->enviarErrorEmail("Error al convertir el json a estructura. getInformacionUtil , error => $error ");
		}
	}else{
		$data = $self->getTicketNew();
		foreach my $res (@{$data}){
			my $cant = scalar(keys %{$res});
			push @{$array},$res if ($cant > 0);
		}
		$data = $self->getDeudaAlumnoNew();
		my $cant1 = scalar(keys %{$data});
		push @{$array},$data if ($cant1 > 0);

		$data = $self->getPermisosDeExamenNew();
		my $cant2 = scalar(keys %{$data});
		push @{$array},$data if ($cant2 > 0);

		$data = $self->getUltimoPoleoNew();
		my $cant3 = scalar(keys %{$data});
		push @{$array},$data if ($cant3 > 0);
		my $msj = to_json($array);      
		$self->saveCacheJson($self->{SESION}->{LEGAJO},2,$msj);
	}

	return $array;
}

#-------------------------------------------------------------

sub getTicketNew {
  my $self = shift;

	my $legajo = $self->{SESION}->{LEGAJO} || '';
  if ($legajo !~ m/^[0-9]+$/){
    $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getTicket Paquete MyUP::MyUp");
    return [];
  }

  my $legajoBD = $self->{DBHPGW}->quote($legajo);

	my $arrayAlumno   = [1,2,3];
	my $arrayDocente  = [];
	my $arrayEmpleado = [];

	my $affiliation = $self->{SESION}->{AFFILIATION} || '';
	my $hashIdTipo = {};	
	if ($affiliation =~ /(student|alum|formerstudent)/ && $affiliation !~ /(employee|faculty)/){
		foreach (@{$arrayAlumno}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation =~ /employee/ && $affiliation !~ /(student|alum|formerstudent|faculty)/){
		foreach (@{$arrayEmpleado}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation =~ /faculty/ && $affiliation !~ /(student|alum|formerstudent|employee)/){
		foreach (@{$arrayDocente}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /employee/ && $affiliation !~ /faculty/){
		foreach (@{$arrayAlumno}){$hashIdTipo->{$_} = 1;}
		foreach (@{$arrayEmpleado}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation !~ /employee/){
		foreach (@{$arrayAlumno}){$hashIdTipo->{$_} = 1;}
		foreach (@{$arrayDocente}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation =~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation =~ /employee/){
		foreach (@{$arrayAlumno}){$hashIdTipo->{$_} = 1;}
		foreach (@{$arrayEmpleado}){$hashIdTipo->{$_} = 1;}
		foreach (@{$arrayDocente}){$hashIdTipo->{$_} = 1;}
	}elsif($affiliation !~ /(student|alum|formerstudent)/ && $affiliation =~ /faculty/ && $affiliation =~ /employee/){
		foreach (@{$arrayEmpleado}){$hashIdTipo->{$_} = 1;}
		foreach (@{$arrayDocente}){$hashIdTipo->{$_} = 1;}
	}

	my $whereIdTipo = '';
	foreach my $idTipo (keys %{$hashIdTipo}){
		$whereIdTipo .= ',' if ($whereIdTipo ne '');
		$idTipo = $self->{DBHPGW}->quote($idTipo);
		$whereIdTipo .= $idTipo;
	}
	return [] if ($whereIdTipo eq '');

   my $ret = [];

	my $sql=<<SQL;
         SELECT 'x' as x, tipo
         FROM myup_tramites_pendientes 
         WHERE emplid like $legajoBD  
               AND tipo in ($whereIdTipo)
SQL

   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

   while ( my $res  = $sth->fetchrow_hashref()) {
      my $tipo  = $res->{'tipo'} || '';
      if ($tipo == 1 ){
				my $hash = {
					'TITULO'     => '',
					'MENSAJE'    => "Equivalencia pendiente",
					'VALOR'      => '',
					'TIPO'       => 'equivalencia_pendiente',
					'LINK'       => '',
					'LINK_LABEL' => '',
					'ICON'       => 'https://wwws.palermo.edu/Intranet/my-up/img/sing.fw.png',
					'ICONPOS'    => 'start' #start | end
				};
				push @{$ret},$hash;
     }elsif($tipo == 2){
				my $hash = {
					'TITULO'     => '',
					'MENSAJE'    => "Solicitud de t�tulo intermedio (en tr�mite)",
					'VALOR'      => '',
					'TIPO'       => 'titulo_intermedio',
					'LINK'       => '',
					'LINK_LABEL' => '',
					'ICON'       => 'https://wwws.palermo.edu/Intranet/my-up/img/reloj-a.fw.png',
					'ICONPOS'    => 'start' #start | end
				};
				push @{$ret},$hash;
	  }
   }
         
}

#-------------------------------------------------------------

sub getDeudaAlumnoNew {
   my $self    = shift;
   
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getDeudaAlumnoNew Paquete MyUP::MyUp");
      return {};
   }

	my $hash = {};

	my $affiliation = $self->{SESION}->{AFFILIATION} || '';
	if ($affiliation =~ /(student|alum|formerstudent)/){
		my $legajoBD = $self->{DBHPGW}->quote($legajo);
		my $sql=<<SQL;
				SELECT 	sum(CASE WHEN currency_cd = 'uss' THEN due_amt ELSE 0 END)  as deuda_uss, 
						sum(CASE WHEN currency_cd = 'ars' THEN due_amt ELSE 0 END)  as deuda_ars, 
						max (fecha_alta)  as fecha_alta,
						(current_date - date(max (fecha_alta))) as diferencia,
						(current_timestamp - (max (fecha_alta))) as diferencia_time
				FROM ps_up_deuda_alumnos
				WHERE emplid like $legajoBD  AND UP_FECHA < current_date 
SQL

		my $sth = $self->{DBHPGW}->prepare($sql);
		$sth->execute();

		while ( my $res = $sth->fetchrow_hashref()) {
			my $deuda_uss = $res->{'deuda_uss'} || 0;
			my $deuda_ars = $res->{'deuda_ars'} || 0;
			
			if ( ($deuda_uss =~ m/^[0-9,\.]+$/ && $deuda_uss > 0 ) ||  ($deuda_ars =~ m/^[0-9,\.]+$/ && $deuda_ars > 0) ){
				my $ret       = '';

				my $linkPagar = '';
				my $linkLabel = '';
				if ($self->{GOTO}->tienePermisosSistemaDeAlumnos()){
					$linkPagar = MyUP::Conf->URLPAGARDEUDA;					
					$linkLabel = "Pagar";
				}

				my $fechaActualizacion = $res->{'fecha_alta'} || '';
				if ($fechaActualizacion ne ''){
					my $diferencia = $res->{'diferencia'} || 0;
					my $accion = "actualizar";

					if ($diferencia > 0){
						$fechaActualizacion = $self->formatearFechaYHora($fechaActualizacion);
					}else{
						$fechaActualizacion = $self->formatearFechaYHora($fechaActualizacion,'hora');
						my $difTime = $res->{'diferencia_time'} || '';
						if ($difTime =~ m/((-?[0-9]+) days? )?(-?[0-9]+):([0-9]+):/g){
							my $dia  = $2 || 0;
							my $hora = $3;
							my $min  = $4;

							$accion = '' if ($dia <= 0 && ($hora < 0 || ($hora == 0 && $min < 5)));

						}
					}
					if ($accion eq 'actualizar'){
						$hash = {
							'TITULO'     => '',
							'MENSAJE'    => "Deuda vencida: ARS:\$$deuda_ars/U\$S:$deuda_uss",
							'VALOR'      => $fechaActualizacion,
							'TIPO'       => 'deuda_vencida_accion',
							'LINK'       => $linkPagar,
							'LINK_LABEL' => $linkLabel,
							'ICON'       => '',
							'ICONPOS'    => '' #start | end
						};
					}else{
						$hash = {
							'TITULO'     => '',
							'MENSAJE'    => "Deuda vencida: ARS:\$$deuda_ars/U\$S:$deuda_uss (actualizado $fechaActualizacion hs)",
							'VALOR'      => '',
							'TIPO'       => 'deuda_vencida',
							'LINK'       => $linkPagar,
							'LINK_LABEL' => $linkLabel,
							'ICON'       => '',
							'ICONPOS'    => '' #start | end
						};
					}
				}else{
					$hash = {
						'TITULO'     => '',
						'MENSAJE'    => "Deuda vencida: ARS:\$$deuda_ars/U\$S:$deuda_uss",
						'VALOR'      => '',
						'TIPO'       => 'deuda_vencida',
						'LINK'       => $linkPagar,
						'LINK_LABEL' => $linkLabel,
						'ICON'       => '',
						'ICONPOS'    => '' #start | end
					};

				}

			}elsif($self->{SESION}->{ADHERIRDEBITO} && $self->{GOTO}->tienePermisosSistemaDeAlumnos()){
				my $urlAdherirDebito = MyUP::Conf->URLADHERIRDEBITO;
				$hash = {
					'TITULO'     => '',
					'MENSAJE'    => '',
					'VALOR'      => '',
					'TIPO'       => 'adherir_debito',
					'LINK'       => $urlAdherirDebito,
					'LINK_LABEL' => 'Adherir d�bito autom�tico',
					'ICON'       => '',
					'ICONPOS'    => '' #start | end
				};

			}
		}

	}

	return $hash;
}

#-------------------------------------------------------------

sub getDeudaAlumnoAjax {
   my $self    = shift;
   
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getDeudaAlumno Paquete MyUP::MyUp");
      return '';
   }

	my $ret = '';

	my $affiliation = $self->{SESION}->{AFFILIATION} || '';
	if ($affiliation =~ /(student|alum|formerstudent)/){
		my $legajoBD = $self->{DBHPGW}->quote($legajo);
		my $sql=<<SQL;
				sum(CASE WHEN currency_cd = 'uss' THEN due_amt ELSE 0 END)  as deuda_uss, 
				sum(CASE WHEN currency_cd = 'ars' THEN due_amt ELSE 0 END)  as deuda_ars, 
				max (fecha_alta)  as fecha_alta,
				(current_date - date(max (fecha_alta))) as diferencia,
				(current_timestamp - (max (fecha_alta))) as diferencia_time
				FROM ps_up_deuda_alumnos
				WHERE emplid like $legajoBD  AND UP_FECHA < current_date 
SQL
		#print "content-type:text/html\n\n";print "$sql<br>";

		my $sth = $self->{DBHPGW}->prepare($sql);
		$sth->execute();

		while ( my $res = $sth->fetchrow_hashref()) {
			my $deuda_uss = $res->{'deuda_uss'} || 0;
			my $deuda_ars = $res->{'deuda_ars'} || 0;

			if (($deuda_uss =~ m/^[0-9,\.]+$/ && $deuda_uss > 0 ) ||  ($deuda_ars =~ m/^[0-9,\.]+$/ && $deuda_ars > 0)){
				my $linkPagar = '';
				if ($self->{GOTO}->tienePermisosSistemaDeAlumnos()){
					$linkPagar = " <a href=\"/cgi-bin/myup/goto.pl?app=sistema_de_alumnos&page=7\">Pagar</a>";
				}

				my $fechaActualizacion = $res->{'fecha_alta'} || '';
				if ($fechaActualizacion ne ''){
					my $diferencia = $res->{'diferencia'} || 0;
					my $accionActualizarImporte = "<span onclick=\"actualizarImporteDeuda();\" style=\"cursor:pointer;\" title=\"Actualizar importe\"><img src=\"/Intranet/my-up/img/iconrefresh.png\" alt=\"Actualizar importe\" width=\"15px\" height=\"15px\" style=\"margin: 0px;\"> actualizado</span> ";

					if ($diferencia > 0){
						$fechaActualizacion = $self->formatearFechaYHora($fechaActualizacion);
					}else{
						$fechaActualizacion = $self->formatearFechaYHora($fechaActualizacion,'hora');
						my $difTime = $res->{'diferencia_time'} || '';
						if ($difTime =~ m/((-?[0-9]+) days? )?(-?[0-9]+):([0-9]+):/g){
							my $dia  = $2 || 0;
							my $hora = $3;
							my $min  = $4;

							$accionActualizarImporte = 'actualizado ' if ($dia <= 0 && ($hora < 0 || ($hora == 0 && $min < 5)));

						}
					}

					
					$fechaActualizacion = " ($accionActualizarImporte$fechaActualizacion hs)";

				}

				$ret = "Deuda vencida: ARS:\$$deuda_ars/U\$S:$deuda_uss$fechaActualizacion$linkPagar";
			}elsif($self->{SESION}->{ADHERIRDEBITO} && $self->{GOTO}->tienePermisosSistemaDeAlumnos()){
				my $urlAdherirDebito = MyUP::Conf->URLADHERIRDEBITO;
				$ret = "<a href=\"$urlAdherirDebito\" style=\"font-size:12px;\">Adherir D&eacute;bito Autom&aacute;tico</a>";
			}
		}

	}
	return $ret;
}

#-------------------------------------------------------------

sub getPermisosDeExamenNew {
   my $self = shift;
   	
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getPermisosDeExamen Paquete MyUP::MyUp");
      return {};
   }

	my $hash = {};

	my $affiliation = $self->{SESION}->{AFFILIATION} || '';
	if ($affiliation =~ /(student|alum|formerstudent)/){
   	my $legajoBD = $self->{DBHPGW}->quote($legajo);
		my $sql=<<SQL;
				SELECT sum(up_perm_disp) as up_perm_disp
				FROM ps_up_pex_vw 
				WHERE emplid like $legajoBD 
SQL

		my $sth = $self->{DBHPGW}->prepare($sql);
		$sth->execute();

		while ( my $res  = $sth->fetchrow_hashref()) {
			my $permisos  = $res->{'up_perm_disp'} || 0;

			$permisos = '0' if ($permisos =~ m/^[0-9]+$/ && $permisos <= 0);

			my $url = MyUP::Conf->URLPERMISOSEXAMEN;

			$hash = {
				'TITULO'     => '',
				'MENSAJE'    => "Permisos de examen: $permisos",
				'VALOR'      => '',
				'TIPO'       => 'permisos_examen',
				'LINK'       => $url,
				'LINK_LABEL' => 'Adquirir',
				'ICON'       => '',
				'ICONPOS'    => '' #start | end
			};
		}
	}
	return $hash;
}

#-------------------------------------------------------------

sub getUltimoPoleoNew {
   my $self = shift;
   	
	my $legajo = $self->{SESION}->{LEGAJO} || '';
   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getUltimoPoleo Paquete MyUP::MyUp");
      return {};
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);
	my	$sql=<<SQL;
         SELECT max(DATE) as poleo 
         FROM ps_up_poleadas_vw 
         WHERE emplid like $legajoBD 
				AND date > '1985-12-31'
SQL

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	my $hash = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
      my $ultimoPoleo = $res->{'poleo'} || '';
		if ($ultimoPoleo ne ''){
			$ultimoPoleo = $self->formatDate($ultimoPoleo);
			$hash = {
				'TITULO'     => '',
				'MENSAJE'    => "Ultimo Poleo: $ultimoPoleo",
				'VALOR'      => '',
				'TIPO'       => 'poleo',
				'LINK'       => '',
				'LINK_LABEL' => '',
				'ICON'       => '',
				'ICONPOS'    => '' #start | end
			};
		}
	}

	return  $hash;
}

#-------------------------------------------------------------

sub replaceDynamicTagsHead {
	my $self = shift;
	my $hash = {};
	
	my $nombreApellido = $self->{SESION}->{CN} || '';
	my $nombre = '';
	if ($nombreApellido ne ''){
		my @arrayNombre = split(' ',$nombreApellido);
		$nombre = $arrayNombre[0];
	}
	$hash->{myup_nombre}            = $nombre || '';
	$hash->{linksHead}              = $self->{GOTO}->getLinkHead();
	$hash->{myup_link_editar_datos} = $self->getLinkEditarDatos();
	$hash->{myup_bienvenido}        = $self->getBienvenido();	
	$hash->{cambiar_foto}           = $self->getCambiarFoto();
	return $hash;
}

#----------------------------------------------------------------

sub getCambiarFoto {
   my $self = shift;

	my $str = '';
	my $affiliation = $self->{SESION}->{AFFILIATION} || '';
	if ($affiliation =~ /(student|alum|formerstudent)/){
   	$str .=<<STR;
				<li><a class="click" href="/Intranet/my-up/subir_imagen.html">Cambiar foto de perfil</a></li>
STR
	}
   return $str;
}


#----------------------------------------------------------------

sub getErrorSubirFoto {
   my $self = shift;
	my $str = $self->{REQUEST}->param('error_subir_imagen') || '';	
   $str = "<font color=\"red\">&nbsp;&nbsp;&nbsp;$str</font>" if ($str ne '');
   return $str;
}

#----------------------------------------------------------------

sub getEstiloInfUtil {
   my $self = shift;
	my $str = 'style="display:none;"';	
   return $str;
}

#----------------------------------------------------------------

sub getEstiloOcultar {
   my $self = shift;
   my $str = 'style="display:none;"';
   return $str;
}

#----------------------------------------------------------------

sub getTituloInfoUtil {
   my $self = shift;
   my $str = <<STR;
		<h2 class="myupinfo">Informaci&oacute;n &uacute;til</h2>
		<span id="expandir_info" onclick="expandirInfo()">(ver mas)</span>
      <span id="colapsar_info" onclick="colapsarInfo()">(cerrar)</span>
STR
   return $str;
}

#----------------------------------------------------------------

sub getEstiloAsignaturas {
   my $self = shift;
   my $str = <<STR;
		style="display:none;"
STR
   return $str;
}

#----------------------------------------------------------------

sub isActivoPlan {
   my $self   = shift;
	my $legajo = shift || return 0;

   my $legajoBD = $self->{DBHPGW}->quote($legajo);
	my $sql=<<SQL;
		SELECT count(*) as cont FROM ps_up_plan_alumnos as p WHERE p.emplid = $legajoBD AND p.up_estado_plan in ('A')
SQL

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	
	my $res= $sth->fetchrow_hashref;
	my $cont = $res->{cont} || return 0;
	return 1 if ($cont > 0 );
	return 0;
}

#----------------------------------------------------------------

sub getRematricular {
   my $self   	 = shift;
   my $legajo 	 = shift || return [1,''];
	my $acadPlan = shift || return [1,''];
	my $acadProg = shift || return [1,''];

	my $uri = MyUP::Conf->URLREMATRICULAR;
	
	if(not defined $uri || $uri eq ''){
		$self->enviarErrorEmail("No esta definida la uri. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
   	return [1,''];
	}

   if ($acadPlan eq ''){
		$self->enviarErrorEmail("El acadPlan vino vacio. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
   	return [1,''];
	}

   if ($acadProg eq ''){
		$self->enviarErrorEmail("El acadProg vino vacio. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
   	return [1,''];
	}

   if ($legajo !~ m/^[0-9]+$/){
		$self->enviarErrorEmail("El legajo es incorrecto. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
   	return [1,''];
	}

	my $uid = $self->{SESION}->{UID} || '';
	if ($uid eq ''){
		$self->enviarErrorEmail("El uid $uid vino vac�o. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
      return [1,''];
	}
	$uid    = uc($uid);
   
	my $hash  = $self->encriptar($uid,$legajo);
	if ($hash eq ''){
		$self->enviarErrorEmail("Hash vac�o. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
      return [1,''];
	}

   my $action = "M";
   $uri .= "Operation=UP_MATRICULACION_OP.v1&emplid=$legajo&action=$action&acad_prog=$acadProg&acad_plan=$acadPlan&uid=$uid&hash=$hash";
	#$self->loguearFile($uri) if (MyUP::Conf->PROD_DES eq 'DESARROLLO');
	
	my $ua = LWP::UserAgent->new(ssl_opts => { verify_hostname => 0 });
	$ua->timeout(MyUP::Conf->TIMEOUTREMATRICULAR);
   #my $hashParam = {
   #     'emplid'      => $legajo,
   #     'action'      => $action,
   #     'acad_prog'   => $acadProg,
   #     'acad_plan'   => $acadPlan,
   #     'uid'         => $uid,
   #     'hash'        => $hash
   #};
   #my $response = $ua->post($uri,$hashParam);
   my $response = $ua->get($uri);
	
	my $respuesta = '';
   if ($response->is_success) {
   	$respuesta = $response->decoded_content;
  	}else{
   	$respuesta = $response->status_line;
   }

	if ($response->is_success) {
   	if($respuesta =~ /^<\?xml version="1.0"\?>/){
      	my $som = SOAP::Custom::XML::Deserializer
                        -> deserialize(join '', $respuesta)
                        -> valueof('//ROOT/');
         if (defined $som){
            if (not defined $som->EMPLID){
					$self->enviarErrorEmail("El campo EMPLID no esta definido Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
               return [1,''];
            }
            my $emplid = $som->EMPLID || '';
            if ($emplid ne $legajo){
					$self->enviarErrorEmail("El campo EMPLID es diferente al que se envio $emplid ne $legajo.  Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
               return [1,''];
            }
            if (not defined $som->STATUS){
					$self->enviarErrorEmail("El campo STATUS no esta definido  Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
               return [1,''];
            }

            if (not defined $som->MSG){
					$self->enviarErrorEmail("El campo MSG no esta definido.  Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
               return [1,''];
            }

            my $campoStatus = $som->STATUS || '';
            my $campoMsg = $som->MSG || '';

            if ($campoStatus eq 'ERROR'){
               if($campoMsg eq 'HASH'){
						$self->enviarErrorEmail("Veriricar que el legajo, acadPlan, acadProg, action que se envian a peoplesoft sean correctos. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
                  return [1,''];
               }elsif ($campoMsg eq 'NULL'){
						$self->enviarErrorEmail("Veriricar que el legajo, acadPlan, acadProg, action  que se envian a peoplesoft no sean null. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
                  return [1,''];
               }elsif ($campoMsg eq ''){
						$self->enviarErrorEmail("El campo msg vino vacio. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
                  return [1,''];
					}elsif($campoMsg eq 'El alumno debe matr�cula'){
                  return [1,''];
					}elsif($campoMsg =~ m/^[0-9]{4}$/){
                  return [1,$campoMsg];
               }else{
						$self->enviarErrorEmail("El campo msg es incorrecto. Se esperaba => El alumno debe matr�cula cuando status es ERROR. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
                  return [1,''];
               }
            }elsif ($campoStatus eq 'OK'){
					my $legajoBD   = $self->{DBHPGW}->quote($legajo);
					my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
					my $sql =<<STR;
						UPDATE ps_up_plan_alumnos 
                  SET    up_deuda_matricula='N' 
                  WHERE emplid    = $legajoBD   AND 
                        acad_plan = $acadPlanBD AND
                        up_estado_plan='A'
STR
 					my $sth = $self->{DBHPGW}->prepare($sql);
					$sth->execute();	
					if ($sth->errstr){
						my $error = $sth->errstr;
						$self->enviarErrorEmail("Error de sql $sql -> Metodo getRematricular Paquete MyUP::MyUp. ". $error) 
					}
            	return [0,''];
            }else{
					$self->enviarErrorEmail("El campo status es incorrecto => $campoStatus. Tiene que ser OK o ERROR. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
					return 1;
				}
         }else{
				$self->enviarErrorEmail("No se pudo armar el objeto som. Verificar si existe la etiqueta root y que este escrito en min�scula. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan)");
            return [1,''];
         }
      }else{
			$self->enviarErrorEmail("El formato del XML es incorrecto. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan). => $respuesta");
         return [1,''];
      }
   }else{
		$self->enviarErrorEmail("Error de respuesta. Metodo getRematricular Paquete MyUP::MyUp. (emplid => $legajo - acad_prog => $acadProg - acad_plan => $acadPlan). => $respuesta");
      return [1,''];
   }

   return [1,''];
}

#----------------------------------------------------------------

sub getJsonAvanceCarrera {
	my $self = shift;

	my $hashAvance       = {};
	my $hashAvanceReturn = {};
	my $arrayEstructura  = [];
	my $arrayEleCult     = [];
	my ($arrayCalif,$arrayCursando,$arrayCicloActual) = [];
  my $usarCache = 1;
	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
		if ($acadPlan eq ''){
			$acadPlan = $self->{PLANPREF} || '';
		}

	if($usarCache){			
		my $isDecoded = 0;
		my $legajoPlan = $self->{SESION}->{LEGAJO};

		my ($status,$arrayJson) = @{$self->getCacheJson($legajoPlan,3)};

		if($status ne 'VENCIDA' && $status ne 'ERROR'){
			my $hashCache = decode_json $arrayJson;
			if($hashCache->{$acadPlan}){ 
				$arrayJson = $hashCache->{$acadPlan};
				$isDecoded = 1;				
			}else{
				$hashAvance = $hashCache; 
				$status = 'ERROR';
			}
		}
		

		if ($status eq 'VENCIDA'){		
			$arrayEstructura = $self->getJsonAvanceEstructuraPlan();
			$arrayEleCult    = $self->getJsonAvanceElectivasCulturales();
			($arrayCalif,$arrayCursando,$arrayCicloActual) = @{$self->getJsonAvanceCalif()};      

			$hashAvance->{$acadPlan}->{'ESTRUCTURA'}          = $arrayEstructura;
			$hashAvance->{$acadPlan}->{'ELECTIVASCULTURALES'} = $arrayEleCult;
			$hashAvance->{$acadPlan}->{'NOTAS'}               = $arrayCalif;
			$hashAvance->{$acadPlan}->{'CURSANDO'}            = $arrayCursando;
			$hashAvance->{$acadPlan}->{'CICLOACTUAL'}         = $arrayCicloActual;
			
			my $msj = to_json($hashAvance);
			$self->saveCacheJson($legajoPlan,3,$msj);
		}elsif($status eq 'OK'){
			if($isDecoded){
				$hashAvance = $arrayJson;				
			}else{	
				eval {				
						$hashAvance = decode_json $arrayJson;				
				};
				if($@) {
				 my $error = "$@ - $arrayJson";				 
					$self->enviarErrorEmail("Error al convertir el json a estructura. getJsonAvanceCarrera , error => $error ");
				}
			}
		}else{
			$arrayEstructura = $self->getJsonAvanceEstructuraPlan();
			$arrayEleCult    = $self->getJsonAvanceElectivasCulturales();
			($arrayCalif,$arrayCursando,$arrayCicloActual) = @{$self->getJsonAvanceCalif()};      

			$hashAvance->{$acadPlan}->{'ESTRUCTURA'}          = $arrayEstructura;
			$hashAvance->{$acadPlan}->{'ELECTIVASCULTURALES'} = $arrayEleCult;
			$hashAvance->{$acadPlan}->{'NOTAS'}               = $arrayCalif;
			$hashAvance->{$acadPlan}->{'CURSANDO'}            = $arrayCursando;
			$hashAvance->{$acadPlan}->{'CICLOACTUAL'}         = $arrayCicloActual;
									
			my $msj = to_json($hashAvance);
			$self->saveCacheJson($legajoPlan,3,$msj);
		}
	}else{
		$arrayEstructura = $self->getJsonAvanceEstructuraPlan();
		$arrayEleCult    = $self->getJsonAvanceElectivasCulturales();
		($arrayCalif,$arrayCursando,$arrayCicloActual) = @{$self->getJsonAvanceCalif()};      

		$hashAvance->{$acadPlan}->{'ESTRUCTURA'}          = $arrayEstructura;
		$hashAvance->{$acadPlan}->{'ELECTIVASCULTURALES'} = $arrayEleCult;
		$hashAvance->{$acadPlan}->{'NOTAS'}               = $arrayCalif;
		$hashAvance->{$acadPlan}->{'CURSANDO'}            = $arrayCursando;
		$hashAvance->{$acadPlan}->{'CICLOACTUAL'}         = $arrayCicloActual;
	}
	
	if($hashAvance->{$acadPlan}){
		$hashAvanceReturn = $hashAvance->{$acadPlan};
	}else{
		$hashAvanceReturn = $hashAvance;
	}
	
	return $hashAvanceReturn;
}

#----------------------------------------------------------------

sub getCondicionPlanAvance {
	my $self   	  = shift;
	my $tablaPlan = shift || '';
	my $acadPlan  = $self->{REQUEST}->param('acadPlan') || '';
	
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
		
	if($tablaPlan ne ''){
		$tablaPlan = $tablaPlan."acad_plan";
	}else{
		$tablaPlan = "acad_plan";
	}

	my $where = "";
   if ($acadPlan =~ /^([^-]+)-/){
      my $acadPlan2 = $1;
      my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
      my $acadPlan2BD = $self->{DBHPGW}->quote($acadPlan2);
      $where = "WHERE $tablaPlan IN ($acadPlanBD,$acadPlan2BD)";
   }else{
   	  my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
			$where = "WHERE $tablaPlan = $acadPlanBD";
   }

   return $where;
}

#----------------------------------------------------------------

sub getJsonAvanceEstructuraPlan {
  my $self   	 = shift;

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
				
	return '' if (!$self->validarAcadPlan($acadPlan));

   my $legajo   = $self->{SESION}->{LEGAJO} || '';
   return '' if ($legajo !~ m/^[0-9]+$/);

   my $where = $self->getCondicionPlanAvance('p.');
       	
  my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql =<<STR;
		SELECT distinct p.rq_grp_line_nbr as grupo, a.descrlong as descripcion,p.up_crsid_cursado cod_cursada,p.up_crsid_final cod_final,p.requirement bolsa,p.up_anio_cuatri ubicacion,p.up_conector conector,p.up_particion numeroGrupo, p.up_ingles ingles, a.up_solo_cursado solo_cur, a.up_solo_final solo_fin, p.acad_plan plan, 
             ca.up_incl_infa inclca
		FROM  ps_up_asign_planes  p 
		LEFT JOIN ps_up_asignatur_vw a ON p.up_crsid_cursado=a.crse_id OR p.up_crsid_final=a.crse_id
		LEFT JOIN ps_up_in_planes_vw ca ON p.acad_plan=ca.acad_plan 
		$where
		
		ORDER BY p.rq_grp_line_nbr ASC, p.up_particion ASC
STR

	#print "content-type:text/html\n\n";
   #print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   my $arrayEstructuraPlan = [];

   while ( my $res = $sth->fetchrow_hashref()) {
      push @{$arrayEstructuraPlan},$res;
   }

	return $arrayEstructuraPlan;

}

#----------------------------------------------------------------

sub getJsonAvanceElectivasCulturales {

  my $self   	 = shift;

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
				
	return '' if (!$self->validarAcadPlan($acadPlan));

   my $legajo   = $self->{SESION}->{LEGAJO} || '';
   return '' if ($legajo !~ m/^[0-9]+$/);

  my $where = $self->getCondicionPlanAvance();
  my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql =<<STR;
		SELECT distinct p.requirement bolsa, a.descrlong as descripcion, a.crse_id cod_cursada_final, p.acad_plan plan
		FROM  ps_up_asign_planes  p 
		INNER JOIN ps_up_asig_req_vw ar ON p.requirement=ar.requirement 
		INNER JOIN ps_up_asignatur_vw a ON ar.crse_id=a.crse_id 
		$where AND ((ar.crse_id like '02%') OR (ar.crse_id like '05%' AND a.up_solo_final='Y'))
		AND ar.up_elec_comodin <> 'Y'
		ORDER BY p.requirement
STR

	#print "content-type:text/html\n\n";
  #print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   my $arrayEstructuraPlan = [];

   while ( my $res = $sth->fetchrow_hashref()) {
      push @{$arrayEstructuraPlan},$res;
   }

	return $arrayEstructuraPlan;

}

#----------------------------------------------------------------

sub getJsonAvanceCalif {
	my $self   	 = shift;

	my $arrayCalif       = [];
	my $arrayCursando    = [];
	my $arrayCicloActual = [];

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
				
	return [[],[],[]] if (!$self->validarAcadPlan($acadPlan));

  my $legajo   = $self->{SESION}->{LEGAJO} || '';
  return [[],[],[]] if ($legajo !~ m/^[0-9]+$/);

  my $where = $self->getCondicionPlanAvance();

  my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql =<<STR;
	SELECT
				cf.crse_id as cod_search, c.acad_career, c.strm ciclo, c.class_nbr, c.crse_id cod_materia, c.up_seleccion_asig tipo_asig, c.crse_grade_off nota, c.up_equiv_ext_pb equivalencia,
				c.crse_offer_nbr, c.session_code, c.class_section, c.up_crse_id_cursado is_cur, c.end_dt fecha_fin, c.up_strm_vto_or ciclo_venc, c.up_strm_vto_prga ciclo_prga, c.requirement bolsa,
            a.descr descripcion, a.up_solo_cursado solo_cur, a.up_solo_final solo_fin, c.up_equiv_ext_pb equivalencia, acad_plan plan
		FROM ps_up_his_calif_vw c LEFT JOIN ps_up_asi_cu_fi_vw cf ON c.crse_id = cf.crse_id_srch
					  LEFT JOIN ps_up_asignatur_vw a ON c.crse_id = a.crse_id
		$where AND emplid      = $legajoBD   				
				AND (c.up_aprobado = 'Y' OR c.up_equiv_ext_pb = 'E')
		ORDER BY up_crse_id_cursado ASC, c.end_dt DESC, c.crse_grade_off ASC   
STR

	#print "content-type:text/html\n\n";
  #print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   while ( my $res = $sth->fetchrow_hashref()) {
   	push @{$arrayCalif},$res;
   }


   $sql =<<STR;
   SELECT distinct oc.crse_id cod_materia, ch.requirement bolsa, a.descr descripcion, i.up_seleccion_asig tipo_asig, a.up_solo_cursado solo_cur, a.up_solo_final solo_fin, i.acad_plan plan
      FROM
              (SELECT max(strm) as strm, acad_career FROM ps_up_cic_ac_cu_vw GROUP BY acad_career) as c,
              ps_up_inscr_act_vw as i
              INNER JOIN ps_up_ofe_ci_ac_vw oc ON i.strm = oc.strm AND i.class_nbr = oc.class_nbr  AND i.emplid like $legajoBD
              INNER JOIN  ps_up_deta_ofer_vw do2 ON
                       oc.crse_id = do2.crse_id AND
                       oc.crse_offer_nbr = do2.crse_offer_nbr AND
                       oc.strm = do2.strm AND
                       oc.session_code = do2.session_code AND
                       oc.class_section = do2.class_section
                       LEFT JOIN ps_up_his_calif_vw ch ON  i.strm = ch.strm AND i.emplid = ch.emplid AND oc.crse_id = ch.crse_id
                       LEFT JOIN ps_up_asignatur_vw a ON ch.crse_id = a.crse_id
		WHERE 
      		c.strm              = i.strm  AND 
            i.stdnt_enrl_status = 'E'     AND 
            c.acad_career = i.acad_career

STR

	#print "content-type:text/html\n\n";
  #print "sql $sql<br>";

   $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   while ( my $res = $sth->fetchrow_hashref()) {
   	push @{$arrayCursando},$res;
   }
	
  $sql =<<STR;
  	SELECT max(strm) as ciclo
    FROM ps_up_cic_ac_fi_vw
STR

	#print "content-type:text/html\n\n";
  #print "sql $sql<br>";

   $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

   while ( my $res = $sth->fetchrow_hashref()) {
   	push @{$arrayCicloActual},$res;
   }



  return [$arrayCalif,$arrayCursando,$arrayCicloActual];
}

#----------------------------------------------------------------

sub getHtmlAvanceCarrera {
   my $self   	 = shift;

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
				
	return ['','','',''] if (!$self->validarAcadPlan($acadPlan));

	$self->{PLANREQ} = $acadPlan; 

   my $legajo   = $self->{SESION}->{LEGAJO} || '';
   return ['','','',''] if ($legajo !~ m/^[0-9]+$/);

   if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }
	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql =<<STR;
		SELECT 
          max(strm) as strm
      FROM 
          ps_up_cic_ac_fi_vw
STR


	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hashCicloActual = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $strm = $res->{'strm'} || next;
		$hashCicloActual->{$strm} = 1;
	}

	$sql=<<SQL;
      SELECT distinct oc.crse_id, ch.requirement, a.descr, i.up_seleccion_asig
      FROM
              (SELECT max(strm) as strm, acad_career FROM ps_up_cic_ac_cu_vw GROUP BY acad_career) as c,
              ps_up_inscr_act_vw as i
              INNER JOIN ps_up_ofe_ci_ac_vw oc ON i.strm = oc.strm AND i.class_nbr = oc.class_nbr  AND i.emplid like $legajoBD
              INNER JOIN  ps_up_deta_ofer_vw do2 ON
                       oc.crse_id = do2.crse_id AND
                       oc.crse_offer_nbr = do2.crse_offer_nbr AND
                       oc.strm = do2.strm AND
                       oc.session_code = do2.session_code AND
                       oc.class_section = do2.class_section
                       LEFT JOIN ps_up_his_calif_vw ch ON  i.strm = ch.strm AND i.emplid = ch.emplid AND oc.crse_id = ch.crse_id
                       LEFT JOIN ps_up_asignatur_vw a ON ch.crse_id = a.crse_id
		WHERE 
      		c.strm              = i.strm  AND 
            i.stdnt_enrl_status = 'E'     AND 
            c.acad_career = i.acad_career
SQL

	#$self->logDebug($sql);

	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";

   $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hashInscripciones = {};
	my $hashInscrEleCult  = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $crse_id     = $res->{'crse_id'}     || '';	
		my $requirement = $res->{'requirement'} || '';	
		my $descr       = $res->{'descr'}       || '';	
		my $up_seleccion_asig = $res->{'up_seleccion_asig'} || '';	
		if ($requirement eq ''){
			$hashInscripciones->{$crse_id} = 1;
		}else{
			$hashInscrEleCult->{$requirement}->{$crse_id} = {
				crse_id => $crse_id,
            descr   => $descr,
				up_seleccion_asig => $up_seleccion_asig
         };	
		}
	}

	$sql =<<STR;
		SELECT
				cf.crse_id as crse_id_srch, c.acad_career, c.strm, c.class_nbr, c.crse_id, c.up_seleccion_asig, c.crse_grade_off, c.up_equiv_ext_pb,
				c.crse_offer_nbr, c.session_code, c.class_section, c.up_crse_id_cursado, c.end_dt, c.up_strm_vto_or, c.up_strm_vto_prga, c.requirement,
            a.descr, a.up_solo_cursado, a.up_solo_final, c.up_equiv_ext_pb
		FROM ps_up_his_calif_vw c LEFT JOIN ps_up_asi_cu_fi_vw cf ON c.crse_id = cf.crse_id_srch
										  LEFT JOIN ps_up_asignatur_vw a ON c.crse_id = a.crse_id
		WHERE
				emplid      = $legajoBD   AND
				acad_plan   like $acadPlanBD AND
				(c.up_aprobado = 'Y' OR c.up_equiv_ext_pb = 'E')
		ORDER BY up_crse_id_cursado ASC, c.end_dt DESC, c.crse_grade_off ASC 
STR
	#$self->logDebug($sql);

	$sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hashCalificacionesCursada = {};
	my $hashCalificacionesFinal   = {};
	my $hashRequirementCalifCursada = {};	
	my $hashRequirementCalifFinal   = {};	
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $acad_career        = $res->{acad_career}        || ''; 
		my $strm               = $res->{strm}               || ''; 
		my $class_nbr          = $res->{class_nbr}          || ''; 
		my $crse_id            = $res->{crse_id}            || ''; 
		my $up_seleccion_asig  = $res->{up_seleccion_asig}  || ''; 
		my $crse_grade_off     = $res->{crse_grade_off}     || ''; 
		my $up_equiv_ext_pb    = $res->{up_equiv_ext_pb}    || ''; 
		my $crse_offer_nbr     = $res->{crse_offer_nbr}     || ''; 
		my $session_code       = $res->{session_code}       || ''; 
		my $class_section      = $res->{class_section}      || ''; 
		my $up_crse_id_cursado = $res->{up_crse_id_cursado} || ''; 
		my $end_dt             = $res->{end_dt}             || ''; 
		my $up_strm_vto_or     = $res->{up_strm_vto_or}     || ''; 
		my $up_strm_vto_prga   = $res->{up_strm_vto_prga}   || '';
		my $requirement        = $res->{requirement}        || '';
		my $crse_id_srch       = $res->{crse_id_srch}       || '';
		my $descr              = $res->{descr}              || '';
		my $up_solo_cursado    = $res->{up_solo_cursado}    || '';
		my $up_solo_final      = $res->{up_solo_final}      || '';

		if ($up_crse_id_cursado eq 'Y'){
			my $warning = 0;
			my $isVencida = 0;
			if (!$hashCalificacionesFinal->{$crse_id_srch}){
				my	$strmWarning = $up_strm_vto_prga || '';
				$strmWarning = $up_strm_vto_or if ($up_strm_vto_prga eq '');
				$warning = ($hashCicloActual->{$strmWarning}) ? 1 : 0;
				if (!$warning){
					$isVencida = $self->isVencidaAsignatura($strmWarning,$hashCicloActual);
				}
			}
			if (!$isVencida){
				my $isInscr = ($hashInscripciones->{$crse_id}) ? 1 : 0;
				if ($requirement ne ''){
					if (!($hashRequirementCalifFinal->{$requirement} && $hashRequirementCalifFinal->{$requirement}->{$crse_id_srch})){
						$hashRequirementCalifCursada->{$requirement}->{$crse_id} = 1;
					}
				}
				$hashCalificacionesCursada->{$crse_id} = {
					acad_career        => $acad_career,
					strm               => $strm,
					class_nbr          => $class_nbr,
					up_seleccion_asig  => $up_seleccion_asig,
					crse_grade_off     => $crse_grade_off,
					up_equiv_ext_pb    => $up_equiv_ext_pb,
					crse_offer_nbr     => $crse_offer_nbr,
					session_code       => $session_code,
					class_section      => $class_section,
					up_crse_id_cursado => $up_crse_id_cursado,
					end_dt             => $end_dt,
					up_strm_vto_or     => $up_strm_vto_or,
					up_strm_vto_prga   => $up_strm_vto_prga,
					requirement        => $requirement,
					crse_id_srch       => $crse_id_srch,
					warning            => $warning,
					descr              => $descr,
      			up_solo_cursado    => $up_solo_cursado,
      			up_solo_final      => $up_solo_final,
					esta_inscripto     => $isInscr
				};
			}
		}elsif ($up_crse_id_cursado eq 'N'){
			if ($requirement ne ''){
				$hashRequirementCalifFinal->{$requirement}->{$crse_id} = 1; 
			}
			$hashCalificacionesFinal->{$crse_id} = {
				acad_career        => $acad_career,
				strm               => $strm,
				class_nbr          => $class_nbr,
				up_seleccion_asig  => $up_seleccion_asig,
				crse_grade_off     => $crse_grade_off,
				up_equiv_ext_pb    => $up_equiv_ext_pb,
				crse_offer_nbr     => $crse_offer_nbr,
				session_code       => $session_code,
				class_section      => $class_section,
				up_crse_id_cursado => $up_crse_id_cursado,
				end_dt             => $end_dt,
				up_strm_vto_or     => $up_strm_vto_or,
				up_strm_vto_prga   => $up_strm_vto_prga,
				requirement        => $requirement,
            crse_id_srch       => $crse_id_srch,
				warning            => 0,
				descr              => $descr,
      		up_solo_cursado    => $up_solo_cursado,
      		up_solo_final      => $up_solo_final
			};
		}
	}

	$sql =<<STR;
			SELECT a.descr, a.crse_id as crseid, ap.up_crsid_cursado, ap.up_crsid_final, ap.requirement, ap.rq_grp_line_nbr, 
					 ap.rq_grp_line_type, ap.up_conector, ap.parenthesis, ap.up_anio_cuatri, ap.up_ingles, a.up_solo_cursado, a.up_solo_final, ap.acad_plan
			FROM
 				   ps_up_plan_alumnos pa INNER JOIN ps_up_asign_planes ap ON pa.acad_plan = ap.acad_plan
               LEFT JOIN ps_up_asignatur_vw a ON (ap.up_crsid_cursado = a.crse_id OR (ap.up_crsid_cursado ='' AND ap.up_crsid_final = a.crse_id))
			WHERE
				pa.emplid      = $legajoBD    AND 
				ap.acad_plan   like $acadPlanBD  AND
            pa.up_estado_plan in ('A','B','E','I') 
			ORDER BY  ap.acad_plan ASC, ap.RQ_GRP_LINE_NBR ASC, crseid ASC, ap.requirement asc

STR
	
	#$self->logDebug($sql);

	$sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	
	my $hashAnio = {};
	my $maxAnio = 0;
	my $hashIngles = {
		'I' => {},
		'II' => {},
		'III' => {},
		'IV' => {},
		'V' => {},
		'VI' => {},
		'VII' => {}
	};
	
	my $hashMaterias    = {};
	my $punteroParenthesis = '';
	my $hashAnioCuatri = {};
	my $hashRequeriment = {};
	
	my $htmlComputacionAplicada = '';
	my $htmlOtros               = '';
	my $hashRequerimentUsados = {};

   while ( my $res  = $sth->fetchrow_hashref()) {
		my $descr            = $res->{'descr'}            || '';
		my $crseid           = $res->{'crseid'}           || '';
		my $acad_plan        = $res->{'acad_plan'}        || '';
		my $up_crsid_cursado = $res->{'up_crsid_cursado'} || '';
		my $up_crsid_final   = $res->{'up_crsid_final'}   || '';
		my $requirement      = $res->{'requirement'}      || '';
		my $rq_grp_line_nbr  = $res->{'rq_grp_line_nbr'}  || '';
		my $rq_grp_line_type = $res->{'rq_grp_line_type'} || '';
		my $up_conector      = $res->{'up_conector'}      || '';
		my $parenthesis      = $res->{'parenthesis'}      || '';
      my $up_anio_cuatri   = $res->{'up_anio_cuatri'}   || '';
		my $up_ingles        = $res->{'up_ingles'}        || '';
		my $up_solo_cursado  = $res->{'up_solo_cursado'}  || '';
      my $up_solo_final    = $res->{'up_solo_final'}    || '';

		my $anio = '';
		my $cuatrimestre = '';
		
		my $tipoAsig = '';

		$tipoAsig = 'obligatoria' if($requirement eq '');
		$tipoAsig = 'electiva' if($requirement =~ m/^9/);
		$tipoAsig = 'cultural' if($requirement =~ m/^8/);

		if (!$hashAnio->{$up_anio_cuatri}){
			($anio,$cuatrimestre) = @{$self->getAnioCuatrimestre($up_anio_cuatri)};
			$hashAnio->{$up_anio_cuatri} = [$anio,$cuatrimestre];
			$maxAnio = $anio if ($anio > $maxAnio);
		}else{
			$anio = $hashAnio->{$up_anio_cuatri}->[0];
			$cuatrimestre = $hashAnio->{$up_anio_cuatri}->[1];
		}
		if ($descr =~ m/COMPUTACION APLICADA/){
			$descr .= " (Final)"   if ($up_solo_final eq 'Y' && $up_solo_cursado eq '');
			$descr .= " (Cursado)" if ($up_solo_cursado eq 'Y' && $up_solo_final eq '');
			
			my $class  = '';
			my $nota   = '';
			my $curFin = '';

			if ($hashCalificacionesCursada->{$up_crsid_cursado}){
				if ($hashCalificacionesFinal->{$up_crsid_final}){
					$nota = $hashCalificacionesFinal->{$up_crsid_final}->{crse_grade_off} || '';
					$class = " verde";
				}else{
					$nota = $hashCalificacionesCursada->{$up_crsid_cursado}->{crse_grade_off} || '';
					$class = ($hashCalificacionesCursada->{$up_crsid_cursado}->{warning}) ? " rosa" : " amarillo";
				 	$curFin = 'N';
				}
			}elsif ($hashCalificacionesFinal->{$up_crsid_final}){
				$nota = $hashCalificacionesFinal->{$up_crsid_final}->{crse_grade_off} || '';
				$class = " verde";
			}elsif($hashInscripciones->{$up_crsid_cursado}){
				$class = " celeste";
			 	$curFin = 'N';
			}else{
				 $curFin = 'Y';
			}

			#$nota = "<p class=\"nota\">$nota</p>" if ($nota ne '');
			my $style = ($htmlComputacionAplicada eq '') ? " style=\"border-left: 1px solid #444444;\" " : '';
			my $crseIdParam = $up_crsid_cursado || $up_crsid_final;
			$htmlComputacionAplicada .= "<div $style title=\"$descr\" class=\"cuadro$class\"><a class=\"clickAvance\" href=\"/cgi-bin/myup/avanceCarreraPopUp.pl?acad_plan=$acadPlan&crse_id=$crseIdParam&codCur=$up_crsid_cursado&codFin=$up_crsid_final&tipoAsig=$tipoAsig&curFin=$curFin\">$descr</a>&nbsp;$nota</div>";

			next;
		}elsif ($requirement ne '' && $requirement !~ /^(8|9)/){
			#####################################################################################################################
			#Aca no entra porque se filtran antes los planes bolsa son los, requiremetn "00000" que solo tienen los planes bolsa.#
			#####################################################################################################################
			
			my ($status,$sthRequirement) = @{$self->getAsignaturasByRequirement($requirement)};

			if ($status eq 'OK'){
				while ( my $resultado  = $sthRequirement->fetchrow_hashref()) {
					my $descrRequirement            = $resultado->{'descr'}            || '';
					my $crseidRequirement           = $resultado->{'crseid'}           || '';
					my $acad_planRequirement        = $resultado->{'acad_plan'}        || '';
					my $up_crsid_cursadoRequirement = $resultado->{'up_crsid_cursado'} || '';
					my $up_crsid_finalRequirement   = $resultado->{'up_crsid_final'}   || '';
					my $requirementRequirement      = $resultado->{'requirement'}      || '';
					my $rq_grp_line_nbrRequirement  = $resultado->{'rq_grp_line_nbr'}  || '';
					my $rq_grp_line_typeRequirement = $resultado->{'rq_grp_line_type'} || '';
					my $up_conectorRequirement      = $resultado->{'up_conector'}      || '';
					my $parenthesisRequirement      = $resultado->{'parenthesis'}      || '';
					my $up_anio_cuatriRequirement   = $resultado->{'up_anio_cuatri'}   || '';
					my $up_inglesRequirement        = $resultado->{'up_ingles'}        || '';
					my $up_solo_cursadoRequirement  = $resultado->{'up_solo_cursado'}  || '';
					my $up_solo_finalRequirement    = $resultado->{'up_solo_final'}    || '';
					
					my $class = '';
					my $nota  = '';
					
					if ($hashCalificacionesCursada->{$up_crsid_cursadoRequirement}){
						if ($hashCalificacionesFinal->{$up_crsid_finalRequirement}){
							$nota = $hashCalificacionesFinal->{$up_crsid_finalRequirement}->{crse_grade_off} || '';
							$class = " verde";
						}else{
							$nota = $hashCalificacionesCursada->{$up_crsid_cursadoRequirement}->{crse_grade_off} || '';
							$class = ($hashCalificacionesCursada->{$up_crsid_cursadoRequirement}->{warning}) ? " rosa" : " amarillo";
						}
					}elsif ($hashCalificacionesFinal->{$up_crsid_finalRequirement}){
						$nota = $hashCalificacionesFinal->{$up_crsid_finalRequirement}->{crse_grade_off} || '';
						$class = " verde";
					}elsif($hashInscripciones->{$up_crsid_cursadoRequirement}){
						$class = " celeste";
					}
					#$nota = "<p class=\"nota\">$nota</p>" if ($nota ne '');
					$htmlOtros .= "<div class=\"cuadro_otras$class\"><p>$descrRequirement</p>&nbsp;$nota</div>";
				}
			}

			next;
		}elsif ($requirement ne ''){
			my $crseIdAux = $self->getCrseIdElectivaOCulturalNew($hashRequerimentUsados,$hashRequirementCalifFinal->{$requirement},$hashRequirementCalifCursada->{$requirement});
			if ($crseIdAux ne ''){
				my $crseId = $crseIdAux;
				$hashRequerimentUsados->{$crseId} = 1;

				my $hash = {};
				my $crseIdSrch = '';
				if ($hashCalificacionesFinal->{$crseId}){
					$hash = $hashCalificacionesFinal;
					$crseIdSrch = $hash->{$crseId}->{crse_id_srch} || '';
					$crseIdSrch = $crseId if ($crseIdSrch eq '');
				}else{
					$hash = $hashCalificacionesCursada;
				}

				$hashMaterias->{$crseId}->{DESCRIPCION} =  $hash->{$crseId}->{descr} || '';
		
				if ($hashMaterias->{$crseId}->{DESCRIPCION} !~ m/\(E\)/ && $hashMaterias->{$crseId}->{DESCRIPCION} !~ m/\(C\)/){
					my $up_seleccion_asig = $hash->{$crseId}->{up_seleccion_asig} || '';
					$hashMaterias->{$crseId}->{DESCRIPCION} .= " ($up_seleccion_asig)"; 
				}
	
				$hashMaterias->{$crseId}->{ESTADO}      = ($hashCalificacionesFinal->{$crseId}) ? 'FA' : 'CA';
				$hashMaterias->{$crseId}->{NOTA}        = $hash->{$crseId}->{crse_grade_off} || '';
				$hashMaterias->{$crseId}->{WARNING}     = $hash->{$crseId}->{warning};
				$hashMaterias->{$crseId}->{CRSEID}      = ($hashCalificacionesFinal->{$crseId}) ? $crseIdSrch : $crseId;
				$hashMaterias->{$crseId}->{REQUIREMENT} = $requirement;
				$hashMaterias->{$crseId}->{LISTADO}     = [];
				$hashMaterias->{$crseId}->{SOLOCURSADO} = $hash->{$crseId}->{up_solo_cursado} || '';
				$hashMaterias->{$crseId}->{SOLOFINAL}   = $hash->{$crseId}->{up_solo_final}   || '';
				$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = $hash->{$crseId}->{esta_inscripto}   || 0;
				$hashMaterias->{$crseId}->{LISTCULTURALORELECTIVA} = 0;
				
				$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
				$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
				$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

				push @{$hashAnioCuatri->{$anio}->{$cuatrimestre}}, $crseId;
			}else{
				my $crseIdAux = $self->getCrseIdElectivaOCulturalNew($hashRequerimentUsados,$hashInscrEleCult->{$requirement});
				if ($crseIdAux ne ''){
					my $crseId = $crseIdAux;
					$hashRequerimentUsados->{$crseId} = 1;

					$hashMaterias->{$crseId}->{DESCRIPCION} =  $hashInscrEleCult->{$requirement}->{$crseId}->{descr} || '';

					if ($hashMaterias->{$crseId}->{DESCRIPCION} !~ m/\(E\)/ && $hashMaterias->{$crseId}->{DESCRIPCION} !~ m/\(C\)/){
						my $up_seleccion_asig = $hashInscrEleCult->{$requirement}->{$crseId}->{up_seleccion_asig} || '';
						$hashMaterias->{$crseId}->{DESCRIPCION} .= " ($up_seleccion_asig)";
					}
					$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = 1;

					$hashMaterias->{$crseId}->{CRSEID}      = $crseId;
					$hashMaterias->{$crseId}->{REQUIREMENT} = $requirement;
					$hashMaterias->{$crseId}->{LISTADO}     = [];
					$hashMaterias->{$crseId}->{LISTCULTURALORELECTIVA} = 0;

					$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
					$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
					$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

					push @{$hashAnioCuatri->{$anio}->{$cuatrimestre}}, $crseId;
				}else{
					my $crseId = $requirement;
					$hashMaterias->{$crseId}->{LISTADO} = [];
					if ($crseId =~ m/^8/){
						$hashMaterias->{$crseId}->{DESCRIPCION} = 'CULTURAL';
					}elsif ($crseId =~ m/^9/){
						$hashMaterias->{$crseId}->{DESCRIPCION} = 'ELECTIVA';
					}else{
						#next;
						$hashMaterias->{$crseId}->{DESCRIPCION} = 'ELECTIVA';
					}
					$hashMaterias->{$crseId}->{ESTADO} = '';
					$hashMaterias->{$crseId}->{NOTA}   = '';
					$hashMaterias->{$crseId}->{WARNING} = 0;
					$hashMaterias->{$crseId}->{CRSEID} = $crseId;
					$hashMaterias->{$crseId}->{LISTCULTURALORELECTIVA} = 1;
					
					$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
					$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
					$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

					push @{$hashAnioCuatri->{$anio}->{$cuatrimestre}}, $crseId;

					$hashRequeriment->{$crseId} = 1;

				}
			}
		}elsif ($up_ingles eq 'Y' || $descr =~ m/INGLES/){
			my $class = '';
			my $nota  = '';
			my $nivelIngles = '';
			if ($descr =~ m/(III|II|I|IV|VII|VI|V)$/){
				$nivelIngles = $1; 
				#print "content-type:text/html\n\n";
				#print "nivel $descr $nivelIngles<br>";
			}

			next if ($hashIngles->{$nivelIngles} && $hashIngles->{$nivelIngles}->{ESTADO} && $hashIngles->{$nivelIngles}->{ESTADO} eq 'FA');
			
			my $curFin='';

			if ($hashCalificacionesCursada->{$up_crsid_cursado} && !$hashCalificacionesFinal->{$up_crsid_final}){
				$hashIngles->{$nivelIngles}->{ESTADO} = 'CA';
				$nota = $hashCalificacionesCursada->{$up_crsid_cursado}->{crse_grade_off} || '';
				$class = ($hashCalificacionesCursada->{$up_crsid_cursado}->{warning}) ? " rosa" : " amarillo";
				$curFin = 'N';
			}elsif ($hashCalificacionesFinal->{$up_crsid_final}){
				$nota = $hashCalificacionesFinal->{$up_crsid_final}->{crse_grade_off} || '';
				$class = " verde";
				$hashIngles->{$nivelIngles}->{ESTADO} = 'FA';
			}else{
				next if ($hashIngles->{$nivelIngles} && $hashIngles->{$nivelIngles}->{ESTADO} && $hashIngles->{$nivelIngles}->{ESTADO} eq 'CA');
				if($hashInscripciones->{$up_crsid_cursado}){
					$class = " celeste";
					$curFin = 'N';
				}else{
					$curFin = 'Y';
				}
				$hashIngles->{$nivelIngles}->{ESTADO} = '';
			}

			$nota = "<p class=\"nota\">$nota</p>" if ($nota ne '');
			$descr =~ s/IDIOMA INGLES //i;
			my $style = ($nivelIngles eq 'I') ? " style=\"border-left: 1px solid #444444;\" " : '';

			my $crseIdParam = $up_crsid_cursado || $up_crsid_final;

			my $hrefIngles = "<a class=\"clickAvance\" href=\"/cgi-bin/myup/avanceCarreraPopUp.pl?acad_plan=$acadPlan&crse_id=$crseIdParam&codCur=$up_crsid_cursado&codFin=$up_crsid_final&tipoAsig=$tipoAsig&curFin=$curFin\">$descr</a>";

			$hashIngles->{$nivelIngles}->{HTML} = "<div $style class=\"cuadro$class\"><p>$hrefIngles</p>$nota</div>";
		}else{
			my $crseId = '';
			my $firstElement = 0;
			if ($punteroParenthesis ne ''){
				$firstElement = 0;
				$crseId  = $punteroParenthesis;
			}else{
				$firstElement = 1;
				$crseId = $up_crsid_cursado;
				$crseId = $up_crsid_final if ($crseId eq '');
				$hashMaterias->{$crseId}->{LISTADO} = [];
				$hashMaterias->{$crseId}->{DESCRIPCION} = $descr;
				$hashMaterias->{$crseId}->{SOLOCURSADO} = $up_solo_cursado;
				$hashMaterias->{$crseId}->{SOLOFINAL}   = $up_solo_final;
				$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = ($hashInscripciones->{$crseId}) ? 1 : 0;
				$hashMaterias->{$crseId}->{ESTADO} = '';
				$hashMaterias->{$crseId}->{NOTA}   = '';
				$hashMaterias->{$crseId}->{WARNING} = 0;
				$hashMaterias->{$crseId}->{LISTCULTURALORELECTIVA} = 0;
				$hashMaterias->{$crseId}->{CRSEID} = $crseId;
				
				$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
				$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
				$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

				push @{$hashAnioCuatri->{$anio}->{$cuatrimestre}}, $crseId;
			}

			if ($parenthesis eq '('){
				$punteroParenthesis = $crseId;
			}elsif($parenthesis eq ')'){		
				$punteroParenthesis = '';
			}

			next if ($hashMaterias->{$crseId} && $hashMaterias->{$crseId}->{ESTADO} && $hashMaterias->{$crseId}->{ESTADO} eq 'FA');

			if ($hashCalificacionesCursada->{$up_crsid_cursado} && !$hashCalificacionesFinal->{$up_crsid_final}){
				$hashMaterias->{$crseId}->{DESCRIPCION} = $descr;
				$hashMaterias->{$crseId}->{CRSEID}      = $up_crsid_cursado;
				$hashMaterias->{$crseId}->{ESTADO}      = 'CA';
				$hashMaterias->{$crseId}->{NOTA}        = $hashCalificacionesCursada->{$up_crsid_cursado}->{crse_grade_off} || '';
				$hashMaterias->{$crseId}->{WARNING}     = $hashCalificacionesCursada->{$up_crsid_cursado}->{warning};
				$hashMaterias->{$crseId}->{SOLOCURSADO} = $hashCalificacionesCursada->{$up_crsid_cursado}->{up_solo_cursado} || '';
				$hashMaterias->{$crseId}->{SOLOFINAL}   = $hashCalificacionesCursada->{$up_crsid_cursado}->{up_solo_final}   || '';
				$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = $hashCalificacionesCursada->{$up_crsid_cursado}->{esta_inscripto}   || 0;
				$hashMaterias->{$crseId}->{LISTADO}     = [];

				$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
				$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
				$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

			}elsif ($hashCalificacionesFinal->{$up_crsid_final}){
				$hashMaterias->{$crseId}->{DESCRIPCION} = $descr;
				$hashMaterias->{$crseId}->{CRSEID}      = $up_crsid_cursado || $up_crsid_final;
				$hashMaterias->{$crseId}->{ESTADO}      = 'FA';
				$hashMaterias->{$crseId}->{NOTA}        = $hashCalificacionesFinal->{$up_crsid_final}->{crse_grade_off}  || '';
				$hashMaterias->{$crseId}->{SOLOCURSADO} = $hashCalificacionesFinal->{$up_crsid_final}->{up_solo_cursado} || '';
				$hashMaterias->{$crseId}->{SOLOFINAL}   = $hashCalificacionesFinal->{$up_crsid_final}->{up_solo_final}   || '';
				$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = 0;
				$hashMaterias->{$crseId}->{LISTADO}     = [];
				
				$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
				$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
				$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

			}else{
				#$self->logDebug("crseId $crseId - up_crsid_cursado $up_crsid_cursado - up_crsid_final $up_crsid_final\n");
				if (! $hashMaterias->{$crseId}->{ESTAINSCRIPTO}){
					if ($hashInscripciones->{$up_crsid_cursado}){
						$hashMaterias->{$crseId}->{ESTAINSCRIPTO} = 1;
						$hashMaterias->{$crseId}->{CRSEID}      = $up_crsid_cursado;
						$hashMaterias->{$crseId}->{DESCRIPCION} = $descr;
						$hashMaterias->{$crseId}->{SOLOCURSADO} = $up_solo_cursado;
						$hashMaterias->{$crseId}->{SOLOFINAL}   = $up_solo_final;

						$hashMaterias->{$crseId}->{CODCUR}   = $up_crsid_cursado;
						$hashMaterias->{$crseId}->{CODFIN}   = $up_crsid_final;
						$hashMaterias->{$crseId}->{TIPOASIG} = $tipoAsig;

					}
				}
				next if ($hashMaterias->{$crseId} && $hashMaterias->{$crseId}->{ESTADO} && $hashMaterias->{$crseId}->{ESTADO} ne '');
				push @{$hashMaterias->{$crseId}->{LISTADO}}, $res if (!$firstElement);
			}

		}
	}

	my $hashRequerimentMaterias = $self->getRequerimentMaterias($hashRequeriment,$hashCalificacionesCursada,$hashCalificacionesFinal,$hashMaterias,$hashCicloActual);

	my $htmlMaterias = '';
	for (my $i=1; $i<=$maxAnio; $i++){
		my ($primerCuatrimestre,$segundoCuatrimestre) = ('','');
		($primerCuatrimestre,$hashRequerimentUsados)  = @{$self->getCuatrimestre($hashAnioCuatri->{$i}->{1},$hashMaterias,$hashRequerimentUsados,$hashRequerimentMaterias)};
		($segundoCuatrimestre,$hashRequerimentUsados) = @{$self->getCuatrimestre($hashAnioCuatri->{$i}->{2},$hashMaterias,$hashRequerimentUsados,$hashRequerimentMaterias)};


		my $style = ($i == 1) ?  "style=\"margin-top: 20px;\"" : '';

		my $contCuatri1 = ($hashAnioCuatri->{$i} && $hashAnioCuatri->{$i}->{1}) ? scalar(@{$hashAnioCuatri->{$i}->{1}}) : 0;
		my $contCuatri2 = ($hashAnioCuatri->{$i} && $hashAnioCuatri->{$i}->{2}) ? scalar(@{$hashAnioCuatri->{$i}->{2}}) : 0;

		my $styleAnio    = '';
		my $styleCuatri1 = ($contCuatri1 > 5) ? "style=\"height: 100px; line-height: 100px;\""  : '';
		my $styleCuatri2 = ($contCuatri2 > 5) ? "style=\"height: 100px; line-height: 100px;\""  : '';

		if ($contCuatri1 > 5 && $contCuatri2 > 5){
			$styleAnio .= "style=\"line-height:206px;\"";
		}elsif(($contCuatri1 > 5 && $contCuatri2 <= 5) || ($contCuatri1 <= 5 && $contCuatri2 > 5)){
			$styleAnio .= "style=\"line-height:181px;\"";
		}

		

		$htmlMaterias .=<<STR;
			<div class="anio">
				<h1 $styleAnio>$i</h1>
				<div class="cuatri1">
					<h2 $styleCuatri1>1</h2>
					$primerCuatrimestre	
				</div>

				<div class="cuatri2">
					<h2 $styleCuatri2>2</h2>
					$segundoCuatrimestre
				</div>
			</div>
STR
	}

	my $htmlIngles = '';
	$htmlIngles .= $hashIngles->{I}->{HTML} if ($hashIngles->{I} && $hashIngles->{I}->{HTML});
	$htmlIngles .= $hashIngles->{II}->{HTML} if ($hashIngles->{II} && $hashIngles->{II}->{HTML});
	$htmlIngles .= $hashIngles->{III}->{HTML} if ($hashIngles->{III} && $hashIngles->{III}->{HTML});
	$htmlIngles .= $hashIngles->{IV}->{HTML} if ($hashIngles->{IV} && $hashIngles->{IV}->{HTML});
	$htmlIngles .= $hashIngles->{V}->{HTML} if ($hashIngles->{V} && $hashIngles->{V}->{HTML});
	$htmlIngles .= $hashIngles->{VI}->{HTML} if ($hashIngles->{VI} && $hashIngles->{VI}->{HTML});
	$htmlIngles .= $hashIngles->{VII}->{HTML} if ($hashIngles->{VII} && $hashIngles->{VII}->{HTML});

	if ($htmlIngles ne ''){
		$htmlIngles =<<STR;
			<div id="ingles"><h6>Ingl&eacute;s</h6>
				$htmlIngles
			</div>
STR
	}

	if ($htmlComputacionAplicada ne ''){
		$htmlComputacionAplicada =<<STR;
			<div id="otras_materias"><h6>Computaci&oacute;n aplicada</h6>
				$htmlComputacionAplicada
			</div>
STR
	}

	if ($htmlOtros ne ''){
		$htmlOtros =<<STR;
			<div id="otras_materias"><h6>Otras</h6>
				$htmlOtros
			</div>
STR
	}

	

	return [$htmlMaterias,$htmlIngles,$htmlComputacionAplicada,$htmlOtros];
}

#----------------------------------------------------------------

sub isVencidaAsignatura {
	my $self            = shift;
	my $strmVenc        = shift || return 0;
	my $hashCicloActual = shift || {};

	my $ret = 0;
	foreach my $strmActual (keys %{$hashCicloActual}){
		return 0 if ($strmActual < $strmVenc);
		$ret = 1;
	}

	return $ret;
}

#----------------------------------------------------------------

sub getRequerimentMaterias {
	my $self                      = shift;
	my $hashRequeriment           = shift || {};
	my $hashCalificacionesCursada = shift || {};
	my $hashCalificacionesFinal   = shift || {};
	my $hashMaterias              = shift || {};
	my $hashCicloActual           = shift || {};

	my $whereRequeriment = '';
	my $hashRequerimentMaterias = {
 		CULTURAL => {},
		ELECTIVA => {}
   };

	foreach (keys %{$hashRequeriment}){
		my $requirement = $_;
		$whereRequeriment .= ',' if ($whereRequeriment ne '');
		$whereRequeriment .= "'$requirement'";
	}

	if ($whereRequeriment ne ''){
		my $sql =<<STR;
			SELECT a.crse_id  as crse_id_cursada, cf.crse_id, a.descr, r.requirement 
			FROM 
				ps_up_asig_req_vw r INNER JOIN ps_up_asi_cu_fi_vw cf ON r.crse_id=cf.crse_id_srch 
				INNER JOIN ps_up_asignatur_vw a ON r.crse_id = a.crse_id
			WHERE
				r.requirement in ($whereRequeriment)
            AND (r.crse_id like '02%' OR r.crse_id like '05%')
			ORDER BY r.requirement,a.crse_id
STR

		#$self->logDebug($sql);

		my $sth = $self->{DBHPGW}->prepare($sql);
		$sth->execute();
		 
		while ( my $res  = $sth->fetchrow_hashref()) {
			my $descr            = $res->{'descr'}           || '';
			my $up_crsid_cursado = $res->{'crse_id_cursada'} || '';
			my $up_crsid_final   = $res->{'crse_id'}         || '';
			my $requirement      = $res->{'requirement'}     || '';

			my $tipo = '';
			if ($requirement =~ m/^8/){
				$tipo = 'CULTURAL';
			}elsif ($requirement =~ m/^9/){
				$tipo = 'ELECTIVA';
			}else{
				$tipo = 'ELECTIVA';
			}

			if ($hashMaterias->{$up_crsid_cursado} || $hashMaterias->{$up_crsid_final}){
				next;
			}

			my $hash = {
				descr            => $descr,
				up_crsid_cursado => $up_crsid_cursado,
				up_crsid_final   => $up_crsid_final,
				requirement      => $requirement				
         };

			if	(!$hashRequerimentMaterias->{$tipo}->{$requirement}){
				$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADOCA} = [];
				$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADOFA} = [];
				$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADO} = [];
			}

			if ($hashCalificacionesCursada->{$up_crsid_cursado} && !$hashCalificacionesFinal->{$up_crsid_final}){
				next if ($hashCalificacionesCursada->{$up_crsid_cursado}->{up_seleccion_asig} eq 'C' && $tipo eq 'ELECTIVA');
				next if ($hashCalificacionesCursada->{$up_crsid_cursado}->{up_seleccion_asig} eq 'E' && $tipo eq 'CULTURAL');
				$hash->{NOTA}    = $hashCalificacionesCursada->{$up_crsid_cursado}->{crse_grade_off} || '';
				$hash->{WARNING} = $hashCalificacionesCursada->{$up_crsid_cursado}->{warning};
				$hash->{ESTADO}  = 'CA';
				$hash->{LISTADO} = [];
				push @{$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADOCA}}, $hash;
			}elsif ($hashCalificacionesFinal->{$up_crsid_final}){
				next if ($hashCalificacionesFinal->{$up_crsid_final}->{up_seleccion_asig} eq 'C' && $tipo eq 'ELECTIVA');
				next if ($hashCalificacionesFinal->{$up_crsid_final}->{up_seleccion_asig} eq 'E' && $tipo eq 'CULTURAL');
				$hash->{NOTA}    = $hashCalificacionesFinal->{$up_crsid_final}->{crse_grade_off} || '';
				$hash->{WARNING} = 0;
				$hash->{ESTADO}  = 'FA';
				$hash->{LISTADO} = [];
				push @{$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADOFA}}, $hash;
			}else{
				push @{$hashRequerimentMaterias->{$tipo}->{$requirement}->{LISTADO}}, $hash;
			}
		}
	}

	return $hashRequerimentMaterias;
}

#----------------------------------------------------------------

sub getCuatrimestre {
	my $self                    = shift;
	my $arrayCuatrimestre       = shift || [];
	my $hashMaterias            = shift || {};
	my $hashRequerimentUsados   = shift || {};
	my $hashRequerimentMaterias = shift || {};

	my $cantAsignaturas = scalar(@{$arrayCuatrimestre});
	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}

	my $cont = 0;
	my $cuatrimestre = '';
	foreach(@{$arrayCuatrimestre}){
		next if ($cont >= 5);
		my $posAux = $cont+5;
		if ($posAux <= ($cantAsignaturas-1)){
			my $classCuadro = 'cuadroch';
			my $crseIdOrRequirement = $_;
			if ($hashMaterias->{$crseIdOrRequirement}->{LISTCULTURALORELECTIVA}){
				my $tipo = $hashMaterias->{$crseIdOrRequirement}->{DESCRIPCION};
				$hashMaterias->{$crseIdOrRequirement}->{LISTADO} = $hashRequerimentMaterias->{$tipo}->{$crseIdOrRequirement}->{LISTADO};
			}
			my $cuadroChico1 = $self->getCuadro($hashMaterias->{$crseIdOrRequirement},$acadPlan,$classCuadro);

			$crseIdOrRequirement = $arrayCuatrimestre->[$posAux];
			if ($hashMaterias->{$crseIdOrRequirement}->{LISTCULTURALORELECTIVA}){
				my $tipo = $hashMaterias->{$crseIdOrRequirement}->{DESCRIPCION};
				$hashMaterias->{$crseIdOrRequirement}->{LISTADO} = $hashRequerimentMaterias->{$tipo}->{$crseIdOrRequirement}->{LISTADO};
			}
			my $cuadroChico2 = $self->getCuadro($hashMaterias->{$crseIdOrRequirement},$acadPlan,$classCuadro);

			$cuatrimestre .=<<STR;
				<div class="mediajornada">
					$cuadroChico1
					$cuadroChico2
				</div>
STR
		}else{
			my $classCuadro = ($cantAsignaturas > 5) ? 'cuadrogde' : 'cuadro';
			my $crseIdOrRequirement = $_;
			if ($hashMaterias->{$crseIdOrRequirement}->{LISTCULTURALORELECTIVA}){
				my $tipo = $hashMaterias->{$crseIdOrRequirement}->{DESCRIPCION};
				$hashMaterias->{$crseIdOrRequirement}->{LISTADO} = $hashRequerimentMaterias->{$tipo}->{$crseIdOrRequirement}->{LISTADO};
			}
			$cuatrimestre .= $self->getCuadro($hashMaterias->{$crseIdOrRequirement},$acadPlan,$classCuadro);
		}
		$cont++;
	}

	for (my $i = $cont; $i<5;$i++){
		$cuatrimestre .=<<STR;
			<div class="cuadro"></div>
STR
	}

	return [$cuatrimestre,$hashRequerimentUsados];
}

#----------------------------------------------------------------

sub getDetalleAsignaturaAvance {
	my $self = shift;

	my $crseId = $self->{REQUEST}->param('crse_id') || '';  
	if($crseId eq '' || $crseId !~ m/^[0-9]+$/){
		$self->loguearFile("Error: El crseId $crseId vino vacio o es incorrecto. Metodo: getDetalleAsignaturaAvance");	
		return ['','','','','','','El crseId vino vacio o es incorrecto'];		
	}

	my $acadPlan = $self->{REQUEST}->param('acad_plan') || '';	
	if (!$self->validarAcadPlan($acadPlan)){
		$self->loguearFile("Error:El Plan vino vacio o es incorrecto. Metodo: getDetalleAsignaturaAvance");
		return ['','','','','','','El Plan vino vacio o es incorrecto'];
	}
   
	if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }
	
   my $legajo   = $self->{SESION}->{LEGAJO} || '';
	if ($legajo !~ m/^[0-9]+$/){
		$self->loguearFile("Legajo vacio o incorrecto. Metodo: getDetalleAsignaturaAvance");	
		return ['','','','','','','Legajo vacio o incorrecto'];
	}

	my $requeriment = $self->{REQUEST}->param('tipo') || '';
	if ($requeriment ne '' && $requeriment !~ m/^[0-9]+$/){
		$self->loguearFile("Requirement. Metodo: getDetalleAsignaturaAvance");
		return ['','','','','','','El requeriment es incorrecto.'];
	}
	
	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);
	my $crseIdBD   = $self->{DBHPGW}->quote($crseId);


	my $sql2 =<<STR;
		SELECT a.descrlong as nombre, a.up_solo_cursado, a.up_solo_final, cf.crse_id_srch, a.equiv_crse_id, a2.equiv_crse_id as equiv_crse_id_srch
		FROM ps_up_asignatur_vw a
         LEFT JOIN  ps_up_asi_cu_fi_vw cf ON a.crse_id = cf.crse_id
		   LEFT JOIN ps_up_asignatur_vw a2 ON a2.crse_id  = cf.crse_id_srch
		WHERE a.crse_id like $crseIdBD
		LIMIT 1;	
STR

	#print "content-type:text/html\n\n";
   #print $sql2;exit;

	my $sth2 = $self->{DBHPGW}->prepare($sql2);
   $sth2->execute();

	if ($sth2->errstr){
		my $error = $sth2->errstr;
      $self->loguearFile("Error sql. Consulta = $sql2 , Metodo = getDetalleAsignaturaAvance, Error= $error");
      return ['','','','','','','Error sql'];
   }

   if ($sth2->rows() <= 0){
		$self->loguearFile("No se encontro el crseId.");
		return ['','','','','','','No se encontro la asignatura.'];
	}

	my $nombre = '';		

	my $up_solo_cursado  = '';
	my $up_solo_final    = '';
	my $crseIdSrch       = '';
	my $equivalencia     = '';
	my $equivalenciaSrch = '';
	while ( my $res2 = $sth2->fetchrow_hashref()) {
		$nombre           = $res2->{nombre}             || 'sin nombre';
		$up_solo_cursado  = $res2->{up_solo_cursado}    || '';
		$up_solo_final    = $res2->{up_solo_final}      || '';
		$crseIdSrch       = $res2->{crse_id_srch}       || '';
		$equivalencia     = $res2->{equiv_crse_id}      || ''; 
		$equivalenciaSrch = $res2->{equiv_crse_id_srch} || '';
	}	
	my $strmParam = '';
	my $crseIdParam = '';
	my $equivalenciaParam = '';
	if ($crseId =~ /^05/){
		if ($crseIdSrch eq ''){
			$crseIdParam = $crseId;
			$equivalenciaParam = $equivalencia;
		}else{
			$crseIdParam = $crseIdSrch;
			$equivalenciaParam = $equivalenciaSrch;
		}
	}else{
		$crseIdParam = $crseId;
		$equivalenciaParam = $equivalencia;
	}
	
	
	my $sql =<<STR;
		(
       SELECT max(strm) as strm,'F' as tipo FROM ps_up_cic_ac_fi_vw GROUP BY tipo
		 UNION
		 SELECT max(strm) as strm, 'C' as tipo FROM ps_up_cic_ac_cu_vw GROUP BY tipo
      )
STR

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hashCicloActualFi = {};
	my $hashCicloActualCu = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $strm = $res->{'strm'} || next;
		my $tipo = $res->{'tipo'} || next;
		if ($tipo eq 'F'){
			$hashCicloActualFi->{$strm} = 1;
		}else{
			$hashCicloActualCu->{$strm} = 1;
		}
	}

	my $crseIdInBD  = $crseIdBD; 
	if ($up_solo_cursado ne 'Y' && $up_solo_final ne 'Y' && $crseIdSrch ne ''){
		my $crseIdSrchBD = $self->{DBHPGW}->quote($crseIdSrch);
		$crseIdInBD .= ", $crseIdSrchBD"; 
	}
	
	
	$sql =<<STR;
		SELECT   
				distinct c.strm, c.crse_id, c.up_crse_id_cursado, c.crse_grade_off as nota, c.end_dt as fecha_nota, c.up_aprobado as aprobado, c.up_strm_vto_or, 
            c.up_strm_vto_prga, a.descrlong, cf.crse_id as crse_id_srch, c.up_equiv_ext_pb
		FROM ps_up_plan_alumnos as p INNER JOIN ps_up_his_calif_vw as c ON p.acad_plan = c.acad_plan AND p.emplid = c.emplid AND  p.up_estado_plan in ('A','B','E','I')
           LEFT JOIN ps_up_asignatur_vw a ON c.crse_id = a.crse_id
           LEFT JOIN ps_up_asi_cu_fi_vw cf ON cf.crse_id_srch = a.crse_id
		WHERE
				p.emplid    = $legajoBD    AND
				p.acad_plan like $acadPlanBD  AND
				c.crse_id   in ($crseIdInBD)
		Order By  up_crse_id_cursado ASC, c.end_dt DESC, c.crse_grade_off DESC 
STR

	#print "content-type:text/html\n\n";print $sql;exit;

	$sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	if ($sth->errstr){
		my $error = $sth->errstr;
      $self->loguearFile("Error sql. Consulta = $sql , Metodo = getDetalleAsignaturaAvance, Error= $error");
      return ['','','','','','','Error sql'];
   }

	my $notasCursada = '';
	my $notasFinal   = '';
	
	my $hashCalificacionesFinal = {};
	
	while ( my $res  = $sth->fetchrow_hashref()) {
		my $cursada  = $res->{up_crse_id_cursado} || next;
		my $crseId   = $res->{crse_id} || next;
		my $fecha    = $res->{fecha_nota} || 'Sin fecha disponible';
		my $aprobado = $res->{aprobado}   || ''; 
		my $nota     = $res->{nota} || '';
		my $strm     = $res->{strm} || '';
		my $up_equiv_ext_pb = $res->{up_equiv_ext_pb} || '';
		$aprobado = 'Y' if ($up_equiv_ext_pb eq 'E');

		if ($cursada eq 'Y'){
			if ($aprobado eq 'Y'){
				$aprobado =  "Aprobado";
				$aprobado =  "Equivalencia" if($up_equiv_ext_pb eq 'E');
				my $up_strm_vto_or     = $res->{up_strm_vto_or}     || ''; 
				my $up_strm_vto_prga   = $res->{up_strm_vto_prga}   || '';
				my $crse_id_srch       = $res->{crse_id_srch}       || '';
				my ($warning,$isVencida) = (0,0);
				if (!$hashCalificacionesFinal->{$crse_id_srch}){
					my	$strmWarning = $up_strm_vto_prga || '';
					$strmWarning = $up_strm_vto_or if ($up_strm_vto_prga eq '');
					$warning = ($hashCicloActualFi->{$strmWarning}) ? 1 : 0;
					$isVencida = $self->isVencidaAsignatura($strmWarning,$hashCicloActualFi) if (!$warning);
				}
				if (!$isVencida){
					$aprobado = ($warning) ? "Aprobado a punto de vencer" : "Aprobado";
					$aprobado =  "Equivalencia" if($up_equiv_ext_pb eq 'E');
					$strmParam = $strm;
				}else{
					$aprobado =  "Vencido";
				}
			}elsif($nota =~ m/^[0-9]+$/ && $nota >= 4){
				$aprobado = "Vencido";
			}elsif($nota eq '' && $hashCicloActualCu->{$strm}){
				$aprobado = "En curso";
			}else{
				my $aInformar = 0;
      		if ($fecha =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
					my $anio = $1;
					my $dt = DateTime->now();
					my $anioActual = $dt->year();
					$aInformar = 1 if ($anio >= $anioActual);
				}
				if ($aInformar){
					$aprobado = "A informar";
				}else{
					$aprobado = "Desaprobado";
				}
			}

			$nota = " | Nota: $nota " if ($nota ne '');
			$nota = '' if($up_equiv_ext_pb eq 'E');

			my $fechaNota = '';
			$fechaNota = " | Fecha: $fecha " if($fecha ne '');			

			$notasCursada .= <<STR;
				<div class="filaNota" >
					<strong>$aprobado</strong>
					$nota 
      			$fechaNota
				</div>
STR

		}elsif ($cursada eq 'N'){
			$hashCalificacionesFinal->{$crseId} = 1;
			if($nota eq '' && $hashCicloActualFi->{$strm}){
				$aprobado = "En curso";
			}elsif($aprobado eq 'Y'){
				$aprobado = "Aprobado";
				$aprobado =  "Equivalencia" if($up_equiv_ext_pb eq 'E');
				$strmParam = $strm;
			}else{
				my $aInformar = 0;
      		if ($fecha =~ /([0-9]+)-([0-9]+)-([0-9]+)/){
					my $anio = $1;
					my $dt = DateTime->now();
					my $anioActual = $dt->year();
					$aInformar = 1 if ($anio >= $anioActual);
				}
				if ($aInformar){
					$aprobado = "A informar";
				}else{
					$aprobado = "Desaprobado";
				}
			}
			$nota = " | Nota: $nota " if ($nota ne '');
			$nota = '' if($up_equiv_ext_pb eq 'E');
			
			my $fechaNota = '';
			$fechaNota = " | Fecha: $fecha " if($fecha ne '');

			$notasFinal .= <<STR;
				<div class="filaNota" >
					<strong>$aprobado</strong>
					$nota 
      			$fechaNota
				</div>
STR
		}
	}
	
	$notasCursada = '<div class="filaNota" >Sin notas de cursada</div>' if($notasCursada eq '');
	$notasFinal   = '<div class="filaNota" >Sin notas de final</div>' if($notasFinal eq ''); 
	
	#my ($dependientes,$requeridas,$errorDepReq) = @{$self->getDepReq($crseIdInBD,$legajoBD,$acadPlanBD,$nombre,$requeriment)};

	my ($status,$msj) = @{$self->getDependientes($crseIdInBD,$legajoBD,$acadPlanBD,$requeriment)};
   return ['','','','','','',$msj] if ($status ne 'OK');
	my $dependientes = ($msj eq '') ? '<p>No posee asignaturas dependientes</p>' : $msj;

	($status,$msj) = @{$self->getRequeridas($crseIdInBD,$legajoBD,$acadPlanBD,$requeriment)};
   return ['','','','','','',$msj] if ($status ne 'OK');
	my $requeridas = ($msj eq '') ? '<p>No posee asignaturas requeridas</p>' : $msj;

	$self->{DBHINSCRIPCION} = UP::Connect2_DB::getDBHinscripcion();

	my $descripcion = $self->getContenidoMinimoAndDescr($equivalenciaParam,$crseIdParam,$strmParam);
	$descripcion = 'Descripci&oacute;n no disponible' if ($descripcion eq '');
	$descripcion = $descripcion;

	return [$nombre,$notasFinal,$notasCursada,$descripcion,$requeridas,$dependientes,''];
}

#----------------------------------------------------------------

sub getRequeridas {
	my $self        = shift;
	my $crseIdIn    = shift || return ['ERROR','No se pudo consultar las asignaturas requeridas. C&oacute;digo de la materia incorrecto.'];
   my $legajoBD    = shift || return ['ERROR','No se pudo consultar las asignaturas requeridas. El legajo es incorrecto.'];
	my $acadPlanBD  = shift || return ['ERROR','No se pudo consultar las asignatuars requeridas. El plan de estudio es incorrecto.'];
   my $requeriment = shift || '';


	my $from  = '';
	my $where = '';
	if ($requeriment ne ''){
     	$requeriment = $self->{DBHPGW}->quote($requeriment);
		$from = ', ps_up_asig_req_vw r';	
		$where =<<STR;
				r.crse_id      = c.rqrmnt_group AND
				r.requirement  = p.requirement  AND
				r.requirement  = $requeriment   AND
STR
	}else{
		my $modulo = $self->{REQUEST}->param('modulo') || '';
		if ($modulo ne 'I'){
			$where =<<STR;
				(p.up_crsid_cursado = c.rqrmnt_group OR p.up_crsid_final = c.rqrmnt_group) AND
STR
		}
	}
	
	my $sql =<<STR;
		SELECT distinct c.rq_connect_type,c.rqrmnt_group, c.crse_id,  c.rq_grp_line_nbr, c.rq_connect as conector, c.parenthesis, a.descrlong
		FROM  
			ps_up_asignatur_vw a, ps_up_corr_asig_vw c, ps_up_asign_planes p 
			$from
		WHERE
			$where 
	--		c.crse_id      NOT IN ($crseIdIn) AND
			c.rqrmnt_group IN ($crseIdIn)     AND
			p.acad_plan    like $acadPlanBD      AND
			c.crse_id      = a.crse_id
		ORDER BY c.rqrmnt_group,  c.rq_grp_line_nbr

STR

	#$self->logDebug($sql);
	
	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	if ($sth->errstr){
		my $error = $sth->errstr;
      $self->loguearFile("Error de sql. Consulta = $sql , Metodo = getRequeridas, Error= $error");
      return ['ERROR','No se pudo consultar las asignaturas requeridas.'];
   }

	my $arrayAuxCursada;
	my @arrayCursada;
	my $isParenthesisCursada = 0;

	my $arrayAuxFinal;
	my @arrayFinal;
	my $isParenthesisFinal = 0;
	
	my $rqConnectType = '';
	while ( my $res  = $sth->fetchrow_hashref()) {
		my $desc          = $res->{descrlong}       || next;
		my $rqrmnt_group  = $res->{rqrmnt_group}    || next;
		my $parenthesis   = $res->{parenthesis}     || '';
		my $conector      = $res->{conector}        || '';
		if ($rqConnectType eq ''){
			$rqConnectType = $res->{rq_connect_type} || '';
			$rqConnectType = ($rqConnectType eq 'AND') ? "y" : "o";
		}

	
		#$self->logDebug("$desc -------$parenthesis----------$conector");

		if ($rqrmnt_group =~ m/^02/){
			push @{$arrayAuxCursada}, $desc;	
			if ($parenthesis eq '('){
				$isParenthesisCursada = 1;
			}elsif($parenthesis eq ')'){
				$isParenthesisCursada = 0;
				push @arrayCursada, $arrayAuxCursada;
				$arrayAuxCursada = [];
			}else{
				if (!$isParenthesisCursada){
					push @arrayCursada, $arrayAuxCursada;
					$arrayAuxCursada = [];
				}
			}
		}elsif($rqrmnt_group =~ m/^05/){
			push @{$arrayAuxFinal}, $desc;	
			if ($parenthesis eq '('){
				$isParenthesisFinal = 1;
			}elsif($parenthesis eq ')'){
				$isParenthesisFinal = 0;
				push @arrayFinal, $arrayAuxFinal;
				$arrayAuxFinal = [];
			}else{
				if (!$isParenthesisFinal){
					push @arrayFinal, $arrayAuxFinal;
					$arrayAuxFinal = [];
				}
			}
		}else{
			$self->enviarErrorEmail("Error el $rqrmnt_group no coincide con ^02 (Cursada)  o ^05 (Final)");
		}
	}



	my $cursada = '';
	my $contCur = 0;
	foreach (@arrayCursada){
		$contCur++;		
		my $array = $_;
		my $desc = $array->[0];

		my $hashAux= {};
		$hashAux->{$desc} = 1;
		
		my $classClick = '';

		my $cant = scalar(@{$array});
		my $listado = '';
		if ($cant > 1){
			$listado .= "<li><span class=\"span_requeridas\">".$desc."</span></li>";

			for (my $i=1; $i<$cant; $i++){
				my $descAux = $array->[$i];
				if (!$hashAux->{$descAux}){
					$hashAux->{$descAux} = 1;
					$listado .= "<li><span class=\"span_requeridas\">".$descAux."</span></li>";
				}
			}

			if ($listado ne ''){
				my $strLeyenda = ($rqConnectType eq 'o') ? "(*) Requiere de todas las asignaturas del listado" : "(*) Requiere una asignatura del listado";
				$classClick = " class=\"requeridas_link\" onClick=\"mostrarVentanaRequeridas('requeridasCur$contCur');\"";
				$listado =<<STR;
					<div class="img_requeridas">
						<div class="ventanaimgrequeridas"><img src="/Intranet/my-up/img/edit.png" onClick="mostrarVentanaRequeridas('requeridasCur$contCur');"></div>
						<div class="listado_requeridas" id="requeridasCur$contCur">

							<div onclick="cerrarVentanaReq('requeridasCur$contCur')" style="position: absolute; top: 10px; right: 1px; cursor: pointer;">
								<img src="/Intranet/my-up/img/cerrar.png">
							</div>

							<h4 class="pop_up_confirmacion_nom_borde">Asignaturas requeridas: Cursadas</h4>			

							<ul class="ul_requeridas">
								$listado
							</ul>
				
							<div class="aviso_requeridas" >
									<p>$strLeyenda</p>
							</div>

						</div>
					</div>
STR
			}
		}

		my $strConector = ($contCur > 1) ? "($rqConnectType) " : '';	
		$cursada .= <<STR;
				<div class="item_requeridas">
					<div class="descr_requeridas">
						<p>$strConector<span  $classClick >$desc</span></p>
					</div>
					$listado
				</div>
STR
	}			

	$cursada = "<div class=\"item_requeridas_cursadas\" ><h4>Cursada</h4><div class=\"item_requeridas_contenedor\">$cursada</div></div>" if ($cursada ne '');

	my $final = '';
	my $contFin = 0;

	foreach (@arrayFinal){
		my $array = $_;
		$contFin++;

		my $desc = $array->[0];
		
		my $hashAux= {};
		$hashAux->{$desc} = 1;
		
		my $classClick = '';

		my $cant = scalar(@{$array});
		my $listado = '';
		if ($cant > 1){

			$listado .= "<li><span class=\"span_requeridas\">".$desc."</span></li>";

			for (my $i=1; $i<$cant; $i++){
				my $descAux = $array->[$i];
				if (!$hashAux->{$descAux}){
					$hashAux->{$descAux} = 1;
					$listado .= "<li><span class=\"span_requeridas\">".$descAux."</span></li>";
				}
			}
			if ($listado ne ''){
				my $strLeyenda = ($rqConnectType eq 'o') ? "(*) Requiere de todas las asignaturas del listado" : "(*) Requiere una asignatura del listado";
				$classClick = " class=\"requeridas_link\" onClick=\"mostrarVentanaRequeridas('requeridasFin$contFin');\"";
				$listado =<<STR;
					<div class="img_requeridas">
						<div class="ventanaimgrequeridas"><img src="/Intranet/my-up/img/edit.png" onClick="mostrarVentanaRequeridas('requeridasFin$contFin');"></div>
						<div class="listado_requeridas" id="requeridasFin$contFin">


							<div onclick="cerrarVentanaReq('requeridasFin$contFin')" style="position: absolute; top: 10px; right: 1px; cursor: pointer;">
								<img src="/Intranet/my-up/img/cerrar.png">
							</div>

							<h4 class="pop_up_confirmacion_nom_borde">Asignaturas requeridas: Finales</h4>			

							<ul class="ul_requeridas">
								$listado
							</ul>
							<div class="aviso_requeridas" >
									<p>$strLeyenda</p>
							</div>

						</div>
					</div>
STR
			}
		}
	
		my $strConector = ($contFin > 1) ? "($rqConnectType) " : '';	
		$final .= <<STR;
				<div class="item_requeridas">
					<div class="descr_requeridas">
						<p>$strConector<span  $classClick >$desc</span></p>
					</div>
					$listado
				</div>
STR
	}

	$final = "<div class=\"item_requeridas_final\"><h4>Final</h4><div class=\"item_requeridas_contenedor\">$final</div></div>" if ($final ne '');
	
	my $ret = '';
	if ($cursada ne '' || $final ne ''){
		$ret =<<STR;
			<div class="contenedor_requeridas">
				$cursada
				$final
			</div>
STR
	}
	return ['OK',$ret];

}

#----------------------------------------------------------------

sub getDependientes {
	my $self        = shift;
	my $crseIdIn    = shift || return ['ERROR','No se pudo consultar las asignaturas dependientes. C&oacute;digo de la materia incorrecto.'];
   my $legajoBD    = shift || return ['ERROR','No se pudo consultar las asignaturas dependientes. El legajo es incorrecto.'];
	my $acadPlanBD  = shift || return ['ERROR','No se pudo consultar las asignatuars dependientes. El plan de estudio es incorrecto.'];
   my $requeriment = shift || '';

	my $from  = '';
	my $where = '';
	if ($requeriment ne ''){
     	$requeriment = $self->{DBHPGW}->quote($requeriment);
		$from = ', ps_up_asig_req_vw r';	
		$where =<<STR;
				r.crse_id      = c.crse_id     AND
				r.requirement  = p.requirement AND
				r.requirement  = $requeriment  AND
STR
	}else{
		$where =<<STR;
				(p.up_crsid_cursado = c.crse_id OR p.up_crsid_final   = c.crse_id) AND
STR
	}
	
	my $sql =<<STR;
		SELECT distinct a.descrlong
		FROM  
            ps_up_asignatur_vw a, ps_up_corr_asig_vw c, ps_up_asign_planes p 
            $from
		WHERE 
				$where
				c.crse_id      IN ($crseIdIn)     AND
				c.rqrmnt_group NOT IN ($crseIdIn) AND
				p.acad_plan    like $acadPlanBD      AND
				c.rqrmnt_group = a.crse_id
		ORDER BY descrlong 
STR

	#$self->logDebug($sql);
	
	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	if ($sth->errstr){
		my $error = $sth->errstr;
      $self->loguearFile("Error de sql. Consulta = $sql , Metodo = getDependientes, Error= $error");
      return ['ERROR','No se pudo consultar las asignaturas dependientes.'];
   }

	my $hashMateriasPlan = $self->getMateriasByPlan();

	my $ret = '';
	while ( my $res  = $sth->fetchrow_hashref()) {
		my $desc = $res->{descrlong} || next;
		next if (!$hashMateriasPlan->{$desc});
		$ret .= <<STR;
			<div class="dependiente_item">
				<p>
					$desc
				</p>
			</div>
STR

	}

	return ['OK',$ret];

}

#----------------------------------------------------------------

sub getCuadro {
   my $self        = shift;
	my $hash        = shift || return '';
	my $acadPlan    = shift || return '';
	my $classCuadro = shift || 'cuadro';

	my $cuadro = '';
	my $descripcion   = $hash->{DESCRIPCION} || '';

	if ($hash->{LISTCULTURALORELECTIVA}){
		my $listado     = $hash->{LISTADO} || [];
		my $htmlListado = '';
		my $hrefListado = '';

		foreach (@{$listado}){
			my $res = $_;
			my $descr = $res->{'descr'}            || '';

			my $crseId = '';
			my $isCurs = '';
			$crseId = $res->{'up_crsid_cursado'};
			$isCurs = ($crseId !~ m/^05/) ? 'Y' : 'N';

			my $req = $res->{'requirement'};

			my $tipoAsig = '';
			
			$tipoAsig = 'obligatoria' if($req eq '');
			$tipoAsig = 'electiva' if($req =~ m/^9/);
			$tipoAsig = 'cultural' if($req =~ m/^8/);			
			
			$htmlListado .= "<li><div class=\"ellipsis\" style=\"margin: 5px;\"><a style=\"float: unset;\" class=\"clickAvance\" href=\"/cgi-bin/myup/avanceCarreraPopUp.pl?acad_plan=$acadPlan&crse_id=$crseId&tipo=$req&curFin=$isCurs&tipoAsig=$tipoAsig\">$descr</a></div></li>";

		}

		if ($htmlListado ne ''){
			$htmlListado =<<STR;
				<div class="listadoRequisitos popUpListadoRequisitos">
					<ul style="padding-left: 22px; margin: 0px; position: absolute;">
						$htmlListado
					</ul>
				</div>
STR
			$hrefListado =<<STR;
				<div style="cursor: pointer" class="ventanasimg"><img src="/Intranet/my-up/img/ventanita.png" /></div>
STR
		}

		$cuadro =<<STR;
			<div title="$descripcion" class="$classCuadro"><a href="#" style="cursor: auto;">$descripcion</a>$hrefListado$htmlListado</div>
STR
		
	}else{
		my $crseId      = $hash->{CRSEID}        || '';	
		my $estado      = $hash->{ESTADO}        || '';
		my $nota 		 = $hash->{NOTA}          || '';
		my $warning     = $hash->{WARNING}       || 0;
		my $requirement = $hash->{REQUIREMENT}   || ''; 
		my $soloCursado = $hash->{SOLOCURSADO}   || ''; 
		my $soloFinal   = $hash->{SOLOFINAL}     || ''; 
		my $estaInscr   = $hash->{ESTAINSCRIPTO} || ''; 

		my $codCur      = $hash->{CODCUR}   || '';
		my $codFin      = $hash->{CODFIN}   || '';
		my $tipoAsig    = $hash->{TIPOASIG} || '';

		my $eleCultu = ($requirement ne '') ? "&tipo=".$requirement : '';

		if ($nota ne ''){
			$nota = "E" if($nota eq 'T');
			$nota = "<p class=\"nota\">$nota</p>";
		}

		my $curFin = '';

		my $class = '';
		if ($estado eq 'FA'){
			$class = " verde";
		}elsif($estado eq 'CA'){
			if ($soloCursado eq 'Y'){
				$class = " verde";
			}elsif($estaInscr){
				$class = " celeste";
				$curFin = "N";
			}else{
				$class = ($warning) ? " rosa" : " amarillo";
				$curFin = "N";
			}
		}else{
			if($estaInscr){
				 $class = " celeste";
				$curFin = "N";
			}else{
				$curFin = "Y";
			}
		}

		$descripcion .= " (Final)" if ($soloFinal eq 'Y' && $soloCursado eq '');
		$descripcion .= " (Cursado)" if ($soloCursado eq 'Y' && $soloFinal eq '');													
	
		$cuadro =<<STR;
			<div class="$classCuadro$class" title="$descripcion"><a class="clickAvance" href="/cgi-bin/myup/avanceCarreraPopUp.pl?acad_plan=$acadPlan&crse_id=$crseId$eleCultu&curFin=$curFin&codCur=$codCur&codFin=$codFin&tipoAsig=$tipoAsig">$descripcion</a>$nota</div>
STR

	}

	return $cuadro;
}

#----------------------------------------------------------------

sub getAnioCuatrimestre {
   my $self   	  = shift;
	my $anioCuatr = shift || return [0,0];

	my $anio  = 0;
	my $cuatr = 0;
	$anio = '1' if ($anioCuatr =~ m/^Primer/);
	$anio = '2' if ($anioCuatr =~ m/^Segundo/);
	$anio = '3' if ($anioCuatr =~ m/^Tercer/);
	$anio = '4' if ($anioCuatr =~ m/^Cuarto/);
	$anio = '5' if ($anioCuatr =~ m/^Quinto/);

	$cuatr = '1' if ($anioCuatr =~ m/- Primer Cuatrim/);
	$cuatr = '2' if ($anioCuatr =~ m/- Segundo Cuatrim/);

	return [$anio,$cuatr];

}

#----------------------------------------------------------------

sub getCrseIdElectivaOCulturalNew {
   my $self   	       = shift;
	my $hashUsados     = shift || {};
	my $hashRequerimentFinal = shift || {};
	my $hashRequerimentCurs  = shift || {};

	foreach (keys %{$hashRequerimentFinal}){
		my $crseId = $_;
		return $crseId if (!$hashUsados->{$crseId});
	}

	foreach (keys %{$hashRequerimentCurs}){
		my $crseId = $_;
		return $crseId if (!$hashUsados->{$crseId});
	}

	return '';
}

#----------------------------------------------------------------

sub getAvisoImportante {
   my $self = shift;

	my $legajo = $self->{SESION}->{LEGAJO} || '';
   return '' if ($legajo !~ m/^[0-9]+$/);
   my $legajoBD = $self->{DBHPGW}->quote($legajo);
	
	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
	return '' if (!$self->validarAcadPlan($acadPlan));
	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);

   my $sql=<<SQL;
         SELECT count(*) as cont 
         FROM myup_tramites_pendientes 
         WHERE 
               emplid    like $legajoBD   AND
               acad_plan like $acadPlanBD AND
               tipo = 1
SQL
	
   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	my $res= $sth->fetchrow_hashref;
	my $cont = $res->{cont} || return '';

	return '<p style="margin-right:50px; margin-top: 40px;">Se registran equivalencias pendientes a cargar. Tenga en cuenta que no ver&aacute; reflejado el avance de carrera  hasta que est&eacute;n cargadas.</p>' if ($cont > 0 );
   return 0;
}

#-------------------------------------------------------------

sub getInfoInscripcion {
	my $self = shift;
	
	my $crseId = $self->{REQUEST}->param('crse_id') || '';  
	if($crseId eq '' || $crseId !~ m/^[0-9]+$/){
		$self->loguearFile("Error: El crseId $crseId vino vacio o es incorrecto. Metodo: getInfoInscripcion");	
		return '';		
	}

	my $acadPlan = $self->{REQUEST}->param('acad_plan') || '';	
	if (!$self->validarAcadPlan($acadPlan)){
		$self->loguearFile("Error:El Plan vino vacio o es incorrecto. Metodo: getInfoInscripcion");
		return '';		
	}
	
	if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }
	
   my $legajo   = $self->{SESION}->{LEGAJO} || '';
	if ($legajo !~ m/^[0-9]+$/){
		$self->loguearFile("Legajo vacio o incorrecto. Metodo: getInfoInscripcion");	
		return '';		
	}

	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);
	my $crseIdBD   = $self->{DBHPGW}->quote($crseId);

   my $fechaBD = $self->{DBHPGW}->quote($self->getFechaActual());

	my $sql=<<SQL;
      SELECT distinct
                     i.strm, i.acad_plan, c.term_category, oc.class_nbr, oc.crse_id, a.descrlong as descr,
                     do2.mon, do2.tues, do2.wed, do2.thurs, do2.fri, do2.sat, do2.sun,
                     do2.meeting_time_start, do2.meeting_time_end,
                     do2.dia, do2.url, do2.facility_id,
                     do2.start_dt, do2.end_dt, p.first_name as name, i.acad_career, i.up_seleccion_asig,
                     car.facility_id as facility_id_novedad, do2.up_bb_tipo_curso as modalidad
      FROM
              (
						SELECT max(strm) as strm, acad_career, term_category FROM (
							 SELECT acad_career, strm, '' as term_category FROM ps_up_cic_ac_cu_vw
							 UNION
							 SELECT acad_career, strm, 'F' as term_category FROM ps_up_cic_ac_fi_vw
						) as cu
						GROUP BY acad_career, term_category
              ) as c,
              ps_up_inscr_act_vw as i INNER JOIN ps_up_ofe_ci_ac_vw oc ON i.strm = oc.strm AND i.class_nbr = oc.class_nbr 
              INNER JOIN ps_up_asignatur_vw a ON oc.crse_id = a.crse_id
              INNER JOIN  ps_up_deta_ofer_vw do2 ON
							  oc.crse_id = do2.crse_id AND
							  oc.crse_offer_nbr = do2.crse_offer_nbr AND
							  oc.strm = do2.strm AND
							  oc.session_code = do2.session_code AND
							  oc.class_section = do2.class_section
              LEFT JOIN ps_up_personas_vw p ON p.emplid=do2.emplid
				  LEFT JOIN ps_up_cartdig_nove car ON
							  car.crse_id            = oc.crse_id             AND
							  car.class_nbr          = oc.class_nbr           AND
							  car.strm               = oc.strm                AND
							  car.session_code       = oc.session_code        AND
							  car.dia                = do2.dia                AND
                       car.meeting_time_start = do2.meeting_time_start AND
                       car.meeting_time_end   = do2.meeting_time_end   AND
                       car.fecha_novedad      = $fechaBD 
      WHERE
              i.stdnt_enrl_status = 'E'           AND
				  c.acad_career       = i.acad_career AND
              c.strm         		 = i.strm        AND
              i.emplid       		 like $legajoBD  AND
              oc.crse_id     		 = $crseIdBD     AND
              i.acad_plan    		 like $acadPlanBD
      ORDER BY term_category ASC, dia ASC, meeting_time_start ASC, descr ASC
SQL

	#$self->logDebug($sql);
	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
   my $calendarioAsignaturas = '';
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $classNbr    = $res->{'class_nbr'}          || '';
		my $strm        = $res->{'strm'}               || '';
		my $isFinal     = $res->{'term_category'}      || '';
      my $nombreAsig  = $res->{'descr'}              || '';
      my $lunes       = $res->{'mon'}                || 'N';
      my $martes      = $res->{'tues'}               || 'N';
      my $miercoles   = $res->{'wed'}                || 'N';
      my $jueves      = $res->{'thurs'}              || 'N';
      my $viernes     = $res->{'fri'}                || 'N';
      my $sabado      = $res->{'sat'}                || 'N';
      my $domingo     = $res->{'sun'}                || 'N';
		my $horaInicio  = $res->{'meeting_time_start'} || '';
      $horaInicio     = $self->formatTime($horaInicio);
      my $horaFin     = $res->{'meeting_time_end'}   || '';
      $horaFin        = $self->formatTime($horaFin);
      my $docente     = $res->{'name'}               || '';
      my $acadCareer  = $res->{'acad_career'}        || '';
		my $startDt  	 = $res->{'start_dt'}           || '';
		my $url         = $res->{'url'}                || '';
	 	$url = "http://$url" if ($url !~ m/^https?:\/\//i && $url ne '' );
		my $crse_id     = $res->{'crse_id'}            || '';	
		my $endDate     = $res->{'end_dt'}             || '';	
		my $acadPlan    = $res->{'acad_plan'}          || '';	

		my $aulaEdificio        = $res->{'facility_id'}         || '';	
		my $aulaEdificioNovedad = $res->{'facility_id_novedad'} || '';
		$aulaEdificio = $aulaEdificioNovedad if ($aulaEdificioNovedad ne '');
		$aulaEdificio = $self->trimAula($aulaEdificio);

		my $modalidad = $res->{'modalidad'} || '';

		my $dia  = '0';
     	if ($lunes eq 'Y'){
     	   $dia = 'Lunes';
     	}elsif($martes eq 'Y'){
     	   $dia = 'Martes';
     	}elsif($miercoles eq 'Y'){
     	   $dia = 'Mi�rcoles';
     	}elsif($jueves eq 'Y'){
     	   $dia = 'Jueves';
     	}elsif($viernes eq 'Y'){
     	   $dia = 'Viernes';
     	}elsif($sabado eq 'Y'){
     	   $dia = 'S�bado';
     	}elsif($domingo eq 'Y'){
     	   $dia = 'Domingo';
     	}else{
      	$self->enviarErrorEmail("El dia vino vacio en el metodo getInfoInscripcion para la asignatura $nombreAsig y el legajo $legajo");
			return ['',''];
    	}
	  
	  	my $strCurOrFinal = ($isFinal eq 'F') ? 'Final' : 'Cursada'; 
		$docente = $self->trim($docente);
		my $strDocente    = ($docente ne '')  ? "Prof: ". uc($docente) : 'Prof: Sin Asignar';
		my $diaMes   		= ($isFinal eq 'F') ? $self->getDiaMes($endDate).' ' : '';
		my $aula          = ''; 
		
		if($acadCareer eq 'UGRD' || $acadCareer eq 'GRAD' || $acadCareer eq 'EXED'){
			
			my $linkUpVirtual = '';
			use URI::Escape;
			my $url_redirect = uri_escape($url);
			if ($url =~ m/(chamilo)|(moodle)|(https:\/\/acad\.palermo\.edu\/)|(https:\/\/palermo\.blackboard\.com\/)/){
				if ($1){
					if ($self->{GOTO}->tienePermisosUpVirtualChamilo()){
         			$linkUpVirtual .=<<STR;
             <a href="/cgi-bin/myup/goto.pl?app=chamilo&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
      			}
				}elsif ($2){
			      if ($self->{GOTO}->tienePermisosUpVirtualMoodle()){
         			 $linkUpVirtual .=<<STR;
							<a href="/cgi-bin/myup/goto.pl?app=moodle&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
					}
				}elsif ($3){
               if ($self->{GOTO}->tienePermisosUpVirtual()){                        
                  $linkUpVirtual .=<<STR;
             <a href="/cgi-bin/myup/goto.pl?app=up_virtual&upvirtual_classNbr=$classNbr&upvirtual_strm=$strm&upvirtual_crseId=$crse_id&upvirtual_dia=$dia&upvirtual_url_redirect=$url_redirect" style=" color:#00A3D8; font-size:11px; text-decoration:none">UP Virtual</a>
STR
               }
				}elsif ($4){
					my $leyenda = ($modalidad eq 'O') ? 'Ingresar' : 'Aula virtual';
               $linkUpVirtual .=<<STR;
             		<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">$leyenda</a>
STR
				}
			}elsif($url ne ''){
             $linkUpVirtual .=<<STR;
             			<a target="_blank" href="$url" style=" color:#00A3D8; font-size:11px; text-decoration:none">Blog Docente</a>
STR
			}

			$aula = ($aulaEdificio ne '') ? 'Aula: '.$aulaEdificio : 'Aula: Consultar';
			#$aula = ''; #No se muestra el aula hasta que desarrollemos las novedades
			
			$calendarioAsignaturas .=<<STR;
                 	<p>$strCurOrFinal</p>         
                 	<p>$dia $diaMes $horaInicio - $horaFin hs. </br> $strDocente </br> $aula $linkUpVirtual</p>
STR
		}

	}

	return $calendarioAsignaturas;
}

#-------------------------------------------------------------

sub tieneElPlanMateriasBolsa {
	my $self = shift;
	
	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';	
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
	if (!$self->validarAcadPlan($acadPlan)){
		$self->loguearFile("Error:El Plan vino vacio o es incorrecto. Metodo: tieneElPlanMateriasBolsa");
		return ['ERROR',''];
	}
	
   if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }
   my $legajo   = $self->{SESION}->{LEGAJO} || '';
	if ($legajo !~ m/^[0-9]+$/){
		$self->loguearFile("Legajo vacio o incorrecto. Metodo: tieneElPlanMateriasBolsa");	
		return ['ERROR',''];
	}

	#14/03/2017 => La estructura del plan SL2013PR tiene en un OR -> fija y electiva
	#Provisorio hasta que se vuelva hacer avance se exceptua.
	return ['OK',1] if ($acadPlan eq 'SL2013PR');

	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlan);
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

	my $sql=<<SQL;
		SELECT  count (*) as cont
		FROM 
				  ps_up_plan_alumnos pa, ps_up_in_planes_vw pi, ps_up_asign_planes ap
		WHERE
			pa.acad_plan   = pi.acad_plan AND
		 	pi.acad_plan   = ap.acad_plan AND
			ap.up_ingles   not like 'Y'   AND
			(
				(
					pa.acad_career like 'UGRD'    AND
					ap.requirement not like ''    AND 
					ap.requirement not like '9%'  AND
					ap.requirement not like '8%'
				) OR

				(
					pa.acad_career like 'GRAD'    AND
					ap.requirement not like ''         
				)
			) AND
			pa.acad_plan   like $acadPlanBD  AND
			pa.emplid      = $legajoBD

SQL

	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
   
	#my $cont = $sth->fetchrow_hashref()->{'cont'} || return ['ERROR',''];
	my $cont = $sth->fetchrow_hashref()->{'cont'} || 0;

	return ['OK',1] if ($cont > 0);
	return ['OK',0];
}

#-------------------------------------------------------------

sub logDebug {
	my $self = shift;
	my $str  = shift || 'No vino el param \$str. Metodo logDebug';	

	$str .= "\n";	
	open(FILE, ">>/tmp/log_debug.txt");
	print FILE $str;
	close(FILE);
	
}

#-------------------------------------------------------------

sub getAsignaturasByRequirement {
	my $self = shift;
	my $requirement = shift || return ['ERROR','El requirement es incorrecto.'];	

	my $acadPlan = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlan eq ''){
		$acadPlan = $self->{PLANPREF} || '';
	}
	return ['ERROR','El plan es incorrecto.'] if (!$self->validarAcadPlan($acadPlan));
   if ($acadPlan =~ /^([^-]+)-/){
      $acadPlan = $1."%";
   }

   my $legajo   = $self->{SESION}->{LEGAJO} || '';
   return ['ERROR','El legajo es incorrecto.'] if ($legajo !~ m/^[0-9]+$/);
 
	my $acadPlanBD    = $self->{DBHPGW}->quote($acadPlan);
   my $legajoBD      = $self->{DBHPGW}->quote($legajo);
   my $requirementBD = $self->{DBHPGW}->quote($requirement);

	my $sql =<<STR;
			SELECT a.descr, a.crse_id as crseid, ap.up_crsid_cursado, ap.up_crsid_final, ap.requirement, ap.rq_grp_line_nbr, 
					 ap.rq_grp_line_type, ap.up_conector, ap.parenthesis, ap.up_anio_cuatri, ap.up_ingles, a.up_solo_cursado, a.up_solo_final
			FROM
 				   ps_up_plan_alumnos pa INNER JOIN ps_up_asign_planes ap ON pa.acad_plan = ap.acad_plan
					INNER JOIN ps_up_asig_req_vw r ON ap.requirement = r.requirement
					INNER JOIN ps_up_asignatur_vw a ON r.crse_id = a.crse_id
			WHERE
				r.requirement  = $requirementBD AND
				pa.emplid      = $legajoBD      AND 
				ap.acad_plan   like $acadPlanBD    AND
            pa.up_estado_plan in ('A','B','E','I')
			ORDER BY  ap.RQ_GRP_LINE_NBR ASC, crseid ASC, ap.requirement asc
STR
	
	#$self->logDebug($sql);

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	
	if ($sth->errstr){
		my $error = $sth->errstr;
      $self->loguearFile("Error sql. Consulta = $sql , Metodo = getAsignaturasByRequirement, Error= $error");
      return ['ERROR',"No se pudo consultar las asignaturas del requerimiento $requirement"];
   }else{
		return ['OK',$sth];
	}
}

#-------------------------------------------------------------

sub getMateriasByPlan {
	my $self = shift;
	
	my $legajo = $self->{SESION}->{LEGAJO} || '';
	if ($legajo !~ m/^[0-9]+$/){
		return {};
	}

	my $acadPlanP = $self->{REQUEST}->param('acad_plan') || '';
	return {} if (!$self->validarAcadPlan($acadPlanP));
   
	if ($acadPlanP =~ /^([^-]+)-/){
      $acadPlanP = $1."%";
   }


	my $legajoBD = $self->{DBHPGW}->quote($legajo);
	my $acadPlanBD = $self->{DBHPGW}->quote($acadPlanP);

	my $sql =<<STR;
		SELECT distinct a.descrlong
		FROM ps_up_asignatur_vw a, ps_up_plan_alumnos pa, ps_up_in_planes_vw pi, ps_up_asign_planes ap
		WHERE
          pa.acad_plan like $acadPlanBD  AND
			 pa.emplid    = $legajoBD    AND
			 pa.acad_plan = pi.acad_plan AND
			 pi.acad_plan = ap.acad_plan AND
			 (ap.up_crsid_cursado = a.crse_id OR ap.up_crsid_final = a.crse_id)
STR
  
	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	my $hashMaterias = {};
	while ( my $res  = $sth->fetchrow_hashref()) {
		my $descr = $res->{'descrlong'} || next;
		$hashMaterias->{$descr} = 1;	
	}
	return $hashMaterias;
}

#-------------------------------------------------------------
#$formato => 1->Todo en mayusculas, 2-> solo la primer letra en mayuscula

sub trimNombreDocente {
   my $self = shift;
   my $str  = shift || return '';
	my $formato = shift || 1;
	
	$str = lc($str);
 
	if($formato == 1){
		$str = uc($str);	
   	$str =~ s/�/�/g;

   	$str =~ s/�/�/g;

   	$str =~ s/�/�/g;

   	$str =~ s/�/�/g;

   	$str =~ s/�/�/g;

   	$str =~ s/�/�/g;
   	$str =~ s/�/�/g;
	
	}else{
		my @strAux = split(' ',$str);
		$str = '';
		foreach  (@strAux){
			$str .= ' '.ucfirst($_);
		}

	}

   return $str;
}



#-------------------------------------------------------------

sub trim {
	my $self   = shift;
	my $string = shift || '';

	if($string){
      $string =~ s/\s+/\ /g;
		$string =~ s/^\s+//;
		$string =~ s/\s+$//;
	}
	return $string;
}

#-------------------------------------------------------------

sub getPreferenciaUsuario {
	my $self    = shift;
	my $clave   = shift;
	my $origen  = shift;
 
	return '' if ($origen !~ m/^(myup|movil|all)$/);
 
   my $usuario = $self->{SESION}->{UID} || '';

	return '' if ($usuario eq '' || $clave eq '' || $origen eq '');

	my $usuarioBD = $self->{DBHPGW}->quote($usuario);
	my $origenBD  = $self->{DBHPGW}->quote($origen);
	my $claveBD   = $self->{DBHPGW}->quote($clave);

	my $condicion = '';
	my @arrayOrigen;
	if ($origen =~ m/^all$/){
		$condicion =  ' LIMIT 1';
		push @arrayOrigen,'myup';
		push @arrayOrigen,'movil';
	}else{
		push @arrayOrigen,$origen;
		$condicion = " AND origen = $origenBD";
	}

	my $sql =<<STR;
			SELECT valor FROM myup_preferencia_usuario 
			WHERE 
					uid    = $usuarioBD AND
					clave  = $claveBD
					$condicion
STR

	my $sth = $self->{DBHPGW}->prepare($sql);
	$sth->execute();

	my $valor = '';
   while ( my $res  = $sth->fetchrow_hashref()) {
		$valor = $res->{'valor'} || '';
	}
	
	
	my $acadPlanParam = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlanParam ne '' && $valor ne $acadPlanParam){
		$valor = $acadPlanParam;
		my $valorBD = $self->{DBHPGW}->quote($valor);

		foreach  (@arrayOrigen){
			$origenBD = $self->{DBHPGW}->quote($_);	
			$sql =<<STR;
				UPDATE myup_preferencia_usuario SET valor = $valorBD, fecha_update=now()
				WHERE 
						uid    = $usuarioBD AND
						clave  = $claveBD   AND
						origen = $origenBD
STR
			$sth = $self->{DBHPGW}->prepare($sql);
			$sth->execute();
			if ($sth->rows <= 0){
				$sql =<<STR;
					INSERT INTO myup_preferencia_usuario (uid,clave,valor,origen) VALUES ($usuarioBD,$claveBD,$valorBD,$origenBD)
STR
				$sth = $self->{DBHPGW}->prepare($sql);
				$sth->execute();
			}
		}
	}
	return $valor;

}

#-------------------------------------------------------------

sub getSeleccionarPlan {
	my $self       = shift;

   my $legajo = $self->{SESION}->{LEGAJO} || '';	

   if ($legajo !~ m/^[0-9]+$/){
      $self->enviarErrorEmail("El legajo $legajo es incorrecto. Metodo getSeleccionarPlan Paquete MyUP::MyUp");
      return '';
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);

	my $sql=<<SQL;
			SELECT pa.acad_plan, ip.descr,
                pa.up_estado_plan, pa.up_deuda_matricula, pf.acad_prog
         FROM 
					ps_up_plan_alumnos as pa INNER JOIN ps_up_in_planes_vw ip ON pa.acad_plan = ip.acad_plan
               LEFT JOIN ps_up_plan_fac_vw as pf ON pa.acad_plan = pf.acad_plan
         WHERE emplid like $legajoBD  AND 
					pa.up_estado_plan in ('A','B','E')
         ORDER BY pa.up_estado_plan ASC, descr ASC
SQL
	#print "content-type:text/html\n\n";print $sql;exit;
	#$self->logDebug($sql);

   my $sth = $self->{DBHPGW}->prepare($sql);

   $sth->execute();

	my $acadPlanParam = $self->{REQUEST}->param('acadPlan') || '';
	if ($acadPlanParam eq ''){
		$acadPlanParam = $self->{PLANPREF} || '';
	}

	my $ret = '';
	while ( my $res = $sth->fetchrow_hashref()) { 
      my $acadPlan = $res->{'acad_plan'}          || '';
		my $acadProg = $res->{'acad_prog'}            || '';
     	my $descr    = $res->{'descr'}              || '';
		my $status   = $res->{'up_estado_plan'}     || '';

		my $color      = "red";
		my $estadoPlan = '';

		if ($status eq 'A'){
			my $up_deuda_matricula = $res->{'up_deuda_matricula'} || '';
			if ($up_deuda_matricula !~ m/^N$/i){
				my ($puedeRematricular,$strmRematricular) = @{$self->getRematricular($legajo, $acadPlan, $acadProg)};
				if($puedeRematricular){
					$estadoPlan = "REMATRICULAR";
				}
			}else{
				$estadoPlan = "ACTIVO";
				$color = '#668c00';
			}
		}elsif($status eq 'E'){
			$estadoPlan = "EGRESADO";
			$color = '#668c00';
		}elsif($status eq 'B'){
			$estadoPlan = "BAJA"
		}else{
			next;
		}

		my $checked = '';
		if ($acadPlan eq $acadPlanParam){
			$checked = 'checked';
		}
		$ret .=<<STR;
			<input type="radio" name="acadPlan" value="$acadPlan" $checked> $descr -  <span style="color:$color;">$estadoPlan</span><br>
STR
	}

	return $ret;

}

#-------------------------------------------------------------

sub validarAcadPlan {
	my $self      = shift;
	my $acadPlan = shift || return 0;

	return 0 if ($acadPlan eq '' || $acadPlan !~ m/^[A-Z0-9-]{4,10}$/);

	return 1;
}


#-------------------------------------------------------------
#Si se modifica , modificar tambien en MyUoInscripciones.pm
sub trimAula {
	my $self         = shift;
	my $aulaEdificio = shift || return '';
	return '' if ($aulaEdificio eq '');

	$aulaEdificio =~ s/^P1-/Mario Bravo 1302 /;
	$aulaEdificio =~ s/^CA-/Av. Madero 942 /;
	$aulaEdificio =~ s/^CB-/Cabrera 3641 /;
	$aulaEdificio =~ s/^EC-/Ecuador 933 /;
	$aulaEdificio =~ s/^LA-/LARREA 1079 /;
	$aulaEdificio =~ s/^P6-/Mario Bravo 1259 /;
	$aulaEdificio =~ s/^P8-/Mario Bravo 1050 /;
	$aulaEdificio =~ s/^P9-/Jean Jaures 932 /;
	
	$aulaEdificio =~ s/^CABRERA /Cabrera 3641 /;
	$aulaEdificio =~ s/^ECUADOR /Ecuador 933 /;
	$aulaEdificio =~ s/^LARREA /LARREA 1079 /;
	$aulaEdificio =~ s/^PALER I /Mario Bravo 1302 /;
	$aulaEdificio =~ s/^PALER IX /Jean Jaures 932 /;
	$aulaEdificio =~ s/^PALER VI /Mario Bravo 1259 /;
	$aulaEdificio =~ s/^PALER VIII /Mario Bravo 1050 /;
	$aulaEdificio =~ s/^CATALINAS /Av. Madero 942 /;

	$aulaEdificio =~ s/^PI-/Mario Bravo 1302 /;
	$aulaEdificio =~ s/^PIX-/Jean Jaures 932 /;
	$aulaEdificio =~ s/^PVI-/Mario Bravo 1259 /;
	$aulaEdificio =~ s/^PVIII-/Mario Bravo 1050 /;

	return $aulaEdificio;	
}

#-------------------------------------------------------------
#cuando se edite editar tambien en MyUpInscripciones.pm
sub getFechaActual {
	my $self         = shift;
	my @actualFecha = localtime(time);
	my $fechaDia = $actualFecha[3];
	my $fechaMes = $actualFecha[4] + 1;
	my $fechaAno = $actualFecha[5] + 1900;
	$fechaDia = "0$fechaDia" if ($fechaDia =~ m/^[1-9]$/);
	$fechaMes = "0$fechaMes" if ($fechaMes =~ m/^[1-9]$/);
	return "$fechaAno-$fechaMes-$fechaDia";
}

#-------------------------------------------------------------

sub getHoraActual {
	my $self         = shift;

	my @fechas = localtime(time);
	my $fechaDia = $fechas[3];
	my $fechaMes = $fechas[4] + 1;
	my $fechaAno = $fechas[5] + 1900;
	my $sec      = $fechas[0];
	my $min      = $fechas[1];
	my $hour     = $fechas[2];

	$fechaDia = "0$fechaDia" if ($fechaDia =~ m/^[1-9]$/);
	$fechaMes = "0$fechaMes" if ($fechaMes =~ m/^[1-9]$/);

	$sec  = "0$sec" if ($sec =~ m/^[1-9]$/);
	$min  = "0$min" if ($min =~ m/^[1-9]$/);
	$hour = "0$hour" if ($hour =~ m/^[1-9]$/);


	return "$hour:$min:$sec";
}

#-------------------------------------------------------------

sub getBotoneraSistemas {
	my $self = shift;
	my $page = shift || '';

	my $array = (MyUP::Conf->HASHSISTEMAS->{$page}) ? MyUP::Conf->HASHSISTEMAS->{$page} : [];
	my $cantArray = scalar(@{$array});
	return '' if ($cantArray <= 0);

	my @array = @{$self->{PLANALUMNO}};
	my $isDepo = '0';

	my $planDepo = '';
	foreach my $res (@array){
		my $acad_plan      = $res->{acad_plan}      || next;
		my $acad_career    = $res->{acad_career}    || next;
				
		if($acad_career eq 'DEPO'){
			$isDepo = '1';
			$planDepo = $acad_plan;
			last;
		}
	}

	my $ret = '';
#	$ret =<<STR;
#<div class="linksGrises">
#  <ul class="nav">
#        <li>
#          <a href="">Inscripciones</a>
#          <ul class="subLinks">
#            <li><a href="">Fechas de inscripci�n</a></li>
#            <li><a href="">Tutor�as ex�menes previos</a></li>
#            <li><a href="">Permisos de examen</a></li>
#            <li><a href="">Permisos de examen</a></li>
#            <li><a href="">Permisos de examen</a></li>
#            <li><a href="">Permisos de examen</a></li>
#            <li><a href="">Permisos de examen</a></li>
#            <li><a href="">Permisos de examen</a></li>
#          </ul>
#        </li>
#        <li><span>|</span><a href="http://riki.palermo.edu/cgi-bin/myup/myup_calificaciones.pl">Mis calificaciones</a></li>
#        <li><span>|</span><a href="http://riki.palermo.edu/cgi-bin/myup/myup_avance_carrera.pl">Avance de carrera</a></li>
#        
#      </ul>
#
#  <div class="iconMenuMisInscripciones"><a href="http://riki.palermo.edu/cgi-bin/myup/myup_inscripcion.pl?cur_fin=F#" class="linksGrisesMisInscripciones" onclick="mostrarInscriptas();"><span></span>Mis inscripciones</a></div>
#
#
#</div>
#STR

	my $carrito = '';
	foreach (@{$array}){
		my $sistema = $_;
		if (ref ($sistema) eq 'HASH'){
			my $habilitado = $sistema->{'HABILITADO'} || next;
			next if (!$habilitado);
			my $etiqueta   = $sistema->{'ETIQUETA'} || next;	
			my $arrayLinks = $sistema->{'LINKS'}    || next;	
			my $str = '';	
			foreach my $sistema2 (@{$arrayLinks}){	
				my $hashSistema2 = MyUP::Conf->$sistema2;
				my $habilitado2 = $hashSistema2->{'habilitado'} || next;
				next if (!$habilitado2);
				next if (!$self->habilitarBoton($sistema2));
				my $url      = $hashSistema2->{'url'}      || next;							
				my $etiqueta = $hashSistema2->{'etiqueta'} || next;
				my $class    = $hashSistema2->{'class'}    || '';
				my $onclick  = $hashSistema2->{'onclick'}  || '';
				
				if($sistema2 eq 'INSCRIPCIONDEPORTES'){
					if(!$isDepo){
						next if (!$self->{GOTO}->tienePermisosSistemaDeAlumnos());
						$url = $hashSistema2->{'urlCredencial'} || $url;							
					}else{
						$url = $url.'&acad_plan='.$planDepo;													
					}
				}else{
					$url = $url;									
				}									

				$str .= "<li><a href=\"$url\" $class $onclick>$etiqueta</a></li>"; 
			}
			if ($str ne ''){
				$ret .= "<span>|</span></li>" if ($ret ne '');
				$ret .= "<li><a href=\"\">$etiqueta</a><ul class=\"subLinks\">$str</ul>"; 
			}
		}else{
			my $hashSistema = MyUP::Conf->$sistema;
			my $habilitado = $hashSistema->{'habilitado'} || next;
			next if (!$habilitado);
			

			next if (!$self->habilitarBoton($sistema));
			
			my $url      = $hashSistema->{'url'}      || next;
			
			#if ($self->{SESION}->isTesting()){
			#	my $url2 = $hashSistema->{'url2'} || '';
			#	$url = $url2 if ($url2 ne '');
			#}	

			my $etiqueta = $hashSistema->{'etiqueta'} || next;
			my $class    = $hashSistema->{'class'}    || '';
			my $onclick  = $hashSistema->{'onclick'}  || '';
			
			if ($sistema eq 'MISINSCRIPCIONES'){
				$carrito = "<div class=\"iconMenuMisInscripciones\"><a href=\"$url\" $class $onclick><span></span>$etiqueta</a></div>"; 		
			}else{
				$ret .= "<span>|</span></li>" if ($ret ne '');
				$ret .= "<li><a href=\"$url\" $class $onclick>$etiqueta</a>"; 
			}
		}
	}
	if ($ret ne ''){
		$ret .= "</li>";
		$ret = "<div class=\"linksGrises\"><ul class=\"nav\">$ret</ul>$carrito</div>";
	}
	return $ret;
}

#-------------------------------------------------------------

sub getProximoCicloIns {
	my $self = shift;

	my $sql = <<SQL;
				SELECT distinct strm_ugrd, strm_grad, concepto 
				FROM myup_link_fecha_insc 
				WHERE now() BETWEEN desde AND hasta
				ORDER BY concepto ASC
SQL

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	if ($sth->errstr){
      my $error = $sth->errstr;
      $self->loguearFile("Error sql. Consulta = $sql , Metodo = getProximoCicloIns, Error= $error");
      return [];
   }

	my $arrayProxCiclos = [];

	while ( my $res  = $sth->fetchrow_hashref()) {		
	   my $strm_ugrd = $res->{'strm_ugrd'} || '';
	   my $strm_grad = $res->{'strm_grad'} || '';
	   my $concepto  = $res->{'concepto'}  || '';
		 my $hashAux={
         'concepto'=>$concepto,
         'strm_ugrd'=>$strm_ugrd,
         'strm_grad'=>$strm_grad,
      };

      push @{$arrayProxCiclos},$hashAux;	
	}

	#$self->loguearFile($sql);

   return $arrayProxCiclos;
}

#-------------------------------------------------------------

sub getGrillaFechaIns {	
	my $self = shift;
	my @array = @{$self->{PLANALUMNO}};
	my $proximoCicloIns = $self->getProximoCicloIns();
	#return ['ERROR','No se encontraron fechas de inscripci&oacute;n'];
	return ['ERROR','No se encontraron fechas de inscripci&oacute;n'] if(!@{$proximoCicloIns});

	my $arrayGrillaFechas = []; 
	my $status = 'OK';
	my $error  = '';	
	my $hashUsados = {};	
	
	foreach my $res (@array){
		my $acad_plan      = $res->{acad_plan}      || next;
		my $acad_career    = $res->{acad_career}    || next;
		my $up_estado_plan = $res->{up_estado_plan} || next;
		my $acad_prog      = $res->{acad_prog}      || next;
		my $acad_group     = $res->{acad_group}     || next;
			
		next if ($acad_career eq 'DEPO');

		foreach my $proxCiclo (@{$proximoCicloIns}) {
			my $cursado = $proxCiclo->{'concepto'};
			my $arrayGrilla = [];
		
			if($acad_career eq 'UGRD'){
				last if(defined $hashUsados->{$acad_career.'_'.$acad_group.'_'.$cursado.'_'.$proxCiclo->{'strm_ugrd'}});
				($status,$error,$arrayGrilla) = @{$self->getArrayGrillaFechaIns($arrayGrilla,$proxCiclo->{'strm_ugrd'},$acad_career,$acad_group,$cursado)};
				$hashUsados->{$acad_career.'_'.$acad_group.'_'.$cursado.'_'.$proxCiclo->{'strm_ugrd'}} = 1;
			}elsif($acad_career eq 'GRAD'){
				last if(defined $hashUsados->{$acad_career.'_'.$acad_group.'_'.$cursado.'_'.$proxCiclo->{'strm_grad'}});
				($status,$error,$arrayGrilla) = @{$self->getArrayGrillaFechaIns($arrayGrilla,$proxCiclo->{'strm_grad'},$acad_career,$acad_group,$cursado)};				
				$hashUsados->{$acad_career.'_'.$acad_group.'_'.$cursado.'_'.$proxCiclo->{'strm_grad'}} = 1;
			}else{
				last;
			}
			
			return['ERROR',$error] if($status ne 'OK');
			push @{$arrayGrillaFechas},@{$arrayGrilla};
		}
		
	}

	$arrayGrillaFechas = $error if($status ne 'OK');

	my $hashRet = {};
	my $descCiclos = $self->getDescripcionCiclosGrilla();

	$hashRet->{'items'}      = $arrayGrillaFechas;
	$hashRet->{'descCiclos'} = $descCiclos;

	foreach my $fecha (@{$arrayGrillaFechas}){
		if($fecha->{'asignatura'} eq ''){
	    	if($descCiclos->{$fecha->{'ciclo'}}){
   	   	$fecha->{'asignatura'} = $descCiclos->{$fecha->{'ciclo'}}->{'descripcion'};      	
      	}else{
        		$fecha->{'asignatura'} = 'INSCRIPCION GENERAL';
	      }
		}elsif($fecha->{'asignatura'} eq '-'){
    		$fecha->{'asignatura'} = 'INSCRIPCION SEGUN GRADO DE AVANCE';
		}	
	}

	return [$status,$arrayGrillaFechas];
}

#-------------------------------------------------------------

sub getDescripcionCiclosGrilla {
	my $self = shift;
	my $hash = {};

	my $sql = <<SQL;
		select descr as descripcion, strm as ciclo, acad_career as grado, descrshort as descripcion_corta, acad_year as anio from ps_up_cicl_syll_vw;  
SQL

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	if ($sth->errstr){
     my $error = $sth->errstr;
     $self->loguearFile("Error sql. Consulta = $sql , Metodo = getDescripcionCiclosGrilla, Error= $error");
     return {};
   }

	while ( my $res  = $sth->fetchrow_hashref()) {
		$hash->{$res->{ciclo}} = $res;
	}
	
	return $hash;
}

#-------------------------------------------------------------

sub getArrayGrillaFechaIns {
	my $self     = shift;
	my $arrayGrilla = shift || ['ERROR','No se encontraron fechas de inscripci&oacute;n',[]];
	my $ciclo       = shift || ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
	my $grado       = shift || ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
	my $facultad    = shift || ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
	my $cursado     = shift || ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
	
	my $legajo 			  = $self->{SESION}->{LEGAJO} || '';
	my $legajoBD 		  = $self->{DBHPGW}->quote($legajo);

	my $sql = <<SQL;
	SELECT a.strm as ciclo, a.transactionid, a.crse_id, '-' as asignatura, '$facultad' as facultad, a.acad_career as carrera, c.up_ss_ini_insc as fecha_inicio_alum, c.up_hora_inicio as hora_inicio_alum, b.up_ss_ini_insc as fecha_inicio_cero_avan, b.up_hora_inicio as hora_inicio_cero_avan, a.up_ss_fin_insc, '$cursado' as inscripcion
	FROM  ps_up_ss_term_tbl a 
			LEFT JOIN ps_up_asignatur_vw as ad ON ad.crse_id = a.crse_id, ps_up_avance_inscr b 
			LEFT OUTER JOIN ps_up_ins_avan_alu c on b.transactionid=c.transactionid and c.emplid=$legajoBD and c.status='A'
		WHERE  a.transactionid   = b.transactionid 
			and b.up_avance_desde = 0
			and a.acad_career     = '$grado'
			and a.strm            = '$ciclo'
			and a.acad_group      = '$facultad'
			and a.crse_id         = ''
			and a.transactionid   > 0
      UNION
      SELECT a.strm as ciclo, a.transactionid, a.crse_id, ad.descrlong as asignatura, '$facultad' as facultad, a.acad_career as carrera, b.up_ss_ini_insc as fecha_inicio_alum, b.up_hora_inicio as hora_inicio_alum, null as fecha_inicio_cero_avan, null as hora_inicio_cero_avan, a.up_ss_fin_insc, '$cursado' as inscripcion
      FROM ps_up_ins_avan_alu b, ps_up_ss_term_tbl a
			LEFT JOIN ps_up_asignatur_vw as ad ON ad.crse_id = a.crse_id
      WHERE  a.transactionid  = b.transactionid
			AND b.emplid         = $legajoBD
			AND b.status         = 'A'
			AND a.acad_career    = '$grado'
			AND a.strm           = '$ciclo'
			AND a.acad_group     = '$facultad'
			AND a.crse_id       != ''
			AND a.transactionid > 0;
SQL

	#$self->loguearFile($sql);

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();

	if ($sth->errstr){
     my $error = $sth->errstr;
     $self->loguearFile("Error sql. Consulta = $sql , Metodo = getArrayGrillaFechaIns, Error= $error");
     return ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
   }


	if ($sth->rows > 0 && $cursado eq 'C'){
		while ( my $res  = $sth->fetchrow_hashref()) {
			push @{$arrayGrilla},$res;
		}
	}else{

		$sql = <<SQL;
			SELECT a.strm as ciclo, a.transactionid, a.crse_id, '' as asignatura, '$facultad' as facultad, a.acad_career as carrera, up_ss_ini_insc as fecha_inicio_alum, up_hora_inicio as hora_inicio_alum, '$cursado' as inscripcion, null as fecha_inicio_cero_avan, null as hora_inicio_cero_avan 
			FROM ps_up_ss_term_tbl as a 
			LEFT JOIN ps_up_asignatur_vw as ad ON ad.crse_id = a.crse_id 
			WHERE strm='$ciclo' AND a.acad_career='$grado' AND a.acad_group='$facultad' and a.crse_id='' 
			ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1;
SQL

	 	#$self->loguearFile($sql);

		$sth = $self->{DBHPGW}->prepare($sql);
		$sth->execute();

		if ($sth->errstr){
      	my $error = $sth->errstr;
	      $self->loguearFile("Error sql. Consulta = $sql , Metodo = getArrayGrillaFechaIns, Error= $error");
     		return ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
	   }

		if ($sth->rows > 0){
			my $res = $sth->fetchrow_hashref();
			push @{$arrayGrilla},$res;
		}else{
			$sql = <<SQL;
				SELECT a.strm as ciclo, a.transactionid, a.crse_id, '' as asignatura, '$facultad' as facultad, a.acad_career as carrera, up_ss_ini_insc as fecha_inicio_alum, up_hora_inicio as hora_inicio_alum, '$cursado' as inscripcion, null as fecha_inicio_cero_avan, null as hora_inicio_cero_avan 
				FROM ps_up_ss_term_tbl as a
				LEFT JOIN ps_up_asignatur_vw as ad ON ad.crse_id = a.crse_id 
				WHERE strm='$ciclo' AND a.acad_career='$grado' AND a.acad_group='' and a.crse_id='' 
				ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1;
SQL
					
		 	#$self->loguearFile($sql);
		
			$sth = $self->{DBHPGW}->prepare($sql);
			$sth->execute();
		
			if ($sth->errstr){
   	      my $error = $sth->errstr;
	         $self->loguearFile("Error sql. Consulta = $sql , Metodo = getArrayGrillaFechaIns, Error= $error");
         	return ['ERROR','No se encontraron fechas de inscripci&oacute;n',$arrayGrilla];
      	}
		
			if ($sth->rows > 0){
				my $res = $sth->fetchrow_hashref();
				push @{$arrayGrilla},$res;
			}
		}
	}

   return ['OK','',$arrayGrilla]; 
}

#-------------------------------------------------------------

sub estaHabilitadoInscripcion {
	my $self = shift;
	my $tipo = shift || return 0;
   my $acad_plan_filtro = shift || '';


	#return 1 if (MyUP::Conf->PROD_DES ne 'PRODUCCION'); #DEBUG -> Comentar cuando se quiere hacer pruebas reales en desarrollo.

	return 0 if (!$self->{CICLOACTUAL}->{$tipo});
	my @array = @{$self->{PLANALUMNO}};

	foreach my $res (@array){
		my $acad_plan      = $res->{acad_plan}      || next;
		my $acad_career    = $res->{acad_career}    || next;
		my $up_estado_plan = $res->{up_estado_plan} || next;
		my $acad_prog      = $res->{acad_prog}      || next;
		my $acad_group     = $res->{acad_group}     || next;
	
		next if ($acad_plan_filtro ne '' && $acad_plan_filtro ne $acad_plan);
		#next if ($up_estado_plan eq 'E' || $acad_career eq 'DEPO');      
		next if ($acad_career eq 'DEPO');

		my $strm = $self->{CICLOACTUAL}->{$tipo}->{$acad_career} || '';
		next if ($strm eq '');
	
		return 1 if ($self->verificarVigenciaCiclo($strm,$acad_career,$acad_group));
	}

	return 0;
}

#-------------------------------------------------------------

sub habilitarBoton {
	my $self = shift;
	my $sistema = shift || return 0;

	if ($sistema eq 'INSCRIPCIONFINAL'){
		return $self->{INSC_FIN_ABIERTO};
	}elsif ($sistema eq 'INSCRIPCIONCURSADA'){
		return $self->{INSC_CUR_ABIERTO};
	}elsif ($sistema eq 'INSCRIPCIONTUTORIAS'){
		return $self->tieneInscripcionesTutorias();
	}elsif ($sistema eq 'AVANCEDECARRERA'){
		my ($status,$tieneElPlanMateriasBolsa) = @{$self->tieneElPlanMateriasBolsa()};
		return 1 if ($status eq 'OK' && !$tieneElPlanMateriasBolsa);
 	}elsif ($sistema eq 'CALIFICACIONES'){
		return 1;
	}elsif ($sistema eq 'MISINSCRIPCIONES'){
		return 1;
	}elsif ($sistema eq 'PERMISOSDEEXAMEN'){
		return 1;
	}elsif ($sistema eq 'INSCRIPCIONDEPORTES'){
		return 1;
	}elsif ($sistema eq 'INSC_MOSTRAR_LINK_GRILLA_INS'){
		return $self->{INSC_MOSTRAR_LINK_GRILLA_INS};
	}
	return 0;
}

#-------------------------------------------------------------

sub tieneInscripcionesTutorias {
	my $self = shift;
	
   my $legajo   = $self->{SESION}->{LEGAJO} || '';
	if ($legajo !~ m/^[0-9]+$/){
		$self->loguearFile("Legajo vacio o incorrecto. Metodo: tieneInscripcionesTutorias");	
		return 0;	
	}
   my $legajoBD   = $self->{DBHPGW}->quote($legajo);

#	my $sql=<<SQL;
#		SELECT  count (*) as cont
#		FROM 
#				  ps_up_plan_alumnos pa
#		WHERE
#			pa.emplid      = $legajoBD AND
#		   pa.up_estado_plan not in ('E') AND
#			pa.acad_group = 'AD' AND
#         pa.acad_career = 'UGRD'
#SQL
	my $sql=<<SQL;
		SELECT  count (*) as cont
		FROM 
				  ps_up_plan_alumnos pa
		WHERE
			pa.emplid      = $legajoBD AND
			pa.acad_group = 'AD' AND
         pa.acad_career = 'UGRD'
SQL

	#print "content-type:text/html\n\n";
	#print "sql $sql<br>";

   my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
   
	my $cont = $sth->fetchrow_hashref()->{'cont'} || return 0;
	return 1 if ($cont > 0);
	return 0;
}

#-------------------------------------------------------------

sub getAvisoInscripcionAvanceCarrera {
	my $self = shift;

	return [0,'']; #Borrar esta lina cuando este habilitada la inscripcion desde avance de carreras

	if ($self->{INSC_CUR_ABIERTO} && $self->{INSC_FIN_ABIERTO}){
		return [1,'Utilice el cuadro de avance para la inscripci&oacute;n a cursado / final.'];
	}elsif($self->{INSC_CUR_ABIERTO}){
		return [1,'Utilice el cuadro de avance para la inscripci&oacute;n a cursado.'];
	}elsif($self->{INSC_FIN_ABIERTO}){
		return [1,'Utilice el cuadro de avance para la inscripci&oacute;n a final.'];
	}

	return [0,''];
}

#-------------------------------------------------------------

sub verificarVigenciaCiclo {
	my $self     = shift;
	my $ciclo    = shift || return 0;
	my $grado    = shift || return 0;
	my $facultad = shift || return 0;

	my $fecha_min_ini = '';
   my $hora_inicio_inscr = '';
   my $fecha_max_fin = '';
   my $transactionid = '';

   my $fecha_min_ini_avance = '';
   my $hora_inicio_inscr_avance = '';
   my $fecha_max_fin_avance = '';
   my $fecha_min_ini_ciclo = '';
   my $hora_inicio_inscr_ciclo = '';
   my $fecha_max_fin_ciclo = '';

	my $sql_fecha_finInsc = '';
	my $sql_fecha_iniInsc = '';

	my $legajo 			  = $self->{SESION}->{LEGAJO} || '';
	my $legajoBD 		  = $self->{DBHPGW}->quote($legajo);


	 my $sql_legajo_a_liquidar = <<SQL; 
											SELECT c.up_ss_ini_insc as fecha_inicio_alum, c.up_hora_inicio as hora_inicio_alum, b.up_ss_ini_insc as fecha_inicio_cero_avan, b.up_hora_inicio as hora_inicio_cero_avan, a.up_ss_fin_insc
											FROM ps_up_ss_term_tbl a, ps_up_avance_inscr b 
												LEFT OUTER JOIN ps_up_ins_avan_alu c on b.transactionid=c.transactionid and c.emplid=$legajoBD and c.status='A'
											WHERE a.transactionid=b.transactionid 
													and b.up_avance_desde = 0
													and a.acad_career = '$grado'
													and a.strm = '$ciclo'
													and a.acad_group = '$facultad'
													and a.crse_id = ''
													and a.transactionid > 0
										UNION
											SELECT b.up_ss_ini_insc as fecha_inicio_alum, b.up_hora_inicio as hora_inicio_alum, null as fecha_inicio_cero_avan, null as hora_inicio_cero_avan, a.up_ss_fin_insc
											FROM ps_up_ss_term_tbl a,ps_up_ins_avan_alu b
											WHERE a.transactionid=b.transactionid
											AND b.emplid = $legajoBD
											AND b.status = 'A'
											AND a.acad_career = '$grado'
											AND a.strm = '$ciclo'
											AND a.acad_group = '$facultad'
											AND a.crse_id != ''
											AND a.transactionid > 0;
SQL

	my $sth = $self->{DBHPGW}->prepare($sql_legajo_a_liquidar);
   $sth->execute();

	while ( my $res  = $sth->fetchrow_hashref()) {
	   my $fecha_inicio_alum 		= $res->{'fecha_inicio_alum'}      || '';
	   my $hora_inicio_alum			= $res->{'hora_inicio_alum'}       || '';
	   my $fecha_inicio_cero_avan = $res->{'fecha_inicio_cero_avan'} || '';
	   my $hora_inicio_cero_avan  = $res->{'hora_inicio_cero_avan'}  || '';
	   my $fecha_fin 					= $res->{'up_ss_fin_insc'} 		  || '';

		my $fecha_inscripcion_avance = '';
		my $hora_inscripcion_avance  = '';

	   if($fecha_inicio_alum ne ''){
         $fecha_inscripcion_avance = $fecha_inicio_alum;
         $hora_inscripcion_avance  = $hora_inicio_alum;
      }else{
         $fecha_inscripcion_avance = $fecha_inicio_cero_avan;
         $hora_inscripcion_avance  = $hora_inicio_cero_avan;
      }		

		if($fecha_min_ini_avance eq '' || $fecha_min_ini_avance gt $fecha_inscripcion_avance){
         $fecha_min_ini_avance     = $fecha_inscripcion_avance;
         $hora_inicio_inscr_avance = $hora_inscripcion_avance;
      }else{
         if($fecha_min_ini_avance eq $fecha_inscripcion_avance && $hora_inicio_inscr_avance gt $hora_inscripcion_avance){
            $hora_inicio_inscr_avance = $hora_inscripcion_avance;
         }
      }	
		
		if($fecha_max_fin_avance eq '' || $fecha_max_fin_avance lt $fecha_fin){
         $fecha_max_fin_avance = $fecha_fin;
      }	
 
	}

	if($fecha_min_ini_avance eq ''){
		$sql_fecha_iniInsc = "SELECT up_ss_ini_insc,up_hora_inicio FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='$facultad' and CRSE_ID='' ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1";

		my $sth2 = $self->{DBHPGW}->prepare($sql_fecha_iniInsc);
	   $sth2->execute();

   	my $res2 = $sth2->fetchrow_hashref();
	   $fecha_min_ini_ciclo     = $res2->{'up_ss_ini_insc'} || '';
   	$hora_inicio_inscr_ciclo = $res2->{'up_hora_inicio'} || '';	
	
		if($fecha_min_ini_ciclo eq ''){
			$sql_fecha_iniInsc = "SELECT up_ss_ini_insc,up_hora_inicio FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='' and CRSE_ID='' ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1";
		   
			my $sth3 = $self->{DBHPGW}->prepare($sql_fecha_iniInsc);
   	   $sth3->execute();
			
	      my $res3 = $sth3->fetchrow_hashref();
   	   $fecha_min_ini_ciclo     = $res3->{'up_ss_ini_insc'} || '';
      	$hora_inicio_inscr_ciclo = $res3->{'up_hora_inicio'} || '';
			
			$sql_fecha_finInsc = "SELECT up_ss_fin_insc FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='' and CRSE_ID='' ORDER BY up_ss_fin_insc DESC LIMIT 1";

		}else{
			$sql_fecha_finInsc = "SELECT up_ss_fin_insc FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='$facultad' and CRSE_ID='' ORDER BY up_ss_fin_insc DESC LIMIT 1";				
		}

		my $sth4 = $self->{DBHPGW}->prepare($sql_fecha_finInsc);
      $sth4->execute();
		
      my $res4 = $sth4->fetchrow_hashref();
      $fecha_max_fin_ciclo = $res4->{'up_ss_fin_insc'} || '';

		$fecha_min_ini     = $fecha_min_ini_ciclo;
      $hora_inicio_inscr = $hora_inicio_inscr_ciclo;
      $fecha_max_fin     = $fecha_max_fin_ciclo;	

	}else{
		$fecha_min_ini     = $fecha_min_ini_avance;
      $hora_inicio_inscr = $hora_inicio_inscr_avance;
      $fecha_max_fin     = $fecha_max_fin_avance;
	}	


	if ($fecha_min_ini gt $self->getFechaActual() || $fecha_min_ini eq ''){
      return 0;
   }else{
      if($fecha_min_ini eq $self->getFechaActual() && $hora_inicio_inscr gt $self->getHoraActual()){
         return 0;
      }else{
         if($fecha_max_fin lt $self->getFechaActual() || $fecha_max_fin eq ''){
            return 0;
         }
      }
   }

   return 1;

}

#-------------------------------------------------------------

sub verificarVigenciaCicloOld {
	my $self     = shift;
	my $ciclo    = shift || return 0;
	my $grado    = shift || return 0;
	my $facultad = shift || return 0;

   my $sql_fecha_iniInsc = "SELECT up_ss_ini_insc,up_hora_inicio FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='$facultad' ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1";


   my $sth = $self->{DBHPGW}->prepare($sql_fecha_iniInsc);
   $sth->execute();

	my $res = $sth->fetchrow_hashref(); 
   my $fecha_min_ini     = $res->{'up_ss_ini_insc'} || '';
   my $hora_inicio_inscr = $res->{'up_hora_inicio'} || '';


	my $sql_fecha_finInsc = '';
	if ($fecha_min_ini eq ''){
      $sql_fecha_iniInsc = "SELECT up_ss_ini_insc,up_hora_inicio FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='' ORDER BY up_ss_ini_insc,up_hora_inicio LIMIT 1";
		$sth = $self->{DBHPGW}->prepare($sql_fecha_iniInsc);
		$sth->execute();

		$res = $sth->fetchrow_hashref(); 
		$fecha_min_ini     = $res->{'up_ss_ini_insc'} || '';
		$hora_inicio_inscr = $res->{'up_hora_inicio'} || '';

      $sql_fecha_finInsc = "SELECT up_ss_fin_insc FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='' ORDER BY up_ss_fin_insc DESC LIMIT 1";

	}else{
		$sql_fecha_finInsc = "SELECT up_ss_fin_insc FROM ps_up_ss_term_tbl WHERE strm='$ciclo' AND acad_career='$grado' AND acad_group='$facultad' ORDER BY up_ss_fin_insc DESC LIMIT 1";

	}

	if ($fecha_min_ini eq '' || $fecha_min_ini gt $self->getFechaActual()){
		return 0;
	}else{
		if($fecha_min_ini eq $self->getFechaActual() && $hora_inicio_inscr gt $self->getHoraActual()){
			return 0;
		}else{

			$sth = $self->{DBHPGW}->prepare($sql_fecha_finInsc);
	      $sth->execute();
	
   	   $res = $sth->fetchrow_hashref();
   		my $fecha_max_fin = $res->{'up_ss_fin_insc'} || '';
			return 0 if ($fecha_max_fin eq '' || $fecha_max_fin lt $self->getFechaActual());
		}
	}

	return 1;
	
}

#-------------------------------------------------------------

sub getPlanesDelAlumno {
	my $self = shift;

	my $legajo = $self->{SESION}->{LEGAJO} || '';
	my $uid	  = $self->{SESION}->{UID} || '';
   
	if ($legajo !~ m/^[0-9]+$/){
	#	my $scriptName = $ENV{SCRIPT_NAME} || 'sin script';
   #   $self->enviarErrorEmail("El legajo $legajo es incorrecto. uid $uid. Metodo getPlanesDelAlumno Paquete MyUP::MyUp => SCRIPT_NAME $scriptName.");
      return [];
   }

   my $legajoBD = $self->{DBHPGW}->quote($legajo);
	
	my $sql=<<SQL;
		SELECT id,emplid,acad_plan,acad_career,up_situacion,row_lastmant_dttm,up_prom_fin_gral,up_prom_fin_apr,up_prom_cur_gral,up_prom_cur_apr,up_avance_cursado,up_avance_finales,up_cohorte,up_ult_cur,up_ult_fin,up_curs_pend,up_curs_aprob,up_fin_pend,up_fin_aprob,up_estado_plan,up_curins_cant,up_finins_cant,up_curin_a_cant,up_finin_a_cant,up_curins_incomp,up_curins_reprob,up_finins_reprob,up_curins_vencid,up_curins_scalif,up_cureqv_apr_cant,up_fineqv_apr_cant,up_cureqv_vencidas,up_curexc_cant,up_finexc_apr_can,up_deuda_matricula,up_curs_pend_fin,up_ciclo_retorno,admit_term,fecha_alta,acad_prog,acad_group  
		FROM ps_up_plan_alumnos 
		WHERE emplid = $legajoBD
SQL

	my $sth = $self->{DBHPGW}->prepare($sql);
	$sth->execute();

	my $error = '';
	if ($sth->errstr){
		return [];
	}else{
		my $array = [];
   	while ( my $res  = $sth->fetchrow_hashref()) {
			push @{$array}, $res;
		}

		return $array;
	}

	return [];
}

#-------------------------------------------------------------

sub getCicloActual {
	my $self = shift;

	my $sql =<<STR;
		(
       SELECT max(strm) as strm, acad_career, 'F' as tipo FROM ps_up_cic_ac_fi_vw GROUP BY tipo, acad_career
		 UNION
		 SELECT max(strm) as strm, acad_career, 'C' as tipo FROM ps_up_cic_ac_cu_vw GROUP BY tipo, acad_career
      )
STR

	my $sth = $self->{DBHPGW}->prepare($sql);
   $sth->execute();
	 
	my $hash = {};
   while ( my $res  = $sth->fetchrow_hashref()) {
		my $strm        = $res->{'strm'} || '';
		my $tipo        = $res->{'tipo'} || '';
		my $acad_career = $res->{'acad_career'} || '';
		$hash->{$tipo}->{$acad_career} = $strm;
	}

	return $hash;
}

#-------------------------------------------------------------

sub getDocumento {
	my $self   = shift;
	my $legajo = shift || re