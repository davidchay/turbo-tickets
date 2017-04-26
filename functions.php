<?php

function send_email($author,$email,$subject,$message)
{
    $admin_mail=get_option( 'admin_email' );
    $to=array($admin_mail,$email);
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From:Turbo Internet <no-reply@turbointernet.com.mx>');
    wp_mail( $to, $subject, $message, $headers );
    
}

function email_template_nuevo($nombre,$asunto,$mensaje,$telefono,$email,$colonia,$token){
    $html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>A Simple Responsive HTML Email</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
  .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; color: #FAFAFA; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td bgcolor="#00389E" class="header">
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <a href="http://www.turbointernet.com.mx/">
                    <img class="fix" src="'.plugin_dir_url( __FILE__ ).'img/turbo-logo.png" width="70" height="70" border="0" alt="" />
                </a>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
            <tr>
              <td height="70">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      SOPORTE
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      TURBO INTERNET 
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="h2">
                Hola, '.$nombre.'
              </td>
            </tr>
            <tr>
              <td class="bodycopy">
                  Hemos recibido información de un nuevo Ticket de soporte.<br>
                  
                  <table class="" style="margin:25px 0;"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align:right;color:#818181">Asunto: </td>
                            <td style="font-weight:700;">&nbsp; '.$asunto.'</td>
                        </tr>
                        <tr>
                           <td  style="text-align:right;color:#818181">Mensaje: </td>
                            <td style="font-weight:700;">&nbsp; '.$mensaje.'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;color:#818181">Colonia: </td>
                            <td style="font-weight:700;">&nbsp; '.$colonia.'</td>
                        </tr>
                        <tr>
                            <td style="text-align:right;color:#818181">Teléfono: </td>
                            <td style="font-weight:700;">&nbsp; '.$telefono.'</td>
                        </tr>
                        <tr>
                           <td style="text-align:right;color:#818181">Email: </td>
                            <td style="font-weight:700;">&nbsp; '.$email.'</td>
                        </tr>
                      </table>

                      En breve uno de nustros tecnicos se pondra en contacto con usted.
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="115" style="padding: 0 20px 20px 0;">
                <img class="fix" src="'.plugin_dir_url( __FILE__ ).'img/soporte.png" width="115" height="115" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">  
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy">
                        Para ver el seguimiento del ticket debes ingresar en la sección <em>seguimiento de ticket</em> con los siguientes datos.
                    </td>
                  </tr>
                  <tr>
                    <td style="padding: 20px 0 0 0;">
                         
                      <table class="buttonwrapper"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td>
                                <strong>Email: </strong>'.$email.'<br>
                                <strong>Token: </strong>'.$token.'
                                
                            </td>
                          
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      
      <tr>
        <td class="innerpadding bodycopy">
          Gracias por su preferencia.
        </td>
      </tr>
      <tr>
        <td class="footer" bgcolor="#626262">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="footercopy">
                &reg; Turbo Internet, 2017<br/>
                
                <span class="hide">Tu conexión al mundo</span>
              </td>
            </tr>
            <tr>
              <td align="center" style="padding: 20px 0 0 0;">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="37" style="text-align: center; padding: 0 10px 0 10px;">
                      <a href="https://www.facebook.com/TurboInternetTapachula/">
                        <img src="'.plugin_dir_url( __FILE__ ).'img/facebook.png" width="37" height="37" alt="Facebook" border="0" />
                      </a>
                    </td>
                    
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</table>
</body>
</html>
';
return $html;
}


