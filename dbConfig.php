<?php session_start();
error_reporting(E_ERROR | E_PARSE);
class dbConfig
{
   private $host, $username,$password,$database;
   public $con;
   
   function __construct()
   {
        $this->host='localhost';
        $this->username='root';
        $this->password='';
        $this->database='tic_tac_toe';
        $this->con=mysqli_connect($this->host,$this->username,$this->password,$this->database);
        
        if(!$this->con)
        {
            mysqli_error($this->con);
        }   
   }	
	
	function __destruct()
	{
	    mysqli_close($this->con);
	}

	function ExNonQuery($query,$type='S')
	{
	  mysqli_query($this->con,"SET NAMES 'utf8'");
	  mysqli_set_charset($this->con,"utf8");
	   if($type=='M')
	   {
		 $setQ=explode(';',substr($query,0,-1));
		 if(count($setQ))
		 {
		    foreach($setQ as $Q) { $done=mysqli_query($this->con,$Q) or die(mysqli_error($this->con)); }	 
		 }   
	   }
	   else
	   {
	   $done=mysqli_query($this->con,$query) or die(mysqli_error($this->con));
	   }
	   if($done)
	   {
		   return 1;   
	   }
	   else
	   {
		   return 0; 	
	   }
		
	}
	
	function IF_Exist($query)
	{
		$res=$this->Num_Rows($query);
		
		if($res)
		{
		  return 1;	
		}
		else
		{
		  return 0;	
		}
		
	}
	
	function Fetch_Data($query)
	{
	    if($this->IF_Exist($query))
		{
			mysqli_query($this->con,"SET NAMES 'utf8'");
			mysqli_set_charset($this->con,"utf8");
		   $rs=mysqli_query($this->con,$query);
		   while($rowset=mysqli_fetch_array($rs,MYSQLI_ASSOC))
		   {
			$data[]=$rowset;   
		   }
		 return $data;	
		}
		
	}
		
	function Fetch_Single($query)
	{
	    if($this->IF_Exist($query))
		{  mysqli_query($this->con,"SET NAMES 'utf8'");	
		   mysqli_set_charset($this->con,"utf8");
		   $rs=mysqli_query($this->con,$query);
           $row=mysqli_fetch_array($rs,MYSQLI_ASSOC);
		 return $row;	
		}
		
	}
	
	
	function Num_Rows($query)
	{   
	    mysqli_query($this->con,"SET NAMES 'utf8'");	
		mysqli_set_charset($this->con,"utf8");
		$result=mysqli_query($this->con,$query);
		$no=mysqli_num_rows($result);
		if($no)
		{
		   return $no;	
		}
		else
		{
		   	return 0;
		}
		
	}
	
    function sanitize($data)
    {
        return mysqli_real_escape_string($this->con,$data);	  
    }

    function filterData($data,$type='s')
    {    
        $data=trim(filter_var(filter_var($data,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH));
        if($type=='s') {return htmlspecialchars(filter_var($data,FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');}
        if($type=='i') { if(filter_var($data,FILTER_VALIDATE_INT)){ return filter_var($data,FILTER_SANITIZE_NUMBER_INT);} else {return 0;} }
        if($type=='e') { if(filter_var($data,FILTER_VALIDATE_EMAIL)){ return filter_var($data,FILTER_SANITIZE_EMAIL);} else {return '';} }	  
    }
	
}


?>