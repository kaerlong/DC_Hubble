<?php
    if ($_GET['pwd'] == "XXXXXX"){
        $type = trim ($_GET['type']);
        $date = trim ($_GET['date']);
        
        require "conn.php";
        require_once("getData.php");
        $rand = new RandomTable();
        
        if ($type == "tmp"){
          $lastTmpRaw = $rand->getLastTmpData();
          $lastTmpData = $lastTmpRaw[0][4];
          echo json_encode(array('tmp'=>$lastTmpData));
        } else if ($type == "dht"){
          $lastDhtRaw = $rand->getLastDhtData();
          $lastDhtData = $lastDhtRaw[0][5];
          echo json_encode(array('dht'=>$lastDhtData));
        } else if ($type == "smk"){
          $lastSmkRaw = $rand->getLastSmkData();
          $lastSmkData = $lastSmkRaw[0][3];
          echo json_encode(array('smk'=>$lastSmkData));
        } else if ($type == "ren"){
          $lastRenRaw = $rand->getLastRenData();
          $lastRenData = $lastRenRaw[0][3];
          echo json_encode(array('ren'=>$lastRenData));
        } else if ($type == "add"){
          $lastAddRaw = $rand->getLastAddData();
          $lastAddData = $lastAddRaw[0][3];
          echo json_encode(array('add'=>$lastAddData));
        } else if ($type == "tmpAfter"){
                $afterTmpRaw = $rand->getAfterTmpData($date);
		$data = array();
		for($i = 0;$i<count($afterTmpRaw);$i++){
          	    $data[] = array('time'=>$afterTmpRaw[$i][3], 'value'=>$afterTmpRaw[$i][4]);
                }
          echo json_encode($data);
        } else if ($type == "dhtAfter"){
                $afterDhtRaw = $rand->getAfterDhtData($date);
		$data = array();
		for($i = 0;$i<count($afterDhtRaw);$i++){
          	    $data[] = array('time'=>$afterDhtRaw[$i][3], 'value'=>$afterDhtRaw[$i][5]);
                }
          echo json_encode($data);
        } else if ($type == "renAfter"){
                $afterRenRaw = $rand->getAfterRenData($date);
		$data = array();
		for($i = 0;$i<count($afterRenRaw);$i++){
          	    $data[] = array('time'=>$afterRenRaw[$i][3], 'value'=>$afterRenRaw[$i][4]);
                }
          echo json_encode($data);
        } else {
          echo json_encode(array('error'=>'error'));
        }
    ?>
<?php
  }
?>
