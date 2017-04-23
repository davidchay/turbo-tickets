<?php
/*
Plugin Name: Turbo tickets
Plugin URI: @github
Description: Plugin para la gestion de tickets de soporte para turbo internet
Author: David Chay 
Author URI: https://github.com/davidchay
Version: 1.0
License: GPLv2
*/

/*
* Al activar el plugin lo primero que hace es llamar una funcion que crea las tablas
*/
register_activation_hook( __FILE__, 'turbotickets_create_tables' );


define('REPORTES','turbotickets_reportes',true);
define('SEGUIMIENTO','turbotickets_seguimiento',true);
define ('PATH','admin.php?page=turbotickets',true);
require_once( plugin_dir_path( __FILE__ ).'/functions.php' );
/*
* La funcion: turbotickets_create_tables(); 
* Crea dos tablas turbotickets_reportes y turbotickets_seguimiento 
*/
function turbotickets_create_tables()
{
	//obtenemos el objeto $wpdb
    global $wpdb;
    //el nombre de la tabla, utilizamos el prefijo de wordpress
    $table_name = $wpdb->prefix . REPORTES;
    //upgrade contiene la función dbDelta la cuál revisará si existe la tabla o no
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    //sql con el statement de la tabla
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id int(11) NOT NULL AUTO_INCREMENT,
      token char(25) NOT NULL,
      nombre char(60) NOT NULL ,
      colonia char(50) NOT NULL ,
      email char(60),
      telefono char(20),
      status char(20),
      asunto varchar(100) DEFAULT NULL,
      fecha datetime,
      UNIQUE KEY id (id)
    );";
    //creamos las tablas
    dbDelta($sql);
    //Segunda tabla
    $table_name = $wpdb->prefix . SEGUIMIENTO;
    $sql2 = "CREATE TABLE IF NOT EXISTS $table_name (
      id_ticket int(11) NOT NULL ,
      comentario varchar(500) DEFAULT NULL,
      autor varchar(7) DEFAULT NULL,
      media varchar(260) DEFAULT NULL, 
      fecha datetime
    );";
    //creamos las tablas
    dbDelta($sql2);
    if (!file_exists(plugin_dir_path( __FILE__ ).'MEDIA')) {
            mkdir(plugin_dir_path( __FILE__ ).'MEDIA', 0777, true);
            if (!file_exists(plugin_dir_path( __FILE__ ).'MEDIA/thumbs')) {
                    mkdir(plugin_dir_path( __FILE__ ).'MEDIA/thumbs', 0777, true);
            }
    }
}
/*
* Cuando se desactiva el plugin elimina las tablas 
*/
register_deactivation_hook(__FILE__, 'turbotickets_remove_tables' );
/*
* Esta funcion elimina las tablas [turbotickets_reportes,turbotickets_seguimiento]
*/
function turbotickets_remove_tables()
{
	//obtenemos el objeto $wpdb
    global $wpdb;
    //el nombre de la tabla, utilizamos el prefijo de wordpress
    $table_reportes = $wpdb->prefix . REPORTES;
    $table_seguimiento = $wpdb->prefix . SEGUIMIENTO;
    //sql con el statement de la tabla
    $sql = "DROP TABLE IF EXISTS $table_reportes, $table_seguimiento ";
    $wpdb->query($sql);
    rrmdir(plugin_dir_path( __FILE__ ).'MEDIA');
    
}
/*
* Agregar estilos personales solo en la pagina del plugin
*/
function load_turbotickets_admin($hook) {
    // Load only on ?page=mypluginname
    if($hook != 'toplevel_page_turbotickets') {
        return;
    }
    wp_enqueue_style( 'turbotickest_admin_magnific_popup', plugins_url('css/magnific-popup.css', __FILE__) );
    wp_enqueue_script( 'turbotickest_admin_magnific_popup', plugins_url('js/jquery.magnific-popup.min.js', __FILE__), array('jquery'), true );

    wp_enqueue_style( 'turbotickest_style', plugins_url('css/turbotickets-style.css', __FILE__) );
    wp_enqueue_script( 'turbotickest_admin_js', plugins_url('js/turbotickets-js.js', __FILE__), array('jquery'), true );
}
add_action( 'admin_enqueue_scripts', 'load_turbotickets_admin' );

