<?php

global $wpdb;
 $lar_links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'lar_links ORDER by id DESC',ARRAY_A); ?>
<?php include_once 'lar_menu.php'; ?>
<div id='lar_main_wrap'>

 <h1><?php echo __('Manage Your Links','lar-links-auto-replacer'); ?></h1>



<h2 class="lar_subheading"><?php echo __('Your Links', 'lar-links-auto-replacer'); ?></h2>

<table class="widefat fixed">
  
  <thead>
    
    <tr>
            <th style="width:20px;">#</th>
            <th><?php echo __('Keyword/s','lar-links-auto-replacer'); ?></th>
            <th style="width:310px"> <?php echo __('URL (Link)','lar-links-auto-replacer'); ?></th>
            <th style="width: 65px;"><?php echo __('Dofollow?','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Open in','lar-links-auto-replacer'); ?></th>
            <th style="width:50px"><?php echo __('Shrink?','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Slug','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Case Sensitive?','lar-links-auto-replacer'); ?></th>
            <th></th>
          </tr>
  </thead>
  <?php if(!empty($lar_links)): ?>
      <?php foreach ($lar_links as $link): ?>
                <tr id="link_row_<?php echo $link['id']; ?>">
                    <td><?php echo $link['id']; ?></td>
                    <td id="keywords_<?php echo $link['id']; ?>"><?php echo stripslashes($link['keyword']); ?></td>
                    <td><a id="link_<?php echo $link['id']; ?>" href="<?php echo $link['keyword_url']; ?>" target="_blank"><?php echo $link['keyword_url']; ?></a></td>
                    <td><?php echo ($link['dofollow']==1)?'Yes':'No'; ?></td>
                    <td><?php echo ($link['open_in'] == '_blank')?'New Window':'Same Window'; ?></td>
                    <td><?php echo ($link['cloack']==1)?'Yes':'No'; ?></td>
                    <td id="slug_<?php echo $link['id']; ?>"><?php echo $link['slug']; ?></td>
                    <td><?php echo ($link['is_sensitive']==1)?'Yes':'No'; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=lar_links_manager&link_id='.$link['id']); ?>" class="lar_green"><?php echo __('Edit','lar-links-auto-replacer'); ?></a> | <a href="javascript:void(0)" onclick="delete_link('<?php echo $link['id']; ?>')" class="lar_red"><?php echo __('Delete','lar-links-auto-replacer'); ?></a></td>
                </tr>
      
         <?php endforeach; ?>
     <?php else: ?>
          <tr><td colspan="8"><?php _e('No links found.','lar-links-auto-replacer'); ?></td></tr>
     <?php endif; ?> 
</table>



<h2 class="lar_subheading"><?php echo __('New Link','lar-links-auto-replacer'); ?></h2>
<div id="lar_add_links_form">
    <form action="<?php echo admin_url('admin.php?page=lar_links_manager&noheader=true'); ?>" method='post'>

   

     <table id="lar_add_link_table" cellspacing="5" cellpadding="3" class="widefat fixed">
       
      <tr>
        <td style="width:100px;"><?php echo __('Keyword/s','lar-links-auto-replacer'); ?></td>
        <td>
        
        <select  class="keyword widefat" name="keyword" multiple="multiple">
            
        </select>
        <input type="hidden" value="" id="keywords" name="keywords" />
          <p>You can use one keyword or multiple keywords separated by comma (,) </p>
          
        </td>
      </tr>

      <tr>
          <td><?php echo __('URL (Link)','lar-links-auto-replacer'); ?></td>
        <td><input type="url" id="keyword_url"   name="keyword_url" value="" placeholder="http://" /></td>
      </tr>


      <tr>
        <td><?php echo __('Dofollow?','lar-links-auto-replacer'); ?>
        <!-- <img id="dofollow-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->
          
        </td>
        <td><input type="checkbox" name="dofollow" value="1" />
        <p><?php echo __('if you checked this option, you will allow search engines to follow this link and use it in ranking.','lar-links-auto-replacer'); ?></p>
        </td>
      </tr>

      <tr>

          <td><?php echo __('Open in','lar-links-auto-replacer'); ?></td>
          <td>
             <select name="target" >
                <option selected value="_self"><?php echo __('Same Window','lar-links-auto-replacer'); ?></option>
                <option value="_blank"><?php echo __('New Window','lar-links-auto-replacer'); ?></option>
              </select>
          </td>

      </tr>




        <tr>
            <td><?php echo __('Shrink','lar-links-auto-replacer'); ?>?
            <!-- <img id="cloak-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->
            </td>
            <td><input id="cloack" name="cloack" type="checkbox" value="1" />
              <p><?php echo __('The link will be shortened (e.g example.com/go/amazon)','lar-links-auto-replacer'); ?></p>
            </td>
        </tr>

      <tr>  
          <td><?php echo __('Slug','lar-links-auto-replacer'); ?> 
              <!-- <img id="slug-info" src="<?php echo  plugins_url( '../images/info.png' , __FILE__ ); ?>"/> -->

          </td>

          <td ><input type="text"  value="" id="lar_slug" name="slug" disabled="" placeholder='slug' />
                  <p><?php echo __('The slug for the shortened link','lar-links-auto-replacer'); ?> <span id="lar_slug_example"></span></p>
          </td>
      </tr>


      <tr>
            <td><?php echo __('Case Sensitive?','lar-links-auto-replacer'); ?></td>
            <td><input id="is_sensitive" name="is_sensitive" type="checkbox" value="1" />
              <p><?php echo __('If you checked this option, the plugin will replace the keywords exactly according to the letters case.','lar-links-auto-replacer'); ?></p>
            </td>
        </tr>



      <tr>
            <td><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Add Link','lar-links-auto-replacer'); ?>"></td>
            <td></td>
        </tr>
      
    </table>
      
    </form>





