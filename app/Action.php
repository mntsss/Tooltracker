<?php

namespace App;
use App\ItemWithdrawal;
use App\ItemSuspention;
use App\ReservationItem;

class Action
{
    protected $ItemName;
    protected $ItemID;
    protected $Action; //in / out
    protected $Type; //withdrawal / reservation / suspention
    protected $Subtype; // null / warranty_fix / unwarranted_fix / unconfirmed_return
    protected $Quantity;
    protected $Username;
    protected $UserID;
    protected $Date;
    protected $ActionID;

    public function __construct($ItemName = null, $ItemID = null, $Action = null, $Type = null, $Username = null, $UserID = null, $Date = null, $Quantity = null, $ActionID = null, $Subtype = null)
    {
        $this->ItemName = $ItemName;
        $this->ItemID = $ItemID;
        $this->Action = $Action;
        $this->Type = $Type;
        $this->Subtype = $Subtype;
        $this->Username = $Username;
        $this->UserID = $UserID;
        $this->Date = $Date;
        $this->Quantity = $Quantity;
        $this->ActionID = $ActionID;
    }
    // getters and setters
    public function setItemName($val){
        $this->ItemName = $val;
    }
    public function setItemID($val){
        $this->ItemID = $val;
    }
    public function setAction($val){
        $this->Action = $val;
    }
    public function setType($val){
        $this->Type = $val;
    }
    public function setUsername($val){
        $this->Username = $val;
    }
    public function setUserID($val){
        $this->UserID = $val;
    }
    public function setDate($val){
        $val = $val->toDateTimeString();
        $this->Date = $val;
    }
    public function setActionID($val){
        $this->ActionID = $val;
    }
    public function setQuantity($val){
        $this->Quantity = $val;
    }
    public function setSubtype($val){
        $this->Subtype = $val;
    }

    public function getItemName(){
        return $this->ItemName;
    }
    public function getItemID(){
        return $this->ItemID;
    }
    public function getAction(){
        return $this->Action;
    }
    public function getType(){
        return $this->Type;
    }
    public function getUsername(){
        return $this->Username;
    }
    public function getUserID(){
        return $this->UserID;
    }
    public function getDate(){
        return $this->Date;
    }
    public function getActionID(){
        return $this->ActionID;
    }
    public function getQuantity(){
        return $this->Quantity;
    }
    public function getSubtype(){
        return $this->Subtype;
    }
    public function __set($name, $value) {

        if (!property_exists($this, $name))
          print_r("Error setting a value for an object field.", 0);

        $mutator = "set" . ucfirst(strtolower($name));
        if (method_exists($this, $mutator) &&
            is_callable(array($this, $mutator))) {
            $this->$mutator($value);
        }
        else {
            $this->$name = $value;
        }

        return $this;
    }

    public function __get($name) {

        if (!property_exists($this, $name))
            print_r("Error getting a value of an object field.", 0);

        $accessor = "get" . ucfirst(strtolower($name));
        return (method_exists($this, $accessor) &&
            is_callable(array($this, $accessor)))
            ? $this->$accessor() : $this->name;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
