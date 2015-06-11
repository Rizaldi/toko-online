<?php

class toko_model extends CI_Model
{
	function login($username='',$pass='') {
        $this->load->database();
        $login = FALSE;
        $query = $this->db->query("SELECT nama_user, pass FROM user WHERE nama_user ='$username' and pass ='$pass'");
        if ($query->num_rows() > 0) {
            $login = TRUE;
        }
        return $login;
    }

    function get_item($id_item='',$id_kategori='',$id_user=''){
    	$this->load->database();
    	$condition = '';
    	if($id_item <> ''){
   			$condition.=" AND id_item = '$id_item' ";
    	}
    	if($id_kategori <> ''){
   			$condition.=" AND id_kategori = '$id_kategori' ";
    	}
    	if($id_user <> ''){
   			$condition.=" AND id_user = '$id_user' ";
    	}
    	 $query = $this->db->query("SELECT id_user , id_barang , barang.nama_barang , barang.jumlah_stock FROM `transaksi` 
				  JOIN `barang` ON transaksi.id_barang = barang.id_barang WHERE 1=1 $condition
				ORDER BY id_user DESC");
    	return $query;
    }
    function get_profil($id_user){
    	$this->load->database();
    	$query = $this->db->query("SELECT id_user, nama_user, email,
    		 tgl_daftar
    		FROM user WHERE id_user = $id_user");
        return $query;
    }

    function get_token($id_user){
    	$this->load->database();
    	$query = $this->db->query("SELECT jumlah_token
    	FROM user JOIN token ON user.id_token = token.id_token
    	WHERE user.id_user = $id_user");
        return $query;
    }

    function get_transaksi($id_trans){
    	$this->load->database();
    	$query = $this->db->query("SELECT jumlah_barang,status_barang,tanggal_transaksi
    	FROM transaksi
    	WHERE id_transaksi = $id_transaksi");
        return $query;
    }
    function buy($id_barang,$banyak){
    	$this->load->database();
    	$var = $this->db->query("SELECT jumlah_stock 
    		FROM barang WHERE id_barang = $id_barang ");
    	$row = $var->row_array(); 
    	$row = $row - $banyak;
    	$query = $this->db->query("UPDATE `barang` SET `jumlah_stock`='$row' WHERE id_barang = $id_barang");
        return $query;
    }

     function add_token($id_token,$banyak){
    	$this->load->database();
    	$var = $this->db->query("SELECT jumlah_token 
    		FROM token WHERE id_token= $id_token");
    	$row = $var->row_array(); 
    	$row = $row + $banyak;
    	$query = $this->db->query("UPDATE `token` SET `jumlah_token`='$row' 
    		WHERE id_token = $id_token");
        return $query; 
    }

    function add_item($id_barang,$banyak='',$nama_barang='',$id_toko='',$kategori_barang=''){
    	$this->load->database();
    	$query = $this->db->query("INSERT INTO `barang`(`id_barang`, `nama_barang`, `id_toko`, `jumlah_stock`, `kategori_barang`) 
    					VALUES ('$id_barang','$nama_barang','$id_toko','$banyak','$kategori_barang')");
        return $query; 
    	
    }

    function edit_item($id_barang,$banyak='',$nama_barang='',$id_toko='',$kategori_barang='')
	    $this->load->database();
	    $condition = '';
	    if($banyak <> ''){
   			$condition.=" , `jumlah_stok` = '$banyak' ";
    	}
    	if($nama_barang <> ''){
   			$condition.=" , `nama_barang` = '$nama_barang' ";
    	}
    	if($id_toko <> ''){
   			$condition.=" , `id_toko` = '$id_toko' ";
    	}
    	if($kategori_barang <> ''){
   			$condition.=" , `kategori_barang` = '$kategori_barang' ";
    	}
		$query = $this->db->query("UPDATE `barang` SET `id_barang` = $id_barang, $condition
		    			WHERE `id_barang` = $id_barang");
		return $query;
    }

    function cek_token($id_user){
    	$this->load->database();
    	$query = $this->db->query("SELECT token.jumlah_token
    		FROM `token` JOIN `user` ON token.id_token = user.id_token
    		WHERE user.id_user = $id_user");
    	return $query;.
    }

    function payment($id_user,$harga){
    	$this->load->database();
    	$var = $this->db->query("SELECT id_token,jumlah_token 
    		FROM user JOIN token ON user.id_token = token.id_token 
    		WHERE id_user = $id_user ");
    	$row = $var->row_array(); 
    	$row['jumlah_token'] = $row['jumlah_token'] - $harga;
    	$query = $this->db->query("UPDATE `token` SET `jumlah_token`= $row['jumlah_token'] 
    		WHERE id_token = $row['id_token']");
        return $query;
    } 
    function delete_user($id_user){
    	$this->load->database();     	   	
    	$query = $this->db->query("DELETE FROM user WHERE id_user = $id_user");
    	return $query;
    }

    function delete_toko($id_toko){
    	$this->load->database();
    	$query = $this->db->query("DELETE FROM toko WHERE id_toko = $id_toko");
		return $query;
	}

	function delete_item($id_barang='',$nama_barang=''){
		$this->load->database();
		$condition = '';
    	if($id_item <> ''){
   			$condition.=" AND id_barang = '$id_barang' ";
    	}
    	if($id_kategori <> ''){
   			$condition.=" AND nama_barang = '$nama_barang' ";
    	}
		$query = $this->db->query("DELETE FROM barang WHERE 1=1 $condition");
		return $query;
	}

	function update_track($id_transaksi,$gudang='',$pengiriman='',$perjalanan='',$sampai=''){
		$this->load->database();
		$condition = '';
	    if($gudang <> ''){
   			$condition.=" , `gudang` = CURDATE() ";
    	}
    	if($pengiriman <> ''){
   			$condition.=" , `pengiriman` = CURDATE() ";
    	}
    	if($perjalanan <> ''){
   			$condition.=" , `perjalanan` = CURDATE() ";
    	}
    	if($sampai <> ''){
   			$condition.=" , `sampai` = CURDATE() ";
    	}
		$query = $this->db->query("UPDATE `tgl_transaksi` SET `id_transaksi` = $id_transaksi, $condition
		    			WHERE `id_transaksi` = $id_transaksi");
		return $query;
	}
	function set_track($id_transaksi){
		$this->load->database();
		$var = $this->db->query("INSERT INTO tgl_transaksi(`id_transaksi`,`gudang`)
    		VALUES($id_transaksi,CURDATE())");
    	return $var;
	}
	function get_track($id_trans){
		$this->load->database();
    	$query = $this->db->query("SELECT `gudang`,`pengiriman`,`perjalanan`,`sampai`
    	FROM tgl_transaksi
    	WHERE id_transaksi = $id_trans");
        return $query;
	}

	function add_transaksi($id_user,$jumlah_barang,$nama_barang){
		$this->load->database();
		$var = $this->db->query("SELECT id_toko,id_barang
    		FROM barang JOIN toko ON barang.id_toko = toko.id_toko 
    		WHERE nama_barang = $nama_barang ");
    	$row = $var->row_array(); 
		$query = $this->db->query("INSERT INTO `transaksi`(`id_user`, `id_barang`, `id_toko`, `jumlah_barang`, `status_barang`, `tanggal_transaksi`) 
		VALUES ($id_user,$row['id_barang'],$row['id_toko'],$jumlah_barang,CURDATE())
		");
		return $query
	}

	
}
