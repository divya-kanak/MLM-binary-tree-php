<?php
/*
mlmBinary class - Multi Level Marketing Binary Plan
version 0.1 beta 12/9/2015

Copyright (c) 2015, Wagon Trader

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class mlmBinary{
    
    //*********************************************************
    // Settings
    //*********************************************************
    
    //database credentials
    protected $dbHost = 'localhost';
    
    protected $dbUser = 'root';
    
    protected $dbPass = '';
    
    public $dbName = 'mlmbinary';
    
    public $dbTable = array(
        'rep' => 'mlm_rep'
    );
    
    public $dbLink;
    
    public $breadcrumb = 'Home';
    
    /* mlmBinary class initialization
    usage: mlmBinary();
    params: None

    This method is automatically called when the class is initialized
    and connects to the database.
    
    retuns:    void
    */
    public function __construct(){
        
        if( $this->dbConnect() === false ){
            
            die('Error connecting to the database<br>'.mysqli_connect_error());
            
        }
        
    }
    
    //create Rep
    public function cRep($name,$repID,$sponsorID,$leg){
        
        $query = "INSERT INTO ".$this->dbTable['rep']." SET 
            name = '".$this->quoteSmart($name)."',
            repID = '".$this->quoteSmart($repID)."',
            sponsorID = '".$this->quoteSmart($sponsorID)."',
            leg = '".$this->quoteSmart($leg)."'
        ";
        $this->dbQuery($query);
        
        return mysqli_insert_id($this->dbLink);
        
    }
    
    //create rep as spill over
    public function cRepSpill($name,$repID,$sponsorID){
        
        $notFound = true;
        while( $notFound ){
            
            $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE sponsorID='".$sponsorID."' AND leg='0'";
            
            $r = $this->dbQuery($query);
            
            $rows = mysqli_num_rows($r);
            
            if( $rows == 2 ){
                
                $f = mysqli_fetch_assoc($r);
                
                $sponsorID = $f['recordID'];
                
            }elseif( $rows == 1 ){
                
                $f = mysqli_fetch_assoc($r);
                
                $sponsorID = $f['recordID'];
                
                if( $f['leg'] ){
                    
                    $notFound = false;
                    
                }
                
            }else{
                
                $notFound = false;
                
            }
            
        }
        
        $recordID = $this->cRep($name,$repID,$sponsorID,0);
        
        return $recordID;
        
    }
    
    //retrieve rep
    //by record ID or rep ID
    public function rRep($id,$isRecord=true){
        
        $idType = ( $isRecord ) ? 'recordID' : 'repID';
        
        $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE ".$idType." = '".$this->quoteSmart($id)."'";
        
        $r = $this->dbQuery($query);
        
        if( mysqli_num_rows($r) ){
            
            $f = mysqli_fetch_assoc($r);
            
            $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE sponsorID = '".$f['recordID']."'";
            
            $ra = $this->dbQuery($query);
            
            for( $x=0;$x<mysqli_num_rows($ra);$x++ ){
                
                $f['reps'][$x] = mysqli_fetch_assoc($ra);
                
            }
            
            $json = json_encode($f);
            
            return json_decode($json);
            
        }else{
            
            return null;
            
        }
        
    }
    
    //update Rep
    public function uRep($recordID,$name,$repID,$sponsorID,$leg){
        
        $query = "UPDATE ".$this->dbTable['rep']." SET 
            sponsorID = '".$this->quoteSmart($sponsorID)."',
            repID = '".$this->quoteSmart($repID)."',
            name = '".$this->quoteSmart($name)."',
            leg = '".$this->quoteSmart($leg)."'
         WHERE recordID = '".$this->quoteSmart($recordID)."'";
        
        $this->dbQuery($query);
        
    }
    
    //delete Rep
    public function dRep($recordID,$sponsorID,$isRecord=true){
        
        $query = "SELECT recordID FROM ".$this->dbTable['rep']." WHERE sponsorID='".$recordID."'";
        $r = $this->dbQuery($query);
        $rows = mysqli_num_rows($r);
        
        $deleteOkay = false;
        
        if( $rows ){
            
            if( !empty($sponsorID) ){
                
                if( empty($isRecord) ){
                    
                    $query="SELECT recordID FROM ".$this->dbTable['rep']." WHERE repID='".$sponsorID."'";
                    $r = $this->dbQuery($query);
                    $f = mysqli_fetch_assoc($r);
                    
                    $sponsorID = $f['recordID'];
                    
                }
                
                $query = "UPDATE ".$this->dbTable['rep']." SET sponsorID='".$sponsorID."' WHERE sponsorID='".$recordID."'";
                $this->dbQuery($query);
                
                $deleteOkay = true;
                
            }
            
        }else{
            
            $deleteOkay = true;
            
        }
        
        if( $deleteOkay ){
            
            $query = "DELETE FROM ".$this->dbTable['rep']." WHERE recordID='".$recordID."'";
            $this->dbQuery($query);
            
        }
        
        return $sponsorID;
        
    }
    
    //swap legs
    public function swapReps($recordID){
        
        $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE sponsorID='".$recordID."'";
        $r = $this->dbQuery($query);
        $rows = mysqli_num_rows($r);
        
        for( $x=0;$x<$rows;$x++ ){
            
            $f = mysqli_fetch_assoc($r);
            
            $leg = ( empty($f['leg']) ) ? 1 : 0;
            
            $query = "UPDATE ".$this->dbTable['rep']." SET leg='".$leg."' WHERE recordID='".$f['recordID']."'";
            $this->dbQuery($query);
            
        }
        
    }
    
    //array of primary Reps
    public function primReps(){
        
        $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE sponsorID = '0'";
        $r = $this->dbQuery($query);
        $rows = mysqli_num_rows($r);
        $return = [];
        for( $x=0;$x<$rows;$x++ ){
            
            $return[$x] = mysqli_fetch_assoc($r);
            
        }
        
        return $return;
        
    }
    
    //breadcrumb links
    public function showBreadcrumb($recordID=0){
        
        if( $recordID == 0 ){
            
            return $this->breadcrumb;
            
        }
        
        $query = "SELECT name,sponsorID FROM ".$this->dbTable['rep']." WHERE recordID='".$recordID."'";
        
        $r = $this->dbQuery($query);
        
        $f = mysqli_fetch_assoc($r);
        
        $sponsorID = $f['sponsorID'];
        
        $breadcrumb = $f['name'];
        
        while( $sponsorID != 0 ){
            
            $query = "SELECT * FROM ".$this->dbTable['rep']." WHERE recordID='".$sponsorID."'";
            
            $ra = $this->dbQuery($query);
            
            $fa = mysqli_fetch_assoc($ra);
            
            $breadcrumb = '<a href="?id='.$fa['recordID'].'">'.$fa['name'].'</a>'.' - '.$breadcrumb;
            
            $sponsorID = $fa['sponsorID'];
                
        }
        
        $this->breadcrumb = '<a href="'.$_SERVER['PHP_SELF'].'">'.$this->breadcrumb.'</a> - '.$breadcrumb;
        
        return $this->breadcrumb;
        
    }
    
    //database query
    public function dbQuery($query){
        
        $r = mysqli_query($this->dbLink,$query) or die(mysqli_error($this->dbLink));
        
        return $r;
        
    }
    
    //database connect
    public function dbConnect(){
        
        $this->dbLink = mysqli_connect($this->dbHost,$this->dbUser,$this->dbPass,$this->dbName);
        
        if( mysqli_connect_errno() ){
            
            return false;
            
        }else{
            
            return true;
            
        }
        
    }
    
    //safely quote passed values
    public function quoteSmart($value){
        
        if( !is_numeric($value) ){
            $value = mysqli_real_escape_string($this->dbLink,$value);
        }
        return $value;
        
    }
    
}

//construct object from data
class repData{
    
    public $data;
    
    public function __construct($data){
        
        $this->data = $data;
        
    }
    
}

?>