</div>

</div>


<script>
  var slugs = [];
  var added_keywords = [];
  var links = [];

  jQuery(document).ready(function(){
     
    jQuery('.keyword').select2({
       tags: true,
       tokenSeparators: [',']
       
    });


    jQuery('.keyword').on("change", function(e){
        
            jQuery('#keywords').val(jQuery(".keyword").select2("val"));
          

    });

    jQuery("#slug-info").hover(function(){
                
                jQuery(this).notify(
                  
                  "<?php echo __('The slug for the shortened link','lar-links-auto-replacer'); ?>", 
                     
                  { position:"top center", className: "info"}
                );

    }).mouseleave(function(){
        jQuery('.notifyjs-wrapper').trigger('notify-hide');
    });


    jQuery("#cloak-info").hover(function(){
                jQuery(this).notify(
                    "<?php echo __('The link will be shortened (e.g example.com/go/amazon)','lar-links-auto-replacer'); ?>",
                  { position:"top center", className: "info"}
                );
    }).mouseleave(function(){
        jQuery('.notifyjs-wrapper').trigger('notify-hide');
    });


    jQuery("#dofollow-info").hover(function(){
                jQuery(this).notify(
                    "<?php echo __('if you checked this, you will allow search engines to follow this link and use it in ranking.','lar-links-auto-replacer'); ?>", 
                  { position:"top center", className: "info"}
                );
    }).mouseleave(function(){
        jQuery('.notifyjs-wrapper').trigger('notify-hide');
    });

    
     <?php foreach ($lar_links as $l): ?>

          <?php if($l['slug'] != ''): ?>
                slugs.push( '<?php echo $l['slug']; ?>');
          <?php endif; ?>

          <?php if($l['keyword_url'] != ''): ?>
               links.push( '<?php echo $l['keyword_url']; ?>');
          <?php endif; ?>

          <?php foreach(explode(',',$l['keyword']) as $keyword): ?>
                    added_keywords.push( '<?php echo $keyword; ?>');
          <?php endforeach; ?>
          
     <?php endforeach; ?>

    jQuery("#submit").click(function(){

      if(jQuery("#keywords").val() == ''){
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

      if(jQuery("#keywords").val() !=''){
        var user_keywords  = jQuery("#keywords").val().split(',');
        for (index = 0; index < user_keywords.length; ++index) {
            if(added_keywords.indexOf(user_keywords[index]) != -1){
              jQuery.notify(user_keywords[index]+" <?php echo __('is exist as a keyword, the keyword must be unique!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'error'});
              return false;
            }
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
              jQuery("#lar_slug_example").html('');
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


 <?php if ( isset($_REQUEST['edited']) && $_REQUEST['edited'] == 'true' ) { ?>

 jQuery.notify("<?php echo __('Your link has been edited successfully!!','lar-links-auto-replacer'); ?>",{ globalPosition:"top center",className:'success'});
      <?php } ?>
      

  });


  function delete_link(id){
    if(window.confirm('Are you sure?')){
              var data = {
              'action': 'delete_link',
              'link_id': id
            };
           
            var link_keywords = jQuery('#keywords_' + id).html().split(',');
            var link_url = jQuery('#link_' + id).html();
            var link_slug = jQuery('#slug_' + id).html();
            console.log(links);
            console.log(slugs);
            for(index =0; index < link_keywords.length; index++){
                added_keywords.splice(added_keywords.indexOf(link_keywords[index]),1);
            }
            links.splice(links.indexOf(link_url),1);
            slugs.splice(slugs.indexOf(link_slug),1);
            console.log(links);
            console.log(slugs);
            
            jQuery.post(ajaxurl, data, function(response) {
              jQuery("#link_row_"+id).css('background','red');
              jQuery("#link_row_"+id).hide('slow');
            });
        }
  }

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