function add_scripts() {
    wp_register_style( 'turbotickest_style', plugins_url('css/turbotickets-style.css', __FILE__) );
    wp_register_script( 'form-consulta-ticket', plugins_url('js/form-consulta-ticket.js', __FILE__), array('jquery'), true);
    
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );

/*
* Agregar el menu en el panel de opciones de wordpress
*/
add_action('admin_menu', 'turbotickets_setup_menu');
 //funcion que crea el menu
function turbotickets_setup_menu(){
       add_menu_page( 'Turbo Tickets', 'Tickets', 'administrator', 'turbotickets', 'turbotickets_init','dashicons-clipboard');
}
/*
* Esta funcion tiene todo el contenido de la pagina de administración del plugin
*/
function turbotickets_init(){
 ?>
    <div class="wrap">
        <?php
        /* optiene la opcion para mostrar la seccion correspondiente
        ** nuevo -> muestra formulario para crear un nuevo ticket
        ** seguimiento -> muestra los detalles de ese ticket
        ** si no hay obcion mostrara la tabla de registros por default
        */
        $titulo='Turbo Tickets';
        if(isset($_GET['op'])){
            if( $_GET['op'] === 'nuevo_ticket'){
                $titulo='Turbo Tickets: Agregar Ticket';
            }else if( $_GET['op'] ==='seguimiento'){
                $titulo='Turbo Tickets: Ver Seguimiento';
            }else if( $_GET['op'] === 'update' ){
                $titulo='Turbo Tickets: Actualizar información';
            }
             
        }
        ?>
        <!-- titulo del plugin -->

        <h1 class="wp-heading-inline"><?php echo $titulo; ?></h1>
        <!-- boton de ayuda alado del titulo -->
        <?php
            /*
            * si no existe ninguna opcion, imprimira el boton de Nuevo ticket
            */
        ?> 
            <a href="<?php echo PATH; ?>&op=nuevo_ticket" class="page-title-action">Nuevo ticket</a>
        <?php 
            /*
            * si la opcion es "nuevo" o "seguimiento" imprime un boton que 
            * redirecciona a la pagina/opcion principal
            */
            if(isset($_GET['op']))
            {
                $op=$_GET['op'];
                ?>
                <a href="<?php echo PATH; ?>" class="page-title-action">&#x2190; Regresar</a>
                <?php
            } else {
                $op=1; //Si no hay ninguna opcion seleda un valor a la variable por default
            }
        ?>
            <hr class="wp-header-end mb">

        <?php 
        /*
        * Aqui muestra las secciones segunsea el caso
        */
        switch($op){
            case 'nuevo_ticket':
                turbotickets_nuevo_ticket();
            break;
            case 'seguimiento':
                turbotickets_seguimiento_ticket();
            break;
            case 'update':
                turbotickets_update_ticket();
            break;
            default: 
                turbotickets_registros_ticket();
        }
      ?>
    </div><!-- ./wrap  -->
<?php
}
function turbotickets_registros_ticket(){
  global $wpdb;
  $table_reportes = $wpdb->prefix . REPORTES;
  $sql="SELECT * FROM $table_reportes ORDER BY id DESC";
  $result = $wpdb->get_results($sql);
  if(!$result){
    echo "No se encontrron Datos que mostrar.";
    return;
  }
?>
    <table class="wp-list-table widefat fixed striped pages ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Colonia</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Status</th>
                    <th>Fecha</th>
                    <th colspan="2" ><span class="d-block text-center">Opciones</span></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                foreach($result as $print){
            ?>
                <tr>
                    <td><?php echo $print->id; ?></td>
                    <td><?php echo $print->nombre; ?></td>
                    <td><?php echo $print->colonia; ?></td>
                    <td><?php echo $print->email; ?></td>
                    <td><?php echo $print->telefono; ?></td>
                    <td>
                        <span class="status  <?php echo strtolower($print->status); ?>">
                            <?php echo $print->status; ?>
                        </span>
                    </td>
                    <td><?php echo $print->fecha; ?></td>
                    <td>
                        <a href="<?php echo PATH; ?>&op=seguimiento&id=<?php echo $print->id; ?>" class="button-secondary" title="Ver seguimiento">Seguimiento</a> 
                     </td>
                     <td>   
                        <a href="<?php echo PATH; ?>&op=update&id=<?php echo $print->id; ?>" class="button-secondary">Update</a>
                    </td>
                </tr>  
            <?php
                }
            ?>              
            </tbody>
        </table>
<?php
}
function turbotickets_nuevo_ticket(){
    $err_g=0;
    $success=0;
    $nombre = (empty($_POST['nombre'])) ? '' : $_POST['nombre'];
    $colonia = (empty($_POST['colonia'])) ? '' : $_POST['colonia'];
    $telefono = (empty($_POST['telefono'])) ? '' : $_POST['telefono'];
    $email = (empty($_POST['email'])) ? '' : $_POST['email'];
    $asunto = (empty($_POST['asunto'])) ? '' : $_POST['asunto'];
    $mensaje = (empty($_POST['mensaje'])) ? '' : $_POST['mensaje'];
    if(isset($_GET['save'])){
        if(strlen($nombre)===0) {
            $nombre_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        } if(strlen($colonia)===0) {
            $colonia_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $email_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $telefono_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($asunto)===0){
            $asunto_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($mensaje)===0){
            $mensaje_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }
        if($err_g==0){
                
                global $wpdb;
                $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
                $token = substr($token, -12);
                $tabla_reportes = $wpdb->prefix . REPORTES;
                $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
                date_default_timezone_set("America/Mexico_City");
                $wpdb->insert(
                    $tabla_reportes,
                    array(
                        'token' => $token,
                        'nombre' => $_POST['nombre'],
                        'colonia'    => $_POST['colonia'],
                        'email' => $_POST['email'],
                        'telefono' => $_POST['telefono'],
                        'status' => 'Pendiente',
                        'asunto' => $_POST['asunto'],
                        'fecha' => current_time('mysql')
                    )
                );
                $id_nuevo = $wpdb->insert_id;
                $wpdb->insert(
                    $tabla_seguimiento,
                    array(
                        'id_ticket' => $id_nuevo,
                        'comentario' => $_POST['mensaje'],
                        'autor' => 'tecnico',
                        'fecha' => current_time('mysql')
                    )
                );
                
                if(!$wpdb->last_error){
                    $success=1;  
                     echo '<div class="notice notice-success is-dismissible">
                            <p>Datos guardados correctamente</p>
                        </div>';   
                }
        }else{
            echo '<div class="notice notice-error is-dismissible">
                    <p>Error los datos no se guardaron</p>
                </div>';
        }
    }
?>
    <form action="<?php echo PATH; ?>&op=nuevo_ticket&save=1" method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label class="mp6-primary">Nombre:</label></th>
                    <td>
                        <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required>
                        <?php if(isset($nombre_err)) echo $nombre_err; ?>
                    </td>
                </tr>            
                <tr>
                    <th><label for="colonia">Colonia:</label></th>
                    <td>
                        <input type="text" name="colonia" id="colonia" value="<?php echo $colonia; ?>" required>
                        <?php if(isset($colonia_err)) echo $colonia_err; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="email">Email:</label></th>
                    <td>
                        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
                        <?php if(isset($email_err)) echo $email_err; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="telefono">Teléfono:</label></th>
                    <td>
                        <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" required>
                        <?php if(isset($telefono_err)) echo $telefono_err;  ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="asunto">Asunto:</label></th>
                    <td>
                        <input type="text" name="asunto" id="asunto" value="<?php echo $asunto; ?>" required>
                        <?php if(isset($asunto_err)) echo $asunto_err; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="mensaje">Mensaje:</label></th>
                    <td>
                        <textarea name="mensaje" id="mensaje" col="" row="" required><?php echo $mensaje; ?></textarea>
                        <?php if(isset($mensaje_err)) echo $mensaje_err; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php   if(!$success){       ?>
                <input type="submit" value="Guardar" class="button-primary">
            <?php }?>
        </p>
    </form>
<?php
}
function turbotickets_seguimiento_ticket(){
    if(!isset($_GET['id'])) {
        return;
    } else {
        $id=$_GET['id'];
    }
    global $wpdb;
    $tabla_reportes = $wpdb->prefix . REPORTES;
    $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
    $comentario='';
    $error=0;
    if(isset($_POST['submit-status']) && !empty($_POST['submit-status'])  )
    {
        $status=$_POST['status'];
        $wpdb->update(
            $tabla_reportes,
            array(
                'status' => $status,
                'fecha' => current_time('mysql')
            ),
            array('id'=>$id)
        );   
        if(!$wpdb->last_error){
            echo '<div class="notice notice-success is-dismissible">
                    <p>El status a cambiado a <em>'.$status.'</em> </p>
                </div>';
        }
    }
    if(isset($_POST['submit']) && !empty($_POST['submit'])){
        $comentario = (empty($_POST['nuevo_mensaje'])) ? '' : $_POST['nuevo_mensaje'];
        if(isset($_FILES['media']) && !empty($_FILES['media']['name']))
        {
            $nombre_archivo = $_FILES['media']['name'];
            $tipo_archivo = $_FILES['media']['type'];
            $tamano_archivo = $_FILES['media']['size'];
            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "jpeg") ) && ($tamano_archivo < 2000000))) 
            {
                ?>

                <div class="notice notice-warning is-dismissible">
                    <p>
                    El archivo debe ser tipo .png .jpeg .jpg <br/> 
                    El archivo debe pesar menos de 2MB.
                    </p>
                </div>
                <?php 
                $error=1;
            }
        }
        if($error===0){
            $media='';
            if(isset($_FILES['media']) && !empty($_FILES['media']['name']))
            {
                $media=plugin_dir_path( __FILE__ ).'MEDIA/'.$nombre_archivo;
                $src_thumbs=plugin_dir_path( __FILE__ ).'MEDIA/thumbs/'.$nombre_archivo;
                move_uploaded_file($_FILES['media']['tmp_name'], $media);
                make_thumb( $media, $src_thumbs, 180);
                $media=$nombre_archivo;
            }
            $wpdb->insert(
                $tabla_seguimiento,
                array(
                    'id_ticket'=>$id,
                    'comentario' => $comentario,
                    'autor' => 'tecnico',
                    'media' => $media,
                    'fecha' => current_time('mysql')
                )
            );
            if(!$wpdb->last_error){
                echo '<div class="notice notice-success is-dismissible">
                        <p>Se agrego nuevo mensaje</p>
                    </div>';
                $comentario='';    
            }
        }
        
        
    }else if(isset($_POST['nuevo_mensaje']) && empty($_POST['nuevo_mensaje'])){
        echo '<div class="notice notice-warning is-dismissible">
                    <p>Mensaje no guardado. El mensaje no tenia contenido</p>
                </div>';
    }
    
    $sql="SELECT * FROM $tabla_reportes WHERE ID=$id";
    $result = $wpdb->get_results($sql);
    if(!$result){
        echo "No se encontraron Datos que mostrar.";
        return;
    }else{
        ?>
        <table class="wp-list-table widefat fixed striped pages ">
            <tbody>
                <?php foreach($result as $print){   ?>
                <tr>
                    <th><span class="mp6-primary">Nombre</span></th>
                    <td><?php echo $print->nombre; ?></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Colonia</span></th>
                    <td><?php echo $print->colonia; ?></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Email</span></th>
                    <td><?php echo $print->email; ?></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Teléfono</span></th>
                    <td><?php echo $print->telefono; ?></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Estatus<span></th>
                    <td><span class="status  <?php echo strtolower($print->status); ?>">
                            <?php $status=$print->status; echo $status;  ?>
                        </span></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Fecha<span></th>
                    <td><?php echo $print->fecha; $fecha_reporte=$print->fecha; ?></td>
                </tr>
                <tr>
                    <th><span class="mp6-primary">Asunto</span></th>
                    <td><?php echo $print->asunto; $asunto = $print->asunto; ?></td>
                </tr>
                
                <?php } ?>
            </tbody>
        </table>
        <?php  if($status!=='Resuelto'){  ?> 
        <hr>
        <p>
            <label><input type="checkbox" id="habilitar" > Editar Estatus</label>
            <form action="<?php echo PATH; ?>&op=seguimiento&id=<?php echo $id; ?>" method="POST" >
                <label for="status">Estatus: 
                    <select id="status" name="status" disabled>
                        <option value="Pendiente" <?php if($status==='Pendiente') echo 'selected' ?>>Pendiente</option>
                        <option value="Atendiendo" <?php if($status==='Atendiendo') echo 'selected' ?>>Atendiendo</option>
                        <option value="Resuelto" <?php if($status==='Resuelto') echo 'selected' ?>>Resuelto</option>
                    </select>
                </label>
                
                <input id="submitstatus" type="submit" class="button-secondary" name="submit-status" value="guardar" disabled>
            </form>
        </p>
        <?php } ?>
        <hr>
        <h2 class="lead"><?php if(isset($asunto)) echo $asunto; ?></h2>      
        <?php 
        $sql_msg="SELECT * FROM $tabla_seguimiento WHERE id_ticket = $id ";
        $mensajes = $wpdb->get_results($sql_msg);
        if($mensajes)
        {
            foreach($mensajes as $mensaje)
            {   
            ?>
                <div class="ttmsg-content <?php echo $mensaje->autor; ?>"> 
                    
                    
                    <small class="d-block <?php if($mensaje->autor!=='tecnico') echo 'text-right';  ?> ttat"> <?php echo $mensaje->fecha; ?></small>
                    <p class="ttmsg <?php echo $mensaje->autor; ?>">
                       <?php 
                       echo $mensaje->comentario; 
                       if(strlen($mensaje->media)>1){
                       ?>
                        <span class="d-block" style="margin-top:5px;">
                            <a href="<?php echo plugin_dir_url( __FILE__ ).'/MEDIA/'.$mensaje->media; ?>" class="image-link">
                                <img src="<?php echo plugin_dir_url( __FILE__ ).'/MEDIA/thumbs/'.$mensaje->media; ?>" />
                            </a>
                        </span>
                       <?php } ?>
                    </p>  
                </div>
                    
        <?php
            } //end foreach   
        } //end if
        ?>
                
        <div class="clearfix"></div>
        <hr>      
        <?php  if($status!=='Resuelto'){  ?> 
         
        <form  action="<?php echo PATH; ?>&op=seguimiento&id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" encoding="multipart/form-data" >
            <p>
                <label for="media" class="d-block">Agregar imagen <small>(Solo imagenes tipo: png,jpeg,jpg)</small></label>
                <input type="file" name="media" >
            </p>
            <?php 
            /*
            * Funcion para agregar timnce a un textarea
            */
            $content   = $comentario;
            $editor_id = 'nuevo_mensaje';
            $settings = array(
                'media_buttons' => false,
                'textarea_rows' => 3,
                'teeny'         => true,
            );
            wp_editor( $content, $editor_id, $settings );
            ?>
            <p>
            
                <input type="submit" class="button-primary" name="submit" value="Enviar">
            </p>
        </form>
        <?php   }       ?>
        <?php 
    }
}
function turbotickets_update_ticket(){
    if(!isset($_GET['id'])) {
        $id=0;
        return;
    } 
    $id=$_GET['id'];
    global $wpdb;
    $tabla_reportes = $wpdb->prefix . REPORTES;
    if(isset($_GET['save']) && !empty($_POST['actualizar']))
    {    
        $err_g=0;
        $nombre = (empty($_POST['nombre'])) ? '' : $_POST['nombre'];
        $colonia = (empty($_POST['colonia'])) ? '' : $_POST['colonia'];
        $telefono = (empty($_POST['telefono'])) ? '' : $_POST['telefono'];
        $email = (empty($_POST['email'])) ? '' : $_POST['email'];
        $asunto = (empty($_POST['asunto'])) ? '' : $_POST['asunto'];
        if(strlen($nombre)===0) {
            $nombre_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        } if(strlen($colonia)===0) {
            $colonia_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $email_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $telefono_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }if(strlen($asunto)===0){
            $asunto_err='<span class="error">Por favor llene este campo</span>';
            $err_g=$err_g+1;
        }
        if($err_g==0)
        {
                $wpdb->update(
                    $tabla_reportes,
                    array(
                        'nombre' => $_POST['nombre'],
                        'colonia' => $_POST['colonia'],
                        'email' => $_POST['email'],
                        'telefono' => $_POST['telefono'],
                        'asunto' => $_POST['asunto']
                    ),
                    array('id' => $id)
                );
                if(!$wpdb->last_error)
                {
                    echo '<div class="notice notice-success is-dismissible">
                                <p>Datos guardados correctamente</p>
                           </div>';      
                 }
        }
        else
        {
            echo '<div class="notice notice-error is-dismissible">
                    <p>Error los datos no se guardaron</p>
                </div>';
        }
       
       
          
    }
    else
    {
        $sql="SELECT * FROM $tabla_reportes WHERE ID=$id";
        $datos = $wpdb->get_results($sql);
        if(!$datos)
        {
            echo "No se encontraron Datos que mostrar.";
            return;
        }else
        {
            foreach($datos as $dato)
            {
                $nombre = $dato->nombre;
                $colonia = $dato->colonia;
                $telefono = $dato->telefono;
                $email = $dato->email;
                $asunto = $dato->asunto;
            }
        }         
    
    }
     if(isset($_GET['eliminar']) && !empty($_GET['eliminar'])){
         ?>
        <div class="notice notice-warning is-dismissible">
            <p>¿Esta realmente seguro de <strong>elimar</strong> este ticket? <em>Tome en cuenta que los datos no podran ser recuperados</em></p>
            <p>
                <a href="<?php echo PATH; ?>&op=update&deleteregister=1&id=<?php echo $id; ?>" class="button-secondary eliminar">Si, Eliminar</a>
                <a href="<?php echo PATH; ?>&op=update&id=<?php echo $id; ?>" class="button">Cancelar</a>
            </p>
        </div>
        <?php
        
     }
     if(isset($_GET['deleteregister']) && !empty($_GET['deleteregister']))
     {
            
            $wpdb->delete(
                $tabla_reportes,
                array('id' => $id)
            );
            if($wpdb->last_error)
            {
               return;        
            }
            $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
            $wpdb->delete(
                $tabla_seguimiento,
                array('id_ticket' => $id)
            );
            if($wpdb->last_error)
            {
                return;        
            }
            ?>
            <p> 
                Se eliminarón los datos.      <br>
                Se borro el seguimiento de este ticket satisfactoriamente.
            </p>
            <?php
            return;
     }
?>
    <form action="<?php echo PATH; ?>&op=update&save=1&id=<?php echo $id; ?>" method="POST" >
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label class="mp6-primary">Nombre:</label></th>
                    <td>
                        <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required>
                        <?php if(isset($nombre_err)) echo $nombre_err; ?>
                    </td>
                </tr>            
                <tr>
                    <th><label for="colonia">Colonia:</label></th>
                    <td>
                        <input type="text" name="colonia" id="colonia" value="<?php echo $colonia; ?>" required>
                        <?php if(isset($colonia_err)) echo $colonia_err; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="email">Email:</label></th>
                    <td>
                        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
                        <?php if(isset($email_err)) echo $email_err; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="telefono">Teléfono:</label></th>
                    <td>
                        <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" required>
                        <?php if(isset($telefono_err)) echo $telefono_err;  ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="asunto">Asunto:</label></th>
                    <td>
                        <input type="text" name="asunto" id="asunto" value="<?php echo $asunto; ?>" required>
                        <?php if(isset($asunto_err)) echo $asunto_err; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="submit">
                            <input type="hidden" name="actualizar" value="1">
                            <input type="submit" value="Actualizar" class="button-primary">
                         </p>
                    </td>
                    <td >
                        <a href="<?php echo PATH; ?>&op=update&eliminar=1&id=<?php echo $id; ?>" class="button-secondary eliminar">Eliminar</a> 
                    </td>
                     
                </tr>
            </tbody>
        </table>
        
       
    </form>
<?php
}



require_once( plugin_dir_path( __FILE__ ).'/shortcode_abrir_ticket.php' );
require_once( plugin_dir_path( __FILE__ ).'/shortcode_seguimiento_ticket.php' );
/**
CHECAR esta pagina
http://www.kminek.pl/lab/wordpress-contact-form-with-attachment-support/
**/


