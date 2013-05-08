<?php
/***************************************************************************
 * Autor: Gonzalo Andres Vergara Rojas,
 * Fecha: Diciembre 2009
 * Correo: webmaster@andresvergara.cl
 * @gnzvergara
 * www.andresvergara.cl
 * 
 * Panel de Exportacion PDF Curso
  - muestra las asignaturas que poseen los alumnos del profesor jefe
    auntentificado en el sistema.
 **************************************************************************/
 
include("configuracion.php");
$user = $_COOKIE['user'];

$query_datos = "SELECT title
				FROM course
				WHERE category_code = (SELECT Responsabilidad FROM user WHERE username = '$user')";
//echo $query_datos;			
$resultado = mysql_query($query_datos);
$tabla = '';
	
	while($row = mysql_fetch_assoc($resultado)) {
		
	  	
		  $tabla .= "<tr>";
		  $tabla .= "<td class=\"todo\"><input type=\"radio\" name=\"asignatura\" value=\"".$row['title']."\"></td>";
		  $tabla .= "<td class=\"todo\">".$row['title']."</td>";
		  $tabla .= "</tr>";

    }
	
	if($_POST['Lista_alumnos'])
	{
		if($_POST['asignatura']) {
			$asignatura = $_POST['asignatura'];
			header("Location: lista_curso_pdf.php?asignatura=$asignatura");
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

$plantilla->setTemplate('exportar_pdf_curso');
$plantilla->setVars(array(	
						"USER"			=>  $user,
						"TABLA"	        =>  $tabla));
echo $plantilla->show();

//Carga CSS
$plantilla->setPath('./css/');
$plantilla->setTemplate('predeterminado');
echo $plantilla->show();

?>