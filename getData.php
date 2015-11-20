<?php
class RandomTable{

    public $IDr = 0 ;
    function conectarBD(){ 
            $server = "XXX.XXX.XXX.XXX";
            $usuario = "root";
            $pass = "XXXXXX";
            $BD = "dc_hubble";
            $conexion = mysqli_connect($server, $usuario, $pass, $BD, 3306); 
            if(!$conexion){ 
               echo 'Ha sucedido un error inexperado en la conexion de la base de datos<br>'; 
            } 
            return $conexion; 
    }  
    function desconectarBD($conexion){
            $close = mysqli_close($conexion); 
            if(!$close){  
               echo 'Ha sucedido un error inexperado en la desconexion de la base de datos<br>'; 
            }    
            return $close;         
    }

    function getArraySQL($sql){
        $conexion = $this->conectarBD();
        if(!$result = mysqli_query($conexion, $sql)) die();

        $rawdata = array();
        $i=0;
        while($row = mysqli_fetch_array($result))
        {   
            $rawdata[$i] = $row;
            $i++;
        }
        $this->desconectarBD($conexion);
        return $rawdata;
    }
    
    function getTmpData(){
        $sql = "select a.* from (select t.* from tmp t where t.nameid=0 ORDER BY date DESC LIMIT 360) a ORDER BY a.date;";
        return $this->getArraySQL($sql);
    }
    function getLastTmpData(){
        $sql = "select * from tmp where id = (SELECT max(id) FROM tmp);";
        return $this->getArraySQL($sql);
    }
    function getAfterTmpData($data){
        $sql = "select * from tmp where date > '".$data."';";
        return $this->getArraySQL($sql);
    }

    function getDhtData(){
        $sql = "select a.* from (select t.* from dht t where t.nameid=0 ORDER BY date DESC LIMIT 360) a ORDER BY a.date;";
        return $this->getArraySQL($sql);
    }
    function getLastDhtData(){
        $sql = "select * from dht where id = (SELECT max(id) FROM dht);";
        return $this->getArraySQL($sql);
    }
    function getAfterDhtData($data){
        $sql = "select * from dht where date > '".$data."';";
        return $this->getArraySQL($sql);
    }

    function getRenData(){
        $sql = "select a.* from (select t.* from ren t where t.nameid=0 ORDER BY date DESC LIMIT 360) a ORDER BY a.date;";
        return $this->getArraySQL($sql);
    }
    function getLastRenData(){
        $sql = "select * from ren where id = (SELECT max(id) FROM ren);";
        return $this->getArraySQL($sql);
    }
    function getAfterRenData($data){
        $sql = "select * from ren where date > '".$data."';";
        return $this->getArraySQL($sql);
    }

    function getLastSmkData(){
        $sql = "select * from smoke where id = (SELECT max(id) FROM smoke);";
        return $this->getArraySQL($sql);
    }

    function getLastAddData(){
        $sql = "SELECT * from ip where id=(SELECT MAX(id) FROM ip where type='add_data');";
        return $this->getArraySQL($sql);
    }
    
    function getAccessIpCount(){
        $sql = "SELECT count(*) from ip where type='get_show';";
        return $this->getArraySQL($sql);
    }  
}
?>
