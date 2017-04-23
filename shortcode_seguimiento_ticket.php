<?php 
// Initialize Formulario de contratacion

//add_action('wp_head', 'form_contratacion_initialize');
// Create Slider
function form_seguimiento_ticket() { 
    wp_enqueue_script('form-nuevo-ticket');
    wp_enqueue_style( 'turbotickest_style');
    $result = '';
    $err_g=0;
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
            global $wpdb;
            $tabla_reportes = $wpdb->prefix . REPORTES;
            $tabla_seguimiento = $wpdb->prefix . SEGUIMIENTO;
            $sql="SELECT * FROM $tabla_reportes WHERE email = '$email' AND token = '$token' ";
            $result = $wpdb->get_row($sql);
            if($result)
            {
                ?>
                Nombre: <?php echo $result->nombre; ?><br/>
                Colonia: <?php echo $result->colonia; ?><br/>
                Email: <?php echo $result->email; ?><br/>
                Telefono: <?php echo $result->telefono; ?><br/>
                Estatus: <?php echo $result->status; ?><br/>
                Asunto: <?php echo $result->asunto; ?><br/>
                Fecha: <?php echo $result->fecha; ?><br/>
                Token: <?php echo $result->token; ?><br/>
                
                
                <?php
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
    if(empty($result)){
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
}


// Slider Shortcode
 
    function form_seguimiento_ticket_shortcode() {
        ob_start();
        form_seguimiento_ticket();
        $form = ob_get_clean();
        return $form;
    }
    add_shortcode( 'form-seguimiento-ticket', 'form_seguimiento_ticket_shortcode' );