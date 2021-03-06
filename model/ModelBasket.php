<?php
/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 24/11/2017
 * Time: 10:10
 */

//require_once File::build_path(array('model','Model.php'));

class ModelBasket
{
    protected static $object = 'Baskets';
    protected static $primary = 'idUser';

    private $products;

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function __construct() {
        $this->products=array();
    }

    public function save() {
        setcookie('basket',serialize($this),time()+3600);
        setcookie('basketSize', count($this->products),time()+3600);
    }

    public function add($idProduct,$quantity) {
        $trouve=false;
        foreach ($this->products as $cle => $value){
            if($value[0]==$idProduct) {
                $trouve=true;
                $q=(int)$value[1];
                $this->products[$cle][1]=$q+(int)$quantity;
            }
        }
        if(!$trouve) {
            $this->products[]=array($idProduct,$quantity);
        }
    }

    public function remove($idProduct) {
        $i=0; $trouve=false; $max=count($this->products);
        foreach ($this->products as $cle => $value) {
            if($value[0]==$idProduct) {
                unset($this->products[$cle]);
            }
        }
    }

    public static function getBasket() {
        if(isset($_COOKIE['basket'])) {
            $basket=unserialize($_COOKIE['basket']);
            if($basket instanceof ModelBasket) {
                return $basket;
            }
            else {
                $basket=new ModelBasket();
                $basket->save();
                return $basket;
            }
        }
        else {
            $basket=new ModelBasket();
            $basket->save();
            return $basket;
        }
    }

    public static function getBasketObject() {
        $basket=ModelBasket::getBasket()->products; $tab=[];
        foreach ($basket as $value) {
            $prod=ModelProduct::select($value[0]);
            if($prod!=false) $tab[]=array($prod,$value[1]);
        }
        return $tab;
    }

}