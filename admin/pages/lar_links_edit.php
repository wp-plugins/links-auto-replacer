<?php

global $wpdb;
 $lar_links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'lar_links',ARRAY_A);
$link = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'lar_links WHERE id='.$_REQUEST['link_id'],ARRAY_A);
  ?>

<div id='lar_main_wrap'>

 <h1>Edit Link</h1>


<div id="lar_add_links_form">
    <form action="<?php echo admin_url('admin.php?page=lar_links_manager&link_id='.$_REQUEST['link_id'].'&noheader=true'); ?>" method='post'>
    <table cellspacing="5" cellpadding="3" class="widefat fixed">
       <thead>
          <tr>
            <th><?php echo __('Keyword','lar-links-auto-replacer'); ?></th>
            <th style="width:280px"> <?php echo __('URL (Link)','lar-links-auto-replacer'); ?></th>
            <th style="width: 66px;"><?php echo __('Dofollow?','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Open in','lar-links-auto-replacer'); ?></th>
            <th style="width: 45px;"><?php echo __('Cloack','lar-links-auto-replacer'); ?></th>
            <th ><?php echo __('Slug','lar-links-auto-replacer'); ?></th>
            <th style="width: 75px;"></th>
          </tr>
          
        </thead>
      <tr>
 
        <td>
          
          
         <input type="text" id="keyword" class="widefat" name="keyword" value="<?php echo $link['keyword']; ?>" />
         
        </td>

        <td>
          
         <input type="url" id="keyword_url" class="widefat"  name="keyword_url" value="<?php echo $link['keyword_url']; ?>" placeholder="http://" />
         

        </td>

        <td>
          
           
         <input type="checkbox" name="dofollow" <?php if($link['dofollow']==1){ echo 'checked'; } ?> />

        </td>
        <td>
         
         <select name="target" class="widefat">
         <option <?php if($link['open_in']=='_self'){echo 'selected'; } ?> value="_self">Same Window</option>
            <option <?php if($link['open_in']=='_blank'){echo 'selected'; } ?> value="_blank">New Window</option>
            

         </select>
        </td>
        <td>
          
         
         <input id="cloack" name="cloack" type="checkbox" <?php if($link['cloack']==1){ echo 'checked'; } ?> />

        </td>

        <td >
             
         <input type="text" class="widefat" value="<?php echo $link['slug'] ?>" id="lar_slug" name="slug" <?php if($link['cloack']!=1){ echo 'disabled'; } ?> placeholder='slug' />

        </td>

        <td>
          

         <input type="submit" name="submit" id="submit" class="button button-primary" value="Edit Link">
    
        </td>
      </tr>
    </table>
   
    
     
      
    </form>





</div>

</div>


<script>
  jQuery(document).ready(function(){

    var slugs = [];
    var keywords = [];
    var links = [];
     <?php foreach ($lar_links as $link):
        if($link['id'] == $_GET['link_id']) continue;
      ?>
          slugs.push( '<?php echo $link['slug']; ?>');
          keywords.push( '<?php echo $link['keyword']; ?>');
          links.push( '<?php echo $link['keyword_url']; ?>');
     <?php endforeach; ?>
    jQuery("#submit").click(function(){

      if(jQuery('#keyword').val() == ''){
        jQuery.notify("You must provide a keyword!",{ globalPosition:"top center",className:'error'});
        return false;
      }
      if(!is_valid_url(jQuery('#keyword_url').val())){
         jQuery.notify("You must provide a valid URL!",{ globalPosition:"top center",className:'error'});
        return false;
      }
      if(jQuery('#cloack').is(':checked') && jQuery('#lar_slug').val()==''){
        jQuery.notify("You must provide a slug in order to cloack the URL!",{ globalPosition:"top center",className:'error'});
        return false;

      }

      if(jQuery("#lar_slug").val()!='' && jQuery('#cloack').is(':checked')){
        if(slugs.indexOf(jQuery("#lar_slug").val()) != -1){
            jQuery.notify(jQuery("#lar_slug").val()+" is exist as a slug, the slug must be unique!",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }

      if(jQuery('#keyword').val()!=''){
        if(keywords.indexOf(jQuery("#keyword").val()) != -1){
            jQuery.notify(jQuery("#keyword").val()+" is exist as a keyword, the keyword must be unique!",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }

      if(jQuery('#keyword_url').val()!=''){
        if(links.indexOf(jQuery("#keyword_url").val()) != -1){
            jQuery.notify("The URL is exist, it must be unique!",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }



      
    });
    jQuery("#cloack").click(function(){
        
        var attr = jQuery('#lar_slug').attr('disabled');
        if (typeof attr !== typeof undefined && attr !== false) {
              jQuery('#lar_slug').removeAttr('disabled');
        }else{
              
              jQuery('#lar_slug').attr('disabled','disabled');
              jQuery('#lar_slug').val('');
        }
        
    });

    <?php if ( isset($_REQUEST['success']) && $_REQUEST['success'] == 'true' ) { ?>

 jQuery.notify("Your link has been added successfully!!",{ globalPosition:"top center",className:'success'});
      <?php } ?>


  });


 

  function is_valid_url(url)
{
    
  
    var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;

        if (!myRegExp.test(url)){
          return false;
        }else{
          return true;
        }

}
</script>