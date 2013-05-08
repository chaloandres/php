<?php
/*************************************************************************** 
 * Autor: Gonzalo Andres Vergara Rojas,
 * Fecha: Diciembre 2009
 * Correo: webmaster@andresvergara.cl
 * @gnzvergara
 * www.andresvergara.cl
 *
 * Pagina principal mopdulo de notas.
  - Realiza la autentificacion de usuario independiente si es profesor u
    alumno.
  - En caso de ser profesor envia al panel del profesor con sus asignaturas
    correspondientes, en el caso de ser profesor jefe realiza lo mismo, pero
	agrega las funciones de exportar a pdf.
 - En caso de ser alumno solo posibilita la opcion de ver sus notas.
 **************************************************************************/

include("configuracion.php");

//Si es redireccionado ala index y existe la autentificacion.
$estado = $_COOKIE['estado'];
$user 	= $_COOKIE['user'];
if($user != "")
{	
	//Profesor ya auntentificado
	if($estado == 1)
	{
		$responsabilidad = $_COOKIE['responsabilidad'];
		if($responsabilidad == '3ro' || $responsabilidad == '4to')
		{
			header("Location: principal_profesor_jefe.php");
		}
		else
		{
			header("Location: principal_profesor.php");
		}
	}
	//Alumno o Apoderado ya auntentificado
	elseif($estado == 5 || $estado == 6)
	{
		header("Location: ver_notas_alumno.php");
	}
}


//Ingresa nombre y contrase�a para autentificacion. 
if($_POST['Ingresar'])
{
	    //Consulta
		$query_datos = "select * from user where username = '" . $_POST["user"] . "' and password='" . md5($_POST["pass"]) . "'";	
		$resultado = mysql_query($query_datos);
		$usuario_encontrado = mysql_fetch_object($resultado);
		
		//Comprueba si el usuario existe y la contrase�a es correcta.
		if($_POST["user"] == $usuario_encontrado->username && md5($_POST["pass"]) == $usuario_encontrado->password)
		{
		    //Cookie de Seguridad para comprobacion posterior.
			setcookie("user",$_POST['user']);
			setcookie("estado",$usuario_encontrado->status);
			setcookie("responsabilidad",$usuario_encontrado->Responsabilidad);
			//Verifica si el usuario es profesor(5) o alumno(1) 
			if($usuario_encontrado->status == 1)
			{
				//Si el usuario es el administrador
				if($usuario_encontrado->username == "admin")
				{
					header("Location: registro_apoderado.php");
				}
				//Si el profesor se encuentra en la BD y si ademas es Profesor Jefe o "Responsable" de un curso.
				elseif($usuario_encontrado->Responsabilidad == '3ro' || $usuario_encontrado->Responsabilidad == '4to')
				{
					header("Location: principal_profesor_jefe.php");
				}
				else 
				{
					header("Location: principal_profesor.php");
				}
			}
			//Si el usuario es un apoderado
			elseif($usuario_encontrado->status == 6)
			{
				header("Location: ver_notas_alumno.php");
			}
			//Si el usuario es un alumno
			elseif($usuario_encontrado->status == 5)
			{
				header("Location: ver_notas_alumno.php");
			}
			//No encontro estatus 5 ni 1 , ni profesor ni alumno
			else
			{
				header("Location: prohibido.html");
			}
		}
		//No encuentra ningun usuario.
		else
		{
			echo "<SCRIPT LANGUAGE=\"JavaScript\"> alert('Usuario o Contrase�a incorrecto.') </SCRIPT>";
		}
}
	include ("Template.php");

	//Carga Plantilla
	$plantilla 			= new Template();
	$plantilla->setPath('./plantillas/');

	$plantilla->setTemplate('index');
	echo $plantilla->show();

	//Carga CSS
	$plantilla->setPath('./css/');
	$plantilla->setTemplate('predeterminado');
	echo $plantilla->show();

?>