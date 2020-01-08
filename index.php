<html>

<!--
// NL = PERIMETER ARMED - Get this when setting ground or first
// OG = OPEN AREA - Get this when unsetting ground or first
// OQ = REMOTE OPENING - Get this when unsetting ground or first
// CG = CLOSE AREA - Get this when setting whole alarm
// CQ = REMOTE CLOSING - Get this when unsetting whole alarm -->

<head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script type="text/javascript">

// WebSockets functions
var writeToScreen = function(message) {
    document.getElementById('websocket_area').innerHTML += message;
};

var websocket;

function ws_connect(){

    var url = 'ws://192.168.100.160:8088/ws/spc?username=ws_user&password=ws_pwd';
	websocket = new WebSocket(url);
	websocket.onmessage = function(ev) {
		// Parse received data
		const obj = JSON.parse(ev.data);
	  	writeToScreen(ev.data);
		var desc = obj.data.sia.description;
		var sia_code = obj.data.sia.sia_code;

		// If we get an Open Zone message, do something
		if (sia_code.includes("Z")) {

			if (desc.includes("Lounge")) {

				$.get( "switchon.php?action=overhead" );
				writeToScreen('Lounge overhead triggered<br />');

				$.get( "switchon.php?action=behindTV" );
				writeToScreen('Lounge behindTV triggered<br />');

				$.get( "database.php?table=activity&sensor=lounge");
		  	}
	  		else if (desc.includes("Kitchen")) {
				$.get( "database.php?table=activity&sensor=kitchen");
		  	}
		  	else if (desc.includes("Downstairs")) {
		  		
				$.get( "switchon.php?action=downstairshall1" );
				writeToScreen('downstairshall 1 triggered<br />');

				$.get( "switchon.php?action=downstairshall2" );
				writeToScreen('downstairshall 2 triggered<br />');

				$.get( "database.php?table=activity&sensor=downstairshall");
		  	}
		  	else if (desc.includes("Upstairs")) {
				$.get( "database.php?table=activity&sensor=upstairslanding");
		  	}
		  	else if (desc.includes("Garage")) {
				$.get( "database.php?table=activity&sensor=garage");
		  	}
		  	else if (desc.includes("Front")) {
				$.get( "database.php?table=activity&sensor=frontbedroom");
		  	}
		  	else if (desc.includes("Nursery")) {
				$.get( "database.php?table=activity&sensor=nursery");
		  	}

		}

		else if (sia_code.includes("NL")) {

			if (desc.includes("GROUND")) {
				$.get( "database.php?table=status&area=ground&status=1");
			}

		}

		else if (sia_code.includes("OG")) {

			if (desc.includes("GROUND")) {
				$.get( "database.php?table=status&area=ground&status=0");
			}

		}
	};
   
	websocket.onerror = function(ev) {
    	writeToScreen('<span style="color: red; ">ERROR: </span> ' + ev.data);
	};
};

ws_connect();

</script>

</head>


<body>
<div id="websocket_area">
</div>


</body>
</html>
