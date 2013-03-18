<?php
//商店商品类（父类）
class ShopProduct {
	private $title;
	private $producerMainName;
	private $producerFirstName;
	protected $price;
	private $discount=0;
	
	public function __construct($title, $firstName, $mainName, $price) {
		$this->title = $title;
		$this->producerFirstName = $firstName;
		$this->producerMainName = $mainName;
		$this->price = $price;
	}
	
	public function getProducerFirstName() {
		return $this->producerFirstName;
	}
	
	public function getProducerMainName() {
		return $this->producerMainName;
	}
	
	public function setDiscount($num) {
		$this->discount = $num;
	}
	
	public function getDiscount($num) {
		return $this->discount;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getPrice() {
		return ($this->price - $this->discount);
	}
	
	public function getProducer() {
		return "{$this->producerFirstName}".
				" {$this->producerMainName}";
	}
	
	public function getSummaryLine() {
		$base = "{$this->title} ( {$this->producerMainName}, ";
		$base .= "{$this->producerFirstName} )";
		return $base;
	}
	
}
//CD类，继承商店商品类
class CdProduct extends ShopProduct {
	private $playLength = 0;
	
	public function __construct($title, $firstName, $mainName, $price, $playLength) {
		parent::__construct($title, $firstName, $mainName, $price);		//调用父类的构造函数
		$this->playLength = $playLength;
	}
	
	function getPlayLength() {
		return $this->playLength;
	}
	
	function getSummaryLine() {
		$base = parent::getSummaryLine();
		$base .= ": playing time - {$this->playLength}";
		return $base;
	}
}
//书籍类，继承商店商品类
class BookProduct extends ShopProduct {
	private $numPages = 0;
	
	public function __construct($title, $firstName, $mainName, $price, $numPages) {
		parent::__construct($title, $firstName, $mainName, $price);
		$this->numPages = $numPages;
	}
	
	public function getNumberOfPages() {
		return $this->numPages;
	}
	
	public function getSummaryLine() {
		$base = parent::getSummaryLine();
		$base .= ": page count - $this->numPages";
		return $base;
	}
	
	public function getPrice() {
		return $this->price;
	}
}

class ShopProductWriter {
	private $products = array();
	
	public function addProduct(ShopProduct $shopProduct) {
		$this->products[] = $shopProduct;
	}
	
	public function write() {
		$str = "";
		foreach ($this->products as $shopProduct) {
			$str = "{$shopProduct->getTitle()}: ";
			$str .= $shopProduct->getProducer();
			$str .= " ({$shopProduct->getPrice()})\n";
		}
		echo $str;
	}
}

$product1 = new ShopProduct("My Antonia", "Willa", "Cather", 5.99);
$writer = new ShopProductWriter();
$writer->addProduct($product1);
$writer->write();
?>