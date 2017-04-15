<?php

/**
* Ultima PHP - OpenSource Ultima Online Server written in PHP
* Version: 0.1 - Pre Alpha
*/

class bardiche extends Object {
	public function build() {
		$this->name = "bardiche";
		$this->graphic = 0xF4D;
		$this->type = "";
		$this->flags = 0x00;
		$this->value = 0;
		$this->amount = 1;
		$this->color = 0;
		$this->aosstrengthreq = 45;
		$this->aosmindamage = 17;
		$this->aosmaxdamage = 18;
		$this->aosspeed = 28;
		$this->mlspeed = 3;
		$this->oldstrengthreq = 40;
		$this->oldmindamage = 5;
		$this->oldspeed = 26;
		$this->defhitsound = 0x237;
		$this->defmisssound = 0x238;
		$this->hits = 31;
		$this->maxHits = 100;
		$this->weight = 7.0;

}}
?>
