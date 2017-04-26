<?php 
// Initialize Formulario de contratacion

//add_action('wp_head', 'form_contratacion_initialize');
// Create Slider
function form_seguimiento_ticket() { 
    wp_enqueue_script('form-ticket');
    wp_enqueue_style( 'turbotickest_style');
    wp_enqueue_style( 'turbotickest_admin_magnific_popup');
    wp_enqueue_script( 'turbotickest_admin_magnific_popup');

    global $wpdb;
    $tabla_reportes = $wpdb->prefix . REPORTES;
    $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
    $result = '';
    $err_g=0;
    $comentario='';
    $error=0;
    $token = (empty($_POST['token'])) ? '' : $_POST['token'];
    $email = (empty($_POST['email'])) ? '' : $_POST['email'];
    if(isset($_POST['submit_seguimiento']) && !empty($_POST['submit_seguimiento'])){
        if(strlen($email)===0){
            $email_err='inputerror';
            $err_g=$err_g+1;
        }if(strlen($token)===0){
            $token_err='inputerror';
            $err_g=$err_g+1;
        }
        if($err_g==0)
        {    
            if(isset($_POST['nuevo_mensaje']) && !empty($_POST['nuevo_mensaje']))
            {
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
                if($error===0)
                {
                    $media='';
                    $sql="SELECT id FROM $tabla_reportes WHERE email = '$email' AND token = '$token' ";
                    $id = $wpdb->get_var($sql);
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
                            'autor' => 'cliente',
                            'media' => $media,
                            'fecha' => current_time('mysql')
                        )
                    );
                    if(!$wpdb->last_error){
                        $sql="SELECT nombre,email,token FROM $tabla_reportes WHERE ID=$id";
                        $data = $wpdb->get_row($sql);
                        $email_body=email_template_seguimiento($data->nombre,$comentario,$data->nombre,$data->email,$data->token);
                        $asunto='Turbo Internet Tapachula. Ticket de soporte: '.$data->token;
                        send_email($data->nombre,$data->email,$asunto,$email_body);
                        ?>
                        <div class="alert success">
                            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                            Se agrego nuevo mensaje
                        </div>

                        <?php
                        $comentario='';    
                    }
                }
                else{
                    ?>
                    <div class="alert error">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        Mensaje no guardado. El mensaje no tenia contenido
                    </div>
                    <?php
                }
            }
            $sql="SELECT * FROM $tabla_reportes WHERE email = '$email' AND token = '$token' ";
            $result = $wpdb->get_row($sql);
            $status='Resuelto';
            if($result)
            {
                $id=$result->id;
                ?>
               <p class="lead"> Hola, <?php echo $result->nombre; ?>.</p>
               <p>
                    Acontinuación te presentamos el seguimiento del ticket 
                    que abriste el <?php echo friendly_date($result->fecha); ?>
                </p>
                <h3>Datos de contacto.</h3> 
                <p>
                    Email: <?php echo $result->email; ?><br/>
                    Teléfono: <?php echo $result->telefono; ?>
                </p>
                <h3>Seguimiento.</h3>
                <p>
                    Estatus: <?php $status=$result->status; echo $status; ?>
                </p>
                <p class="lead"><?php echo $result->asunto; ?></p>
                <?php
                $sql_msg="SELECT * FROM $tabla_seguimiento WHERE id_ticket = $id ";
                $mensajes = $wpdb->get_results($sql_msg);
                if($mensajes)
                {
                    foreach($mensajes as $mensaje)
                    {   
                        ?>
                        <div class="ttmsg-content <?php echo $mensaje->autor; ?>">
                            <span class="d-block text-light <?php if($mensaje->autor!=='cliente') echo 'text-right';  ?>"><?php 
                            echo ($mensaje->autor == 'cliente') ? $result->nombre : 'Técnico' ; 
                            ?></span> 
                           
                            <p class="ttmsg <?php echo $mensaje->autor; ?>">
                            <?php 
                                echo $mensaje->comentario; 
                                if(strlen($mensaje->media)>1)
                                {
                                ?>
                                <span class="d-block" style="margin-top:5px;">
                                    <a href="<?php echo plugin_dir_url( __FILE__ ).'/MEDIA/'.$mensaje->media; ?>" class="image-link">
                                        <img src="<?php echo plugin_dir_url( __FILE__ ).'/MEDIA/thumbs/'.$mensaje->media; ?>" />
                                    </a>
                                </span>
                                <?php 
                                } 
                                ?>
                            </p>  
                             <small class="d-block <?php if($mensaje->autor!=='tecnico') echo 'text-right';  ?> ttat"> <?php echo friendly_date($mensaje->fecha); ?></small>
                        </div>
                    <?php
                    } //end foreach   
                } //end if mensajes
                
                       
            } 
            else
            {
                ?>
                <div class="alert error">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    No se encontro ningun ticket con estos datos. 
                    Asegurese de ingresar los datos correctos o que el ticket aun siga abierto.
                </div>
                <?php
            } 
      
        }// cierra if(g_error)
        else
        {
        ?>
            <div class="alert warning">
                 <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                Por favor completa el formulario para que pueda ser procesado.
            </div>
        <?php
        }
    }
    
    if(empty($result))
    {
    ?>
    <div>
        <form action ="" method="POST">
            <fieldset>
                <p>
                    <label for="email">Email</label>
                    <input type="email" class="<?php if(isset($email_err)) echo $email_err;?>" name="email" value="<?php echo $email; ?>" >
                </p>
                <p>
                    <label for="token">Token</label>
                    <input type="text" class="<?php if(isset($token_err)) echo $token_err;?>" name="token" value="<?php echo $token; ?>">
                </p>
            </fieldset>
            
  	        <input type="submit" name="submit_seguimiento" value="Seguimiento" style="margin-top:15px;">
        </form>
    </div>   
    <?php 
    }
    else if($status!=='Resuelto')
    {
      ?>
        <div class="clearfix"></div>
        <hr>      
        <form  action="" method="POST" enctype="multipart/form-data" encoding="multipart/form-data" >
            <p>
                <label for="media" class="d-block">Agregar imagen <small>(Solo imagenes tipo: png,jpeg,jpg)</small></label>
                <input type="file" name="media" >
            </p>
            <?php 
            /*
            * Funcion para agregar timnce a un textarea
            */
            $content   = '';
            $editor_id = 'nuevo_mensaje';
            $settings = array(
                'media_buttons' => false,
                'textarea_rows' => 3,
                'teeny'         => true,
            );
            wp_editor( $content, $editor_id, $settings );
            ?>
            <p>
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>">    
                <input type="submit" class="button-primary" name="submit_seguimiento" value="Enviar">
            </p>
        </form>

        <form  action="" method="POST">
           <p>
                <input type="hidden" name="email" value="0">
                <input type="hidden" name="token" value="0">    
                <input type="hidden" name="nuevo_mensaje" value="0">    
                <input type="submit" class="button-secondary" name="submit_seguimiento" value="Salir">
            </p>
        </form>
        <?php
    }else{
        ?>
        <form  action="" method="POST">
           <p>
                <input type="hidden" name="email" value="0">
                <input type="hidden" name="token" value="0">    
                <input type="hidden" name="nuevo_mensaje" value="0">    
                <input type="submit" class="button-secondary" name="submit_seguimiento" value="Salir">
            </p>
        </form>
        <?php
    }
    
}


// Slider Shortcode
 
    function form_seguimiento_ticket_shortcode() {
        ob_start();
        form_seguimiento_ticket();
        $form = ob_get_clean();
        return $form;
    }
    add_shortcode( 'form-seguimiento-ticket', 'form_seguimiento_ticket_shortcode' );