<?php
class productController{

	public function index(){
		Loader::load_js("products/products");
		Output::load(BACK_VIEW.'template/products/productsView.php', array());
	}

}