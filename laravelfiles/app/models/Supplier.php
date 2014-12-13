<?php
class Supplier extends Eloquent {
	protected $table = 'supplier';
	public $timestamps = false;
	public $primaryKey = 'Code';
}