<?php
/***************************************************************************
 * Autor: Gonzalo Andres Vergara Rojas,
 * Fecha: Diciembre 2009
 * Correo: webmaster@andresvergara.cl
 * @gnzvergara
 * www.andresvergara.cl
 *
 * Panel de Seleccion de Asignaturas Solo para Cuarto Medio
 * - posibilita al profesor a seleccionar las asignaturas de cuarto medio
 *   que tiene enroladas.
 **************************************************************************/
 
include("configuracion.php");
$user = $_COOKIE['user'];
//Consulta las asignaturas asociadas al profesor especificamente de 4to medio.
$query_datos = "SELECT title
				FROM course
				WHERE tutor_name = ( SELECT concat( lastname, ' ', firstname ) FROM user WHERE username = '$user' )
				AND category_code = '4to'";
//echo $query_datos;			
$resultado = mysql_query($query_datos);
$tabla = '';
	
	while($row = mysql_fetch_assoc($resultado)) {
		
	  	
		  $tabla .= "<tr class=\"todo\">";
		  $tabla .= "<td><input type=\"radio\" name=\"asignatura\" value=\"".$row['title']."\"></td>";
		  $tabla .= "<td>".$row['title']."</td>"; 
		  $tabla .= "</tr>";

    }
	//Si se pulsa el boton para editar o cambiar las notas de una asignatura.
	if($_POST['Modificar'])
	{
		if($_POST['asignatura'] && $_POST['ed_nota'])
		{
			$asignatura = $_POST['asignatura'];
			$nota		= $_POST['ed_nota'];
			//Solo cambia el nombre de la asginatura el curso 4to siempre sera el mismo y le envia la calificaciona ser cambiada.
			header("Location: editar_notas.php?asignatura=$asignatura&curso=4to&nota=$nota");
		}
		elseif($_POST['asignatura'] == "")
		{
			echo "<SCRIPT LANGUAGE=\"JavaScript\"> alert('Debe seleccionar una asignatura.') </SCRIPT>";
		}
		elseif($_POST['ed_nota'] == "")
		{
			echo "<SCRIPT LANGUAGE=\"JavaScript\"> alert('Debe seleccionar la asignatura y la nota que desea editar.') </SCRIPT>";
		}
		
	}
	
	if($_POST['Ver'])
	{
		if($_POST['asignatura'])
		{
			$asignatura = $_POST['asignatura'];
			//Solo cambia el nombre de la asginatura el curso 3ro siempre sera el mismo.
			header("Location: ver_notas.php?asignatura=$asignatura&curso=4to");
		}
		else
		{
			echo "<SCRIPT LANGUAGE=\"JavaScript\"> alert('Debe seleccionar una asignatura.') </SCRIPT>";
		}
		
	}

include ("Template.php");

//Carga Plantilla
$plantilla 			= new Template();
$plantilla->setPath('./plantillas/');

$plantilla->setTemplate('cuarto_medio');
$plantilla->setVars(array(	
						"USER"			=>  $user,
						"TABLA"	        =>  $tabla));
echo $plantilla->show();

//Carga CSS
$plantilla->setPath('./css/');
$plantilla->setTemplate('predeterminado');
echo $plantilla->show();

?>