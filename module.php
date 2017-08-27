
<?
	class ReadFhemDB extends IPSModule {
		
		public function Create() {
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyInteger("SourceVariable", 0);
			$this->RegisterPropertyString("host",		"192.168.178.77");
			//$this->RegisterPropertyString("port",		"port");
			$this->RegisterPropertyString("user",		"fhem");
			$this->RegisterPropertyString("database",	"fhem");
			$this->RegisterPropertyString("password",	"geheim");
			$this->RegisterPropertyString("device",		"Terrarium.Sensor2");
			$this->RegisterPropertyString("reading",	"temperature");
			$this->RegisterVariableFloat("Value",		"Value", "", 0);
		}
	
		public function ApplyChanges() {
			
			//Never delete this line!
			parent::ApplyChanges();
			
			
			
		}
	
		
		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * RFHEM_MeineErsteEigeneFunktion($id);
        *
        */

		public function MeineErsteEigeneFunktion() {
            echo $this->InstanceID;

			$host     = $this->ReadPropertyString("host");
			//$port     = $this->ReadPropertyString("port");
			$user     = $this->ReadPropertyString("user");
			$database = $this->ReadPropertyString("database");
			$password = $this->ReadPropertyString("password");
			$device   = $this->ReadPropertyString("device");
			$reading  = $this->ReadPropertyString("reading");


			$con = mysqli_connect($host, $user, $password, $database);

			$output = "";
			$strSQL = "SELECT * FROM current WHERE DEVICE = '" . addslashes($device) . "' AND READING = '" . addslashes($reading) . "' ORDER BY TIMESTAMP DESC LIMIT 1";

			$query = mysqli_query($con, $strSQL);
			while($result = mysqli_fetch_array($query)){

				// this is only for testing, no logical background ;)
				if($output != "") $output .= ", ";
				$output .= '"'.$result['READING'].'" : "'.htmlspecialchars($result['VALUE']).'"';
			}

			echo "{\"" . $device . "\":{" . $output . "}";
			mysqli_close($con);
        }
	
	}
?>
