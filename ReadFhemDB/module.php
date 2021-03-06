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
        * RFHEM_ReadValuesFromDB($id);
        *
        */
		public function readValuesFromDB() {
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

			$redQuery = $this->mergeReadingQuery("",        $reading1);
			$redQuery = $this->mergeReadingQuery($redQuery, $reading2);
			$redQuery = $this->mergeReadingQuery($redQuery, $reading3);

			$con = mysqli_connect($host, $user, $password, $database);
			$output = "";
			$strSQL = "SELECT * FROM current WHERE DEVICE = '" . addslashes($device) . "' AND (" . $redQuery . ") ORDER BY TIMESTAMP DESC";
			$query = mysqli_query($con, $strSQL);
			while($result = mysqli_fetch_array($query)){
				if($result['READING'] == $reading1) SetValueFloat($this->GetIDForIdent("value1"), floatval($result['VALUE']));
				if($result['READING'] == $reading2) SetValueFloat($this->GetIDForIdent("value2"), floatval($result['VALUE']));
				if($result['READING'] == $reading3) SetValueFloat($this->GetIDForIdent("value3"), floatval($result['VALUE']));

				// this is only for testing, no logical background ;)
				if($output != "") $output .= ", ";
				$output .= '"'.$result['READING'].'" : "'.htmlspecialchars($result['VALUE']).'"';
			}
			echo "{\"" . $device . "\":{" . $output . "}";
			mysqli_close($con);
        }

		private function mergeReadingQuery($query, $new){
			if(strlen($new) == 0) return $query;

			$merged = $query;

			if(strlen($merged) > 0){
				$merged .= " OR READING = '" . addslashes($new) . "' ";
			}else{
				$merged = " READING = '" . addslashes($new) . "' ";
			}

			return $merged;
		}
	
	}
?>
