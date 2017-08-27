<?
	class ReadFhemDB extends IPSModule {
		
		public function Create() {
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyInteger("SourceVariable", 0);
			$this->RegisterPropertyString("host",		"192.168.178.63");
			$this->RegisterPropertyString("port",		"3306");
			$this->RegisterPropertyString("user",		"fhem");
			$this->RegisterPropertyString("database",	"fhem");
			$this->RegisterPropertyString("password",	"geheim");

			$this->RegisterPropertyString("device",	"Terrarium.Sensor2");
			$this->RegisterPropertyString("reading1",	"temperature");
			$this->RegisterPropertyString("reading2",	"humidity");
			$this->RegisterPropertyString("reading3",	"dewpoint");

			$this->RegisterVariableFloat("value1",		"Value No 1", "", 1);
			$this->RegisterVariableFloat("value2",		"Value No 2", "", 2);
			$this->RegisterVariableFloat("value3",		"Value No 3", "", 3);
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
			$port     = $this->ReadPropertyString("port");
			$user     = $this->ReadPropertyString("user");
			$database = $this->ReadPropertyString("database");
			$password = $this->ReadPropertyString("password");
			$device   = $this->ReadPropertyString("device");
			$reading1 = $this->ReadPropertyString("reading1");
			$reading2 = $this->ReadPropertyString("reading2");
			$reading3 = $this->ReadPropertyString("reading3");


			$con = mysqli_connect($host, $user, $password, $database);
			$output = "";
			$strSQL = "SELECT * FROM current WHERE DEVICE = '" . addslashes($device) . "' AND (READING = '" . addslashes($reading1) . "' OR READING = '" . addslashes($reading2) . "' OR READING = '" . addslashes($reading3) . "') ORDER BY TIMESTAMP DESC";
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
