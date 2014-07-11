

    <div class="container">      
      <form id="form-signin" class="form-signin" role="form" method="post" action="/v1/ajax/login">
        <h2 class="form-signin-heading">Please sign in</h2>        
	    <div id="msg-error" class="alert alert-info" role="alert" style="display:none;"></div>
	    <div id="msg-success" class="alert alert-success" role="alert" style="display:none;"></div>
        <input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> 

    
    <script type="text/javascript">
    (function() {

    	$('#form-signin').ajaxForm({
    		beforeSerialize : function(){
    		
    		},
    		beforeSend : function() {
    			$('.modal').fadeIn(10);
    		},
    		success: function(){
    			$('.modal').fadeOut(10);
    		},
    		complete : function(xhr) {
    			$('#msg-error').hide();
    			$('#msg-success').hide();
    			if(xhr.responseText.trim().length>0){
    				var json = $.parseJSON(xhr.responseText);
    				if(json.success){
    					$('#msg-success').html(json.success);
    	    			$('#msg-success').fadeIn(10);
    					window.location.assign("/v1/account/index");					
    				}
    				if(json.error){
    					$('#msg-error').html(json.error);
    	    			$('#msg-error').fadeIn(10)
    				}	
    			}
    			$('.modal').fadeOut(10);
    		}
    	});

    })();
    </script>