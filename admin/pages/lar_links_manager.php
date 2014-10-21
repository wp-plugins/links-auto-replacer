<?php

global $wpdb;
 $lar_links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'lar_links',ARRAY_A); ?>

<div id='lar_main_wrap'>

 <h1><?php echo __('Manage Your Links','lar-links-auto-replacer'); ?></h1>

<h2 class="lar_subheading">New Link</h2>
<div id="lar_add_links_form">
    <form action="<?php echo admin_url('admin.php?page=lar_links_manager&noheader=true'); ?>" method='post'>
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
          
          
         <input type="text" id="keyword" class="widefat" name="keyword" value="" />
         
        </td>

        <td>
          
         <input type="url" id="keyword_url" class="widefat"  name="keyword_url" value="" placeholder="http://" />
         

        </td>

        <td>
          
           
         <input type="checkbox" name="dofollow" value="1" />

        </td>
        <td>
         
         <select name="target" class="widefat">
         <option selected value="_self">Same Window</option>
            <option value="_blank">New Window</option>
            

         </select>
        </td>
        <td>
          
         
         <input id="cloack" name="cloack" type="checkbox" value="1" />

        </td>

        <td >
             
         <input type="text" class="widefat" value="" id="lar_slug" name="slug" disabled="" placeholder='slug' />

        </td>

        <td>
          

         <input type="submit" name="submit" id="submit" class="button button-primary" value="Add Link">
    
        </td>
      </tr>
    </table>
   
    
     
      
    </form>



<h2 class="lar_subheading"><?php echo __('Your Links', 'lar-links-auto-replacer'); ?></h2>

<table class="widefat fixed">
  
  <thead>
    
    <tr>
            <th style="width:20px;">#</th>
            <th><?php echo __('Keyword','lar-links-auto-replacer'); ?></th>
            <th style="width:310px"> <?php echo __('URL (Link)','lar-links-auto-replacer'); ?></th>
            <th style="width: 65px;"><?php echo __('Dofollow?','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Open in','lar-links-auto-replacer'); ?></th>
            <th style="width:50px"><?php echo __('Cloack','lar-links-auto-replacer'); ?></th>
            <th><?php echo __('Slug','lar-links-auto-replacer'); ?></th>
            <th></th>
          </tr>
  </thead>

  <?php foreach ($lar_links as $link): ?>
            <tr id="link_row_<?php echo $link['id']; ?>">
                <td><?php echo $link['id']; ?></td>
                <td><?php echo $link['keyword']; ?></td>
                <td><a href="<?php echo $link['keyword_url']; ?>" target="_blank"><?php echo $link['keyword_url']; ?></a></td>
                <td><?php echo ($link['dofollow']==1)?'Yes':'No'; ?></td>
                <td><?php echo ($link['open_in'] == '_blank')?'New Window':'Same Window'; ?></td>
                <td><?php echo ($link['cloack']==1)?'Yes':'No'; ?></td>
                <td><?php echo $link['slug']; ?></td>
                <td><a href="<?php echo admin_url('admin.php?page=lar_links_manager&link_id='.$link['id']); ?>" class="lar_green">Edit</a> | <a href="javascript:void(0)" onclick="delete_link('<?php echo $link['id']; ?>')" class="lar_red">Delete</a></td>
            </tr>
  <?php endforeach; ?>
</table>

</div>

</div>


<script>
  jQuery(document).ready(function(){

    var slugs = [];
    var keywords = [];
    var links = [];
     <?php foreach ($lar_links as $l): 
            
      ?>
          slugs.push( '<?php echo $l['slug']; ?>');
          keywords.push( '<?php echo $l['keyword']; ?>');
          links.push( '<?php echo $l['keyword_url']; ?>');
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


 <?php if ( isset($_REQUEST['edited']) && $_REQUEST['edited'] == 'true' ) { ?>

 jQuery.notify("Your link has been edited successfully!!",{ globalPosition:"top center",className:'success'});
      <?php } ?>
      


  });


  function delete_link(id){
    if(window.confirm('Are you sure?')){
              var data = {
              'action': 'delete_link',
              'link_id': id
            };

            
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