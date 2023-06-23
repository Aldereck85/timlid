function activityWatcher(){

    //The number of seconds that have passed
    //since the user was active.
    var secondsSinceLastActivity = 1;

    //Five minutes. 60 x 5 = 300 seconds.
    var maxInactivity = (60 * 3);

    //Setup the setInterval method to run
    //every second. 1000 milliseconds = 1 second.
    setInterval(function(){
        secondsSinceLastActivity = secondsSinceLastActivity + 30;
        //console.log(secondsSinceLastActivity + ' seconds since the user was last active');
        //if the user has been inactive or idle for longer
        //then the seconds specified in maxInactivity
        if(secondsSinceLastActivity > maxInactivity){
            /*console.log('User has been inactive for more than ' + maxInactivity + ' seconds');
            //Redirect them to your logout.php page.
            location.href = 'logout.php';*/
			
			
			 $.ajax({
				  url : rutare + "js/chat/functions/estadoUsuario.php",
				  type: "POST",
				  data : { "Estado" : 0 },
				  success: function(data,status,xhr)
				  {        

				  }
			 });
			
			
			
        }
		else{
			$.ajax({
				  url : rutare + "js/chat/functions/estadoUsuario.php",
				  type: "POST",
				  data : { "Estado" : 1 },
				  success: function(data,status,xhr)
				  {        

				  }
			 });
		}
    }, 30000);

    //The function that will be called whenever a user is active
    function activity(){
        //reset the secondsSinceLastActivity variable
        //back to 1
        secondsSinceLastActivity = 1;
    }

    //An array of DOM events that should be interpreted as
    //user activity.
    var activityEvents = [
        'mousedown', 'mousemove', 'keydown',
        'scroll', 'touchstart'
    ];

    //add these events to the document.
    //register the activity function as the listener parameter.
    activityEvents.forEach(function(eventName) {
        document.addEventListener(eventName, activity, true);
    });


}

activityWatcher();