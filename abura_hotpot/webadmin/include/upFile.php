<?
class upFile{
  public $size_bytes; //檔案大小
  public $limitedext; //限制格式
  public $unlimitedext;
  public $dirpath;    //檔案目錄
  public $file_name;  //新檔名
  public $fieldname;  //欄位名稱
  public $up_filename; //原始上傳檔案
  public $file_ext;//附檔名
  public $_message = "";
  public $new_file;
  function __construct() {
    $this->size_bytes = 2048 * 1024;
  }
  function check($perfix,$assign_new_name='',$overexit = true){
    $this->fieldname = $perfix;

    try{
      $this->new_file = $_FILES[$this->fieldname];

      // 存入暫存區的檔名
      $file_tmp = $this->new_file['tmp_name'];

      // 判斷欄位是否指定上傳檔案…
      if (!is_uploaded_file($file_tmp)) {
        //return true;
        throw new Exception("請上傳檔案!!");
      }
      // 讀取上傳檔名
      $this->up_filename = $this->new_file['name'];//iconv("utf-8", "big5", $this->new_file['name']);
      //echo $this->new_file['name'].'<br>';
      //echo $this->up_filename.'<br>';
      // 若有上傳檔，則取出該檔案的副檔名
      $ext = array_pop(explode('.',$this->up_filename));
      $ext = '.'.strtolower($ext);
      if ($assign_new_name==''){
        //新檔名
        $this->file_name = iconv("utf-8", "big5", $this->new_file['name']);
        if (!(mb_strlen($this->file_name,"Big5") == strlen($this->file_name))){
          $this->file_name = mktime().mt_rand(10000,100000).$ext;
        }
      }else{
        $this->file_name = $assign_new_name.$ext;
        if (!$overexit){
          if (is_file($this->dirpath.$this->file_name)){
            throw new Exception("檔名已存在!!");
          }
        }
      }
      // 判斷副檔名是否符合預期
      if (!empty($this->limitedext) && is_array($this->limitedext)){
        if (!in_array(strtolower($ext),$this->limitedext)) {
          // 不符合預期，顯示錯誤訊息。
          $errmsg = "";
          foreach($this->limitedext as $key => $value) {
            $errmsg .= ($errmsg==""?"":" ").$value;
          }
          throw new Exception('只允許'.$errmsg.'格式');
        }
      }
      //判斷副檔名是否要被排除
      if (!empty($this->unlimitedext) && is_array($this->unlimitedext)){
        if (in_array(strtolower($ext),$this->unlimitedext)) {
          throw new Exception('不允許的檔案格式');
        }
      }

      $this->file_ext = $ext;
      // 檢查檔案是否太大
      $file_size = $this->new_file['size'];
      if ($file_size > $this->size_bytes){
        throw new Exception("檔案大小超過:".($this->size_bytes / 1024)." KB。");
      }
      //檢查要寫入的目錄是否存在
      if(!is_dir($this->dirpath)){
        mkdir($this->dirpath, 0777);
        if(!is_dir($this->dirpath)){
          throw new Exception("目錄不存在或無法寫入");
        }
      }
      if (!is_writeable($this->dirpath)){
      	throw new Exception("目錄無法寫入");
      }
      $this->_message = "";
    } catch (Exception $e) {
      $this->_message= $e->getMessage();
    }
  }
  function write(){
    $tmpname = mktime().mt_rand(10000,100000);
    if (move_uploaded_file($this->new_file['tmp_name'],$this->dirpath.$tmpname)) {
      chmod ($this->dirpath.$tmpname , 0777);
      @copy($this->dirpath.$tmpname,$this->dirpath.$this->file_name);
      @unlink($this->dirpath.$tmpname);
      chmod($this->dirpath.$this->file_name,0777);
      return true;
    }else{
      return false;
    }
  }
}
?>