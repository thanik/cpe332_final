<?php
class Customer extends Eloquent {
	protected $table = 'customer';
	public $timestamps = false;
	public $primaryKey = 'Code';
}