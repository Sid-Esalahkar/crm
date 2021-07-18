<?php
class AssessorModel extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->database();		
	}

	// public function allList(){
	// 		$this->db->select('*');
	// 		$this->db->from(ASSESSOR);
 //            $this->db->order_by('id DESC');
	// 		$query = $this->db->get();
	// 		$result = $query->result_array();
	// 		return $result;
	// }
	   function allList($params = array()){
        $this->db->select('*');
        $this->db->from(ASSESSOR);
        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
    }
    public function allschemeListold()
    {
			$this->db->select('*');
			$this->db->from(SCHEME);
            $this->db->order_by('id DESC');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
	}
	public function allschemeList($params = array())
	{
		  $this->db->select('*');
        $this->db->from(SCHEME);
        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
	}
    // =====================abhijeet==============================

    public function allschemenameold()
    {
            $this->db->select('*');
            $this->db->from(SCHEME);
            $this->db->order_by('id DESC');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
    }
    public function allschemename($params = array())
    {
          $this->db->select('*');
        $this->db->from(SCHEME);
        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
    }


    public function allsubschemeold()
    {
            $this->db->select('*');
            $this->db->from(SUB_SCHEME);
            $this->db->order_by('id DESC');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
    }
    public function allsubscheme($params = array())
    {
          $this->db->select('*');
        $this->db->from(SUB_SCHEME);
        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
    }

    // =====================abhijeet==============================
    public function allqpcodeListold(){
			$this->db->select('*');
			$this->db->from(QP_CODE);
            $this->db->order_by('id DESC');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
	}
	    public function allqpcodeList($params = array())
	    {
			$this->db->select('*');
	        $this->db->from(QP_CODE);
	        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
	}
    public function allqpackListold(){
			$this->db->select('*');
			$this->db->from(Q_PACK);
            $this->db->order_by('id DESC');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
	}
	  public function allqpackList($params = array())
	  {
			$this->db->select('*');
	        $this->db->from(Q_PACK);
	        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
	}
    public function allsectorlistold(){
			$this->db->select('*');
			$this->db->from(SECTOR);
            $this->db->order_by('id DESC');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
	}
	public function allsectorlist($params = array())
	{
			$this->db->select('*');
	        $this->db->from(SECTOR);
	        $this->db->order_by('id DESC');
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
	}
     public function alluserlist(){
			$this->db->select('*');
			$this->db->from(ADMIN_TBL);
            $this->db->where("id!=",1);
            $this->db->order_by('id DESC');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
	}

	   function alluserslist($params = array()){
        $this->db->select('*');
        $this->db->from(ADMIN_TBL);
         $this->db->where_not_in('id',1);
       
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
       
        return $result;
    }
	public function addassessor($insert_data){
		$query = $this->db->insert(ASSESSOR,$insert_data);
		$insert_id = $this->db->insert_id();
		//return $result;
	}
    public function checkAssessor($id){
      $this->db->where("id",$id);
      $query = $this->db->get(ASSESSOR);
      return $query->num_rows();
    }
    public function checkScheme($id){
      $this->db->where("id",$id);
      $query = $this->db->get(SCHEME);
      return $query->num_rows();
    }
    public function checkqpcode($id){
      $this->db->where("id",$id);
      $query = $this->db->get(QP_CODE);
      return $query->num_rows();
    }
    public function checkqpack($id){
      $this->db->where("id",$id);
      $query = $this->db->get(Q_PACK);
      return $query->num_rows();
    }
    public function checksector($id){
      $this->db->where("id",$id);
      $query = $this->db->get(SECTOR);
      return $query->num_rows();
    }
     public function checkuser($id){
      $this->db->where("id",$id);
      $query = $this->db->get(ADMIN_TBL);
      return $query->num_rows();
    }
	public function deleteassessor($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(ASSESSOR);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
	public function deletescheme($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(SCHEME);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
    public function deleteqpcode($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(QP_CODE);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
    public function deleteqpack($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(Q_PACK);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
    public function deletesector($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(SECTOR);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
    public function deleteuser($id){
        $this->db->where('id', $id);
		$query = $this->db->delete(ADMIN_TBL);
        $this->db->last_query();
		$result = $this->db->affected_rows();
		return $result;
	}
}

?>