function email_template_seguimiento($nombre,$mensaje,$author,$email,$token){
    $html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>A Simple Responsive HTML Email</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
  .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; color: #FAFAFA; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td bgcolor="#00389E" class="header">
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <a href="http://www.turbointernet.com.mx/">
                    <img class="fix" src="'.plugin_dir_url( __FILE__ ).'img/turbo-logo.png" width="70" height="70" border="0" alt="" />
                </a>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">  
            <tr>
              <td height="70">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      SOPORTE
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      TURBO INTERNET 
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="h2">
                Hola, '.$nombre.'
              </td>
            </tr>
            <tr>
              <td class="bodycopy">
                  Se agrego un nuevo mensaje al ticket: <strong><em>'.$token.'.</em></strong>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="115" style="padding: 0 20px 20px 0;">
                <img class="fix" src="'.plugin_dir_url( __FILE__ ).'img/info.png" width="115" height="115" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">  
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy">
                        <strong>'.$author.'</strong> <em>Escribio...</em>
                        <p style="font-weight:300;font-style: oblique;color:gray">
                            '.$mensaje.'
                        </p>
                    </td>
                  </tr>
                  
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      
        
    <tr>
        <td class="innerpadding borderbottom">
          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="115" style="padding: 0 20px 20px 0;">
                <img class="fix" src="'.plugin_dir_url( __FILE__ ).'img/soporte.png" width="115" height="115" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">  
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy">
                        Para ver o responder a este ticket, por favor ingresa en la sección <em>seguimiento de ticket</em> con los siguientes datos.
                    </td>
                  </tr>
                  <tr>
                    <td style="padding: 20px 0 0 0;">
                         
                      <table class="buttonwrapper"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td>
                                <strong>Email: </strong>'.$email.'<br>
                                <strong>Token: </strong>'.$token.'
                                
                            </td>
                          
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
          
        
        
      <tr>
        <td class="innerpadding bodycopy">
          Gracias por su preferencia.
        </td>
      </tr>
      <tr>
        <td class="footer" bgcolor="#626262">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="footercopy">
                &reg; Turbo Internet, 2017<br/>
                
                <span class="hide">Tu conexión al mundo</span>
              </td>
            </tr>
            <tr>
              <td align="center" style="padding: 20px 0 0 0;">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="37" style="text-align: center; padding: 0 10px 0 10px;">
                      <a href="https://www.facebook.com/TurboInternetTapachula/">
                        <img src="'.plugin_dir_url( __FILE__ ).'img/facebook.png" width="37" height="37" alt="Facebook" border="0" />
                      </a>
                    </td>
                    
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</table>
</body>
</html>
';
return $html;
}



function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
    $ext = strtolower(substr($src, -3));
    switch ($ext){
        case 'png':
            $source_image = imagecreatefrompng($src);
        break;
        case 'jpg':
        case 'peg':
            $source_image = imagecreatefromjpeg($src);
        break;
        
        case 'gif':
            $source_image = imagecreatefromgif($src);
        break;
    }
	//$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	//imagejpeg($virtual_image, $dest);
    switch($ext){
        case 'png':
            imagepng($virtual_image, $dest);
        break;
        case 'jpg':
        case 'peg':
            imagejpeg($virtual_image, $dest);
        break;
        case 'gif':
            imagegif($virtual_image, $dest);
        break;
    }
}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

function friendly_date($date_in){
    //2017 - 04 - 24
    //El 24 de abril del 2017 
    $fecha = explode(' ',$date_in);
    $date=explode('-',$fecha[0]);
    $date_out=$date[2].' de ';
    switch($date[1])
    {
        case '01':  $date_out.='enero';     break;
        case '02':  $date_out.='febrero';   break;
        case '03':  $date_out.='marzo';     break;
        case '04':  $date_out.='abril';     break;
        case '05':  $date_out.='mayo';      break;
        case '06':  $date_out.='junio';     break;
        case '07':  $date_out.='julio';     break;
        case '08':  $date_out.='agosto';    break;
        case '09':  $date_out.='septiembre';break;
        case '10':  $date_out.='octubre';   break;
        case '11':  $date_out.='noviembre'; break;
        case '12':  $date_out.='diciembre'; break;
    }
    $date_out.=' del '.$date[0].', a las '.substr($fecha[1], 0, 5).'.';
    return $date_out;
}