<?php

class albums_model extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->www_images_url = WWW_IMAGES."airline".DS;
        $this->images_url = IMAGES."airline/";
    }

    public function get_albums($id, $type) {

        return $this->db->select("SELECT a.album_id as id, a.album_name as name FROM albums_permit p LEFT JOIN albums a ON p.album_id=a.album_id WHERE p.obj_id=:id AND p.obj_type=:type", array(
            ':id'=>$id,
            ':type'=>$type
        ));

    }

    public function search_id( $folder='my', $_dataPost=array(), $create=false ) {
        

        $sW .= 'folder=:folder';
        $aW[':folder'] = $folder;

        $sth = $this->db->prepare("SELECT album_id as id FROM albums_permit WHERE {$sW} LIMIT 1");
        $sth->execute( $aW );

        if( $sth->rowCount()==1 ){
            $data =  $sth->fetch( PDO::FETCH_ASSOC );
            return $data['id'];
        }
        else if( $create ){
            $_dataPost = array_merge( array('album_created'=>date('c')), $_dataPost );
            $this->db->insert('albums', $_dataPost );
            $album_id = $this->db->lastInsertId();

            $this->db->insert('albums_permit', array(
                'album_id' => $album_id,
                'obj_id' => $id,
                'obj_type'=> $type,
                'folder' => $folder
            ));

            return $album_id;
        }
        else{
            return '';
        }
    }
    public function set_media(&$data)
    {
        $this->db->insert('albums_media', $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function get_media($id)
    {
        $sth = $this->db->prepare("SELECT * FROM albums_media m INNER JOIN albums_permit p ON m.media_album_id=p.album_id WHERE m.media_id=:id LIMIT 1");
        $sth->execute( array(
            ':id'=>$id
        ) );

        $data = array();
        if( $sth->rowCount()==1 ){
            $data = $this->convert_media( $sth->fetch( PDO::FETCH_ASSOC ) );
        }
        

        return $data;
        
    }
    public function up_media($id, $data) {
        $this->db->update('albums_media', $data, "`media_id`={$id}");
    }

    public function lists_media($id, $type, $folder='me')
    {

        return $this->buildFrag_media( $this->db->select("SELECT * FROM albums_media m LEFT JOIN albums_permit p ON m.media_album_id=p.album_id WHERE p.obj_id=:id AND p.obj_type=:type AND p.folder=:folder", array(
            ':id' => $id,
            ':type' => $type,
            ':folder' => $folder
        )) );

    }

    public function buildFrag_media($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert_media( $value );
        }

        return $data;
    }
    public function convert_media($fdata) {

        $aid =  Hash::create('crc32b', $fdata['album_id'], 'album');
        $pid =  Hash::create('md5', $fdata['media_id'], 'media');

        $data['id'] = $fdata['media_id'];
        $data['name'] = $fdata['media_name'];
        $data['filename'] = "{$aid}_{$pid}_n.{$fdata['media_type']}";
        $data['url'] = UPLOADS.$data['filename'];
        $data['caption'] = $fdata['media_caption'];

        if( !empty($fdata['folder']) ){
            $data['folder'] = $fdata['folder'];
        }

        return $data;
    }

    public function update_media($id, $data)
    {
        $this->db->update('albums_media', $data, "`media_id`={$id}");
    }
    public function del_media($id){

        $data = $this->get_media($id);
        if( !empty( $data ) ){

            $filename = WWW_UPLOADS . $data['filename'];
            if( file_exists($filename) ){

                unlink($filename);
            }
        }

        $this->db->delete('albums_media', "`media_id`={$id}");
    }

    public function getObjID($type){

    	$a = array('system'=>1, 'company'=>2);
    	return $a[$type];
    }

    public function upload($userfile)
    {
    	
    }
    
}