// delete a document
function del_element(id)
    { var tmp = 'Are you sure you want to delete this Document?';
      if (confirm(tmp))
         { setTimeout(function() {  
              $.post(SITE+"ajaxfiles/deldoc_profile.php", {id : id},    
              function(data)
                 { if(data['error'])
                     { alert(data['error']);
                     }
                   else
                     { $('#doc_'+id).fadeOut(1000);
                     }                     
                 }, "json"); 
           }, 0 );  
         }
      else
         return false;
 }