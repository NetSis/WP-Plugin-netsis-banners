<?php
include_once(sprintf("%s/../../netsis/classes/WPSysObject.php", dirname(__FILE__)));

class Banner extends WPSysObject
{
	public $table = 'netsis_banners';

	public static function get_basedir()
	{
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'].'/banners/';
	}

	public static function get_baseurl()
	{
		$upload_dir = wp_upload_dir();
		return $upload_dir['baseurl'].'/banners/';
	}

	public function get_link()
	{
		if (stristr($this->link, 'http://') || stristr($this->link, 'https://'))
			return $this->link;
		else if ($this->link != '')
			return 'http://'.$this->link;
		else
			return '';
	}

	public function Insert($imagem)
	{
		$this->SalvarImagem($imagem);

		parent::Insert();
	}

	public function Update($imagem = array())
	{
		$this->SalvarImagem($imagem);

		parent::Update();
	}

	public function SalvarImagem($file)
	{
		$allowedExts = array('jpg', 'jpeg', 'png', 'gif');
		$extension = end(explode('.', $file['imagem']['name']));
		if ((($file['imagem']['type'] == 'image/gif')
			|| ($file['imagem']['type'] == 'image/jpeg')
			|| ($file['imagem']['type'] == 'image/png')
			|| ($file['imagem']['type'] == 'image/pjpeg'))
			&& in_array($extension, $allowedExts))
		{
			$img_name = $check_img_name = $file['imagem']['name'];

			$num = 0;
			while(file_exists(Banner::get_basedir().'/'.$img_name))
			{
				$ext = substr($check_img_name, strrpos($check_img_name, '.'));
				$file_name = substr($check_img_name, 0, strrpos($check_img_name, '.'));

				$img_name = $file_name.$num.$ext;
				$num++;
			}

			$path_destino = Banner::get_basedir().'/';
			if (!is_dir($path_destino))
				mkdir($path_destino);

			$img_destino = $path_destino.$img_name;
			move_uploaded_file($file['imagem']['tmp_name'], $img_destino);

			$image = wp_get_image_editor($img_destino);
			$image->resize(940, 280);
			$image->save($img_destino);

			$this->file = $img_name;
		}
	}

	public function Delete($ids)
	{
		global $wpdb;

		if (count($ids) == 0)
			$ids[0] = $this->id;

		$str_ids = '';
		foreach($ids as $id)
			$str_ids .= ','.$id;

		$banner = new Banner();
		$rows = $wpdb->get_results($wpdb->prepare('SELECT file FROM '.$banner->get_table().' WHERE id IN (%s)', substr($str_ids, 1)));
		foreach($rows as $row)
			@unlink(Banner::get_basedir().'/'.$row->file);

		parent::Delete($ids);
	}
}
?>