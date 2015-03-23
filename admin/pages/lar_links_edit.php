<?php

global $wpdb;
 $lar_links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'lar_links',ARRAY_A);
$link = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'lar_links WHERE id='.$_REQUEST['link_id'],ARRAY_A);
  ?>
<?php include 'lar_menu.php'; ?>
<div id='lar_main_wrap'>

 <h1><?php echo __('Edit Link','lar-links-auto-replacer'); ?></h1>


<div id="lar_add_links_form">
    <form action="<?php echo admin_url('admin.php?page=lar_links_manager&link_id='.$_REQUEST['link_id'].'&noheader=true'); ?>" method='post'>
   
   
    
     
      <table id="lar_add_link_table" cellspacing="5" cellpadding="3" class="widefat fixed">
       
      <tr>
        <td style="width:100px;"><?php echo __('Keyword/s','lar-links-auto-replacer'); ?></td>
        <td>
        
        <select  class="keyword widefat" name="keyword" multiple="multiple">
            <?php foreach(explode(',',$link['keyword']) as $keyword): ?>
                <option value="<?php echo  $keyword; ?>" data-select2-tag="true"><?php echo  $keyword; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" value="<?php echo $link['keyword']; ?>" id="keywords" name="keywords" />
          <p>You can use one keyword or multiple keywords separated by comma (,) </p>
          
        </td>
      </tr>

      <tr>
          <td><?php echo __('URL (Link)','lar-links-auto-replacer'); ?></td>
        <td><input type="url" id="keyword_url"   name="keyword_url" value="<?php echo $link['keyword_url']; ?>" placeholder="http://" /></td>
      </tr>


      <tr>
        <td><?php echo __('Dofollow?','lar-links-auto-replacer'); ?>
        <!-- <img id="dofollow-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->
          
        </td>
        <td><input type="checkbox" name="dofollow"  <?php if($link['dofollow']==1){ echo 'checked'; } ?> />
        <p><?php echo __('if you checked this option, you will allow search engines to follow this link and use it in ranking.','lar-links-auto-replacer'); ?></p>
        </td>
      </tr>

      <tr>

          <td><?php echo __('Open in','lar-links-auto-replacer'); ?></td>
          <td>
             <select name="target" >
                <option <?php if($link['open_in']=='_self'){echo 'selected'; } ?> value="_self"><?php echo __('Same Window','lar-links-auto-replacer'); ?></option>
                <option <?php if($link['open_in']=='_blank'){echo 'selected'; } ?> value="_blank"><?php echo __('New Window','lar-links-auto-replacer'); ?></option>
            </select>
          </td>

      </tr>




        <tr>
            <td><?php echo __('Shrink','lar-links-auto-replacer'); ?>?
            <!-- <img id="cloak-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->
            </td>
            <td><input id="cloack" name="cloack" type="checkbox" <?php if($link['cloack']==1){ echo 'checked'; } ?> />
              <p><?php echo __('The link will be shortened (e.g example.com/go/amazon)','lar-links-auto-replacer'); ?></p>
            </td>
        </tr>

      <tr>  
          <td><?php echo __('Slug','lar-links-auto-replacer'); ?> 
              <!-- <img id="slug-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->

          </td>

          <td ><input type="text" <?php if($link['cloack']!=1){ echo 'disabled'; } ?> value="<?php echo $link['slug'] ?>" id="lar_slug" name="slug" disabled="" placeholder='slug' />
                  <p><?php echo __('The slug for the shortened link','lar-links-auto-replacer'); ?> <span id="lar_slug_example"><?php if($link['slug']!=''){ echo home_url().'/go/'.$link['slug'];  }?></span></p>
          </td>
      </tr>



      <tr>
            <td><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Edit Link','lar-links-auto-replacer'); ?>"></td>
            <td></td>
        </tr>
      
    </table>
    </form>





</div>

</div>


<script>
  jQuery(document).ready(function(){
    jQuery('.keyword').select2({
       tags: true,
       tokenSeparators: [',']
       
    });
    var keywords_val = [<?php foreach(explode(',',$link['keyword']) as $keyword): ?><?php echo '"'.$keyword.'",'; ?><?php endforeach; ?>];
    jQuery(".keyword").select2("val",keywords_val);
    jQuery('.keyword').on("change", function(e){
        
            jQuery('#keywords').val(jQuery(".keyword").select2("val"));
          

    });


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
        jQuery.notify("<?php echo __('You must provide a keyword!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
        return false;
      }
      if(!is_valid_url(jQuery('#keyword_url').val())){
         jQuery.notify("<?php echo __('You must provide a valid URL!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
        return false;
      }
      if(jQuery('#cloack').is(':checked') && jQuery('#lar_slug').val()==''){
        jQuery.notify("<?php echo __('You must provide a slug in order to shrink the URL!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
        return false;

      }

      if(jQuery("#lar_slug").val()!='' && jQuery('#cloack').is(':checked')){
        if(slugs.indexOf(jQuery("#lar_slug").val()) != -1){
            jQuery.notify(jQuery("#lar_slug").val()+" <?php echo __('is exist as a slug, the slug must be unique!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }

      if(jQuery('#keyword').val()!=''){
        if(keywords.indexOf(jQuery("#keyword").val()) != -1){
            jQuery.notify(jQuery("#keyword").val()+" <?php echo __('is exist as a keyword, the keyword must be unique!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }

      if(jQuery('#keyword_url').val()!=''){
        if(links.indexOf(jQuery("#keyword_url").val()) != -1){
            jQuery.notify("<?php echo __('The URL is exist, it must be unique!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
            return false;
        }
      }



      
    });
    jQuery("#cloack").click(function(){
        
        var attr = jQuery('#lar_slug').attr('disabled');
        if (typeof attr !== typeof undefined && attr !== false) {
              jQuery('#lar_slug').removeAttr('disabled');
              jQuery('#lar_slug').val('<?php echo $last_link_id; ?>'); 
              jQuery("#lar_slug").trigger('change');
        }else{
              
              jQuery('#lar_slug').attr('disabled','disabled');
              jQuery('#lar_slug').val('');
        }
        
    });

    jQuery("#lar_slug").bind('change paste keyup', function(){
          if(jQuery(this).val()!=''){
            jQuery("#lar_slug_example").html('<?php echo home_url(); ?>/go/' + jQuery(this).val());
          }else{
            jQuery("#lar_slug_example").html('');
          }
    });

    <?php if ( isset($_REQUEST['success']) && $_REQUEST['success'] == 'true' ) { ?>

 jQuery.notify("<?php echo __('Your link has been added successfully!!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'success'});
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