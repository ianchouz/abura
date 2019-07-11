<?
class parseXML {
    public $xml;
    function __construct($xmlstring) {
        if ($xmlstring!= null && $xmlstring!= ""){
            $this->xml = simplexml_load_string($xmlstring);
        }
    }
    function value($xpath){
      if ($xpath != null && $this->xml != null){
          $xml_tmpobj = $this->xml->xpath($xpath);
          return (string)$xml_tmpobj[0];
      }
      return "";
    }
    function xpath($xpath){
        if ($xpath != null && $this->xml != null){
            return $this->xml->xpath($xpath);
        }
        return "";
    }
    function dataList($arr=array()){ //$arr=array("欄位1","欄位2") 指定撈取的欄位
        $data=array();
       // var_dump($arr);
        //echo "<br>";
        $xml = $this->xml;
        if($arr){
            //echo "1-";
            foreach($arr as $key => $val){

                $data[$key] = $this->value('/content/'.$key);
                 //echo $key."@".$data[$key];
                 //echo "<br>";
            }
        }else{
            //echo "2-";
            if($xml!= null){
                $data= $this->object2array($xml);
                $trees = $xml->xpath('/content');
                foreach($data as $key => $val){
                    $value = $xml->xpath('/content/'.$key);
                    $data[$key] = $value[0];
                }     
            }     
        }
        return $data;
    }
    function object2array($object) { 
        return json_decode(json_encode($object),1); 
    } 

}
?>