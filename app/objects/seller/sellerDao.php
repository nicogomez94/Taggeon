<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/seller.php");
interface  SellerDao{
   public function alta (Seller $seller);
   public function editar (Seller $seller);
   public function eliminar ($id,$idUsuario);
   public function get ($id,$idUsuario);
   public function getByIdUsuario ($idUsuario);
   public function getList();
   public function existeEmail ($email);
   public function existeEmailMod ($email,$id);
	public function existeSeller ($id);
}
?>
