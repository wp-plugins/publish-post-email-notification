<?php

 /* 
    Plugin Name: Send email notification to author when post is published
    Plugin URI: http://my-php-scripts.net
    Description: Send email notification to author when post is published
    Author: Nik Gandhi <webmaster@my-php-scripts.net>
    Version:1.0
    Author URI: http://www.postfreeadvertising.com/
*/  

  add_action('admin_menu', 'load_submenu');
   
  add_action('publish_post',    'send_email_notification');  
  wp_enqueue_script('jquery');  

  function load_submenu(){
  
        add_submenu_page( 'options-general.php', 'Publish post notification options', 'Publish post email template', 'manage_options', 'manage_publish_post_notification_options', 'manage_publish_post_notification_options_func' ); 
  }
  
  
  function send_email_notification($post_ID){
      
      $meta_values = get_post_meta($post_ID, 'is_notified', true);
       
      if($meta_values!='yes'){
       
          $pub_post = get_post($post_ID);
          $author_id=$pub_post->post_author;
          $post_title=$pub_post->post_title;
          $postperma=get_permalink( $post_ID );
          $user_info = get_userdata($author_id);
          
          $usernameauth=$user_info->user_login;
          $user_nicename=$user_info->user_nicename;
          $user_email=$user_info->user_email;
          $first_name=$user_info->user_firstname;
          $last_name=$user_info->user_lastname;
          
          $blog_title = get_bloginfo('name');
          $siteurl=get_bloginfo('wpurl');  
          $siteurlhtml="<a href='$siteurl' target='_blank' >$siteurl</a>";
         
         
         
          $publish_post_notification_settings=get_option('publish_post_notification_settings');  
     
          $subject=$publish_post_notification_settings['subject'];
          $from_name=$publish_post_notification_settings['from_name'];
          $from_email=$publish_post_notification_settings['from_email'];
          $emailBody=$publish_post_notification_settings['emailBody'];
          $emailBody=stripslashes($emailBody);
          $emailBody=str_replace('[username]',$usernameauth,$emailBody); 
          $emailBody=str_replace('[user_login]',$usernameauth,$emailBody); 
          $emailBody=str_replace('[user_nicename]',$user_nicename,$emailBody); 
          $emailBody=str_replace('[user_email]',$user_email,$emailBody); 
          $emailBody=str_replace('[first_name]',$first_name,$emailBody); 
          $emailBody=str_replace('[last_name]',$last_name,$emailBody); 
          
          $emailBody=str_replace('[published_post_link_plain]',$postperma,$emailBody); 
          
          $postlinkhtml="<a href='$postperma' target='_blank'>$postperma</a>";
          
          $emailBody=str_replace('[published_post_link_html]',$postlinkhtml,$emailBody); 
          
          $emailBody=str_replace('[published_post_title]',$post_title,$emailBody); 
          $emailBody=str_replace('[site_name]',$blog_title,$emailBody); 
          $emailBody=str_replace('[site_url]',$siteurl,$emailBody); 
          $emailBody=str_replace('[site_url_html]',$siteurlhtml,$emailBody); 
         
          $emailBody=stripslashes(htmlspecialchars_decode($emailBody));
          
          $mailheaders .= "MIME-Version: 1.0\n";
          $mailheaders .= "X-Priority: 1\n";
          $mailheaders .= "Content-Type: text/html; charset=\"UTF-8\"\n";
          $mailheaders .= "Content-Transfer-Encoding: 7bit\n\n";
          $mailheaders .= "From: $from_name <$from_email>" . "\r\n";
          $message='<html><head></head><body>'.$emailBody.'</body></html>';
          $Rreturns=wp_mail($user_email, $subject, $message, $mailheaders);
          
          if($Rreturns){
            add_post_meta($post_ID, 'is_notified', 'yes');
          } 
      }
  }

  function manage_publish_post_notification_options_func(){
  
  if(isset($_POST['savesettings'])){
  
        $subject=$_POST['email_subject'];
        $from_name=$_POST['email_From_name'];
        $from_email=$_POST['email_From'];
         $emailBody=$_POST['txtArea'];
        if(function_exists('get_magic_quotes_gpc')){
            if(get_magic_quotes_gpc()){
              $emailBody=addslashes($emailBody);  
            }
        }
        
        $emailBody=htmlentities($emailBody);
        

        $publish_post_notification_settings=array('subject'=>$subject,'from_name'=>$from_name,'from_email'=>$from_email,'emailBody'=>$emailBody);
        update_option('publish_post_notification_settings',$publish_post_notification_settings); 
        $publish_post_notification_settings=get_option('publish_post_notification_settings');
  
  ?>
  
  <div id='succMsg'>Settings updated successfully</div>
 <?php 
  }
 else{
 
     $publish_post_notification_settings=get_option('publish_post_notification_settings');
     
     
     
 } 
?>  
<table><tr><td><a href="https://twitter.com/FreeAdsPost" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @FreeAdsPost</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>
<td>
<a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=nik_gandhi007@yahoo.com&amp;item_name=Publish Post Email Notification&amp;item_number=publish post notification support&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">
<img id="help us for free plugin" height="30" width="90" src="http://www.postfreeadvertising.com/images/paypaldonate.jpg" border="0" alt="help us for free plugin" title="help us for free plugin">
</a>
</td>
</tr>
</table>
<h3>Post notification email template settings</h3>  
   <?php  $url = plugin_dir_url(__FILE__);
       $urlJS=$url."js/jqueryValidate.js";
       $urlCss=$url."styles.css";
 ?>
     <script src="<?php echo $urlJS; ?>"></script>
    
     <link rel='stylesheet' href='<?php echo $urlCss; ?>' type='text/css' media='all' />
 <div style="width: 100%;">  
     <div style="float:left;width:69%;" >
      
        <form name="publishpostfrm" id='publishpostfrm' method="post" action=""> 
        <table class="form-table" style="" >
        <tbody>
          <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right;">Subject *</th>
             <td>    
                <input type="text" id="email_subject" name="email_subject" value="<?php echo $publish_post_notification_settings['subject'];?>"  class="valid" size="70">
                <div style="clear: both;"></div><div></div>
              </td>
           </tr>
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right">Email From Name*</th>
             <td>    
                <input type="text" id="email_From_name" name="email_From_name"  value="<?php echo $publish_post_notification_settings['from_name'];?>" class="valid" size="70">
                 <br/>(ex. admin)  
                <div style="clear: both;"></div><div></div>
               
              </td>
           </tr>
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right">Email From *</th>
             <td>    
                <input type="text" id="email_From" name="email_From" value="<?php echo $publish_post_notification_settings['from_email'];?>"  class="valid" size="70">
                <br/>(ex. admin@yoursite.com) 
                <div style="clear: both;"></div><div></div>
          
              </td>
           </tr>
          
           <tr valign="top" id="subject">
             <th scope="row" style="width:30%;text-align: right">Email Body *</th>
              <?php
                   
                    $emailBody=stripslashes($publish_post_notification_settings['emailBody']);  
                    $emailBody=html_entity_decode($emailBody);
               ?>

             <td>    
               <div class="wrap">
                <textarea id="txtArea"  cols="100" rows="8" name="txtArea" class="ckeditor"><?php echo $emailBody;?></textarea>
                 <div style="clear: both;"></div><div></div> 
                </div>
                <span>you can use [username] , [user_login] , [user_nicename] , [user_email] , [first_name] , [last_name] ,[published_post_link_html] , [published_post_link_plain] ,
                    [published_post_title] , [site_name] , [site_url],[site_url_html] place holder into email body</span>   
              </td>
           </tr>
           
              <tr valign="top" id="subject">
             <th scope="row" style="width:30%"></th>
             <td> 
               
               <input type='submit'  value='Save Settings' name='savesettings' class='button-primary' id='savesettings' >  
              </td>
           </tr>
           
        </table>
        </form>
     </div>
     <div id="poststuff" class="metabox-holder has-right-sidebar" style="float:right;width:30%;"> 
             <div class="postbox"> 
              <h3 class="hndle"><span></span>Recommended WordPress Hosting</h3> 
              <div class="inside">
                   <center><a href="http://www.justhost.com/track/jh50601/CODE4" target="_top"><img border="0" src="http://img.justhost.com/300x250/jh_300x250_us_01.gif" /></a></center>

                  <div style="margin:10px 5px">
          
                  </div>
          </div></div>
           
           <div class="postbox"> 
              <h3 class="hndle"><span></span>Recommended WordPress Themes</h3> 
              <div class="inside">
                   <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/300x250.gif" width="300" height="250"></a></center>

                  <div style="margin:10px 5px">
          
                  </div>
          </div></div>
           
           
           </div>   
 </div>   

    <script type="text/javascript">


     jQuery(document).ready(function() {

     

       jQuery("#publishpostfrm").validate({
                        errorClass: "error_admin_massemail",
                        rules: {
                                     email_subject: { 
                                            required: true
                                      },
                                      email_From_name: { 
                                            required: true
                                      },  
                                      email_From: { 
                                            required: true ,email:true
                                      }, 
                                     txtArea:{
                                        required: true
                                     }  
                                
                           }, 
          
                                errorPlacement: function(error, element) {
                                error.appendTo( element.next().next());
                          }
                          
                     });
                          

      });
     
     </script> 


<?php  
  }

?>