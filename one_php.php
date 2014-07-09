ONE IMAGE BY ITEM | CI

/********
Controller
********/


/**** Controller | Save function ****/
$file  = $this->upload_file();
if($_FILES['avatar']['size'] > 0){
	if ( $file['status'] == 0 ){
		$this->session->set_flashdata('error', $file['msg']);
	}
	}else{
	$file['filename'] = '';
}


/**** Controller | Update function ****/
if($_FILES['avatar']['size'] > 0){
		
		$file  = $this->upload_file();
		
		if ( $file['status'] != 0 )
		{
			//guardo
			$aptitud = $this->aptitud->get_record($this->input->post('id'));
				 
			$path = 'images-aptitudes/'.$aptitud->icono;
			unlink($path);
			
			$data = array(
			'titulo' => $this->input->post('titulo'), 
			'icono' => $file['filename']
			);

		}
		
		
}
$this->session->set_flashdata('success', 'Aptitud Actualizada!');
$this->aptitud->update_record($this->input->post('id'), $data);



/**** Controller | image upload function ****/
/**** require class.upload ****/
public function upload_file(){

	//1 = OK - 0 = Failure
	$file = array('status' => '', 'filename' => '', 'msg' => '' );
	
	
	//check ext.
	$file_extensions_allowed = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg');
	$exts_humano = array('gif', 'png', 'jpeg', 'jpg');
	$exts_humano = implode(', ',$exts_humano);
	$ext = $_FILES['avatar']['type'];
	#$ext = strtolower($ext);
	if(!in_array($ext, $file_extensions_allowed)){
		$exts = implode(', ',$file_extensions_allowed);
		
	$file['msg'] .="<p>".$_FILES['avatar']['name']." <br />Puede subir archivos que tengan alguna de estas extenciones: ".$exts_humano."</p>";
		
	}else{
		include(APPPATH.'libraries/class.upload.php');
		$yukle = new upload;
		$yukle->set_max_size(1900000);
		$yukle->set_directory('./images');
		$yukle->set_tmp_name($_FILES['avatar']['tmp_name']);
		$yukle->set_file_size($_FILES['avatar']['size']);
		$yukle->set_file_type($_FILES['avatar']['type']);
		$random = substr(md5(rand()),0,6);
		$name_whitout_whitespaces = str_replace(" ","-",$_FILES['avatar']['name']);
		$imagname=''.$random.'_'.$name_whitout_whitespaces;
		#$thumbname='tn_'.$imagname;
		$yukle->set_file_name($imagname);
		
	
		$yukle->start_copy();
		
		
		if($yukle->is_ok()){
		#$yukle->resize(600,0);
		#$yukle->set_thumbnail_name('tn_'.$random.'_'.$name_whitout_whitespaces);
		#$yukle->create_thumbnail();
		#$yukle->set_thumbnail_size(180, 0);
		
			//UPLOAD ok
			$file['filename'] = $imagname;
			$file['status'] = 1;
		}
		else{
			$file['status'] = 0 ;
			$file['msg'] = 'Error al subir archivo';
		}
		
		//clean
		$yukle->set_tmp_name('');
		$yukle->set_file_size('');
		$yukle->set_file_type('');
		$imagname='';
	}//fin if(extencion)	
		
		
	return $file;
}