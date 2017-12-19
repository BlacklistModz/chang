<?php

class customers_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "customers";
    private $_table = "customers LEFT JOIN city ON customers.cus_city_id = city.city_id";
    private $_field = "cus_id, cus_prefix_name, cus_first_name, cus_last_name, cus_nickname,  cus_created, cus_updated, cus_birthday, cus_card_id, cus_phone, cus_email, cus_lineID, cus_bookmark, cus_address, cus_zip, cus_city_id, city_name, cus_emp_id";
    private $_cutNamefield = "cus_";

    private function _setDate($data) {
        if( !isset($data['cus_updated']) ){
            $data['cus_updated'] = date('c');
        }

        return $data;
    }
    public function insert(&$data) {
        if( !isset($data['cus_created']) ){
            $data['cus_created'] = date('c');
        }

        $this->db->insert( $this->_objName, $this->_setDate($data) );
        $data['cus_id'] = $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $this->db->update( $this->_objName, $this->_setDate($data), "`cus_id`={$id}" );
    }
    public function delete($id) {
        $this->db->delete( 'customers_notes', "`note_cus_id`={$id}", $this->db->count('customers_notes', "`note_cus_id`=:id", array(':id'=>$id)) );

        $this->db->delete( 'customers_options', "`option_customer_id`={$id}", $this->db->count('customers_options', "`option_customer_id`=:id", array(':id'=>$id)) );

        $this->db->delete( $this->_objName, "`cus_id`={$id}" );
    }


    public function set_option($data) { 
        
        $post = array(
            'option_customer_id' => $data['cus_id'],
            'option_type' => $data['type'],
            'option_label' => $data['label'],
            'option_value' => $data['value'],
        );

        if( empty($data['id']) ){
            $this->db->insert('customers_options', $post);
        }
        else{
            // update 
            $this->db->update('customers_options', $post, "`option_id`={$data['id']}");
        }
    }
    public function del_option($id) {
        $this->db->delete('customers_options', "`option_id`={$id}");
    }

    public function getOptions($id, $type='') {
        $data = $this->db->select("SELECT 
            option_id as id, 
            option_type as type, 
            option_label as label, 
            option_value as value 
        FROM customers_options WHERE option_customer_id=:id", 
            array(
                ':id' => $id
            )
        );

        $results = array();

        foreach ($data as $key => $value) {
            $results[$value['type']][] = array(
                'id' => $value['id'],
                'name' => $value['label'],
                'value' => $value['value'],
            );
        }

        return $results;
    }

    public function lists( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,


            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:'',

            'more' => true
        ), $options);

        if( isset($_REQUEST['view_stype']) ){
            $options['view_stype'] = $_REQUEST['view_stype'];
        }

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        $groupby = "";

        if( isset($options['not']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str = "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "cus_first_name LIKE :q{$key} OR cus_first_name=:f{$key} OR cus_last_name LIKE :q{$key} OR cus_last_name=:f{$key} OR cus_phone LIKE :s{$key} OR cus_phone=:f{$key} OR cus_email LIKE :s{$key} OR cus_email=:f{$key} OR cus_card_id=:f{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        if( ( !empty($_REQUEST['period_start']) && !empty($_REQUEST['period_end']) ) || ( !empty($options['period_start']) && !empty($options['period_end']) ) ){

            $period_start = !empty($options['period_start']) ? $options['period_start'] : $_REQUEST['period_start'];
            $period_end = !empty($options['period_end']) ? $options['period_end'] : $_REQUEST['period_end'];

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "cus_created BETWEEN :startDate AND :endDate";
            $where_arr[':startDate'] = $period_start;
            $where_arr[':endDate'] = $period_end;
        }

        if( !empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date']) ){
            $options['start_date'] = $_REQUEST['start_date'];
            $options['end_date'] = $_REQUEST['end_date'];
        }

        if( !empty($options['start_date']) && !empty($options['end_date']) ){

            $d_start = date("d", strtotime($options['start_date']));
            $d_end = date("d", strtotime($options['end_date']));
            $m_start = date("m",strtotime($options['start_date']));
            $m_end = date("m",strtotime($options['end_date']));

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= '(DAYOFMONTH(cus_birthday) BETWEEN :d_start AND 31 AND MONTH(cus_birthday) = :m_start)';
            $where_str .= 'OR (DAYOFMONTH(cus_birthday) BETWEEN 1 AND :d_end AND MONTH(cus_birthday) = :m_end)';

            if( ($m_end - $m_start) > 1 ){
                $where_str .= 'OR (DAYOFMONTH(cus_birthday) BETWEEN 1 AND 31 AND MONTH(cus_birthday) = :m_min)';
                $where_arr['m_min'] = $m_start+1;
            }

            $where_arr[':d_start'] = $d_start > 1 ? $d_start : 1;
            $where_arr[':d_end'] = $d_end;
            $where_arr[':m_start'] = $m_start;
            $where_arr[':m_end'] = $m_end;
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $sort = $this->_cutNamefield.$options['sort'];
        if( $options['sort'] == 'birthday' ){
            $sort = "SUBSTRING({$this->_cutNamefield}{$options['sort']},5)";
        }

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $sort, $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$groupby} {$orderby}  {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function get($id, $options=array()){
        $select = $this->_field;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options )
            : array();
    }
    public function buildFrag($results, $options=array()) {
        
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert($value, $options);
        }
        return $data;
    }
    public function bucketed($data, $options=array()) {

        $text = $data['fullname'];
        $start_date = '';
        $end_date = '';
        $icon = '';

        if( !empty($options['sort']) && $options['sort'] == 'birthday' ){

            $icon = 'birthday-cake';
            $start_date = !empty($data['birthday']) ? substr($data['birthday'],4):'';
            $end_date = !empty($data['birthday']) ? substr($data['birthday'],4):'';

            if( !empty($start_date) && !empty($end_date) ){

                /* SET YEAR */
                $start_date = date("Y").$start_date;
                $end_date = date("Y").$end_date;

                $text = $text.' (ลูกค้า)';

                $category = '<i class="icon-birthday-cake"></i>'.$this->fn->q('time')->str_event_date($start_date, $end_date);
            }
        }
        // $subtext = 'ทะเบียน: '.$data['plate'];
        // $category = $data['cus']['fullname'];
        //pro

        return array(
            'id'=> $data['id'],
            'created' => $data['created'],
            'icon'=> $icon,
            'text'=> isset($text)?$text:"",
            "category"=>isset($category)?$category:"",
            "subtext"=>isset($subtext)?$subtext:"",
            "type"=>"customers",
            "start_date" => $start_date,
            "end_date" => $end_date,
            "color_code" => 'FF0066',
            "url"=> URL.'customers/'.$data['id'],
            // "image_url"=>isset($image_url)?$image_url:"",
            // 'status' => isset($status)?$status:"",
            // 'data' => $data,
        );
    }
    public function convert($data, $options=array()){

        $data = $this->cut($this->_cutNamefield, $data);

        foreach ($this->query('system')->_prefixNameCustomer() as $key => $value) {
            if( $value['id']==$data['prefix_name'] ){
                
                $data['prefix_name_th'] = $value['name'];
                break;
            }
        }

        if( !empty($data['address']) ){
            
            $data['address'] = json_decode($data['address'], true);
        }

        if( !empty($data['address']['city']) ){
            $data['address']['city_name'] = $this->query('system')->city_name($data['address']['city']);
        }

        if( empty($data['prefix_name_th']) ){
            $data['prefix_name_th'] = '';
        }

        $data['fullname'] = "{$data['prefix_name_th']}{$data['first_name']} {$data['last_name']}";

        $data['initials'] = $this->fn->q('text')->initials( $data['first_name'] );

        if( !empty($options['options']) ){
            $data['options'] = $this->getOptions($data['id']);
        }

        if( !empty($data['birthday']) ){
            if( $data['birthday']=='0000-00-00' ){
                $data['birthday'] = '';
            }
            else{
                $data['age'] = $this->fn->q('time')->age( $data['birthday'] );
            }
        }

        $data['total_booking'] = $this->db->count('booking', "book_cus_id={$data['id']} AND book_status='booking'");

        $data['total_car'] = $this->db->count('cars', "car_cus_id={$data['id']}");

        $data['total_cancel'] = $this->db->count('booking', "book_cus_id={$data['id']} AND book_status='cancel'");

        // if( !empty($data['image_id']) ){
        //     $image = $this->query('media')->get($data['image_id']);

        //     if( !empty($image) ){
        //         $data['image_arr'] = $image;
        //         $data['image_url'] = $image['quad_url'];
        //     }
        // }

        if( !empty($options['type']) ){
            $data['type'] = $options['type'];
        }

        // print_r($data['options']); die;
        $data['permit']['del'] = true;

        $view_stype = !empty($options['view_stype']) ? $options['view_stype']:'convert';
        if( !in_array($view_stype, array('bucketed', 'convert')) ) $view_stype = 'convert';

        return $view_stype == 'bucketed' 
               ? $this->bucketed( $data, $options )
               : $data;
    }

    public function setAddress($str) {
        
        // $arr = explode(' ', $str);

        return array();
    }

    public function lists_status()
    {
        $a[] = array('id'=>1, 'name'=>'');
        return $a; 
    }

    public function lists_sex()
    {
        $a[] = array('id'=>'m', 'name'=>'ชาย');
        $a[] = array('id'=>'f', 'name'=>'หญิง');
    }

    /**/
    /* Check */
    /**/
    public function is_name( $first_name=null , $last_name=null ){
        return $this->db->count('customers', "cus_first_name=':first_name' AND cus_last_name=':last_name'", array(':first_name'=>$first_name , ':last_name'=>$last_name) );
    }

    public function count_book_service( $options=array() ){

        $count_book = 0;
        $count_service = 0;

        $where_book = "";
        $where_service = "";
        $where_arr = array();

        if( !empty($options['period_start']) && !empty($options['period_end']) ){
            $where_book = "book_created BETWEEN :s AND :e";
            $where_service = "service_date_repair :s AND :e";

            $where_arr[":s"] = $options['period_start'];
            $where_arr[":e"] = $options['period_end'];
        }

        $book = $this->db->select("SELECT COUNT(*) AS count_book FROM booking WHERE {$where_book} GROUP By book_cus_id", $where_arr);
        $service = $this->db->select("SELECT COUNT(*) AS count_service FROM services WHERE {$where_service} GROUP By service_cus_id", $where_arr);

        if( !empty($book) ){
            $count_book = count($book);
        }

        if( !empty($service) ){
            $count_service = count($service);
        }

        return ($count_book+$count_service);
    }
}