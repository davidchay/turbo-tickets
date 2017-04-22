<?php 
// Initialize Formulario de contratacion

//add_action('wp_head', 'form_contratacion_initialize');
// Create Slider
function form_nuevo_ticket() { 
    wp_enqueue_script('form-nuevo-ticket');
    wp_enqueue_style('form-turboticket-style');

require_once('recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "6LdhIR4UAAAAAPvCqEi0QHfNsScz8LZ6pY36qDJH";
$privatekey = "6LdhIR4UAAAAAEYQeqWhnn6IHHVbWO4MScOHKgXa";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
    
    $err_g=0;
    $nombre = (empty($_POST['nombre'])) ? '' : $_POST['nombre'];
    $colonia = (empty($_POST['colonia'])) ? '' : $_POST['colonia'];
    $telefono = (empty($_POST['telefono'])) ? '' : $_POST['telefono'];
    $email = (empty($_POST['email'])) ? '' : $_POST['email'];
    $asunto = (empty($_POST['asunto'])) ? '' : $_POST['asunto'];
    $mensaje = (empty($_POST['mensaje'])) ? '' : $_POST['mensaje'];
    $response_captcha= (empty($_POST["recaptcha_response_field"])) ? '' : $_POST["recaptcha_response_field"];
    if(isset($_POST['submit']) && !empty($_POST['submit'])){
 
        // Check if user answered the question
       
        if (strlen($response_captcha)===0) 
        {
         $err_g=$err_g+1;       
        }
       
        if(isset($_FILES['media']))
        {
            $nombre_archivo = $_FILES['media']['name'];
            $tipo_archivo = $_FILES['media']['type'];
            $tamano_archivo = $_FILES['media']['size'];
            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "jpeg") ) && ($tamano_archivo < 2000000))) 
            {
                ?>
                <div class="alert warning">
                 <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                 Solo imagenes de tipo  .png .jpeg  .jpg y no debe pesar más de 2MB.
                </div>
                <?php 
                $err_g=$err_g+1;
            }
        }
        if(strlen($nombre)===0) {
            $nombre_err='inputerror';
            $err_g=$err_g+1;
        } if(strlen($colonia)===0) {
            $colonia_err='inputerror';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $email_err='inputerror';
            $err_g=$err_g+1;
        }if(strlen($email)===0){
            $telefono_err='inputerror';
            $err_g=$err_g+1;
        }if(strlen($asunto)===0){
            $asunto_err='inputerror';
            $err_g=$err_g+1;
        }if(strlen($mensaje)===0){
            $mensaje_err='inputerror';
            $err_g=$err_g+1;
        }
        if($err_g==0)
        {       
                $resp = recaptcha_check_answer ($privatekey,
                                                $_SERVER["REMOTE_ADDR"],
                                                $_POST["recaptcha_challenge_field"],
                                                $_POST["recaptcha_response_field"]);

                if ($resp->is_valid) 
                {
                        $media='';
                        if(isset($_FILES['media'])){
                            
                            $media=plugin_dir_path( __FILE__ ).'MEDIA/'.$nombre_archivo;
                            $src_thumbs=plugin_dir_path( __FILE__ ).'MEDIA/thumbs/'.$nombre_archivo;
                            move_uploaded_file($_FILES['media']['tmp_name'], $media);
                            make_thumb( $media, $src_thumbs, 180);
                        }
                        global $wpdb;
                        $tabla_reportes = $wpdb->prefix . REPORTES;
                        $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
                        date_default_timezone_set("America/Mexico_City");
                        
                        $wpdb->insert(
                            $tabla_reportes,
                            array(
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
                                'autor' => 'cliente',
                                'media' => $nombre_archivo,
                                'fecha' => current_time('mysql')
                            )
                        );
                        
                        if(!$wpdb->last_error)
                        {
                            ?>
                            <div class="alert success">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                :) Gracias por usar nuestro sistema de tickets en breve uno de nuestros tecnicos se pondra en contacto con usted. 
                            </div>
                            <?php
                           
                            return;
                        
                        }
                } 
                else 
                {
                        # set the error code so that we can display it
                   ?>
                <div class="alert error">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    reCaptcha Error
                </div>
                <?php
                        $error = $resp->error;

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
    ?>
    <div id="contentturboticketform">
        <form action ="" method="POST" enctype="multipart/form-data" class="">
        <fieldset>
            
            <p>
                <label for="nombre">Nombre*</label>
                <input type="text" class="<?php if(isset($nombre_err)) echo $nombre_err;?>" name="nombre" value="<?php echo $nombre ?>">
            </p>
            <p>
                <label for="colonia">Colonia *</label>
                <input type="text" class="<?php if(isset($colonia_err)) echo $colonia_err;?>" name="colonia" value="<?php echo $colonia; ?>">
            </p>
            <p>
                <label for="email">Email *</label>
                <input type="email" class="<?php if(isset($email_err)) echo $email_err;?>" name="email" value="<?php echo $email; ?>" >
            </p>
            <p>
                <label for="telefono">Telefono *</label>
                <input type="tel" class="<?php if(isset($telefono_err)) echo $telefono_err;?>" name="telefono" value="<?php echo $telefono; ?>">
            </p>
            <p>
                <label for="asunto">Asunto *</label>
                <input type="text" name="asunto" class="<?php if(isset($asunto_err)) echo $asunto_err;?>"  value="<?php echo $asunto; ?>">
            </p>
            
            <p>
                <label for="mensaje">Mensaje</label>
                <textarea name="mensaje" class="<?php if(isset($mensaje_err)) echo $mensaje_err;?>" ><?php echo $mensaje; ?></textarea>
            </p>
            <p>
                <label for="media">Imagen (.png,.jpeg,.jpg)</label>
                <input type="file" name="media">
            </p>
            
        </fieldset>
  		<?php
          echo recaptcha_get_html($publickey, $error);
        ?>
  
        <input type="submit" name="submit" value="Crear ticket">
        </form>
    </div>   
<?php 
    }


// Slider Shortcode
 
    function form_nuevo_ticket_shortcode() {
        ob_start();
        form_nuevo_ticket();
        $form = ob_get_clean();
        return $form;
    }
    add_shortcode( 'form-nuevo-ticket', 'form_nuevo_ticket_shortcode' );